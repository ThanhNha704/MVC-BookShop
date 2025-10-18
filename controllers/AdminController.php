<?php
// controllers/AdminController.php

class AdminController extends BaseController
{
    private $productModel;
    private $orderModel;
    // ... Khai báo các Models khác

    public function __construct()
    {
        // QUAN TRỌNG: KIỂM TRA PHÂN QUYỀN TRUY CẬP
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Nếu không tồn tại session 'role' hoặc role không phải 'admin', cấm truy cập
        if (($_SESSION['role'] ?? 'guest') !== 'admin') {
            $_SESSION['error'] = "Bạn không có quyền truy cập vào trang quản trị.";
            // Sử dụng hàm redirect của BaseController, giả định nó chuyển hướng về index.php
            $this->redirect('index.php');
            exit(); 
        }

        // Load Models cần thiết
        // $this->loadModel('ProductModel');
        // $this->productModel = new ProductModel();
        // ...
    }

    /**
     * Hiển thị trang Dashboard Tổng quan (Yêu cầu 9, 10)
     */
    public function index()
    {
        // Lấy dữ liệu thống kê từ Models: Tổng sản phẩm, đơn hàng, doanh thu...
        $data = [
            'stats' => [], // Dữ liệu thống kê
            // ...
        ];
        
        // Tham số thứ 3 là true để sử dụng layout Admin (views/admin/layouts/master.php)
        return $this->view('admin/index', $data, true); 
    }

    /**
     * Hiển thị Quản lý Sản phẩm (Yêu cầu 3)
     */
    public function products()
    {
        // Lấy danh sách sản phẩm, xử lý tìm kiếm/phân trang
        return $this->view('admin/products/index', [], true);
    }
    
    /**
     * Hiển thị Quản lý Đơn hàng (Yêu cầu 5, 6)
     */
    public function orders()
    {
        // Lấy danh sách đơn hàng và trạng thái
        return $this->view('admin/orders/index', [], true);
    }
    
    /**
     * Hiển thị Quản lý Người dùng (Yêu cầu 7, 8)
     */
    public function users()
    {
        // Lấy danh sách người dùng, nhân viên, khách hàng
        return $this->view('admin/users/index', [], true);
    }
    
    /**
     * Hiển thị Quản lý Voucher & Khuyến mãi (Yêu cầu 11, 12)
     */
    public function vouchers()
    {
        // Quản lý tạo/sửa/xóa voucher
        return $this->view('admin/vouchers/index', [], true);
    }
    
    /**
     * Hiển thị Báo cáo Doanh thu (Yêu cầu 9)
     */
    public function revenue()
    {
        // Lấy dữ liệu doanh thu chi tiết
        return $this->view('admin/revenue/index', [], true);
    }
    
    // public function updateOrderStatus() { ... } // Ví dụ: POST method để xử lý trạng thái đơn hàng
}