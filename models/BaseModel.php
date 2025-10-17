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
    public function getAll($selectFields = '*', $table = '', $orderBy = '', $limit = '') {
        $sql = "SELECT $selectFields FROM $table";
        if (!empty($orderBy)) {
            $sql .= " ORDER BY $orderBy";
        }
        if(!empty($limit)) {
            $sql .= " LIMIT $limit";
        }
        
        $result = $this->db->query($sql);
        
        // --- MÃ DEBUG RẤT QUAN TRỌNG ĐỂ XÁC ĐỊNH NGUYÊN NHÂN ---
        if (!$result) {
            echo "<pre style='color: red; font-size: 16px; border: 1px solid red; padding: 10px;'>";
            echo "LỖI TRUY VẤN DB: KHÔNG THỂ LẤY SÁCH\n";
            echo "Lỗi: " . $this->db->error . "\n";
            echo "SQL: " . $sql . "\n";
            echo "Vui lòng kiểm tra lại tên bảng 'books' và dữ liệu trong đó.";
            echo "</pre>";
            return [];
        }
        
        // Kiểm tra nếu truy vấn thành công VÀ có kết quả
        if ($result && $result->num_rows > 0) { 
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        if ($result && $result->num_rows === 0) {
            echo "<pre style='color: orange; font-size: 16px; border: 1px solid orange; padding: 10px;'>";
            echo "THÔNG BÁO: Truy vấn thành công, nhưng bảng 'books' hiện không có bản ghi nào. Vui lòng thêm dữ liệu.";
            echo "</pre>";
        }
        // -----------------------------------------------------------
        
        // Luôn trả về mảng rỗng nếu không có kết quả hoặc truy vấn thất bại
        return [];
    }

    public function findByName($table, $title, $name){
        $sql = "SELECT * FROM $table WHERE title LIKE '%$name%'";
        $query = $this->db->query($sql);
        if (!$query) { /* DEBUG */ return []; }
        return $result = $query->fetch_all(MYSQLI_ASSOC);
    }

    public function findById($table, $id)
    {
        // RẤT QUAN TRỌNG: Dùng LIMIT 1 để tối ưu hóa truy vấn khi chỉ cần 1 hàng.
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