<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full p-8 space-y-6 bg-white rounded-xl shadow-lg">
        <h2 class="text-3xl font-extrabold text-center text-gray-900">
            Đăng Nhập
        </h2>

        <form action="?controller=auth&action=login" method="POST" class="mt-8 space-y-6">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <input type="email" name="email" placeholder="Email" required
                   class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

            <input type="password" name="password" placeholder="Mật khẩu" required
                   class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">

            <button type="submit"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Đăng Nhập
            </button>
        </form>

        <p class="text-center text-sm text-gray-600">
            Chưa có tài khoản? 
            <a href="/register" class="font-medium text-indigo-600 hover:text-indigo-500">Đăng ký ngay</a>
        </p>
    </div>
</div>