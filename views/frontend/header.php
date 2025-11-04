<?php
// ...
require_once __DIR__ . '/../../core/assets.php';
$controller = $_GET['controller'] ?? 'home';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// ...
?>
<!DOCTYPE html>
<html lang="en ">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php
        echo ucfirst($controller);
        ?>
    </title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- css, script cho swiper -->
    <!-- <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" /> -->
    <meta http-equiv="refresh" content="">
</head>

<body>
<div class="container max-w-7xl my-0 mx-auto">

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