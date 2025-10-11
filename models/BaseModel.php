<?php
class BaseModel extends Database {

    protected $db;
    protected $table;

    // Kết nối database trong constructor
    public function __construct(){
        // Kết nối database
        $this->db = $this->connect();
    }

    // Lấy tất cả bản ghi từ bảng
    public function getAll($selectFields = '*', $table = '', $orderBy = '', $limit = 16) {
        $sql = "SELECT $selectFields FROM $table";
        if (!empty($orderBy)) {
            $sql .= " ORDER BY $orderBy";
        }
        $sql .= " LIMIT $limit";
        $result = $this->db->query($sql);
        if ($result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }

    public function findByName($table, $title, $name){
        $sql = "SELECT * FROM $table WHERE title LIKE '%$name%'";
        $query = $this->db->query($sql);
        return $result = $query->fetch_all(MYSQLI_ASSOC);
    }

    public function findById($table, $id)
{
    // RẤT QUAN TRỌNG: Dùng LIMIT 1 để tối ưu hóa truy vấn khi chỉ cần 1 hàng.
    // LƯU Ý: Đây là ví dụ KHÔNG SỬ DỤNG PREPARED STATEMENTS. Cần cẩn thận với SQL Injection.
    $sql = "SELECT * FROM $table WHERE id = $id LIMIT 1";
    
    $query = $this->db->query($sql);

    // Kiểm tra xem truy vấn có trả về kết quả không
    if ($query && $query->num_rows > 0) {
        // Dùng fetch_assoc() để chỉ lấy HÀNG ĐẦU TIÊN dưới dạng mảng kết hợp
        return $query->fetch_assoc();
    }
    
    return null; // Trả về null nếu không tìm thấy
}
}