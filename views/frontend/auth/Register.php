<?php
$oldName = $_SESSION['old_input']['name'] ?? '';
$oldEmail = $_SESSION['old_input']['email'] ?? '';
unset($_SESSION['old_input']);
?>

<div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">
    <div class="max-w-md w-full p-8 space-y-6 bg-white rounded-xl shadow-lg">

        <h2 class="text-3xl font-bold text-center text-gray-900">
            Đăng Ký Tài Khoản
        </h2>

        <form action="?controller=auth&action=register" method="POST" class="mt-8 space-y-4">

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-sm font-medium"
                    role="alert">
                    <?= htmlspecialchars($_SESSION['error']); ?>
                </div>
                <?php unset($_SESSION['error']);?>
            <?php endif; ?>

            <input type="text" name="name" placeholder="Họ và Tên" required value="<?= htmlspecialchars($oldName); ?>"
                class="appearance-none rounded-lg relative block w-full px-4 py-2.5 border border-gray-300 placeholder-gray-500 text-gray-900 
                focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-base transition duration-150">

            <input type="email" name="email" placeholder="Email" required value="<?= htmlspecialchars($oldEmail); ?>"
                class="appearance-none rounded-lg relative block w-full px-4 py-2.5 border border-gray-300 placeholder-gray-500 text-gray-900 
                focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-base transition duration-150">

            <input type="password" name="password" placeholder="Mật khẩu" required class="appearance-none rounded-lg relative block w-full px-4 py-2.5 border border-gray-300 placeholder-gray-500 text-gray-900 
                focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-base transition duration-150">

            <input type="password" name="confirm_password" placeholder="Xác nhận Mật khẩu" required class="appearance-none rounded-lg relative block w-full px-4 py-2.5 border border-gray-300 placeholder-gray-500 text-gray-900 
                focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-base transition duration-150">

            <button type="submit" class="group relative w-full flex justify-center py-2.5 px-4 border border-transparent text-lg font-medium rounded-lg text-white 
                bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 
                transition duration-150 ease-in-out">
                Đăng Ký
            </button>
        </form>

        <p class="text-center text-base text-gray-600">
            Đã có tài khoản?
            <a href="?controller=auth&action=showLoginForm" class="font-medium text-amber-600 hover:text-amber-500">
                Đăng nhập ngay
            </a>
        </p>
    </div>
</div>