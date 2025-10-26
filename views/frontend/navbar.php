<?php
// Lấy controller từ $_GET['controller']
$controller = $_GET['controller'] ?? 'home';
$active = 'border-white';

// Khởi động session nếu chưa có
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Lấy thông tin người dùng từ Session
$isLoggedIn = !empty($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? '';
// Biến $userRole không còn được sử dụng để kiểm tra logic hiển thị
?>

<nav class="w-full sticky top-0 z-50 pb-2 gap-2 h-auto bg-amber-500 shadow-xl">
    <div class="w-[90%] mx-auto grid grid-cols-12 gap-4 py-1">

        <div class="img_logo flex justify-center col-span-3">
            <a href="index.php">
                <img class="sm:w-20 md:w-35 logo_img" src="<?= $assets['logo_icon'] ?>" alt="logo">
            </a>
        </div>

        <div class="flex grid items-center col-span-6">
            <ul class="grid grid-cols-4 px-2">
                <li class="flex justify-center items-center col-span-1 text-white text-xl font-bold">
                    <a href="index.php">
                        <p class="p-1 border-b-2 border-amber-500 hover:border-white
                        <?php if ($controller == 'home' && (!isset($_GET['action']) || $_GET['action'] == 'index'))
                            echo $active; ?>">
                            TRANG CHỦ</p>
                    </a>
                </li>
                <li class="flex justify-center items-center col-span-1 text-white text-xl font-bold">
                    <a href="?controller=product">
                        <p class="p-1 border-b-2 border-amber-500 hover:border-white hover:border-b-2
                        <?php if ($controller == 'product')
                            echo $active; ?>">SẢN PHẨM</p>
                    </a>
                </li>
                <li class="flex justify-center items-center col-span-1 text-white text-xl font-bold">
                    <a href="index.php?controller=home&action=about">
                        <p class="p-1 border-b-2 border-amber-500 hover:border-white 
                        <?php if ($controller == 'home' && isset($_GET['action']) && $_GET['action'] == 'about')
                            echo $active; ?>">
                            GIỚI THIỆU</p>
                    </a>
                </li>
                <li class="flex justify-center items-center col-span-1 text-white text-xl font-bold">
                    <a href="?controller=contactus">
                        <p class="p-1 border-b-2 border-amber-500 hover:border-white hover:border-b-2
                        <?php if ($controller == 'contactus')
                            echo $active; ?>">LIÊN HỆ</p>
                    </a>
                </li>
            </ul>
        </div>

        <div class="flex justify-center space-x-2 col-span-3 space-x-2">
            <div class="group relative flex justify-center items-center space-x-2">
                <?php if ($isLoggedIn): ?>
                    <p class="flex items-center text-2xl text-white">Xin chào,
                        <?= htmlspecialchars($userName) ?>
                    </p>
                <?php endif; ?>
                <img src="<?= $assets['user_icon'] ?>" alt="profile" class="sm:w-6 md:w-8 cursor-pointer">

                <?php if ($isLoggedIn): ?>
                    <!-- <p class="text-xl text-red-500">Xin chào,
                        <?= htmlspecialchars($userName) ?>
                    </p> -->
                    <div
                        class="z-50 group-hover:block hidden absolute left-[100%] top-[50%] bg-white shadow-lg rounded-r-lg rounded-bl-lg pt-2 border-1 border-amber-500">
                        <div class="w-max flex flex-col gap-2 w-48 py-2 px-4">

                            <a href="?controller=orders&action=index" class="text-xl hover:font-medium">
                                Trạng Thái Đơn Hàng</a>

                            <a href="?controller=auth&action=logout" class="text-xl hover:font-medium">
                                Đăng xuất</a>
                        </div>
                    </div>
                <?php else: ?>

                    <div
                        class="z-50 group-hover:block hidden absolute right-[100%] top-[50%] bg-white shadow-xl rounded-l-lg rounded-br-lg p-2 border-1 border-amber-500">
                        <div class="flex flex-col gap-2 w-36 py-2 px-3">
                            <a href="?controller=auth&action=showLoginForm" class="text-xl hover:font-medium">
                                Đăng nhập
                            </a>
                            <a href="?controller=auth&action=showRegisterForm" class="text-xl hover:font-medium">
                                Đăng kí
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <a href="?controller=cart" class="flex justify-center items-center">
                <img src="<?= $assets['cart_icon'] ?>" alt="cart" class="sm:w-6 md:w-8 cursor-pointer">
            </a>

        </div>
    </div>
</nav>