<div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">
    <div class="max-w-md w-full p-8 space-y-6 bg-white rounded-xl shadow-lg">
        <h2 class="text-3xl font-extrabold text-center text-gray-900">
            Đăng Ký Tài Khoản
        </h2>

        <form action="http://localhost/MVC_BookShop/index.php?controller=auth&action=register" method="POST"
            class="mt-8 space-y-4">

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-sm" role="alert">
                    <?= $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <input type="text" name="name" placeholder="Họ và Tên" required
                class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

            <input type="email" name="email" placeholder="Email" required
                class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

            <input type="password" name="password" placeholder="Mật khẩu" required
                class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

            <input type="password" name="confirm_password" placeholder="Xác nhận Mật khẩu" required
                class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

            <button type="submit"
                class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                Đăng Ký
            </button>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <?= $_SESSION['error']; ?>
                </div>

                <?php unset($_SESSION['error']); // Xóa session sau khi hiển thị ?>
            <?php endif; ?>
        </form>

        <p class="text-center text-sm text-gray-600">
            Đã có tài khoản?
            <a href="?controller=auth&action=login" class="font-medium text-indigo-600 hover:text-indigo-500">Đăng nhập ngay</a>
        </p>
    </div>
</div>