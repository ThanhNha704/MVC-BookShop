<!-- <h1>ProductController</h1> -->
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
        $products = $this->productModel->getAll('*', 'books');
        return $this->view('layouts/products/index', ['books' => $products]);
    }

    public function show($id)
    {
        $product = $this->model->getById($id);
        $this->view->render('products/show', ['product' => $product]);
    }

    // SearchController.php (hoặc method search trong HomeController)
    public function search()
    {
        $keyword = $_GET['q'] ?? '';
        $keyword = htmlspecialchars(trim($keyword));

        // 2. Gọi Model để tìm kiếm (dùng phương thức findByName đã sửa lỗi)
        $productModel = $this->loadModel('ProductModel');
        $results = $productModel->findByName('books', 'title', $keyword);

        return $this->view('layouts/products/index', ['books' => $results]);
    }

    public function getById($id)
    {
        $product = $this->productModel->findById($id);
    }

    public function details()
    {
        $product = $this->productModel->findById('books', $_GET['id']);
        return $this->view('components/BookDetail', ['book'=>$product]);

    }
}