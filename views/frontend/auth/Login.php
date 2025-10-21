<?php 
// Đảm bảo session được bắt đầu nếu nó chưa có
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Lấy dữ liệu cũ (old_input) nếu có, dùng để giữ lại email sau khi đăng nhập lỗi
$oldEmail = $_SESSION['old_input']['email'] ?? '';
unset($_SESSION['old_input']); 
?>

<div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">
    <div class="max-w-md w-full p-8 space-y-6 bg-white rounded-xl shadow-lg">
        
        <h2 class="text-3xl font-bold text-center text-gray-900">
            Đăng Nhập Tài Khoản
        </h2>

        <form action="?controller=auth&action=login" method="POST" class="mt-8 space-y-4">
            
            <!-- XỬ LÝ VÀ HIỂN THỊ THÔNG BÁO -->
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

            <!-- Email -->
            <input type="email" name="email" placeholder="Email" required 
                value="<?= htmlspecialchars($oldEmail); ?>"
                class="appearance-none rounded-lg relative block w-full px-4 py-2.5 border border-gray-300 placeholder-gray-500 text-gray-900 
                focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-base transition duration-150">

            <!-- Mật khẩu -->
            <input type="password" name="password" placeholder="Mật khẩu" required 
                class="appearance-none rounded-lg relative block w-full px-4 py-2.5 border border-gray-300 placeholder-gray-500 text-gray-900 
                focus:outline-none focus:ring-amber-500 focus:border-amber-500 sm:text-base transition duration-150">

            <!-- Nút Đăng Nhập -->
            <button type="submit" 
                class="group relative w-full flex justify-center py-2.5 px-4 border border-transparent text-base font-medium rounded-lg text-white 
                bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 
                transition duration-150 ease-in-out">
                Đăng Nhập
            </button>
            
            <!-- Quên mật khẩu -->
            <div class="text-sm text-right">
                <a href="?controller=auth&action=showForgotPasswordForm" class="font-medium text-amber-600 hover:text-amber-500">
                    Quên mật khẩu?
                </a>
            </div>

        </form>

        <!-- Liên kết Đăng ký -->
        <p class="text-center text-sm text-gray-600">
            Chưa có tài khoản?
            <!-- SỬ DỤNG QUERY STRING để tương thích -->
            <a href="?controller=auth&action=showRegisterForm" class="font-medium text-amber-600 hover:text-amber-500">
                Đăng ký ngay
            </a>
        </p>
    </div>
</div>
