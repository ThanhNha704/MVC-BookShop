<?php
class ProductController extends BaseController
{

    public function index()
    {
        $products = $this->model->getAll('id, name, price', 'products', 'name ASC');
        $this->view->render('products/index', ['products' => $products]);
    }

    public function show($id)
    {
        $product = $this->model->getById($id);
        $this->view->render('products/show', ['product' => $product]);
    }
}