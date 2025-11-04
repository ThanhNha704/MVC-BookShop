<?php
// controllers/AdminController.php

class AdminController extends BaseController
{
    private $productModel;
    private $orderModel;
    private $userModel;
    private $reviewModel;

    public function __construct()
    {
        // Bắt đầu Session
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // KIỂM TRA PHÂN QUYỀN TRUY CẬP (BẮT BUỘC)
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
        $this->loadModel('ReviewModel');
        $this->reviewModel = new ReviewModel();
    }

    // Hiển thị trang Dashboard Tổng quan
    public function index()
    {
        // $orderModel = new OrderModel();
        $totalOrders = $this->orderModel->getTotalOrderCount();
        $totalRevenue = $this->orderModel->getTotalRevenue();
        $totalNewUser = $this->userModel->getTotalNewUsers();
        $totalNewReview = $this->reviewModel->getTotalNewReviews();

        // Lấy dữ liệu của order
        $order = $this->orderModel->getAllOrdersWithUserDetails();

        $data = [
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'newUsers' => $totalNewUser,
            'newReviews' => $totalNewReview,
            'recentOrders' => $order
        ];

        return $this->view('index', $data, true);
    }

    // ----------------------------------------------------
    // QUẢN LÝ SẢN PHẨM
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
            if (
                empty($productData['title']) || empty($productData['author']) ||
                empty($productData['price']) || empty($productData['quantity'])
            ) {
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
            return $this->redirect('?controller=admin&action=products');
        }

        $product = $this->productModel->getProductById((int) $productId);

        if (!$product) {
            $_SESSION['error'] = "Không tìm thấy sản phẩm có ID: {$productId}.";
            return $this->redirect('?controller=admin&action=products');
        }

        $data = [
            'product' => $product,
            'page_title' => 'Sửa Sản phẩm',
            'current_controller' => 'admin',
            'current_action' => 'products'
        ];

        $this->view('layouts/products/edit', $data, true);
    }

    // Ẩn/Hiện sản phẩm
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

        // =================================================================
        //HÀM CHUYỂN CHUỖI CÓ DẤU SANG KHÔNG DẤU
        // =================================================================
        function slugify($text)
        {
            // 1. Chuyển tất cả chữ hoa thành chữ thường
            $text = strtolower($text);

            // 2. Thay thế các ký tự tiếng Việt có dấu
            $text = str_replace(
                ['á', 'à', 'ả', 'ã', 'ạ', 'ă', 'ắ', 'ằ', 'ẳ', 'ẵ', 'ặ', 'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ'],
                'a',
                $text
            );
            $text = str_replace(
                ['é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ'],
                'e',
                $text
            );
            $text = str_replace(
                ['í', 'ì', 'ỉ', 'ĩ', 'ị'],
                'i',
                $text
            );
            $text = str_replace(
                ['ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ', 'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ'],
                'o',
                $text
            );
            $text = str_replace(
                ['ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'ứ', 'ừ', 'ử', 'ữ', 'ự'],
                'u',
                $text
            );
            $text = str_replace(
                ['ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ'],
                'y',
                $text
            );
            $text = str_replace('đ', 'd', $text);
            // Thay thế các ký tự không phải là chữ cái, số, hoặc dấu gạch ngang/dấu cách
            $text = preg_replace('/[^a-z0-9\s-]/', '', $text);

            // Thay thế dấu cách và các dấu gạch ngang liên tiếp bằng một dấu gạch ngang đơn
            $text = preg_replace('/[\s-]+/', '_', $text);

            // 3. Cắt bỏ dấu gạch ngang ở đầu và cuối chuỗi (nếu có)
            $text = trim($text, '-');
            return $text;
        }

        // =================================================================

        // Xử lý upload ảnh mới nếu có
        if (!empty($_FILES['image']['name'])) {

            // 1. CHUYỂN TIÊU ĐỀ SÁCH THÀNH CHUỖI KHÔNG DẤU (SLUG)
            $titleSlug = slugify($_POST['title']);

            $uploadDir = 'public/products/';

            // 2. TẠO TÊN FILE MỚI: [slug-của-tên-sách]_[tên-file-ảnh-gốc]
            // Sử dụng slugify và nối với tên file gốc
            $fileName = $titleSlug . '_' . basename($_FILES['image']['name']);

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
    // QUẢN LÝ ĐƠN HÀNG
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

    // Xử lý cập nhật trạng thái đơn hàng
    public function updateOrderStatusAjax()
    {
        // Đặt header để trình duyệt hiểu đây là JSON
        header('Content-Type: application/json');

        $response = ['success' => false, 'message' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $orderId = $_POST['order_id'] ?? null;
            $newStatus = $_POST['status'] ?? null;

            if ($orderId && $newStatus) {
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
        exit;
    }

    // Xem chi tiết đơn hàng
    public function viewOrderDetail()
    {
        $orderId = $_GET['id'] ?? null;

        if (!$orderId) {
            // Nếu không có ID, chuyển hướng hoặc hiển thị lỗi
            return $this->redirect('admin/orders/index');
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
    // QUẢN LÝ NGƯỜI DÙNG
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

        $user['revenue'] = $this->userModel->getUserRevenue($userId);
        $user['membership'] = $this->userModel->getMembershipLevels($user['revenue']);
        return $this->view('layouts/users/view', [
            'user' => $user,
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
        $redirectUrl = $_POST['redirect'] ?? '?controller=admin&action=users';

        if (!$userId || !isset($newStatus)) {
            $_SESSION['error'] = "Thiếu thông tin cần thiết.";
            return $this->redirect('?controller=admin&action=users');
        }

        if ($this->userModel->updateUserStatus($userId, $newStatus)) {
            $_SESSION['success'] = "Cập nhật trạng thái thành công.";
        } else {
            $_SESSION['error'] = "Không thể cập nhật trạng thái." . $userId . $newStatus;
        }
        print_r($_POST);
        return $this->redirect($redirectUrl);
    }

    // ----------------------------------------------------
    // ĐÁNH GIÁ (reviews)
    // ----------------------------------------------------
    //  Hiển thị danh sách Đánh giá
    public function reviews()
    {
        // Lấy danh sách các đánh giá của sản phẩm
        if (!empty($_GET['search'])) {
            $searchQuery = $_GET['search'];
            $reviews = $this->reviewModel->searchReviewsByBookTitle($searchQuery);
        } else {
            $reviews = $this->reviewModel->getAllReviewsAdmin();
        }

        // Lấy danh sách các đánh giá của sản phẩm
        return $this->view('layouts/reviews/index', ['reviews' => $reviews], true);
    }

    // Cập nhật trạng thái đánh giá
    public function updateStatusReview()
    {
        $reviewId = (int) ($_GET['id'] ?? 0);
        $status = $_GET['status'] ?? '';
        $status = ['hidden' => 'ẩn', 'visible' => 'hiện'][$status] ?? '';

        if ($reviewId > 0 && in_array($status, ['hiện', 'ẩn'])) {
            if ($this->reviewModel->updateStatus($reviewId, $status)) {
                $_SESSION['success'] = 'Cập nhật trạng thái đánh giá thành công.';
            } else {
                $_SESSION['error'] = 'Lỗi khi cập nhật trạng thái đánh giá.';
            }
        } else {
            $_SESSION['error'] = 'Trạng thái hoặc ID đánh giá không hợp lệ.';
        }

        // Giả định hàm redirect tồn tại
        return $this->redirect('?controller=admin&action=reviews');
    }

    // ----------------------------------------------------
    // KHUYẾN MÃI & BÁO CÁO
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