<h1>mo duoc HomeController</h1>
<?php
class HomeController extends BaseController
{

    private $productModel;

    public function __construct()
    {
        echo"construct trong HomeController";
        // Gọi model để lấy dữ liệu sản phẩm hiển thị ở trang chủ
        $this->loadModel('ProductModel');
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        echo'----index trong homeController';
         // Lấy tất cả sản phẩm
        $products = $this->productModel->getItem(
            '*',
            'books'
        );

        // Gọi view home.php
        return $this->view('product/index', ['books'=>$products]);
    }
}
