<?php
class ProductModel extends BaseModel
{
    protected $table = 'books';

    public function getAll($selectFields = '*', $table, $orderBy = '', $limit = 16)
    {
        return parent::getAll($selectFields, $table, $orderBy, $limit);
    }
    
    public function getItem($id)
    {
        return $this-> findByName('books', 'title', $id);
    }
}