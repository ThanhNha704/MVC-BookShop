<?php
class ProductController extends BaseController
{
    private $productModel;
    private $orderModel;
    private $reviewModel;

    public function __construct()
    {
        // Gọi model để lấy dữ liệu sản phẩm hiển thị ở trang chủ
        $this->loadModel('ProductModel');
        $this->productModel = new ProductModel();
        $this->loadModel('OrderModel');
        $this->orderModel = new OrderModel();
        $this->loadModel('ReviewModel');
        $this->reviewModel = new ReviewModel();
    }
    public function index()
    {
        // Nếu có category trong URL thì lọc theo category
        if (isset($_GET['category']) && $_GET['category'] !== 'Tất cả') {
            $category = $_GET['category'];
            $products = $this->productModel->getProductsByCategory($category);
        } else {
            // Mặc định hiện tất cả
            $products = $this->productModel->getProduct('*', '', '', '', '');
        }

        $this->view('layouts/products/index', ['products' => $products]);
    }

    public function show($id)
    {
        // Sửa lỗi: Dùng getBookById mới, và dùng $this->view
        $product = $this->productModel->getProductById($id);
        // Prepare reviews and purchase flag
        $reviews = $this->reviewModel->getReviewsForBook($id);
        $userId = $_SESSION['user_id'] ?? null;
        $canReview = $userId ? $this->orderModel->userHasPurchasedBook($userId, $id) : false;

        return $this->view('layouts/products/detail', ['book' => $product, 'reviews' => $reviews, 'canReview' => $canReview]);
    }

    // SearchController.php
    public function search()
    {
        $keyword = $_GET['keyword'] ?? '';
        $products = $this->productModel->getProductByName($keyword);
        $this->view('layouts/products/index', ['products' => $products]);
    }


    public function details()
    {
        $id = $_GET['id'] ?? null;
        if ($id === null) {
            return;
        }

        $product = $this->productModel->getProductById($id);

        if (!$product) {
            return;
        }

        $reviews = $this->reviewModel->getReviewsForBook($id);
        $userId = $_SESSION['user_id'] ?? null;
        if (
            $this->orderModel->userHasPurchasedBook($userId, $id)
            && !($this->reviewModel->haveReview($userId, $id))
        ) {
            $canReview = true;
        } else {
            $canReview = false;
        }
        $canDelete = $userId ? $this->reviewModel->haveReview($userId, $id) : false;

        return $this->view('layouts/products/detail', ['book' => $product, 'reviews' => $reviews, 'canReview' => $canReview, 'canDelete' => $canDelete]);
    }
}
