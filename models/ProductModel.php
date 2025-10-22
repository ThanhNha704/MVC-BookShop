<?php
class ProductModel extends BaseModel
{
    protected $table = 'books';

    // getAll trong BaseModel đã được viết cho việc gọi từ ProductModel
    public function getAll($selectFields = '*', $table = '', $orderBy = '', $limit = '')
    {
        return parent::getAll($selectFields, $this->table, $orderBy, $limit);
    }

    public function getProductByName(string $name): array
    {
        // Gọi getByName trong BaseModel (đã được sửa)
        // Lưu ý: Giá trị $name được xử lý tối thiểu (real_escape_string) trong BaseModel
        return parent::getByName($this->table, 'title', $name);
    }
    public function getProductById(string $id): array
    {
        return parent::getById($this->table, 'id', $id);
    }

    public function updateProduct($id, $data)
    {
        try {
            $setClause = [];
            foreach ($data as $key => $value) {
                if ($value !== null) {
                    $value = $this->db->real_escape_string($value);
                    $setClause[] = "`$key` = '$value'";
                }
            }

            if (empty($setClause)) {
                return false;
            }

            $setClause = implode(', ', $setClause);
            $sql = "UPDATE {$this->table} SET $setClause WHERE id = " . (int)$id;
            
            return $this->db->query($sql);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}