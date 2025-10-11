<?php
$url_parts = explode('?', $_SERVER['REQUEST_URI']);
$controller = $url_parts[1] ?? 'home';
if ($controller != 'home') {
    $url_parts = explode('=', $controller);
    $controller = $url_parts[1];
}
$active = 'border-white';
?>
<nav class="w-full sticky top-0 z-50 pb-2 gap-2 h-auto bg-amber-500">
    <div class="w-[90%] mx-auto grid grid-cols-12 gap-4 py-1">

        <!-- Logo -->
        <div class="img_logo flex justify-center col-span-3">
            <a href="index.php">
                <img class="sm:w-20 md:w-35 logo_img" src="<?= $assets['logo_icon'] ?>" alt="logo">
            </a>
        </div>

        <!-- Menu chính -->
        <div class="flex grid items-center col-span-6">
            <ul class="grid grid-cols-4 px-2">
                <li class="flex justify-center items-center col-span-1 text-white text-xl font-bold">
                    <a href="index.php">
                        <p class="p-1 border-b-2 border-amber-500 hover:border-white
                        <?php
                        if ($controller == 'home')
                            echo $active;
                        ?>">TRANG CHỦ</p>
                    </a>
                </li>
                <li class="flex justify-center items-center col-span-1 text-white text-xl font-bold">
                    <a href="?controller=product">
                        <p class="p-1 border-b-2 border-amber-500 hover:border-white hover:border-b-2
                        <?php
                        if ($controller == 'product')
                            echo $active;
                        ?>">SẢN PHẨM</p>
                    </a>
                </li>
                <li class="flex justify-center items-center col-span-1 text-white text-xl font-bold">
                    <a href="?controller=aboutus">
                        <p class="p-1 border-b-2 border-amber-500 hover:border-white hover:border-b-2">VỀ CHÚNG TÔI</p>
                    </a>
                </li>
                <li class="flex justify-center items-center col-span-1 text-white text-xl font-bold">
                    <a href="?controller=contactus">
                        <p class="p-1 border-b-2 border-amber-500 hover:border-white hover:border-b-2">LIÊN HỆ</p>
                    </a>
                </li>
            </ul>
        </div>

        <!-- User Actions -->
        <div class="flex justify-center space-x-4 col-span-3">

            <!-- Profile -->
            <div class="group relative flex justify-center align-items-center col-span-4">
                <img src="<?= $assets['user_icon'] ?>" alt="profile" class="sm:w-6 md:w-8 cursor-pointer">

                <?php if (!empty($_SESSION['user_id'])): ?>

                    <!-- Nếu đã đăng nhập -->
                    <div class="z-50 group-hover:block hidden absolute right-0 bg-white shadow-lg rounded-lg pt-4">
                        <div class="flex flex-col gap-2 w-36 py-3 px-5">
                            <p class="cursor-pointer hover:text-black">Xin chào,
                                <?= htmlspecialchars($_SESSION['user_name']) ?>
                            </p>
                            <p onclick="window.location.href='index.php?controllers=order&action=index'"
                                class="cursor-pointer">
                                Giỏ Hàng</p>
                            <p onclick="window.location.href='index.php?controllers=auth&action=logout'"
                                class="cursor-pointer">
                                Đăng xuất</p>
                        </div>
                    </div>
                <?php else: ?>

                    <!-- Nếu chưa đăng nhập -->
                    <div class="z-50 group-hover:block hidden absolute bottom-[-100%]  bg-white shadow-xl rounded-lg p-2">
                        <div class="flex flex-col gap-2 w-36 py-3 px-5">
                            <a href="?controller=authen&action=login" class="text-xl hover:font-medium">
                                Đăng nhập
                            </a>
                            <a href="?controller=authen&action=register" class="text-xl hover:font-medium">
                                Đăng kí
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>