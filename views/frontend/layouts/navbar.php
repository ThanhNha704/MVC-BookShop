<nav class="w-full sticky top-0 z-50 pb-2 gap-2 h-auto bg-gray-100">
    <div class="w-[90%] mx-auto grid grid-cols-10 gap-4 py-3 bg-gray-100">

        <!-- Logo -->
        <div class="img_logo flex justify-center col-span-2">
            <a href="index.php">
                <img class="sm:w-20 md:w-35 logo_img" src="<?= $assets['logo_icon'] ?>" alt="logo">
            </a>
        </div>

        <!-- Search -->
        <div class="flex justify-center items-center col-span-6">
            <form id="filterForm" action="index.php" method="GET"
                class="relative h-[60%] flex items-center justify-center grow">

                <input type="hidden" name="controller" value="product">

                <input type="text" name="search_query" id="searchInput" placeholder="Tìm kiếm sản phẩm..."
                    class="h-full w-full px-4 py-2 border-2 border-amber-500 rounded-xl focus:outline-none">

                <button type="submit" class="hidden md:block absolute inset-y-0 right-0 flex items-center bg-amber-500 border-2 border-amber-500 
                focus:outline-none px-2 py-1 rounded-r-xl hover:bg-amber-600 hover:border-amber-600">
                    <img src="<?= $assets['search_icon'] ?? 'path/to/search_icon.png' ?>" alt="search" class="h-6">
                </button>
            </form>
        </div>

        <!-- User Actions -->
        <div class="flex justify-center space-x-4 col-span-2">

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
                    <div class="login flex">
                        <a href="?controller=authen&action=login" class="flex items-center text-black text-decoration-none">
                            <p class="hidden md:block cursor-pointer m-0 px-2 border-r-1 
                            md:text-lg lg:text-xl text-lg font-normal">Đăng nhập</p>
                        </a>

                        <a href="?controller=authen&action=register"
                            class="flex items-center text-black text-decoration-none">
                            <p class="hidden md:block cursor-pointer m-0 p-2 
                            md:text-lg lg:text-xl font-normal">Đăng ký</p>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bottom Navbar -->
    <div class="bottom-navbar h-max w-full py-2 gap-2 bg-amber-500">
        <!-- <div class="flex justify-center align-items-center"> -->
        <div class="w-[90%] mx-auto grid grid-cols-8 gap-4">

            <!-- Danh mục sản phẩm -->
            <div class="group relative col-span-2 flex justify-center items-center py-2">
                <img src="<?= $assets['menu_icon'] ?>" alt="menu" class="sm:w-6 md:w-8">
                <p class="text-black md:text-lg lg:text-2xl font-normal m-0 pl-2 hidden md:block">
                    DANH MỤC SẢN PHẨM
                </p>

                <div class="absolute top-full left-16 hidden group-hover:block bg-white shadow-lg rounded-lg z-50">
                    <div class="flex flex-col w-48 py-3 pl-5">
                        <a class="flex w-full justify-between pr-2 py-2 text-xl cursor-pointer">
                            Sản phẩm 1
                            <img src="<?= $assets['right_icon'] ?>" alt="'right_icon" class="sm:w-6 md:w-8">
                        </a>
                        <a class="flex w-full justify-between pr-2 py-2 text-xl cursor-pointer">
                            Sản phẩm 2
                            <img src="<?= $assets['right_icon'] ?>" alt="'right_icon" class="right-0 sm:w-6 md:w-8">
                        </a>
                        <a class="flex w-full justify-between pr-2 py-2 text-xl cursor-pointer">
                            Sản phẩm 3
                            <img src="<?= $assets['right_icon'] ?>" alt="'right_icon" class="sm:w-6 md:w-8">
                        </a>
                    </div>
                </div>
            </div>

            <!--  -->
            <div class="col-span-2"></div>

            <!-- Theo dõi đơn hàng -->
            <a href="tracking.php"
                class="flex place-content-end items-center col-span-2 py-2 text-black text-decoration-none">
                <img src="<?= $assets['tracking_icon'] ?>" alt="tracking" class="sm:w-6 md:w-8">
                <p class="md:text-lg lg:text-xl m-0 pl-2 pr-4 hidden md:block border-r-1">Theo dõi đơn hàng</p>
            </a>

            <!-- Hỗ trợ khách hàng -->
            <a href="support.php"
                class="flex place-content-start items-center col-span-2 py-2 text-black text-decoration-none">
                <img src="<?= $assets['support_icon'] ?>" alt="support" class="sm:w-6 md:w-8">
                <p class="md:text-lg lg:text-xl m-0 pl-2 hidden md:block">Hỗ trợ khách hàng</p>
            </a>
        </div>
        <!-- </div> -->
    </div>
</nav>