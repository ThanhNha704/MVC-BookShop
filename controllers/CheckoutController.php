<?php
class CheckoutController extends BaseController
{
    private $cartModel;
    private $productModel;
    private $userModel;
    private $orderModel;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Require login for checkout
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để tiến hành thanh toán.';
            header('Location: ?controller=auth&action=login');
            exit;
        }

        $this->loadModel('CartModel');
        $this->cartModel = new CartModel();

        $this->loadModel('ProductModel');
        $this->productModel = new ProductModel();

        $this->loadModel('UserModel');
        $this->userModel = new UserModel;

        $this->loadModel('OrderModel');
        $this->orderModel = new OrderModel;
    }

    public function index()
    {
        $userId = $_SESSION['user_id'];
        $items = $this->cartModel->getCartItemsByUserId($userId);
        if (empty($items)) {
            $_SESSION['error'] = 'Giỏ hàng trống.';
            return $this->redirect('?controller=cart');
        }

        $subtotal = 0;
        $totalDiscount = 0;
        foreach ($items as $item) {
            // Giá gốc nhân số lượng
            $subtotal += $item['price'] * $item['quantity'];
            // Tiền giảm giá cho item đó
            $totalDiscount += $item['discount_amount'];
        }
        $total = $subtotal - $totalDiscount;

        $user = $this->userModel->getUserById($userId);
        return $this->view('layouts/checkout/index', [
            'items' => $items,
            'subtotal' => $subtotal,
            'totalDiscount' => $totalDiscount,
            'total' => $total,
            'user' => $user,
        ]);
    }

    public function place_order()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('?controller=cart');
        }

        $userId = $_SESSION['user_id'];

        // Lấy và Validate thông tin giao hàng
        $delivery = [
            'name' => trim($_POST['delivery_name'] ?? ''),
            'phone' => trim($_POST['delivery_phone'] ?? ''),
            'address' => trim($_POST['delivery_address'] ?? '')
        ];

        if (empty($delivery['name']) || empty($delivery['phone']) || empty($delivery['address'])) {
            $_SESSION['error'] = 'Vui lòng nhập đầy đủ tên, số điện thoại và địa chỉ giao hàng.';
            return $this->redirect('?controller=checkout');
        }

        // LẤY DỮ LIỆU TỔNG TIỀN VÀ SẢN PHẨM TỪ FORM POST
        $subtotal = (float) ($_POST['subtotal'] ?? 0);
        $totalDiscount = (float) ($_POST['totalDiscount'] ?? 0);
        $total = (float) ($_POST['total'] ?? 0);
        $itemsFromPost = $_POST['items'] ?? []; // Mảng chi tiết sản phẩm từ hidden inputs

        // 3. Chuẩn bị mảng orderItems hợp lệ và kiểm tra tính hợp lệ cơ bản
        $orderItems = [];
        if (!is_array($itemsFromPost) || empty($itemsFromPost)) {
            $_SESSION['error'] = 'Giỏ hàng trống hoặc dữ liệu sản phẩm không hợp lệ.';
            return $this->redirect('?controller=checkout');
        }

        foreach ($itemsFromPost as $item) {
            // Đảm bảo dữ liệu được ép kiểu chính xác
            $price = (float) ($item['price'] ?? 0);
            $qty = (int) ($item['quantity'] ?? 0);

            if ($price <= 0 || $qty <= 0)
                continue;

            $orderItems[] = [
                'product_id' => (int) ($item['product_id'] ?? 0),
                'quantity' => $qty,
                'price' => $price,
                'discount_percent' => (float) ($item['discount_percent'] ?? 0),
                'subtotal' => (float) ($item['subtotal'] ?? 0),
            ];
        }

        // Kiểm tra lại tổng tiền và danh sách sản phẩm
        if ($total <= 0 || empty($orderItems)) {
            $_SESSION['error'] = 'Đơn hàng không hợp lệ (Tổng tiền hoặc sản phẩm không đúng).';
            return $this->redirect('?controller=checkout');
        }

        // 4. Gọi OrderModel::createOrder với toàn bộ dữ liệu
        $orderId = $this->orderModel->createOrder([
            'user_id' => $userId,
            'delivery_recipient' => $delivery['name'],
            'delivery_phone' => $delivery['phone'],
            'delivery_address' => $delivery['address'],
            'subtotal' => $subtotal,
            'discount' => $totalDiscount,
            'total' => $total,
            'items' => $orderItems
        ]);

        if (!$orderId) {
            $_SESSION['error'] = 'Không thể tạo đơn hàng. Vui lòng thử lại.';
            return $this->redirect('?controller=checkout');
        }

        // Dọn giỏ hàng
        $this->cartModel->clearCartByUserId($userId);

        $_SESSION['success'] = 'Đặt hàng thành công! Mã đơn hàng của bạn là #' . $orderId;
        return $this->redirect('?controller=orders');
    }
}