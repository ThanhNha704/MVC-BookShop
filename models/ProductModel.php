<?php
class ProductModel extends BaseModel
{
    protected $table = 'books';

    // getAll trong BaseModel đã được viết cho việc gọi từ ProductModel
    public function getAll($selectFields = '*', $table = '', $orderBy = '', $limit = '')
    {
        return parent::getAll($selectFields, $this->table, $orderBy, $limit); 
    }
    
    public function getByName($name)
    {
        // Gọi findByName của BaseModel, truyền tên bảng và tên cột
        return $this-> findByName($this->table, 'title', $name); 
    }
    public function getBookById($id)
    {
        // Nhưng vì $this->table đã là 'books', ta chỉ cần gọi với $this->table và $id
        // HOẶC dùng $table được truyền vào.
        // Để nhất quán, ta dùng $table được truyền vào (BaseModel dùng $table)
        return parent::findById($this->table, $id);
    }
    
    // Thêm một phương thức tiện ích cho Controller để không cần truyền tên bảng
    // public function getBookById($id)
    // {
    //     // Gọi phương thức findById đã định nghĩa lại ở trên, truyền tên bảng mặc định
    //     return $this->findById($this->table, $id);
    // }
}