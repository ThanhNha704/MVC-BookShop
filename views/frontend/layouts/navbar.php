<?php
// SỬA: Lấy controller từ $_GET['controller'] để tương thích với các liên kết
$controller = $_GET['controller'] ?? 'home'; 
$active = 'border-white';

// Khởi động session nếu chưa có (RẤT QUAN TRỌNG CHO AUTH)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Lấy thông tin người dùng từ Session
$isLoggedIn = !empty($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? '';
?>

<nav class="w-full sticky top-0 z-50 pb-2 gap-2 h-auto bg-amber-500">
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
                        <?php if ($controller == 'home' && (!isset($_GET['action']) || $_GET['action'] == 'index')) echo $active; ?>">TRANG CHỦ</p>
                    </a>
                </li>
                <li class="flex justify-center items-center col-span-1 text-white text-xl font-bold">
                    <a href="?controller=product">
                        <p class="p-1 border-b-2 border-amber-500 hover:border-white hover:border-b-2
                        <?php if ($controller == 'product') echo $active; ?>">SẢN PHẨM</p>
                    </a>
                </li>
                <li class="flex justify-center items-center col-span-1 text-white text-xl font-bold">
                    <a href="index.php?controller=home&action=about">
                        <p class="p-1 border-b-2 border-amber-500 hover:border-white 
                        <?php if ($controller == 'home' && isset($_GET['action']) && $_GET['action'] == 'about') echo $active; ?>">GIỚI THIỆU</p>
                    </a>
                </li>
                <li class="flex justify-center items-center col-span-1 text-white text-xl font-bold">
                    <a href="?controller=contactus">
                        <p class="p-1 border-b-2 border-amber-500 hover:border-white hover:border-b-2
                        <?php if ($controller == 'contactus') echo $active; ?>">LIÊN HỆ</p>
                    </a>
                </li>
            </ul>
        </div>

        <div class="flex justify-center space-x-4 col-span-3">

            <div class="group relative flex justify-center align-items-center col-span-4">
                <img src="<?= $assets['user_icon'] ?>" alt="profile" class="sm:w-6 md:w-8 cursor-pointer">

                <?php if ($isLoggedIn): ?>

                    <div class="z-50 group-hover:block hidden absolute right-0 bg-white shadow-lg rounded-lg pt-4">
                        <div class="flex flex-col gap-2 w-36 py-3 px-5">
                            <p class="cursor-default hover:text-black">Xin chào,
                                <?= htmlspecialchars($userName) ?>
                            </p>
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <p onclick="window.location.href='index.php?controller=admin&action=index'"
                                   class="cursor-pointer text-red-600 font-semibold">
                                    Admin Dashboard</p>
                            <?php endif; ?>
                            <p onclick="window.location.href='index.php?controller=order&action=index'"
                                class="cursor-pointer">
                                Giỏ Hàng</p>
                            <p onclick="window.location.href='index.php?controller=authen&action=logout'"
                                class="cursor-pointer text-red-500">
                                Đăng xuất</p>
                        </div>
                    </div>
                <?php else: ?>

                    <div class="z-50 group-hover:block hidden absolute right-0 bg-white shadow-xl rounded-lg p-2">
                        <div class="flex flex-col gap-2 w-36 py-3 px-5">
                            <a href="?controller=authen&action=login" class="text-lg hover:font-medium">
                                Đăng nhập
                            </a>
                            <a href="?controller=authen&action=register" class="text-lg hover:font-medium">
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