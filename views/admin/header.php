<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// views/admin/layouts/header.php
$controller = $_GET['controller'] ?? 'Admin';
$userName = $_SESSION['user_name'] ?? 'Admin';

// views/admin/layouts/sidebar.php - Biến cần thiết cho sidebar
$current_controller = $_GET['controller'] ?? 'admin';
$current_action = $_GET['action'] ?? 'index';
$active_class = "bg-amber-500 text-white border-r-4 border-white shadow-md";
$inactive_class = "text-amber-50 hover:bg-amber-600 hover:text-white";

// Tiêu đề động cho Header
$pageTitle = [
    'index' => 'Dashboard Tổng quan',
    'products' => 'Quản lý Sản phẩm',
    'orders' => 'Quản lý Đơn hàng',
    'users' => 'Quản lý Người dùng',
    'reviews' => 'Quản lý Đánh giá',
    'vouchers' => 'Quản lý Voucher & Khuyến mãi',
    'revenue' => 'Báo cáo Doanh thu'
];
$title_text = $pageTitle[$_GET['action'] ?? 'index'] ?? 'Trang Quản Trị';

?>
<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo ucfirst($controller); ?>
    </title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="h-full flex flex-col">
    <header class="h-16 bg-white shadow-xl flex items-center justify-between px-6 z-50 sticky top-0">
        <h1 class="text-xl font-bold text-gray-800">
            <?php echo $title_text; ?>
        </h1>

        <div class="flex items-center space-x-4">
            <div class="text-gray-700 font-base text-2xl">
                Xin chào, <span class="font-bold text-amber-700"><?= htmlspecialchars($userName) ?></span>
            </div>
            <a href="?controller=auth&action=logout"
                class="flex items-center p-2 rounded-lg bg-red-500 text-white hover:bg-red-600 transition-colors duration-150 text-xl">
                <span class="mr-1">➡️</span> Đăng xuất
            </a>
        </div>
    </header>