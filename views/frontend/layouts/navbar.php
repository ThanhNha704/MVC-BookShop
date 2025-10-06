<nav class="top-navbar grid grid-rows-2 py-2 gap-2 h-auto bg-gray-100">
    <div class="container grid grid-cols-12 gap-4 justify-center align-items-center">
        <!-- Logo -->
        <div class="img_logo flex justify-center col-span-2">
            <a href="index.php">
                <img class="w-20 logo_img" src="<?= $assets['logo_icon'] ?>" alt="logo">
            </a>
        </div>
        <!-- Search -->
        <div class="flex justify-center align-items-center col-span-6">
            <form action="product" method="GET" class="relative h-10 flex items-center justify-center grow">
                <input type="text" name="search" placeholder="Tìm kiếm..."
                    class=" w-full px-4 py-2 border-2 border-amber-500 rounded-4 focus:outline-none">
                <button type="submit"
                    class="hidden d-md-block absolute inset-y-0 right-0 flex items-center bg-amber-500 border-2 border-amber-500 focus:outline-none px-2 py-1 rounded-end-4 hover:bg-amber-600 hover:border-amber-600">
                    <img src="<?= $assets['search_icon'] ?>" alt="search" class="h-6">
                </button>
            </form>
        </div>
        <!--  -->
        <!-- User Actions -->
        <div class="flex justify-center space-x-4 col-span-4">

            <!-- Profile -->
            <div class="group relative flex justify-center align-items-center col-span-4">
                <img src="<?= $assets['user_icon'] ?>" alt="profile" class="w-5 cursor-pointer">

                <?php if (!empty($_SESSION['user_id'])): ?>
                    <!-- Nếu đã đăng nhập -->
                    <div class="z-50 group-hover:block hidden absolute right-0 bg-white shadow-lg rounded-lg pt-4">
                        <div class="flex flex-col gap-2 w-36 py-3 px-5">
                            <p class="cursor-pointer hover:text-black">Xin chào,
                                <?= htmlspecialchars($_SESSION['user_name']) ?>
                            </p>
                            <p onclick="window.location.href='index.php?controllers=order&action=index'"
                                class="cursor-pointer hover:text-black">Giỏ Hàng</p>
                            <p onclick="window.location.href='index.php?controllers=auth&action=logout'"
                                class="cursor-pointer hover:text-black">Đăng xuất</p>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Nếu chưa đăng nhập -->
                    <div class="login flex">
                        <a href="index.php?controllers=auth&action=register" class="text-black text-decoration-none">
                            <p class="hidden d-md-block cursor-pointer hover:text-black m-0 p-2 border-r-1">Đăng nhập</p>
                        </a>

                        <a href="index.php?controllers=auth&action=register" class="text-black text-decoration-none">
                            <p class="hidden d-md-block cursor-pointer hover:text-black m-0 p-2">Đăng ký</p>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bottom Navbar -->
    <div class="bottom-navbar py-2 gap-2 h-auto bg-amber-500">
        <div class="container flex justify-center align-items-center">
            <div class="grid grid-cols-12 w-full">
                <div class="group relative col-span-4 flex justify-center align-items-center">
                    <img src="<?= $assets['menu_icon'] ?>" alt="menu">
                    <p class="text-black m-0 pl-2 hidden d-md-block">DANH MỤC SẢN PHẨM</p>
                    <div class="absolute top-full left-0 hidden group-hover:block bg-white shadow-lg rounded-lg">
                        <div class="flex flex-col gap-2 w-48 py-3 px-5">
                            <p class="cursor-pointer hover:text-black">Sản phẩm 1</p>
                            <p class="cursor-pointer hover:text-black">Sản phẩm 2</p>
                            <p class="cursor-pointer hover:text-black">Sản phẩm 3</p>
                        </div>
                    </div>
                </div>

                <div class="col-span-2"></div>
                <div class="flex justify-center align-items-center col-span-3">
                    <a href="tracking.php"
                        class="flex justify-center align-items-center text-black hover:text-red-500 text-decoration-none">
                        <img src="<?= $assets['tracking_icon'] ?>" alt="tracking" class="h-6">
                        <p class="align-items-center m-0 pl-2 hidden d-md-block">Theo dõi đơn hàng</p>
                    </a>
                </div>
                <div class="flex justify-center align-items-center col-span-3">
                    <a href="support.php"
                        class="flex justify-center align-items-center text-black hover:text-red-500 text-decoration-none">
                        <img src="<?= $assets['support_icon'] ?>" alt="support" class="h-6">
                        <p class="align-items-center m-0 pl-2 hidden d-md-block">Hỗ trợ khách hàng</p>
                    </a>
                </div>
            </div>
        </div>
</nav>

<!-- script bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>