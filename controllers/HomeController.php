<?php
class HomeController extends BaseController
{

    private $productModel;

    public function __construct()
    {
        // Gọi model để lấy dữ liệu sản phẩm hiển thị ở trang chủ
        $this->loadModel('ProductModel');
        // Vấn đề có thể nằm ở đây: $this->loadModel() đã nạp model vào $this->model (của BaseController)
        // Nếu loadModel không gán vào $this->productModel, ta cần sửa lại BaseController.
        
        // GIẢI PHÁP: Giả định loadModel() gán model vào $this->model, sau đó ta gán nó vào biến riêng.
        // Tuy nhiên, để đơn giản, ta chỉ cần khởi tạo nó một lần.
        
        $this->productModel = new ProductModel(); // Giữ nguyên cách này nếu loadModel() chỉ nạp file
    }

     public function index()
    {
        // Lấy tất cả sản phẩm.
        $products = $this->productModel->getAll(
            '*',
            'books', // Tên bảng
            'created_at DESC'
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
