<?php
// models/CartModel.php

class CartModel extends BaseModel
{
    protected $cartTable = 'carts';
    protected $itemsTable = 'cart_items';
    protected $productTable = 'books';

    // Lấy hoặc tạo giỏ hàng cho user
    public function getOrCreateCartByUserId(int $userId)
    {
        $userId = (int) $userId;
        $sql = "SELECT * FROM {$this->cartTable} WHERE user_id = $userId LIMIT 1";
        $res = $this->execute_query($sql);
        if ($res && $res->num_rows > 0) {
            return $res->fetch_assoc();
        }

        $sql = "INSERT INTO {$this->cartTable} (user_id, created_at, updated_at) VALUES ($userId, NOW(), NOW())";
        if ($this->execute_query($sql)) {
            return ['id' => $this->db->insert_id, 'user_id' => $userId];
        }
        return null;
    }

    // Thêm / cập nhật item vào giỏ hàng của user
    public function addOrUpdateItem(int $userId, int $productId, int $quantity = 1)
    {
        $userId = (int) $userId;
        $productId = (int) $productId;
        $quantity = max(1, (int) $quantity);

        $cart = $this->getOrCreateCartByUserId($userId);
        if (!$cart) return false;
        $cartId = (int) $cart['id'];

        // Kiểm tra nếu đã tồn tại item
        $sql = "SELECT * FROM {$this->itemsTable} WHERE cart_id = $cartId AND product_id = $productId LIMIT 1";
        $res = $this->execute_query($sql);
        if ($res && $res->num_rows > 0) {
            $item = $res->fetch_assoc();
            $newQty = $item['quantity'] + $quantity;
            $sql = "UPDATE {$this->itemsTable} SET quantity = $newQty WHERE id = " . (int)$item['id'];
            $ok = $this->execute_query($sql);
        } else {
            $sql = "INSERT INTO {$this->itemsTable} (cart_id, product_id, quantity, created_at, updated_at)
                    VALUES ($cartId, $productId, $quantity, NOW(), NOW())";
            $ok = $this->execute_query($sql);
        }

        if ($ok) {
            $this->execute_query("UPDATE {$this->cartTable} SET updated_at = NOW() WHERE id = $cartId");
        }

        return (bool) $ok;
    }

    public function updateItemQuantity(int $userId, int $productId, int $quantity)
    {
        $userId = (int) $userId;
        $productId = (int) $productId;
        $quantity = max(0, (int) $quantity);

        $cart = $this->getOrCreateCartByUserId($userId);
        if (!$cart) return false;
        $cartId = (int) $cart['id'];

        if ($quantity === 0) {
            return $this->removeItem($userId, $productId);
        }

        $sql = "UPDATE {$this->itemsTable} SET quantity = $quantity, updated_at = NOW() 
                WHERE cart_id = $cartId AND product_id = $productId";
        $ok = $this->execute_query($sql);
        if ($ok) $this->execute_query("UPDATE {$this->cartTable} SET updated_at = NOW() WHERE id = $cartId");
        return (bool) $ok;
    }

    // Lấy số lượng item hiện có trong giỏ của user (nếu có)
    public function getItemQuantity(int $userId, int $productId): int
    {
        $userId = (int) $userId;
        $productId = (int) $productId;

        $cart = $this->getOrCreateCartByUserId($userId);
        if (!$cart) return 0;
        $cartId = (int) $cart['id'];

        $sql = "SELECT quantity FROM {$this->itemsTable} WHERE cart_id = $cartId AND product_id = $productId LIMIT 1";
        $res = $this->execute_query($sql);
        if ($res && $res->num_rows > 0) {
            $row = $res->fetch_assoc();
            return (int) $row['quantity'];
        }
        return 0;
    }

    public function removeItem(int $userId, int $productId)
    {
        $userId = (int) $userId;
        $productId = (int) $productId;

        $cart = $this->getOrCreateCartByUserId($userId);
        if (!$cart) return false;
        $cartId = (int) $cart['id'];

        $sql = "DELETE FROM {$this->itemsTable} WHERE cart_id = $cartId AND product_id = $productId";
        $ok = $this->execute_query($sql);
        if ($ok) $this->execute_query("UPDATE {$this->cartTable} SET updated_at = NOW() WHERE id = $cartId");
        return (bool) $ok;
    }

    // Lấy giỏ hàng (items + product details) cho user
    public function getCartItemsByUserId(int $userId): array
    {
        $userId = (int) $userId;
        $cart = $this->getOrCreateCartByUserId($userId);
        if (!$cart) return [];
        $cartId = (int) $cart['id'];

    // Include product stock and discount so view can respect limits and show discounts
    $sql = "SELECT ci.product_id, ci.quantity, p.title, p.price, p.image, p.quantity AS stock, IFNULL(p.discount,0) AS discount
        FROM {$this->itemsTable} ci
        JOIN {$this->productTable} p ON p.id = ci.product_id
        WHERE ci.cart_id = $cartId";

        $res = $this->execute_query($sql);
        $items = [];
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $price = (float)($row['price'] ?? 0);
                $discount = (float)($row['discount'] ?? 0);
                $discountedPrice = $price * (1 - $discount/100);
                $row['price'] = $price;
                $row['discount_percent'] = $discount;
                $row['discounted_price'] = $discountedPrice;
                $row['subtotal'] = $row['quantity'] * $discountedPrice;
                $row['discount_amount'] = ($price - $discountedPrice) * $row['quantity'];
                $items[] = $row;
            }
        }

        return $items;
    }

    // Tính tổng giỏ hàng
    public function calculateTotal(array $items): float
    {
        $total = 0.0;
        foreach ($items as $it) {
            $total += (float) $it['subtotal'];
        }
        return $total;
    }

    // Xóa toàn bộ giỏ hàng
    public function clearCartByUserId(int $userId)
    {
        $cart = $this->getOrCreateCartByUserId($userId);
        if (!$cart) return false;
        $cartId = (int) $cart['id'];
        $ok = $this->execute_query("DELETE FROM {$this->itemsTable} WHERE cart_id = $cartId");
        if ($ok) $this->execute_query("UPDATE {$this->cartTable} SET updated_at = NOW() WHERE id = $cartId");
        return (bool) $ok;
    }
}
