<?php
class ProductModel extends BaseModel
{
    protected $table = 'books';

    // getProduct trong BaseModel đã được viết cho việc gọi từ ProductModel
    public function getProduct($selectFields = '*', $table = '', $where ='', $orderBy = '', $limit = '')
    {
        return parent::getProduct($selectFields, $this->table, $where, $orderBy, $limit);
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

    // Kiểm tra xem sản phẩm có đơn hàng đang chờ giao không
    public function hasActiveOrders($productId): bool
    {
        $sql = "SELECT *
                FROM order_details od
                JOIN orders o ON od.order_id = o.id 
                WHERE od.product_id = " . (int) $productId . "
                AND o.status IN ('xác nhận', 'đang giao', 'shipping')";

        $result = $this->db->query($sql);
        if ($result && $row = $result->fetch_assoc()) {
            return false;
        }
        return true;
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
            $sql = "UPDATE {$this->table} SET $setClause WHERE id = " . (int) $id;

            return $this->db->query($sql);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }
}