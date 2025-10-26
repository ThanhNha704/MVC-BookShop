<?php
// controllers/ReviewController.php
class ReviewController extends BaseController
{
    private $reviewModel;
    private $orderModel;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) session_start();

        $this->loadModel('ReviewModel');
        $this->reviewModel = new ReviewModel();

        $this->loadModel('OrderModel');
        $this->orderModel = new OrderModel();
    }

    // Xử lý thêm review (POST)
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->redirect('?controller=product');
        }

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            $_SESSION['error'] = 'Bạn cần đăng nhập để đánh giá sản phẩm.';
            return $this->redirect($_SERVER['HTTP_REFERER'] ?? '?controller=product');
        }

        $bookId = (int) ($_POST['book_id'] ?? 0);
        $rating = (int) ($_POST['rating'] ?? 5);
        $comment = trim($_POST['comment'] ?? '');

        if ($bookId <= 0) {
            $_SESSION['error'] = 'Sản phẩm không hợp lệ.';
            return $this->redirect($_SERVER['HTTP_REFERER'] ?? '?controller=product');
        }

        // Kiểm tra user đã mua sản phẩm chưa
        if (!$this->orderModel->userHasPurchasedBook($userId, $bookId)) {
            $_SESSION['error'] = 'Chỉ khách hàng đã mua sản phẩm mới được đánh giá.';
            return $this->redirect($_SERVER['HTTP_REFERER'] ?? '?controller=product');
        }

        $ok = $this->reviewModel->addReview($userId, $bookId, $rating, $comment);
        if ($ok) {
            $_SESSION['success'] = 'Cảm ơn bạn đã đánh giá sản phẩm.';
        } else {
            $_SESSION['error'] = 'Không thể gửi đánh giá. Vui lòng thử lại sau.' . $comment;
        }

        return $this->redirect($_SERVER['HTTP_REFERER'] ?? '?controller=product');
    }
}
