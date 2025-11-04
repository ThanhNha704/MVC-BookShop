<?php
// controllers/CartController.php

class CartController extends BaseController
{
    private $cartModel;
    private $productModel;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $this->loadModel('CartModel');
        $this->cartModel = new CartModel();

        $this->loadModel('ProductModel');
        $this->productModel = new ProductModel();
    }

    // Hiển thị giỏ hàng
    public function index()
    {
        $userId = $_SESSION['user_id'] ?? null;

        if ($userId) {
            // DB-backed cart for logged-in users
            $items = $this->cartModel->getCartItemsByUserId($userId);
            $total = $this->cartModel->calculateTotal($items);
        } else {
            // session-based cart for guests
            $sessionCart = $_SESSION['cart'] ?? [];
            $items = [];
            foreach ($sessionCart as $productId => $qty) {
                $product = $this->productModel->getProductById($productId);
                if ($product) {
                    $price = (float) ($product['price'] ?? 0);
                    $discount = (float) ($product['discount'] ?? 0);
                    $discountedPrice = $price * (1 - $discount / 100);

                    $items[] = [
                        'product_id' => $productId,
                        'quantity' => $qty,
                        'title' => $product['title'] ?? '',
                        'price' => $price,
                        'discount_percent' => $discount,
                        'discounted_price' => $discountedPrice,
                        'image' => $product['image'] ?? '',
                        'subtotal' => $discountedPrice * $qty,
                        'discount_amount' => ($price - $discountedPrice) * $qty,
                        'stock' => isset($product['quantity']) ? (int) $product['quantity'] : null,
                    ];
                }
            }
            $total = $this->cartModel->calculateTotal($items);
        }

        return $this->view('layouts/cart/index', ['items' => $items, 'total' => $total]);
    }

    // Thêm vào giỏ hàng (POST)
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('?controller=cart');
        }

        $userId = $_SESSION['user_id'] ?? null;
        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity = (int) max(1, ($_POST['quantity'] ?? 1));

        if (!$userId) {
            $_SESSION['error'] = 'Vui lòng đăng nhập trước khi mua hàng.';
            return $this->redirect('?controller=auth&action=showLoginForm');
        }


        if (!$productId) {
            $_SESSION['error'] = 'Sản phẩm không hợp lệ.';
            return $this->redirect('?controller=product');
        }


        $buyNow = isset($_POST['buy_now']) && $_POST['buy_now'];

        // Check product stock before adding
        $product = $this->productModel->getProductById($productId);
        $p = is_array($product) ? $product : (is_object($product) ? (array) $product : []);

        // If product can't be found, reject the request (avoid treating unknown stock as unlimited)
        if (empty($p)) {
            $_SESSION['error'] = 'Sản phẩm không tồn tại.';
            return $this->redirect('?controller=product');
        }

        $stock = isset($p['quantity']) ? (int) $p['quantity'] : (isset($p['stock']) ? (int) $p['stock'] : null);

        if ($userId) {
            // For logged-in users, check existing quantity in DB cart
            $existing = $this->cartModel->getItemQuantity($userId, $productId);
            $newQty = $existing + $quantity;
            if ($stock !== null && $newQty > $stock) {
                $_SESSION['error'] = 'Số lượng thêm vào vượt quá tồn kho.';
                return $this->redirect('?controller=product');
            }
            $ok = $this->cartModel->addOrUpdateItem($userId, $productId, $quantity);
            if ($ok)
                $_SESSION['success'] = 'Đã thêm vào giỏ hàng.';
            else
                $_SESSION['error'] = 'Không thể thêm vào giỏ hàng.';
        } else {
            // session cart for guest
            if (!isset($_SESSION['cart']))
                $_SESSION['cart'] = [];
            $existing = $_SESSION['cart'][$productId] ?? 0;
            $newQty = $existing + $quantity;
            if ($stock !== null && $newQty > $stock) {
                $_SESSION['error'] = 'Số lượng thêm vào vượt quá tồn kho.';
                return $this->redirect($_SERVER['HTTP_REFERER'] ?? '?controller=product');
            }
            $_SESSION['cart'][$productId] = $newQty;
            $_SESSION['success'] = 'Đã thêm vào giỏ hàng.';
        }

        // Nếu người dùng chọn MUA NGAY, chuyển hướng tới checkout
        if ($buyNow) {
            return $this->redirect('?controller=checkout');
        }

        return $this->redirect($_SERVER['HTTP_REFERER'] ?? '?controller=product');
    }

    // Cập nhật số lượng item
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('?controller=cart');
        }

        $productId = (int) ($_POST['product_id'] ?? 0);
        $quantity = (int) ($_POST['quantity'] ?? 1);
        $userId = $_SESSION['user_id'] ?? null;

        // Check stock before applying update
        $product = $this->productModel->getProductById($productId);
        $p = is_array($product) ? $product : (is_object($product) ? (array) $product : []);

        if (empty($p)) {
            // clear possible stale success
            unset($_SESSION['success']);
            $_SESSION['error'] = 'Sản phẩm không tồn tại.';
            return $this->redirect('?controller=cart');
        }

        // Support both 'quantity' and 'stock' field names
        $stock = isset($p['quantity']) ? (int) $p['quantity'] : (isset($p['stock']) ? (int) $p['stock'] : null);

        // Ensure non-negative requested quantity
        $quantity = max(0, (int) $quantity);

        // If we don't know the stock, reject to avoid allowing unlimited quantity
        if ($stock === null) {
            unset($_SESSION['success']);
            $_SESSION['error'] = 'Không thể xác định tồn kho của sản phẩm.';
            return $this->redirect('?controller=cart');
        }

        if (($quantity) > $stock) {
            unset($_SESSION['success']);
            $_SESSION['error'] = 'Số lượng yêu cầu vượt quá tồn kho.';
            return $this->redirect('?controller=cart');
        }

        if ($userId) {
            $this->cartModel->updateItemQuantity($userId, $productId, $quantity);
            $_SESSION['success'] = 'Cập nhật giỏ hàng thành công.';
        } else {
            if (isset($_SESSION['cart'][$productId])) {
                if ($quantity <= 0)
                    unset($_SESSION['cart'][$productId]);
                else
                    $_SESSION['cart'][$productId] = $quantity;
            }
            $_SESSION['success'] = 'Cập nhật giỏ hàng thành công.';
        }

        return $this->redirect('?controller=cart');
    }

    // Xóa item
    public function remove()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('?controller=cart');
        }

        $productId = (int) ($_POST['product_id'] ?? 0);
        $userId = $_SESSION['user_id'] ?? null;

        if ($userId) {
            $this->cartModel->removeItem($userId, $productId);
            $_SESSION['success'] = 'Đã xóa sản phẩm khỏi giỏ hàng.';
        } else {
            if (isset($_SESSION['cart'][$productId]))
                unset($_SESSION['cart'][$productId]);
            $_SESSION['success'] = 'Đã xóa sản phẩm khỏi giỏ hàng.';
        }

        return $this->redirect('?controller=cart');
    }

    // Xóa toàn bộ giỏ hàng
    public function clear()
    {
        $userId = $_SESSION['user_id'] ?? null;
        if ($userId) {
            $this->cartModel->clearCartByUserId($userId);
        } else {
            unset($_SESSION['cart']);
        }
        $_SESSION['success'] = 'Giỏ hàng đã được làm trống.';
        return $this->redirect('?controller=cart');
    }
}
