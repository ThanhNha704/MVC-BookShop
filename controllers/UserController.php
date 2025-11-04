<?php
// UserController.php
class UserController extends BaseController
{
    private $userModel;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();

        $this->loadModel('UserModel');
        $this->userModel = new UserModel();
    }

    public function profile()
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            $_SESSION['error'] = 'Bạn cần đăng nhập để xem hồ sơ.';
            return $this->redirect('?controller=auth&action=login');
        }

        $user = $this->userModel->findById($userId);
        if (!$user) {
            $_SESSION['error'] = 'Người dùng không tồn tại.';
            return $this->redirect('?controller=auth&action=login');
        }
        $user['user_tier'] = $this->userModel->getMembershipLevels($userId);
        $data = [
            'user' => $user,
            'user_revenue' => $this->userModel->getUserRevenue($userId),
            'order_count' => $this->userModel->getCompletedOrderCount($userId),
            'review_count' => $this->userModel->getUserReviewCount($userId),
        ];

        $this->view('profile/index', $data);
    }

}