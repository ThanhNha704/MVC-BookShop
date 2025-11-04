<?php
// models/BaseModel.php 
class BaseModel extends Database
{

    protected $db;
    protected $table;

    // Kết nối database trong constructor
    public function __construct()
    {
        // Kết nối database
        $this->db = $this->connect();
    }

    // Thực thi truy vấn và trả về kết quả
    protected function execute_query(string $sql)
    {
        $result = $this->db->query($sql);

        if (!$result) {
            error_log("Lỗi SQL: " . $this->db->error . " | SQL: " . $sql);
            return false; // Trả về false nếu có lỗi truy vấn
        }
        return $result; // Trả về MySQLi_Result Object
    }

    // Lấy tất cả bản ghi từ bảng
    public function getProduct($selectFields = '*', $table = '', $where = '', $orderBy = '', $limit = ''): array
    {
        $sql = "SELECT {$selectFields} FROM {$table}";
        if (!empty($where)) {
            $sql .= " WHERE {$where}";
        }
        if (!empty($orderBy)) {
            $sql .= " ORDER BY {$orderBy}";
        }
        if (!empty($limit)) {
            $sql .= " LIMIT {$limit}";
        }

        // 1. Thực thi truy vấn
        $result = $this->execute_query($sql);

        if ($result === false) {
            return []; // Trả về mảng rỗng nếu có lỗi
        }

        $data = [];
        // 2. Lấy tất cả kết quả và giải phóng
        if (method_exists($result, 'fetch_all')) {
            $data = $result->fetch_all(MYSQLI_ASSOC) ?? [];
        } else {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        $result->free();
        return $data;
    }

    // -------------------------------------------------------------------
    // PHƯƠNG THỨC TÌM KIẾM (Đã xử lý bảo mật cơ bản)
    // -------------------------------------------------------------------

    // Tìm kiếm theo id (Trả về 1 bản ghi hoặc null)
    public function getById(string $table, string $column, string $id): ?array
    {
        // BẢO MẬT: Dùng real_escape_string
        $safe_id = $this->db->real_escape_string($id);

        // Luôn bao quanh ID bằng dấu nháy đơn trừ khi bạn chắc chắn ID là kiểu số nguyên
        // Nếu ID là kiểu số nguyên, chỉ cần: "{$column} = {$safe_id}"
        $sql = "SELECT * FROM {$table} WHERE {$column} = '{$safe_id}' LIMIT 1";

        $result = $this->execute_query($sql);

        if ($result && $result->num_rows > 0) {
            $data = $result->fetch_assoc();
            $result->free();
            return $data;
        }

        if ($result) {
            $result->free();
        }
        return null;
    }

    // Tìm kiếm theo tên/giá trị
    public function getByName(string $table, string $column, string $value): array
    {
        $safe_value = $this->db->real_escape_string($value);

        $sql = "SELECT * FROM {$table} WHERE {$column} LIKE '%{$safe_value}%'";

        $result = $this->execute_query($sql);

        if ($result === false) {
            return [];
        }

        $data = [];
        if (method_exists($result, 'fetch_all')) {
            $data = $result->fetch_all(MYSQLI_ASSOC) ?? [];
        } else {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        $result->free();
        return $data;
    }
}