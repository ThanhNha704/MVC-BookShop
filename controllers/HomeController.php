<?php
class HomeController extends BaseController
{

    private $productModel;

    public function __construct()
    {
        // Gọi model để lấy dữ liệu sản phẩm hiển thị ở trang chủ
        $this->loadModel('ProductModel');
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        // Lấy tất cả sản phẩm
        $products = $this->productModel->getAll(
            '*',
            'books',
            'created_at DESC'
        );

        // Gọi view home.php
        // return $this->view('home/index', ['books'=>$products]);
        return $this->view('layouts/home/index', ['books' => $products]);

    }

    public function search()
    {
        // $products = $this->productModel->getItem()
    }
}
