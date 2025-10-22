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
<html lang="en" class="h-100% overflow-hidden">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo ucfirst($controller); ?>
    </title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="h-100%">
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



    <?php
    // ==========================================================
// CODE HIỂN THỊ THÔNG BÁO FLASH MESSAGE
// ==========================================================
    
    // 1. Thông báo LỖI (error)
    if (isset($_SESSION['error'])):
        ?>
        <div id="flash-error"
            class="flash-message fixed top-20 right-5 z-55 p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-100 border border-red-400 flex items-center"
            role="alert">
            <span class="font-medium mr-2">Lỗi:</span>
            <div class="flex-grow text-medium"><?= htmlspecialchars($_SESSION['error']); ?></div>

            <button type="button"
                class="close-button ml-4 text-red-900 hover:text-red-600 focus:outline-none transition-colors duration-150"
                aria-label="Close">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <?php
        unset($_SESSION['error']);
    endif;

    // 2. Thông báo THÀNH CÔNG (success)
    if (isset($_SESSION['success'])):
        ?>
        <div id="flash-success"
            class="flash-message fixed top-20 right-5 z-55 p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-100 border border-green-400 flex items-center"
            role="alert">
            <div class="flex-grow text-medium"><?= htmlspecialchars($_SESSION['success']); ?></div>

            <button type="button"
                class="close-button ml-4 text-green-900 hover:text-green-600 focus:outline-none transition-colors duration-150"
                aria-label="Close">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
        <?php
        unset($_SESSION['success']);
    endif;

    // ==========================================================
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const flashMessages = document.querySelectorAll('.flash-message');
            const DURATION = 3000;

            // Hàm chung để ẩn phần tử
            function hideElement(element) {
                if (element) {

                    element.style.transition = 'opacity 0.5s ease-out';
                    element.style.opacity = '0';

                    // Sau khi fade out xong (0.5s), ẩn hẳn phần tử
                    setTimeout(() => {
                        element.style.display = 'none';
                    }, 500);
                }
            }

            flashMessages.forEach(message => {
                let timeoutId;

                // 1. Tự động tắt sau 4s
                timeoutId = setTimeout(() => {
                    hideElement(message);
                }, DURATION);

                // 2. Xử lý nút tắt thủ công
                const closeButton = message.querySelector('.close-button');
                if (closeButton) {
                    closeButton.addEventListener('click', () => {
                        // Xóa hẹn giờ tự động tắt
                        clearTimeout(timeoutId);
                        // Ẩn ngay lập tức
                        hideElement(message);
                    });
                }
            });
        });
    </script>