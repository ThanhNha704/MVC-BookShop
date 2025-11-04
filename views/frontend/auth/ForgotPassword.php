<?php
// views/frontend/auth/ForgotPassword.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$oldEmail = $_SESSION['old_input']['email'] ?? '';
unset($_SESSION['old_input']);
?>

<div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">
    <div class="max-w-md w-full p-8 space-y-6 bg-white rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold text-center text-gray-900">Quên mật khẩu</h2>

        <p class="text-sm text-gray-600 text-center">Nhập email của bạn để nhận mã đặt lại mật khẩu.</p>

        <form action="?controller=auth&action=sendForgotPassword" method="POST" class="mt-6 space-y-4">

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-sm font-medium" role="alert">
                    <?= htmlspecialchars($_SESSION['error']); ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-sm font-medium" role="alert">
                    <?= htmlspecialchars($_SESSION['success']); ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <input type="email" name="email" placeholder="Email của bạn" required
                value="<?= htmlspecialchars($oldEmail); ?>"
                class="appearance-none rounded-lg relative block w-full px-4 py-2.5 border border-gray-300 placeholder-gray-500 text-gray-900 
                focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-base transition duration-150">

            <div class="flex items-center justify-between">
                <a href="?controller=auth&action=showLoginForm" class="text-sm text-amber-600 hover:text-amber-500">Quay lại đăng nhập</a>
                <button type="submit" class="py-2 px-4 bg-amber-600 text-white rounded-lg hover:bg-amber-700">Gửi</button>
            </div>
        </form>
    </div>
</div>
