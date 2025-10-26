<?php
class OrdersController extends BaseController
{
    private $orderModel;

    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để xem đơn hàng.';
            header('Location: ?controller=auth&action=login');
            exit;
        }

        $this->loadModel('OrderModel');
        $this->orderModel = new OrderModel();
    }

    public function index()
    {
        $userId = (int) $_SESSION['user_id'];

        // Lấy đơn hàng của user
        $orders = $this->orderModel->getOrdersByUserId($userId);

        return $this->view('layouts/orders/index', ['orders' => $orders]);
    }

    public function detail()
    {
        $orderId = (int)($_GET['id'] ?? ($_POST['id'] ?? 0));
        if (!$orderId) {
            $_SESSION['error'] = 'Đơn hàng không hợp lệ.';
            return $this->redirect('?controller=orders');
        }

        $order = $this->orderModel->getOrderDetail($orderId);
        if (!$order) {
            $_SESSION['error'] = 'Không tìm thấy đơn hàng.';
            return $this->redirect('?controller=orders');
        }

        // Ensure the current user owns the order
        $userId = (int) $_SESSION['user_id'];
        if ((int)$order['user_id'] !== $userId) {
            $_SESSION['error'] = 'Bạn không có quyền xem đơn hàng này.';
            return $this->redirect('?controller=orders');
        }

        return $this->view('layouts/orders/view', ['order' => $order], false);
    }
}
