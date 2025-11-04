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
    
    // ==============================================
    // PHƯƠNG THỨC MỚI: CẬP NHẬT THÔNG TIN GIAO HÀNG
    // ==============================================
    public function updateShipping()
    {
        $orderId = (int) ($_POST['order_id'] ?? 0);
        $userId = (int) $_SESSION['user_id'];
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$orderId) {
            $_SESSION['error'] = 'Yêu cầu không hợp lệ.';
            return $this->redirect('?controller=orders&action=detail&id=' . $orderId);
        }

        $order = $this->orderModel->getOrderDetail($orderId);
        
        // Kiểm tra trạng thái đơn hàng (Chỉ cho phép sửa khi đang 'chờ xác nhận')
        if (strtolower($order['status']) !== 'chờ xác nhận') {
            $_SESSION['error'] = 'Chỉ có thể chỉnh sửa đơn hàng đang "chờ xác nhận".';
            return $this->redirect('?controller=orders&action=detail&id=' . $orderId);
        }

        // 3. Lấy và vệ sinh dữ liệu POST
        $recipient = trim($_POST['recipient_name'] ?? '');
        $phone = trim($_POST['phone_number'] ?? '');
        $address = trim($_POST['address_text'] ?? '');

        if (empty($recipient) || empty($phone) || empty($address)) {
            $_SESSION['error'] = 'Vui lòng điền đầy đủ thông tin giao hàng.';
            return $this->redirect('?controller=orders&action=detail&id=' . $orderId);
        }

        // 4. Cập nhật vào Model
        if ($this->orderModel->updateShippingDetails($orderId, $recipient, $phone, $address)) {
            $_SESSION['success'] = 'Cập nhật thông tin giao hàng thành công.';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi cập nhật thông tin giao hàng.';
        }

        return $this->redirect('?controller=orders&action=detail&id=' . $orderId);
    }
    
    // ==============================================
    // PHƯƠNG THỨC MỚI: HỦY ĐƠN HÀNG
    // ==============================================
    public function cancel()
    {
        // Nhận ID đơn hàng từ tham số GET (Giả định URL: ?controller=orders&action=cancel&id=X)
        $orderId = (int) ($_GET['id'] ?? 0);
        $userId = (int) $_SESSION['user_id'];
        
        if (!$orderId) {
            $_SESSION['error'] = 'Đơn hàng không hợp lệ.';
            return $this->redirect('?controller=orders');
        }

        $order = $this->orderModel->getOrderDetail($orderId);

        // 1. Kiểm tra quyền sở hữu và sự tồn tại
        if (!$order || (int)$order['user_id'] !== $userId) {
            $_SESSION['error'] = 'Bạn không có quyền hủy đơn hàng này.';
            return $this->redirect('?controller=orders');
        }
        
        // 2. Kiểm tra trạng thái đơn hàng (Chỉ cho phép hủy khi đang 'chờ xác nhận')
        if (strtolower($order['status']) !== 'chờ xác nhận') {
            $_SESSION['error'] = 'Chỉ có thể hủy đơn hàng đang "chờ xác nhận".';
            return $this->redirect('?controller=orders&action=detail&id=' . $orderId);
        }

        // 3. Hủy đơn hàng trong Model
        if ($this->orderModel->cancelOrder($orderId)) {
            $_SESSION['success'] = 'Đơn hàng #' . $orderId . ' đã được hủy thành công.';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi hủy đơn hàng.';
        }

        return $this->redirect('?controller=orders&action=detail&id=' . $orderId);
    }
}