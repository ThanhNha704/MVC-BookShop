<!-- <div class="flex items-center justify-center min-h-screen bg-gray-100 p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden md:max-w-lg">
        
        <div class="md:flex">
            <div class="hidden md:block md:w-1/2 bg-amber-500 flex items-center justify-center p-6">
                <i class="fas fa-lock text-white text-8xl opacity-80"></i>
            </div>
            
            <div class="w-full md:w-1/2 p-8">
                <h2 class="text-3xl font-extrabold text-gray-800 text-center mb-6">Đăng Nhập</h2>
                <p class="text-sm text-gray-500 text-center mb-6">Chào mừng trở lại BOOKAZA!</p>

                <?php if (!empty($error ?? '')): ?>
                    <div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-sm text-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i><?= $error ?>
                    </div>
                <?php endif; ?>
                
                <form action="index.php?controller=authen&action=login" method="POST" class="space-y-6">
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 transition duration-150"
                               placeholder="VD: tenban@example.com">
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Mật khẩu</label>
                        <input type="password" id="password" name="password" required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500 transition duration-150"
                               placeholder="Nhập mật khẩu của bạn">
                    </div>
                    
                    <button type="submit" 
                            class="w-full flex items-center justify-center bg-amber-500 text-white font-bold py-2.5 rounded-lg hover:bg-amber-600 transition duration-200 shadow-md">
                        <i class="fas fa-sign-in-alt mr-2"></i>ĐĂNG NHẬP
                    </button>
                </form>
                
                <p class="mt-6 text-center text-sm text-gray-600">
                    Chưa có tài khoản? 
                    <a href="index.php?controller=authen&action=register" class="text-amber-600 font-semibold hover:text-amber-700 hover:underline transition duration-150">
                        Đăng ký ngay
                    </a>
                </p>
            </div>
        </div>
    </div>
</div> -->