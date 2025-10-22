<?php
// models/BaseModel.php (Sử dụng MySQLi thuần và Query thô)

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

    // -------------------------------------------------------------------
    // HÀM queryOne (Lấy 1 bản ghi - Dùng query thô, không bảo mật)
    // -------------------------------------------------------------------

    public function queryOne(string $sql): ?array
    {
        // CHỈ SỬ DỤNG $this->db->query(), không dùng prepare/execute
        $result = $this->db->query($sql);

        if (!$result || $result->num_rows === 0) {
            return null;
        }

        // Dùng fetch_assoc() để lấy một hàng
        return $result->fetch_assoc();
    }

    // -------------------------------------------------------------------
    // HÀM query (Lấy nhiều bản ghi - Dùng query thô, không bảo mật)
    // -------------------------------------------------------------------
    public function query(string $sql): array
    {
        $result = $this->db->query($sql);

        // KIỂM TRA LỖI TRUY VẤN
        if ($result === false) {
            // Ghi log lỗi để dễ debug
            error_log("SQL Error: " . $this->db->error . " | Query: " . $sql);
            return [];
        }

        $data = [];

        // Sử dụng fetch_all nếu có (Tối ưu hơn)
        if (method_exists($result, 'fetch_all')) {
            $data = $result->fetch_all(MYSQLI_ASSOC) ?? [];
        } else {
            // Dùng vòng lặp fetch_assoc()
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        // Giải phóng kết quả và trả về
        if (isset($result) && is_object($result) && method_exists($result, 'free')) {
            $result->free();
        }
        return $data;
    }

    // -------------------------------------------------------------------
    // Hàm execute_query (Dùng cho INSERT/UPDATE/DELETE)
    // -------------------------------------------------------------------

    protected function execute_query(string $sql)
    {
        // Trong MySQLi, query() được dùng cho cả SELECT và Non-SELECT
        return $this->db->query($sql);
    }


    // Lấy tất cả bản ghi từ bảng (Giữ nguyên logic của bạn)
    public function getAll($selectFields = '*', $table = '', $orderBy = '', $limit = '')
    {
        // ... (Logic cũ của bạn, đã hoạt động với $this->db->query($sql)) ...
        $sql = "SELECT $selectFields FROM $table";
        if (!empty($orderBy)) {
            $sql .= " ORDER BY $orderBy";
        }
        if (!empty($limit)) {
            $sql .= " LIMIT $limit";
        }

        $result = $this->db->query($sql);

        // ... (Logic DEBUG của bạn không đổi) ...
        if (!$result) {
            // ... (hiển thị lỗi) ...
            return [];
        }

        if ($result && $result->num_rows > 0) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        if ($result && $result->num_rows === 0) {
        }
        return [];
    }

    // Tìm kiếm theo id
    public function getById($table, string $column, string $id)
    {
        $sql = "SELECT * FROM $table WHERE $column = $id";

        return $this->query($sql);
    }

    // Tìm kiếm theo tên
    public function getByName(string $table, string $column, string $value): array
    {
        $value = $this->db->real_escape_string($value);

        $sql = "SELECT * FROM {$table} WHERE {$column} LIKE '%{$value}%'";

        return $this->query($sql);
    }
}