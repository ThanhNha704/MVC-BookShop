<?php
class ProductController extends BaseController
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
        $products = $this->productModel->getProduct('*', 'books', 'is_visible = 1');
        return $this->view('layouts/products/index', ['books' => $products]);
    }

    public function show($id)
    {
        // Sửa lỗi: Dùng getBookById mới, và dùng $this->view
        $product = $this->productModel->getProductById($id);
        return $this->view('components/BookDetail', ['book' => $product]);
    }

    // SearchController.php (hoặc method search trong HomeController)
    public function search()
    {
        $keyword = $_GET['q'] ?? '';
        $keyword = htmlspecialchars(trim($keyword));

        // 2. Gọi Model để tìm kiếm (dùng phương thức getByName đã sửa trong Model)
        $results = $this->productModel->getProductByName($keyword);

        return $this->view('layouts/products/index', ['books' => $results]);
    }


    public function details()
    {
        $id = $_GET['id'] ?? null;
        if ($id === null) {
            return;
        }

        // Sửa lỗi: Dùng getBookById mới
        $product = $this->productModel->getProductById($id);

        if (!$product) {
            // Xử lý không tìm thấy sách
            return;
        }

        return $this->view('components/BookDetail',  ['book'=>$product]);
    }
}