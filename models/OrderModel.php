<?php
// models/OrderModel.php

class OrderModel extends BaseModel {
    
    protected $table = 'orders'; 

    // Các trạng thái đơn hàng (Dựa trên enum trong DB)
    const STATUSES = ['chờ xác nhận', 'xác nhận', 'đang giao', 'đã giao', 'thành công', 'đã hủy'];

    /**
     * Lấy danh sách tất cả đơn hàng, JOIN với users để lấy tên khách hàng.
     * Hỗ trợ tìm kiếm theo ID đơn hàng.
     * SỬ DỤNG QUERY THÔ (KHÔNG AN TOÀN)
     */
    public function getAllOrdersWithUserDetails($searchId = null) {
        // Vệ sinh đầu vào thô (chỉ chấp nhận số)
        $where = '';
        if (!empty($searchId) && is_numeric($searchId)) {
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
        
        $result = $this->db->query($sql);

        if (!$result) {
            // Xử lý lỗi (BaseModel không có hàm này)
            // echo "Lỗi truy vấn: " . $this->db->error; 
            return [];
        }
        
        return $result->fetch_all(MYSQLI_ASSOC) ?? [];
    }

    /**
     * Cập nhật trạng thái đơn hàng (SỬ DỤNG QUERY THÔ)
     */
    public function updateStatus($orderId, $status) {
    // ... (kiểm tra in_array)

    // Bắt buộc phải bọc $status bằng dấu nháy đơn
    $query = "UPDATE " . $this->table . " SET status = '$status' WHERE id = $orderId";
    
    $result = $this->db->query($query);

    if (!$result) {
         // Thêm DEBUG để biết lỗi SQL là gì (ví dụ: error_log("SQL Error: " . $this->db->error);)
         return false;
    }

    return $result;
}
}
?>