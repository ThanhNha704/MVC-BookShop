<?php
// controllers/AdminController.php

class AdminController extends BaseController
{
    private $productModel;
    private $orderModel;
    private $userModel;

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
        $this->loadModel('UserModel');
        $this->userModel = new UserModel();
    }
    // Hiển thị trang Dashboard Tổng quan
    public function index()
    {
        // $orderModel = new OrderModel();
        $totalOrders = $this->orderModel->getTotalOrderCount();
        $totalRevenue = $this->orderModel->getTotalRevenue();
        $totalNewUser = $this->userModel->getTotalNewUsers();

        // Lấy dữ liệu của order
        $order = $this->orderModel->getAllOrdersWithUserDetails();

        $data = [
            'totalRevenue' => $totalRevenue,
            'totalOrders' => $totalOrders,
            'newUsers' => $totalNewUser,
            'recentOrders' => $order
        ];

        return $this->view('index', $data, true);
    }

    // ----------------------------------------------------
    // I. QUẢN LÝ SẢN PHẨM
    // ----------------------------------------------------

    // Hiển thị danh sách sản phẩm
    public function products()
    {
        $searchQuery = $_GET['search'] ?? '';

        if (!empty($searchQuery)) {
            $products = $this->productModel->getProductByName($searchQuery);
        } else {
            $products = $this->productModel->getProduct('*', 'books');
        }

        return $this->view('layouts/products/index', ['products' => $products], true);
    }

    // Thêm sản phẩm
    public function addProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Thu thập dữ liệu từ form
            $productData = [
                'title' => $_POST['title'] ?? '',
                'author' => $_POST['author'] ?? '',
                'price' => $_POST['price'] ?? 0,
                'description' => $_POST['description'] ?? '',
                'quantity' => $_POST['quantity'] ?? 0,
                'discount' => $_POST['discount'] ?? 0,
                'is_visible' => isset($_POST['is_visible']) ? 1 : 0
            ];

            // Kiểm tra dữ liệu bắt buộc
            if (empty($productData['title']) || empty($productData['author']) || 
                empty($productData['price']) || empty($productData['quantity'])) {
                $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin bắt buộc.";
                $_SESSION['old_input'] = $_POST;
                return $this->redirect('?controller=admin&action=addProduct');
            }

            // Xử lý upload ảnh
            if (!empty($_FILES['image']['name'])) {
                $uploadDir = 'public/products/';
                $fileName = str_replace(' ', '_', $productData['title']) . '_' . basename($_FILES['image']['name']);
                $targetFile = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                    $productData['image'] = $fileName;
                } else {
                    $_SESSION['error'] = "Không thể upload ảnh.";
                    $_SESSION['old_input'] = $_POST;
                    return $this->redirect('?controller=admin&action=addProduct');
                }
            }

            // Thêm sản phẩm vào database
            $newProductId = $this->productModel->createProduct($productData);

            if ($newProductId) {
                $_SESSION['success'] = "Thêm sản phẩm thành công.";
                return $this->redirect('?controller=admin&action=products');
            } else {
                $_SESSION['error'] = "Có lỗi xảy ra khi thêm sản phẩm.";
                $_SESSION['old_input'] = $_POST;
                return $this->redirect('?controller=admin&action=addProduct');
            }
        }

        return $this->view('layouts/products/add', [], true);
    }

    // edit sản phẩm
    public function editProduct()
    {
        $productId = $_GET['id'] ?? null;

        if (!$productId || !is_numeric($productId)) {
            header('Location: ?controller=admin&action=products');
            exit;
        }

        $product = $this->productModel->getProductById((int) $productId);

        if (!$product) {
            $_SESSION['error'] = "Không tìm thấy sản phẩm có ID: {$productId}.";
            header('Location: ?controller=admin&action=products');
            exit;
        }

        $data = [
            'product' => $product,
            'page_title' => 'Sửa Sản phẩm',
            'current_controller' => 'admin',
            'current_action' => 'products'
        ];

        $this->view('layouts/products/edit', $data, true);
    }

    public function toggleProductStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = "Phương thức không hợp lệ.";
            return $this->redirect('?controller=admin&action=products');
        }

        $productId = $_POST['product_id'] ?? null;
        $action = $_POST['is_visible'] ?? '';

        if (!$productId || !in_array($action, ['show', 'hide'])) {
            $_SESSION['error'] = "Dữ liệu không hợp lệ.";
            return $this->redirect('?controller=admin&action=products');
        }

        // Kiểm tra đơn hàng đang giao khi cố gắng ẩn sản phẩm
        if ($action === 'hide' && $this->productModel->hasActiveOrders($productId)) {
            $_SESSION['error'] = "Không thể ẩn sản phẩm này vì đang có đơn hàng đang giao.";
            return $this->redirect('?controller=admin&action=products');
        }

        $isVisible = $action === 'show';
        if ($this->productModel->updateProduct($productId, ['is_visible' => $isVisible ? 1 : 0])) {
            $_SESSION['success'] = $isVisible ? "Đã hiện sản phẩm." : "Đã ẩn sản phẩm.";
        } else {
            $_SESSION['error'] = "Không thể cập nhật trạng thái sản phẩm.";
        }

        return $this->redirect('?controller=admin&action=products');
    }

    public function updateProduct()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = "Phương thức không hợp lệ.";
            return $this->redirect('?controller=admin&action=products');
        }

        $productId = $_POST['id'] ?? null;
        if (!$productId) {
            $_SESSION['error'] = "ID sản phẩm không hợp lệ.";
            return $this->redirect('?controller=admin&action=products');
        }

        // Thu thập dữ liệu từ form
        $productData = [
            'title' => $_POST['title'] ?? '',
            'author' => $_POST['author'] ?? '',
            'price' => $_POST['price'] ?? 0,
            'discount' => $_POST['discount'],
            'description' => $_POST['description'] ?? '',
            'quantity' => $_POST['quantity'] ?? 0,
            'is_visible' => $_POST['is_visible']
        ];

        // Xử lý upload ảnh mới nếu có
        if (!empty($_FILES['image']['name'])) {
            $uploadDir = 'public/products/';
            $fileName = str_replace(' ', '_', $_POST['title']) . '_' . basename($_FILES['image']['name']);
            $targetFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $productData['image'] = $fileName;
            } else {
                $_SESSION['error'] = "Không thể upload ảnh.";
                return $this->redirect("?controller=admin&action=editProduct&id={$productId}");
            }
        }

        // Cập nhật sản phẩm
        if ($this->productModel->updateProduct($productId, $productData)) {
            $_SESSION['success'] = "Cập nhật sản phẩm thành công.";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật sản phẩm.";
        }

        return $this->redirect('?controller=admin&action=products');
    }

    // ----------------------------------------------------
    // VII. QUẢN LÝ ĐƠN HÀNG (Yêu cầu 5, 6)
    // ----------------------------------------------------

    //  Hiển thị danh sách Đơn hàng (sửa trạng thái)
    public function orders()
    {
        $searchId = $_GET['search'] ?? null;

        $orders = $this->orderModel->getAllOrdersWithUserDetails($searchId);

        $data = [
            'orders' => $orders,
            'statuses' => OrderModel::STATUSES
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

    // Xem chi tiết đơn hàng
    public function viewOrderDetail()
    {
        $orderId = $_GET['id'] ?? null;

        if (!$orderId) {
            // Nếu không có ID, chuyển hướng hoặc hiển thị lỗi
            $this->redirect('admin/orders/index');
            exit;
        }

        // Lấy dữ liệu chi tiết đơn hàng
        $order = $this->orderModel->getOrderDetail($orderId);

        if (!$order) {
            // Nếu không tìm thấy đơn hàng, chuyển hướng hoặc hiển thị lỗi
            $this->redirect('index.php?controller=admin&action=orders');
            exit;
        }

        // Truyền dữ liệu sang View
        $data = [
            'order' => $order
        ];
        $this->view('layouts/orders/viewOrderDetail', $data, 'true');
    }


    // ----------------------------------------------------
    // IV. QUẢN LÝ NGƯỜI DÙNG & ĐÁNH GIÁ
    // ----------------------------------------------------

    // Hiển thị danh sách Người dùng (Khách hàng & Nhân viên)
    public function users()
    {
        $users = $this->userModel->getAllUsers();
        return $this->view('layouts/users/index', [
            'users' => $users,
            'statusLabels' => UserModel::$STATUS_LABELS
        ], true);
    }

    // Xem chi tiết người dùng
    public function viewUser()
    {
        $userId = $_GET['id'] ?? null;
        if (!$userId) {
            $_SESSION['error'] = "ID người dùng không hợp lệ.";
            return $this->redirect('?controller=admin&action=users');
        }

        $user = $this->userModel->getUserById($userId);
        if (!$user) {
            $_SESSION['error'] = "Không tìm thấy người dùng.";
            return $this->redirect('?controller=admin&action=users');
        }

        // Nếu model trả về addresses bên trong user, tách ra để view dùng trực tiếp
        $addresses = $user['addresses'] ?? [];

        // Thiết lập thông tin liên hệ chính (lấy address mặc định nếu có, hoặc lấy phần tử đầu)
        if (!empty($addresses)) {
            $primary = null;
            foreach ($addresses as $addr) {
                if (!empty($addr['is_default'])) {
                    $primary = $addr;
                    break;
                }
            }
            if ($primary === null) {
                $primary = $addresses[0];
            }

            // Gán số điện thoại và địa chỉ gọn vào $user để view hiển thị thuận tiện
            $user['phone'] = $primary['phone_number'] ?? $user['phone'] ?? null;
            $user['address'] = (
                ($primary['address_line'] ?? '') .
                (!empty($primary['ward']) ? ', ' . $primary['ward'] : '') .
                (!empty($primary['district']) ? ', ' . $primary['district'] : '') .
                (!empty($primary['city']) ? ', ' . $primary['city'] : '')
            );
        }

        return $this->view('layouts/users/view', [
            'user' => $user,
            'addresses' => $addresses,
            'statusLabels' => UserModel::$STATUS_LABELS
        ], true);
    }

    // Cập nhật trạng thái người dùng
    public function updateUserStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = "Phương thức không hợp lệ.";
            return $this->redirect('?controller=admin&action=users');
        }

        $userId = $_POST['user_id'] ?? null;
        $newStatus = $_POST['status'] ?? null;

        if (!$userId || !isset($newStatus)) {
            $_SESSION['error'] = "Thiếu thông tin cần thiết.";
            return $this->redirect('?controller=admin&action=users');
        }

        if ($this->userModel->updateUserStatus($userId, $newStatus)) {
            $_SESSION['success'] = "Cập nhật trạng thái thành công.";
        } else {
            $_SESSION['error'] = "Không thể cập nhật trạng thái." . $userId . $newStatus;
        }

        return $this->redirect('?controller=admin&action=users');
    }

    //  Hiển thị danh sách Đánh giá
    public function reviews()
    {
        // Lấy danh sách các đánh giá của sản phẩm
        return $this->view('layouts/users/reviews', ['reviews' => []], true);
    }

    // ----------------------------------------------------
    // V. KHUYẾN MÃI & BÁO CÁO
    // ----------------------------------------------------

    // Hiển thị Quản lý Voucher & Khuyến mãi (số lượng, thời hạn)
    public function vouchers()
    {
        // Quản lý tạo/sửa/xóa voucher và khuyến mãi sản phẩm
        return $this->view('layouts/vouchers/index', ['vouchers' => []], true); // Giữ nguyên TRUE
    }

    // Hiển thị Báo cáo Doanh thu chi tiết (tuần, tháng, năm)
    public function revenue()
    {
        // Lấy dữ liệu doanh thu chi tiết
        return $this->view('layouts/revenue/index', ['report' => []], true); // Giữ nguyên TRUE
    }
}