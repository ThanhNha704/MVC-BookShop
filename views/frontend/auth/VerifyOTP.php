<!-- <div class="flex items-center justify-center min-h-screen bg-gray-100 p-4">
    <div class="w-full max-w-sm bg-white rounded-xl shadow-2xl p-8">
        
        <div class="text-center mb-6">
            <i class="fas fa-envelope-open-text text-amber-500 text-5xl mb-3"></i>
            <h2 class="text-2xl font-extrabold text-gray-800">Xác Nhận Mã OTP</h2>
            <p class="text-sm text-gray-500 mt-2">Vui lòng kiểm tra email của bạn để nhận mã xác nhận.</p>
        </div>

        <?php if (!empty($message ?? '')): ?>
            <div class="bg-green-100 text-green-600 p-3 rounded mb-4 text-sm text-center">
                <i class="fas fa-check-circle mr-2"></i><?= $message ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($error ?? '')): ?>
            <div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-sm text-center">
                <i class="fas fa-exclamation-triangle mr-2"></i><?= $error ?>
            </div>
        <?php endif; ?>
        
        <form action="index.php?controller=authen&action=verifyOtp" method="POST" class="space-y-6">
            
            <div>
                <label for="otp" class="block text-sm font-medium text-gray-700 mb-1 text-center">Nhập mã 6 chữ số:</label>
                <input type="text" id="otp" name="otp" required 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg text-center text-xl tracking-widest focus:outline-none focus:ring-2 focus:ring-amber-500 transition duration-150" 
                       maxlength="6" placeholder="______">
            </div>
            
            <input type="hidden" name="action" value="verify">
            
            <button type="submit" 
                    class="w-full flex items-center justify-center bg-amber-500 text-white font-bold py-2.5 rounded-lg hover:bg-amber-600 transition duration-200 shadow-md">
                <i class="fas fa-check-circle mr-2"></i>XÁC NHẬN
            </button>
        </form>
        
        <p class="mt-4 text-center text-xs text-gray-500">
            *Mã có thể hết hạn sau vài phút.
        </p>
    </div>
</div> -->