<h1>ProductController</h1>
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
        echo'index(productcontroller)';

        $searchQuery = $_GET['search_query'] ?? '';

        $products = $this->productModel->findByName('books', 'title', $searchQuery);
        return $this->view('product/index', ['books' => $products]);
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

        // 3. Tải View chỉ chứa danh sách sản phẩm (ví dụ: 'product/list_ajax')
        // *Lưu ý: Thay vì dùng $this->view(), bạn chỉ cần tải nội dung View và echo ra.*

        // Giả định bạn có hàm renderViewPart để tải một phần view mà không có layout
        // $htmlContent = $this->view('product/list_ajax', ['results' => $results]);
        return $this->view('product/index', ['books' => $results]);
        // Trả về HTML
        // echo $htmlContent;
    }
}