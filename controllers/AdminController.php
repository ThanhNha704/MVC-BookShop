<?php
// controllers/AdminController.php

class AdminController extends BaseController
{
    private $productModel;
    private $orderModel;
    private $userModel; // Thêm userModel để quản lý users/nhân viên
    // ... Khai báo các Models khác

    public function __construct()
    {
        // 1. Bắt đầu Session
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // 2. KIỂM TRA PHÂN QUYỀN TRUY CẬP (BẮT BUỘC)
        if (($_SESSION['role'] ?? 'guest') !== 'admin') {
            $_SESSION['error'] = "Bạn không có quyền truy cập vào trang quản trị.";
            // Chuyển hướng người dùng về trang chủ
            $this->redirect('index.php');
            exit();
        }
        $this->loadModel('ProductModel');
        $this->productModel = new ProductModel();
        $this->loadModel('OrderModel');
        $this->orderModel = new OrderModel();
    }
    /**
     * Hiển thị trang Dashboard Tổng quan
     */
    public function index()
    {
        // Giả lập dữ liệu thống kê
        $data = [
            'totalProducts' => 150,
            'totalOrders' => 45,
            'newUsers' => 12,
            'bestSeller' => "Harry Potter 7",
            'worstSeller' => "Tự truyện G.G.",
            'revenueData' => [],
        ];

        // SỬA: Đảm bảo có tham số TRUE để dùng layout Admin
        return $this->view('index', $data, true);
    }

    // ----------------------------------------------------
    // II. QUẢN LÝ SẢN PHẨM (Yêu cầu 3)
    // ----------------------------------------------------

    /**
     * Hiển thị danh sách Sản phẩm (thêm, xóa, sửa)
     */
    public function products()
    {
        $searchQuery = $_GET['search'] ?? '';

        if (!empty($searchQuery)) {
            // Nếu có từ khóa tìm kiếm, gọi hàm tìm kiếm an toàn (getByName)
            $products = $this->productModel->getByName($searchQuery);
        } else {
            // Nếu không có, lấy tất cả sản phẩm
            $products = $this->productModel->getAll('id, title, price, quantity, created_at', 'books');
        }

        // Truyền dữ liệu vào View.
        return $this->view('layouts/products/index', ['products' => $products], true);
    }

    /**
     * Hiển thị form Thêm Sản phẩm
     */
    public function addProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Logic xử lý thêm sản phẩm
            $_SESSION['success'] = "Thêm sản phẩm thành công.";
            return $this->redirect('/admin/products');
        }
        return $this->view('admin/products/add', [], true);
    }

    /**
     * Xử lý Sửa Sản phẩm
     */
    public function editProduct()
    {
        $id = $_GET['id'] ?? null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Logic xử lý cập nhật sản phẩm
            $_SESSION['success'] = "Cập nhật sản phẩm thành công.";
            return $this->redirect('/admin/products');
        }
        return $this->view('layouts/products/edit', ['product' => []], true);
    }

    // ----------------------------------------------------
    // III. QUẢN LÝ ĐƠN HÀNG (Yêu cầu 5, 6)
    // ----------------------------------------------------

    /**
     * Hiển thị danh sách Đơn hàng (sửa trạng thái)
     */
    public function orders()
    {
        $searchId = $_GET['search'] ?? null;
        
        // Gọi hàm getAllOrdersWithUserDetails (đã được sửa trong OrderModel)
        $orders = $this->orderModel->getAllOrdersWithUserDetails($searchId);
        
        // Giả định view nằm ở 'admin/orders/index'
        $data = [
            'orders' => $orders,
            'statuses' => OrderModel::STATUSES // Truyền trạng thái để dùng trong dropdown
        ];
        
        return $this->view('layouts/orders/index', $data, true); 
    }
    
    /**
     * Xử lý cập nhật trạng thái đơn hàng
     */
    public function updateOrderStatusAjax()
    {
        // Đặt header để trình duyệt hiểu đây là JSON
        header('Content-Type: application/json');
        
        $response = ['success' => false, 'message' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'] ?? null;
            $newStatus = $_POST['status'] ?? null;
            
            if ($orderId && $newStatus) {
                // Giả định $this->orderModel là đối tượng Model đã được khởi tạo
                if ($this->orderModel->updateStatus($orderId, $newStatus)) { 
                    $response['success'] = true;
                    $response['message'] = "Cập nhật trạng thái đơn hàng #$orderId thành công.";
                } else {
                    $response['message'] = "Lỗi: Không thể cập nhật đơn hàng #$orderId (Lỗi DB hoặc trạng thái không hợp lệ).";
                }
            } else {
                $response['message'] = "Thiếu thông tin cập nhật (ID hoặc Trạng thái).";
            }
        } else {
            $response['message'] = "Phương thức không hợp lệ. Chỉ chấp nhận POST.";
        }

        // Trả về kết quả dưới dạng JSON
        echo json_encode($response);
        exit; // Quan trọng: Dừng script để không tải thêm nội dung View nào khác
    }

    
    // ----------------------------------------------------
    // IV. QUẢN LÝ NGƯỜI DÙNG & ĐÁNH GIÁ (Yêu cầu 7, 8)
    // ----------------------------------------------------

    /**
     * Hiển thị danh sách Người dùng (Khách hàng & Nhân viên)
     */
    public function users()
    {
        // Lấy danh sách người dùng (cột role trong DB: admin, user)
        return $this->view('layouts/users/index', ['users' => []], true); // SỬA: Thêm TRUE
    }

    /**
     * Hiển thị danh sách Đánh giá
     */
    public function reviews()
    {
        // Lấy danh sách các đánh giá của sản phẩm
        return $this->view('layouts/users/reviews', ['reviews' => []], true); // SỬA: Thêm TRUE
    }

    // ----------------------------------------------------
    // V. KHUYẾN MÃI & BÁO CÁO (Yêu cầu 11, 12)
    // ----------------------------------------------------

    /**
     * Hiển thị Quản lý Voucher & Khuyến mãi (số lượng, thời hạn)
     */
    public function vouchers()
    {
        // Quản lý tạo/sửa/xóa voucher và khuyến mãi sản phẩm
        return $this->view('layouts/vouchers/index', ['vouchers' => []], true); // Giữ nguyên TRUE
    }

    /**
     * Hiển thị Báo cáo Doanh thu chi tiết (tuần, tháng, năm)
     */
    public function revenue()
    {
        // Lấy dữ liệu doanh thu chi tiết
        return $this->view('layouts/revenue/index', ['report' => []], true); // Giữ nguyên TRUE
    }
}