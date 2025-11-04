<?php
// models/OrderModel.php (Đã sửa hàm cancelOrder chỉ dùng execute_query)

class OrderModel extends BaseModel
{
    protected $table = 'orders';

    // Các trạng thái đơn hàng
    const STATUSES = ['chờ xác nhận', 'xác nhận', 'đang giao', 'đã giao', 'thành công', 'đã hủy'];

    /**
     * Lấy tất cả đơn hàng kèm tên khách hàng, có thể tìm theo ID. (Giữ nguyên)
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
                    o.discount,
                    o.subtotal AS final_total,
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

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $result->free();
        return $data;
    }

    // Cập nhật trạng thái đơn hàng (Giữ nguyên)
    public function updateStatus($orderId, $status)
    {

        $query = "UPDATE " . $this->table . " SET status = '$status' WHERE id = $orderId";
        $result = $this->execute_query($query);

        if ($status == 'đã hủy') {
            // Cập nhật lại số lượng sách trong kho khi hủy đơn
            $queryQty = "UPDATE books AS b
                          JOIN order_items AS oi ON b.id = oi.product_id
                          SET
                              b.quantity = b.quantity + oi.quantity,
                              b.sold = b.sold - oi.quantity  
                          WHERE
                              oi.order_id = $orderId;";
            $this->execute_query($queryQty);
        }

        if (!$result) {
            return false;
        }

        return true;
    }

    public function getOrderDetail($orderId)
    {
        $sqlOrder = "SELECT 
                            o.id, o.user_id, o.total, o.discount, o.subtotal AS final_total, o.status, o.created_at,
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

        $sqlDetails = "SELECT 
                            b.title AS book_title, b.author AS book_author,
                            b.image AS book_image,
                            oi.product_id, oi.quantity, oi.price, oi.discount_percent, oi.subtotal AS final_item_subtotal,
                            o.total, o.discount, o.subtotal
                                FROM orders o 
                                JOIN order_items oi ON oi.order_id = o.id
                                JOIN books b ON oi.product_id = b.id
                                WHERE oi.order_id = $orderId";
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

    // Kiểm tra xem user đã mua product (book) nào chưa (Giữ nguyên)
    public function userHasPurchasedBook(?int $userId, int $bookId): bool
    {
        if ($userId === null)
            return false;
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

    // Lấy tổng số đơn hàng (Giữ nguyên)
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

    // Tính tổng doanh thu (Giữ nguyên)
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

    // Lấy danh sách đơn hàng của 1 user (Giữ nguyên)
    public function getOrdersByUserId(int $userId): array
    {
        $userId = (int) $userId;
        $sql = "SELECT id, subtotal, status, created_at FROM {$this->table} WHERE user_id = $userId ORDER BY created_at DESC";
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

    public function createOrder(array $data): ?int
    {
        $userId = (int) ($data['user_id'] ?? 0);
        $deliveryRecipient = $this->db->real_escape_string($data['delivery_recipient'] ?? '');
        $deliveryPhone = $this->db->real_escape_string($data['delivery_phone'] ?? '');
        $deliveryAddress = $this->db->real_escape_string($data['delivery_address'] ?? '');

        $subtotal = (float) ($data['subtotal'] ?? 0);
        $discount = (float) ($data['discount'] ?? 0);
        $total = (float) ($data['total'] ?? 0);

        $items = $data['items'] ?? [];

        if ($userId <= 0 || empty($items) || $total <= 0) {
            return null;
        }

        // CHÈN ĐƠN HÀNG VÀO BẢNG orders
        $sqlOrder = "INSERT INTO " . $this->table . " (user_id, delivery_recipient, delivery_phone, delivery_address, total, discount, subtotal, status, created_at, updated_at) 
    VALUES ($userId, '$deliveryRecipient', '$deliveryPhone', '$deliveryAddress', " . $total . ", " . $discount . ", " . $subtotal . ", 'chờ xác nhận', NOW(), NOW())";

        $resOrder = $this->execute_query($sqlOrder);

        if ($resOrder === false) {
            return null;
        }

        $orderId = (int) $this->db->insert_id;

        // CHÈN TỪNG MỤC VÀO order_items
        foreach ($items as $it) {
            $bookId = (int) ($it['product_id'] ?? 0);
            $qty = (int) ($it['quantity'] ?? 0);
            $price = (float) ($it['price'] ?? 0);
            $discountVal = isset($it['discount_percent']) ? (float) $it['discount_percent'] : 0.0;

            $itemSubtotal = $price * $qty * (1 - $discountVal / 100);

            $sqlItem = "INSERT INTO order_items (order_id, product_id, quantity, price, discount_percent, subtotal) 
        VALUES ($orderId, $bookId, $qty, $price, $discountVal, $itemSubtotal)";

            $resItem = $this->execute_query($sqlItem);

            if ($resItem === false) {
                // Cleanup khi có lỗi
                $this->execute_query("DELETE FROM order_items WHERE order_id = $orderId");
                $this->execute_query("DELETE FROM {$this->table} WHERE id = $orderId");
                return null;
            }
        }

        // CẬP NHẬT SỐ LƯỢNG TỒN KHO VÀ SẢN PHẨM ĐÃ BÁN
        $sqlUpdateQty = "UPDATE books AS b
                      JOIN order_items AS oi ON b.id = oi.product_id
                      SET
                          b.quantity = b.quantity - oi.quantity,
                          b.sold = b.sold + oi.quantity  
                      WHERE
                          oi.order_id = $orderId";

        if (!$this->execute_query($sqlUpdateQty)) {
            return null;
        }

        return $orderId;
    }

    // ==============================================
    // CẬP NHẬT THÔNG TIN GIAO HÀNG
    // ==============================================
    public function updateShippingDetails(int $orderId, string $recipient, string $phone, string $address): bool
    {
        // Vệ sinh dữ liệu đầu vào để tránh SQL Injection
        $safeRecipient = $this->db->real_escape_string($recipient);
        $safePhone = $this->db->real_escape_string($phone);
        $safeAddress = $this->db->real_escape_string($address);

        $sql = "UPDATE " . $this->table . " SET 
                    delivery_recipient = '$safeRecipient', 
                    delivery_phone = '$safePhone', 
                    delivery_address = '$safeAddress' 
                WHERE id = $orderId";

        $result = $this->execute_query($sql);

        return $result !== false;
    }

    // ==============================================
    // HỦY ĐƠN HÀNG
    // ==============================================
    public function cancelOrder(int $orderId): bool
    {
        $status_canceled = 'đã hủy';

        // 1. CẬP NHẬT TỒN KHO VÀ SỐ LƯỢNG ĐÃ BÁN (Tăng tồn kho, giảm đã bán)
        // Hoàn trả tồn kho (quantity +) và giảm số lượng đã bán (sold -)
        $sqlRevertQty = "UPDATE books AS b
                          JOIN order_items AS oi ON b.id = oi.product_id
                          SET
                              b.quantity = b.quantity + oi.quantity,
                              b.sold = b.sold - oi.quantity  
                          WHERE
                              oi.order_id = $orderId;";

        $resultRevert = $this->execute_query($sqlRevertQty);

        if ($resultRevert === false) {
            // Nếu hoàn trả tồn kho thất bại, dừng lại và trả về lỗi
            return false;
        }

        // 2. CẬP NHẬT TRẠNG THÁI ĐƠN HÀNG
        $sql = "UPDATE " . $this->table . " SET status = '$status_canceled' WHERE id = $orderId";

        $resultUpdateStatus = $this->execute_query($sql);

        // Trả về kết quả của việc cập nhật trạng thái (sau khi đã hoàn trả tồn kho)
        return $resultUpdateStatus !== false;
    }
}