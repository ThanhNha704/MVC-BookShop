<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
<<<<<<< HEAD
require_once __DIR__ . '/../../core/assets.php';
=======
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
// views/admin/layouts/header.php
$controller = $_GET['controller'] ?? 'Admin';
$userName = $_SESSION['user_name'] ?? 'Admin';

<<<<<<< HEAD
// (Giữ nguyên các biến này để đảm bảo tính liên tục của giao diện)
=======
// views/admin/layouts/sidebar.php - Biến cần thiết cho sidebar
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
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
<<<<<<< HEAD
<html lang="en" class="h-full">
=======
<html lang="en" class="h-100% overflow-hidden">
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
<<<<<<< HEAD
        Quản Trị | <?php echo $title_text; ?>
    </title>

    <!-- Favicon và Open Graph Image -->
    <link rel="icon" type="image/png" href="<?= $assets['logo_icon'] ?>">
    <!-- Open Graph -->
    <meta property="og:image" content="<?= $assets['logo_icon'] ?>">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="h-full bg-gray-100">

    <header class="h-16 bg-white shadow-md flex items-center justify-between px-4 sm:px-6 z-50 sticky top-0">

        <div class="flex items-center">
            <button id="open-menu-sidebar" class="text-amber-600 p-2 rounded-md md:hidden mr-3">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <h1 class="text-lg sm:text-xl font-bold text-gray-800">
                <?php echo $title_text; ?>
            </h1>
        </div>


        <div class="flex items-center space-x-2 sm:space-x-4">
            <div class="hidden sm:block text-gray-700 font-base text-sm sm:text-base md:text-lg">
                Xin chào, <span class="font-bold text-amber-700"><?= htmlspecialchars($userName) ?></span>
            </div>

            <a href="?controller=auth&action=logout"
                class="flex items-center p-1 sm:p-2 rounded-lg bg-red-500 text-white hover:bg-red-600 transition duration-150 text-sm sm:text-base">
                <span class="mr-1 hidden sm:inline">Đăng xuất</span>
                <i class="fas fa-sign-out-alt"></i> </a>
        </div>
    </header>

    <?php

    // Thông báo LỖI (error)
    if (isset($_SESSION['error'])):
        ?>
        <div id="flash-error" class="flash-message fixed top-20 right-5 z-55 p-4 text-sm text-red-800 rounded-lg bg-red-100 border border-red-400 flex items-center shadow-lg 
                   
                   /* Hiệu ứng Fade-in */
                   opacity-100 transition-opacity duration-500 ease-out" role="alert">
=======
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
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
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

<<<<<<< HEAD
    // Thông báo THÀNH CÔNG (success)
    if (isset($_SESSION['success'])):
        ?>
        <div id="flash-success" class="flash-message fixed top-20 right-5 z-55 p-4 text-sm text-green-800 rounded-lg bg-green-100 border border-green-400 flex items-center shadow-lg
                   
                   /* Hiệu ứng Fade-in */
                   opacity-100 transition-opacity duration-500 ease-out" role="alert">
=======
    // 2. Thông báo THÀNH CÔNG (success)
    if (isset($_SESSION['success'])):
        ?>
        <div id="flash-success"
            class="flash-message fixed top-20 right-5 z-55 p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-100 border border-green-400 flex items-center"
            role="alert">
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
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
<<<<<<< HEAD
=======

    // ==========================================================
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const flashMessages = document.querySelectorAll('.flash-message');
<<<<<<< HEAD
            const DURATION = 3000; // 3 giây
            const FADE_OUT_DURATION = 500; // 0.5 giây (khớp với transition-opacity duration-500)

            // Hàm chung để ẩn phần tử sử dụng Tailwind opacity
            function hideElement(element) {
                if (element) {
                    // Kích hoạt hiệu ứng fade-out (opacity: 0)
                    element.classList.remove('opacity-100');
                    element.classList.add('opacity-0');

                    // Sau khi fade out xong, ẩn hẳn phần tử bằng cách loại bỏ nó khỏi DOM
                    setTimeout(() => {
                        element.remove();
                    }, FADE_OUT_DURATION);
=======
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
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
                }
            }

            flashMessages.forEach(message => {
                let timeoutId;

<<<<<<< HEAD
                // Tự động tắt sau 3s (Fade-out bắt đầu)
=======
                // 1. Tự động tắt sau 4s
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
                timeoutId = setTimeout(() => {
                    hideElement(message);
                }, DURATION);

<<<<<<< HEAD
                // Xử lý nút tắt thủ công
                const closeButton = message.querySelector('.close-button');
                if (closeButton) {
                    closeButton.addEventListener('click', () => {
                        clearTimeout(timeoutId); // Xóa hẹn giờ tự động tắt
                        hideElement(message); // Ẩn ngay lập tức
                    });
                }
            });

            // ==========================================================
            // LOGIC XỬ LÝ NÚT MỞ/ĐÓNG SIDEBAR
            // ==========================================================

            const openMenuBtn = document.getElementById('open-menu-sidebar');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            if (openMenuBtn && sidebar && overlay) {
                openMenuBtn.addEventListener('click', () => {
                    // Mở sidebar
                    sidebar.classList.remove('-translate-x-full');
                    sidebar.classList.add('translate-x-0');
                    // Hiện overlay
                    overlay.classList.remove('hidden');
                });
            }
=======
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
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
        });
    </script>