<?php
class ProductModel extends BaseModel
{
    protected $table = 'books';

    // getProduct trong BaseModel đã được viết cho việc gọi từ ProductModel
    public function getProduct($selectFields = '*', $table = '', $where = '', $orderBy = '', $limit = '') :array
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
        $productId = (int) $id;

        // Fetch product row
        $sql = "SELECT * FROM {$this->table} WHERE id = {$productId} LIMIT 1";
        $result = $this->execute_query($sql);
        if (! $result || $result->num_rows === 0) {
            return [];
        }

        $product = $result->fetch_assoc();

        // Fetch reviews for this product (if reviews table exists)
        $reviews = [];
        $reviewSql = "SELECT r.*, u.username as user_name
                      FROM reviews r
                      LEFT JOIN users u ON r.user_id = u.id
                      WHERE r.book_id = {$productId}
                      ORDER BY r.created_at DESC";

        $rRes = $this->execute_query($reviewSql);
        if ($rRes) {
            while ($row = $rRes->fetch_assoc()) {
                $reviews[] = $row;
            }
        }


        return $product;
    }

    // Kiểm tra xem sản phẩm có đơn hàng đang chờ giao không
    public function hasActiveOrders($productId): bool
    {
    $sql = "SELECT *
        FROM order_items oi
        JOIN orders o ON oi.order_id = o.id 
        WHERE oi.book_id = " . (int) $productId . "
        AND o.status IN ('thành công')";

        $result = $this->execute_query($sql);
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

            return $this->execute_query($sql);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function createProduct(array $data): ?int
    {
        try {
            // Xử lý và kiểm tra dữ liệu đầu vào
            $title = $this->db->real_escape_string($data['title']);
            $author = $this->db->real_escape_string($data['author']);
            $price = (float) $data['price'];
            $description = $this->db->real_escape_string($data['description']);
            $quantity = (int) $data['quantity'];
            $discount = isset($data['discount']) ? (float) $data['discount'] : 0;
            $image = isset($data['image']) ? $this->db->real_escape_string($data['image']) : '';
            $isVisible = isset($data['is_visible']) ? 1 : 0;

            $sql = "INSERT INTO {$this->table} (
                title, author, price, description, quantity, 
                discount, image, is_visible, created_at, updated_at
            ) VALUES (
                '$title', '$author', $price, '$description', $quantity,
                $discount, '$image', $isVisible, NOW(), NOW()
            )";

            if ($this->execute_query($sql)) {
                return $this->db->insert_id;
            }

            return null;
        } catch (Exception $e) {
            error_log("Lỗi khi thêm sản phẩm: " . $e->getMessage());
            return null;
        }
    }
    public function getProductsByCategory($category)
{
    // Nếu chọn "Tất cả" thì trả về toàn bộ sản phẩm
    if ($category === 'Tất cả') {
        return $this->getProduct('*', 'books');
    }

    // Lọc sản phẩm theo thể loại (category)
    $where = "category = '" . $this->db->real_escape_string($category) . "'";
    return $this->getProduct('*', 'books', $where);
}
}
