<?php
// Lấy email từ biến $data được truyền từ Controller (showVerifyOtpForm)
$email = $_SESSION['verify_email'] ?? 'bạn';
?>

<div class="min-h-screen flex items-center justify-center bg-gray-100 p-4">
    <div class="max-w-md w-full p-8 space-y-6 bg-white rounded-xl shadow-lg">

        <h2 class="text-3xl font-bold text-center text-gray-900">
            Xác Thực Mã OTP
        </h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-sm" role="alert">
                <?= $_SESSION['error']; ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form action="?controller=auth&action=verifyOtp" method="POST" class="mt-8 space-y-4">

            <p class="text-center text-gray-600">
                Mã OTP đã được gửi đến email:
                <strong class="font-medium text-indigo-600"><?= htmlspecialchars($email) ?></strong>
            </p>

            <input type="text" name="otp_code" placeholder="Nhập mã OTP 6 chữ số" maxlength="6" required
                class="appearance-none rounded-md relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-center text-2xl tracking-widest text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                style="">

            <button type="submit"
                class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md 
                    text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                Xác Thực
            </button>

            <div class="text-center">
                <span class="text-sm font-medium text-gray-500">Chưa nhận được mã?</span>
                <a href="?controller=auth&action=resendOtp"
                    class="text-sm font-medium text-amber-600 hover:text-amber-700">
                    Gửi lại
                </a>
            </div>

        </form>
    </div>
</div>