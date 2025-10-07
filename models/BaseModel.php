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
    public function getItem($selectFields = '*', $table, $orderBy = '', $limit = 16) {
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
}