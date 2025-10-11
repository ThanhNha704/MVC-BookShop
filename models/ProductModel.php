<?php
class ProductModel extends BaseModel
{
    protected $table = 'books';

    public function getAll($selectFields = '*', $table = '', $orderBy = '', $limit = 16)
    {
        return parent::getAll($selectFields, $table, $orderBy, $limit);
    }
    
    public function getByName($name)
    {
        return $this-> findByName('books', 'title', $name);
    }
    
    public function getById($table, $id)
    {
        return $this-> findById('books', $id);
    }

    
}