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
        $safeOrderId = intval($orderId);
        // THÊM: Vệ sinh $status bằng real_escape_string để tránh lỗi cú pháp và SQL Injection cơ bản
        $safeStatus = $this->db->real_escape_string($status);

        // Bắt buộc phải bọc $safeStatus bằng dấu nháy đơn
        $query = "UPDATE " . $this->table . " SET status = '$safeStatus' WHERE id = $safeOrderId";

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
                          o.id, o.user_id, o.total, o.status, o.created_at,
                          o.delivery_address_id,
                          u.username AS customer_name, u.email,
                          da.recipient_full_name, da.phone_number, da.address_line,
                          da.ward, da.district, da.city
                      FROM orders o
                      JOIN users u ON o.user_id = u.id
                      JOIN delivery_addresses da ON o.delivery_address_id = da.id
                      WHERE o.id = $orderId LIMIT 1";

        $resultOrder = $this->execute_query($sqlOrder);
        if (!$resultOrder || $resultOrder->num_rows === 0) {
            return null;
        }

        $order = $resultOrder->fetch_assoc();
        $resultOrder->free();

        // 2. Lấy chi tiết các sản phẩm
        $sqlDetails = "SELECT 
                          oi.book_id, oi.quantity, oi.price,
                          b.title AS book_title, b.author AS book_author
                        FROM order_items oi
                        JOIN books b ON oi.book_id = b.id
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

}