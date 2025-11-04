<?php
class ProductModel extends BaseModel
{
    protected $table = 'books';

    // Giữ đúng kiểu hàm với BaseModel
    public function getProduct($selectFields = '*', $table = '', $where = '', $orderBy = '', $limit = ''): array
    {
        // Luôn sử dụng $this->table thay vì $table bên ngoài
        return parent::getProduct($selectFields, $this->table, $where, $orderBy, $limit);
    }

    public function getProductByName(string $name): array
    {
        $name = $this->db->real_escape_string($name);
        $sql = "SELECT * FROM {$this->table} WHERE title LIKE '%{$name}%'";
        $result = $this->execute_query($sql);

        $data = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function getProductById(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = " . (int)$id . " LIMIT 1";
        $result = $this->execute_query($sql);

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        return null;
    }

    public function updateProduct($id, $data): bool
    {
        $setClause = [];
        foreach ($data as $key => $value) {
            $value = $this->db->real_escape_string($value);
            $setClause[] = "`$key` = '$value'";
        }

        $sql = "UPDATE {$this->table} SET " . implode(',', $setClause) . " WHERE id = " . (int)$id;
        return $this->execute_query($sql) ? true : false;
    }

    public function createProduct(array $data): ?int
    {
        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            $fields[] = "`$key`";
            $values[] = "'" . $this->db->real_escape_string($value) . "'";
        }

        $sql = "INSERT INTO {$this->table} (" . implode(',', $fields) . ") VALUES (" . implode(',', $values) . ")";
        if ($this->execute_query($sql)) {
            return $this->db->insert_id;
        }
        return null;
    }
    public function getProductsByCategory(string $category): array
{
    // Nếu category là "Tất cả" thì trả về toàn bộ
    if ($category === 'Tất cả') {
        return $this->getProduct('*', '', '', '', '');
    }

    // Tránh lỗi SQL injection
    $safeCategory = $this->db->real_escape_string($category);

    // Lọc theo cột 'category' trong bảng books
    $sql = "SELECT * FROM {$this->table} WHERE category = '$safeCategory' AND is_visible = 1";
    $result = $this->execute_query($sql);

    $products = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }

    return $products;
}
}
