<?php
class ProductModel extends BaseModel
{
    protected $table = 'products';

    public function getAll($selectFields = '*', $table, $orderBy = '', $limit = 16)
    {
        return parent::getAll($selectFields, $table, $orderBy, $limit);
    }
    
    public function getById($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}