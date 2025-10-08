<h1>homecontroller</h1>
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
            'books'
        );

        // Gọi view home.php
        return $this->view('product/index', ['books'=>$products]);
    }

    public function search()
    {
        // $products = $this->productModel->getItem()
    }
}
