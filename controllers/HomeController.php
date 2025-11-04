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
        if (isset($_SESSION['user_id'])) {

            // Nếu người dùng có vai trò là 'admin', chuyển hướng về trang quản trị
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                // Chuyển hướng đến Admin Dashboard
                return $this->redirect('?controller=admin');
            }
        }
        // Lấy tất cả sản phẩm.
        $products = $this->productModel->getProduct(
            '*',
            'books', // Tên bảng
            '',
            'sold DESC'
        );

        // Gán $products = [] nếu $products là null
        if (!is_array($products)) {
            $products = [];
        }

        return $this->view(
            'layouts/home/index',
            [
                'books' => $products,
                'current_controller' => 'home'
            ],
            false
        );
    }
    // PHƯƠNG THỨC MỚI CHO TRANG VỀ CHÚNG TÔI
    public function about()
    {
        return $this->view('pages/AboutUs', []);

    }
}