<?php
// Lấy controller từ $_GET['controller']
$controller = $_GET['controller'] ?? 'home';
$active = 'border-white';

// Khởi động session nếu chưa có
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// print_r($_SESSION);
// Lấy thông tin người dùng từ Session
$isLoggedIn = !empty($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? '';
// print_r($data);
?>

<nav class="w-full sticky top-0 z-50 h-auto bg-amber-500 shadow-lg">
    <div class="w-[95%] mx-auto py-2 flex items-center justify-between md:grid md:grid-cols-12 md:gap-4">

        <div class="flex items-center col-span-2">
            <a href="index.php">
                <img class="w-20 sm:w-30 md:w-24 logo_img" src="<?= $assets['logo_icon'] ?>" alt="logo">
            </a>
        </div>

        <button id="menu-toggle" class="text-white md:hidden p-2">
            <i class="fas fa-bars text-xl"></i>
        </button>

        <div id="main-menu" class="hidden md:flex items-center col-span-7">
            <ul class="flex justify-between w-full">
                <?php
                // Định nghĩa các mục menu
                $menu_items = [
                    ['title' => 'TRANG CHỦ', 'href' => 'index.php', 'controller' => 'home', 'action' => 'index'],
                    ['title' => 'SẢN PHẨM', 'href' => '?controller=product', 'controller' => 'product'],
                    ['title' => 'GIỚI THIỆU', 'href' => 'index.php?controller=home&action=about', 'controller' => 'home', 'action' => 'about'],
                    ['title' => 'LIÊN HỆ', 'href' => '?controller=contactus', 'controller' => 'contactus']
                ];

                foreach ($menu_items as $item):
                    $is_active = ($controller == $item['controller']);

                    // Logic kiểm tra active phức tạp cho Trang chủ và Giới thiệu
                    if ($item['controller'] == 'home') {
                        $current_action = $_GET['action'] ?? 'index';
                        if ($item['action'] == 'index') {
                            $is_active = ($controller == 'home' && $current_action == 'index');
                        } elseif ($item['action'] == 'about') {
                            $is_active = ($controller == 'home' && $current_action == 'about');
                        }
                    }
                    ?>
                    <li class="flex justify-center items-center text-white text-xs lg:text-base font-bold px-2">
                        <a href="<?= $item['href'] ?>">
                            <p class="p-1 border-b-2 border-amber-500 hover:border-white 
                        <?= $is_active ? $active : '' ?>">
                                <?= $item['title'] ?>
                            </p>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <div class="hidden md:flex justify-end space-x-2 col-span-3">

            <div class="group relative flex justify-center items-center space-x-1">
                <?php if ($isLoggedIn): ?>
                    <p class="hidden sm:flex items-center text-sm md:text-base text-white">
                        Xin chào,
                        <?= htmlspecialchars($userName) ?>
                    </p>
                <?php endif; ?>

                <div class="relative flex justify-center items-center">
                    <img src="<?= $assets['user_icon'] ?>" alt="profile" class="w-6 md:w-8 cursor-pointer">

                    <?php if ($isLoggedIn): ?>
                <div
                    class="z-50 group-hover:block hidden absolute right-[100%] top-[20%] mt-2 bg-white shadow-lg rounded-md pt-2 border border-amber-500">
                    <div class="w-max flex flex-col gap-2 py-2 px-2 text-sm lg:text-lg">
                        <a href="?controller=user&action=profile" class="hover:font-medium text-gray-800 border-b pb-2 mb-1">
                            Hồ sơ & Hạng TV
                        </a>
                        <a href="?controller=orders&action=index" class="hover:font-medium text-gray-800">
                            Trạng Thái Đơn Hàng</a>
                        <a href="?controller=auth&action=logout" class="hover:font-medium text-gray-800">
                            Đăng xuất</a>
                    </div>
                </div>
                    <?php else: ?>
                        <div
                            class="z-50 group-hover:block hidden absolute right-[100%] top-[20%] mt-2 bg-white shadow-lg rounded-md p-2 border border-amber-500">
                            <div class="w-max flex flex-col gap-2 w-36 py-2 px-2 text-sm lg:text-lg">
                                <a href="?controller=auth&action=showLoginForm" class="hover:font-medium text-gray-800">
                                    Đăng nhập
                                </a>
                                <a href="?controller=auth&action=showRegisterForm" class="hover:font-medium text-gray-800">
                                    Đăng kí
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

            </div>

            <a href="?controller=cart" class="flex justify-center items-center">
                <img src="<?= $assets['cart_icon'] ?>" alt="cart" class="w-6 md:w-8 cursor-pointer">
            </a>

        </div>
    </div>
</nav>

<!-- Mobile Menu -->
<div id="mobile-menu-container"
    class="hidden md:hidden fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity duration-300">
    <div id="mobile-sidebar" class="mt-15 w-64 bg-white h-full shadow-lg p-4 flex flex-col space-y-2 
        transform -translate-x-full transition duration-300">

        <div class="text-right">
            <button id="close-menu"
                class="text-gray-600 hover:text-gray-800 text-2xl transition duration-300 hover:rotate-90">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <?php if ($isLoggedIn): ?>
            <p class="font-bold text-lg text-amber-600 border-b pb-2">Xin chào, <?= htmlspecialchars($userName) ?></p>
        <?php endif; ?>

        <a href="index.php"
            class="text-gray-800 hover:text-amber-500 text-md transition duration-200  <?= ($controller == 'home' && (!isset($_GET['action']) || $_GET['action'] == 'index')) ? 'font-bold text-amber-500' : '' ?>">
            <i class="fas fa-home w-5 mr-2"></i> TRANG CHỦ
        </a>
        <a href="?controller=product"
            class="text-gray-800 hover:text-amber-500 text-md transition duration-200  <?= ($controller == 'product') ? 'font-bold text-amber-500' : '' ?>">
            <i class="fas fa-book w-5 mr-2"></i> SẢN PHẨM
        </a>
        <a href="index.php?controller=home&action=about"
            class="text-gray-800 hover:text-amber-500 text-md transition duration-200  <?= ($controller == 'home' && isset($_GET['action']) && $_GET['action'] == 'about') ? 'font-bold text-amber-500' : '' ?>">
            <i class="fas fa-info-circle w-5 mr-2"></i> GIỚI THIỆU
        </a>
        <a href="?controller=contactus"
            class="text-gray-800 hover:text-amber-500 text-md transition duration-200  <?= ($controller == 'contactus') ? 'font-bold text-amber-500' : '' ?>">
            <i class="fas fa-envelope w-5 mr-2"></i> LIÊN HỆ
        </a>

        <div class="border-t pt-4 space-y-3 grid grid-row">
            <a href="?controller=cart" class="text-gray-800 hover:text-amber-500 text-md transition duration-200 ">
                <i class="fas fa-shopping-cart w-5 mr-2"></i> GIỎ HÀNG
            </a>

            <?php if ($isLoggedIn): ?>
                <a href="?controller=orders&action=index"
                    class="text-gray-800 hover:text-amber-500 text-md transition duration-200 ">
                    <i class="fas fa-history w-5 mr-2"></i> TRẠNG THÁI ĐƠN HÀNG
                </a>
                <a href="?controller=auth&action=logout"
                    class="text-gray-800 hover:text-amber-500 text-md transition duration-200 ">
                    <i class="fas fa-sign-out-alt w-5 mr-2"></i> ĐĂNG XUẤT
                </a>
            <?php else: ?>
                <a href="?controller=auth&action=showLoginForm"
                    class="text-gray-800 hover:text-amber-500 text-md transition duration-200 ">
                    <i class="fas fa-sign-in-alt w-5 mr-2"></i> ĐĂNG NHẬP
                </a>
                <a href="?controller=auth&action=showRegisterForm"
                    class="text-gray-800 hover:text-amber-500 text-md transition duration-200 ">
                    <i class="fas fa-user-plus w-5 mr-2"></i> ĐĂNG KÍ
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.getElementById('menu-toggle').addEventListener('click', function () {
        const menu = document.getElementById('mobile-menu-container');
        const sidebar = document.getElementById('mobile-sidebar');

        menu.classList.remove('hidden');

        // Timeout ngắn để đảm bảo menu được hiển thị trước khi chạy transition
        setTimeout(() => {
            menu.classList.add('opacity-100');
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');
        }, 10);
    });

    function closeMobileMenu() {
        const menu = document.getElementById('mobile-menu-container');
        const sidebar = document.getElementById('mobile-sidebar');

        sidebar.classList.remove('translate-x-0');
        sidebar.classList.add('-translate-x-full');
        menu.classList.remove('opacity-100');

        // Chờ transition hoàn tất trước khi ẩn hoàn toàn
        setTimeout(() => {
            menu.classList.add('hidden');
        }, 300); // 300ms là thời gian của transition duration-300
    }

    document.getElementById('close-menu').addEventListener('click', closeMobileMenu);

    // Đóng menu khi click bên ngoài (phần nền mờ)
    document.getElementById('mobile-menu-container').addEventListener('click', function (e) {
        if (e.target.id === 'mobile-menu-container') {
            closeMobileMenu();
        }
    });
</script>