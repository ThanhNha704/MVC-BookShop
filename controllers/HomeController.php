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
        // Lấy tất cả sản phẩm.
        $products = $this->productModel->getAll(
            '*',
            'books', // Tên bảng
            'sold DESC'
        );
        
        // Gán $products = [] nếu $products là null
        if (!is_array($products)) {
            $products = [];
        }

        return $this->view('layouts/home/index', [
            'books' => $products,
            'current_controller' => 'home' 
        ]);
    }
    // PHƯƠNG THỨC MỚI CHO TRANG VỀ CHÚNG TÔI
    public function about()
    {
        // Gọi view tĩnh AboutUs.php (Cần tạo tệp này)
        return $this->view('pages/AboutUs', []);
    }

    public function search()
    {
        // $products = $this->productModel->getItem()
    }
}
