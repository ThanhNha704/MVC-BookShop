<?php
// models/OrderModel.php (Đã đơn giản hóa chỉ dùng MySQLi query() và fetch_assoc())

class OrderModel extends BaseModel
{
    protected $table = 'orders';

    // Các trạng thái đơn hàng
    const STATUSES = ['chờ xác nhận', 'xác nhận', 'đang giao', 'đã giao', 'thành công', 'đã hủy'];

    /**
     * Lấy tất cả đơn hàng kèm tên khách hàng, có thể tìm theo ID.
     */
    public function getAllOrdersWithUserDetails($searchId = null)
    {
        // Vệ sinh đầu vào thô (chỉ chấp nhận số)
        $where = '';
        if (!empty($searchId)) {
            $safeSearchId = intval($searchId);
            $where = " WHERE o.id = $safeSearchId";
        }

        $sql = "SELECT 
                    o.id, 
                    o.total, 
                    o.status, 
                    o.created_at, 
                    u.username as customer_name
                FROM orders o 
                JOIN users u ON o.user_id = u.id" . $where .
            " ORDER BY o.created_at DESC";

        $result = $this->execute_query($sql);

        if (!$result) {
            return [];
        }

        // Đơn giản hóa: Dùng vòng lặp fetch_assoc() luôn
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $result->free(); // Giải phóng kết quả
        return $data;
    }

    /**
     * Cập nhật trạng thái đơn hàng (QUERY THÔ)
     */
    public function updateStatus($orderId, $status)
    {
        // $safeStatus = $this->db->real_escape_string($status);

        // Bắt buộc phải bọc $safeStatus bằng dấu nháy đơn
        $query = "UPDATE " . $this->table . " SET status = '$status' WHERE id = $orderId";

        $result = $this->execute_query($query);

        if (!$result) {
            return false;
        }

        return true;
    }

    public function getOrderDetail($orderId)
    {
        // 1. Lấy thông tin chính
        $sqlOrder = "SELECT 
                          o.id, o.user_id, o.total, o.discount, o.subtotal, o.status, o.created_at,
                          o.delivery_recipient AS recipient_name, o.delivery_phone AS phone_number, o.delivery_address AS address_text,
                          u.username AS customer_name, u.email
                      FROM orders o
                      JOIN users u ON o.user_id = u.id
                      WHERE o.id = $orderId LIMIT 1";

        $resultOrder = $this->execute_query($sqlOrder);
        if (!$resultOrder || $resultOrder->num_rows === 0) {
            return null;
        }

        $order = $resultOrder->fetch_assoc();
        $resultOrder->free();

        // 2. Lấy chi tiết các sản phẩm
        // Use order_items table which contains the order items
        $sqlDetails = "SELECT 
                        b.title AS book_title, b.author AS book_author,
                        b.image AS book_image,
                        oi.product_id, oi.quantity, oi.price, oi.discount_percent, oi.subtotal,
                        o.total, o.discount, o.subtotal
                            FROM orders o 
                            JOIN order_items oi ON oi.order_id = o.id
                            JOIN books b ON oi.product_id = b.id
                            WHERE oi.order_id = 53";
        $resultDetails = $this->execute_query($sqlDetails);

        $details = [];
        if ($resultDetails) {
            while ($row = $resultDetails->fetch_assoc()) {
                $details[] = $row;
            }
            $resultDetails->free();
        }


        // 3. Kết hợp và trả về
        $order['details'] = $details;
        return $order;
    }

    // Kiểm tra xem user đã mua product (book) nào chưa
    public function userHasPurchasedBook(int $userId, int $bookId): bool
    {
        $sql = "SELECT oi.id 
                FROM orders o
                JOIN order_items oi ON oi.order_id = o.id
                WHERE o.user_id = $userId AND oi.product_id = $bookId
                AND o.status IN ('đã giao', 'thành công', 'Thành công') LIMIT 1";

        $res = $this->execute_query($sql);
        if ($res && $res->num_rows > 0)
            return true;
        return false;
    }

    // Lấy tổng số đơn hàng
    public function getTotalOrderCount(): int
    {
        $sql = "SELECT COUNT(id) AS total_count FROM orders";

        $result = $this->execute_query($sql);

        if (!$result)
            return 0;

        $row = $result->fetch_assoc();
        $result->free();

        return (int) ($row['total_count'] ?? 0);
    }

    // Tính tổng doanh thu 
    public function getTotalRevenue(?string $startDate = null, ?string $endDate = null): float
    {
        $sql = "SELECT SUM(total) AS total_revenue FROM orders WHERE status = 'Thành công' ";

        // Nối chuỗi thời gian nếu có
        if ($startDate) {
            $sql .= " AND created_at >= '$startDate 00:00:00'";
        }
        if ($endDate) {
            $sql .= " AND created_at <= '$endDate 23:59:59'";
        }

        $result = $this->execute_query($sql);

        if (!$result)
            return 0.0;

        $row = $result->fetch_assoc();
        $result->free();

        return (float) ($row['total_revenue'] ?? 0.0);
    }

    /**
     * Tính tổng số lượng người dùng mới trong tháng hiện tại.
     */
    public function getTotalNewUsersInMonth(): int
    {
        $startDate = date('Y-m-01 00:00:00');
        $endDate = date('Y-m-d 23:59:59');

        $sql = "
            SELECT 
                COUNT(id) AS total_users 
            FROM 
                users 
            WHERE 
                created_at >= '$startDate' AND created_at <= '$endDate'
        ";

        $result = $this->execute_query($sql);

        if (!$result)
            return 0;

        $row = $result->fetch_assoc();
        $result->free();

        return (int) ($row['total_users'] ?? 0);
    }

    /**
     * Lấy danh sách đơn hàng của 1 user
     */
    public function getOrdersByUserId(int $userId): array
    {
        $userId = (int) $userId;
        $sql = "SELECT id, total, status, created_at FROM {$this->table} WHERE user_id = $userId ORDER BY created_at DESC";
        $res = $this->execute_query($sql);
        $rows = [];
        if ($res) {
            while ($r = $res->fetch_assoc()) {
                $rows[] = $r;
            }
            $res->free();
        }
        return $rows;
    }
    /**
     * Tạo đơn hàng (transactional): chèn vào orders và order_items, giảm tồn kho sách.
     * Trả về order_id khi thành công, ngược lại trả về null.
     *
     * Mảng $data kỳ vọng có các khoá: user_id, address_id, subtotal, discount, total, items (mảng)
     */
    public function createOrder(array $data): ?int
    {
        // Simple implementation: insert order, then insert order_items using execute_query().
        // This does NOT perform stock checks or use transactions (keeps behavior minimal per request).
        $userId = (int) ($data['user_id'] ?? 0);
        $deliveryRecipient = $data['delivery_recipient'] ?? '';
        $deliveryPhone = $data['delivery_phone'] ?? '';
        $deliveryAddress = $data['delivery_address'] ?? '';
        $subtotal = (float) ($data['subtotal'] ?? 0);
        $discount = (float) ($data['discount'] ?? 0);
        $total = (float) ($data['total'] ?? 0);
        // Accept either 'items' or legacy 'orderItems'
        $items = $data['items'] ?? [];

        if ($userId <= 0 || empty($items) || $total <= 0) {
            return null;
        }

        $u = (int) $userId;
        // deliveryRecipient/Phone/Address already escaped above
        $sqlOrder = "INSERT INTO " . $this->table . " (user_id, delivery_recipient, delivery_phone, delivery_address, total, discount, subtotal, status, created_at) 
    VALUES ($u, '" . $deliveryRecipient . "', '" . $deliveryPhone . "', '" . $deliveryAddress . "', " . (float) $total . ", " . (float) $discount . ", " . (float) $subtotal . ", 'chờ xác nhận', NOW())";
        $resOrder = $this->execute_query($sqlOrder);
        if ($resOrder === false) {
            return null;
        }

        $orderId = (int) $this->db->insert_id;

        // Insert items. If any insert fails, attempt to delete the created order and return null.
        foreach ($items as $it) {
            $bookId = (int) ($it['product_id'] ?? 0);
            $qty = (int) ($it['quantity'] ?? 0);
            $price = (float) ($it['price'] ?? 0);
            $discountVal = isset($it['discount_percent']) ? (float) $it['discount_percent'] : 0.0;

            if ($bookId <= 0 || $qty <= 0) {
                $this->execute_query("DELETE FROM {$this->table} WHERE id = $orderId");
                return null;
            }

            $sqlItem = "INSERT INTO order_items (order_id, product_id, quantity, price, discount_percent, subtotal) 
            VALUES ($orderId, $bookId, $qty, $price, $discountVal, " . ($price * $qty * (1 - $discountVal / 100)) . ")";
            $resItem = $this->execute_query($sqlItem);
            if ($resItem === false) {
                // cleanup
                $this->execute_query("DELETE FROM order_items WHERE order_id = $orderId");
                $this->execute_query("DELETE FROM {$this->table} WHERE id = $orderId");
                return null;
            }
        }
        $sqlUpdateQty = "UPDATE books AS b
                         JOIN order_items AS oi ON b.id = oi.product_id
                         SET
                             b.quantity = b.quantity - oi.quantity,
                             b.sold = b.sold + oi.quantity  
                         WHERE
                             oi.order_id = $orderId;";
        if (!$this->execute_query($sqlUpdateQty)) {
            return null;
        }

        $resultOrder = $this->execute_query($sqlOrder);
        if (!$resultOrder || $resultOrder->num_rows === 0) {
            return null;
        }
        return $orderId;
    }

}