<footer class="bg-gray-800 text-white mt-10 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-10">
            <div>
                <a href="#" class="flex items-center space-x-2 mb-5">
                    <span class="text-3xl font-bold tracking-wider text-orange-400">BOOKAZA</span>
                </a>
                <p class="text-base text-gray-400">
                    Thế giới sách online, nơi tri thức được trao đổi và lan tỏa.
                </p>

                <div class="flex space-x-5 mt-5">
                    <a href="mailto:support@bookaza.com"
                        class="text-lg text-gray-400 hover:text-orange-400 transition duration-300" title="Gửi Email">
                        <i class="fas fa-envelope"></i> Mail </a>
                    <a href="#" class="text-lg text-gray-400 hover:text-orange-400 transition duration-300">
                        <i class="fab fa-facebook-f"></i> FB
                    </a>
                    <a href="#" class="text-lg text-gray-400 hover:text-orange-400 transition duration-300">
                        <i class="fab fa-twitter"></i> TW
                    </a>
                    <a href="#" class="text-lg text-gray-400 hover:text-orange-400 transition duration-300">
                        <i class="fab fa-instagram"></i> IG
                    </a>
                </div>
            </div>

            <div>
                <h3 class="text-xl font-semibold mb-5 text-orange-400">Thông tin</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-base text-gray-400 hover:text-white transition duration-300">Về chúng
                            tôi</a></li>
                    <li><a href="#" class="text-base text-gray-400 hover:text-white transition duration-300">Chính sách
                            bảo mật</a></li>
                    <li><a href="#" class="text-base text-gray-400 hover:text-white transition duration-300">Điều khoản
                            dịch vụ</a></li>
                    <li><a href="#" class="text-base text-gray-400 hover:text-white transition duration-300">Tuyển
                            dụng</a></li>
                </ul>
            </div>

            <div>
                <h3 class="text-xl font-semibold mb-5 text-orange-400">Danh mục</h3>
                <ul class="space-y-3">
                    <?php
                    $categories = ["Sách Kinh Doanh", "Sách Văn Học", "Sách Kỹ Năng", "Sách Thiếu Nhi"];
                    foreach ($categories as $cat) {
                        echo "<li><a href='#' class='text-base text-gray-400 hover:text-white transition duration-300'>{$cat}</a></li>";
                    }
                    ?>
                </ul>
            </div>

            <div>
                <h3 class="text-xl font-semibold mb-5 text-orange-400">Liên hệ</h3>
                <p class="text-base text-gray-400 mb-3">
                    <span class="font-medium text-white">Địa chỉ:</span> 597/14 Quang Trung, phường 11, Gò Vấp, TP.HCM.
                </p>
                <p class="text-base text-gray-400 mb-3">
                    <span class="font-medium text-white">Điện thoại:</span> <a href="tel:0843636561"
                        class="hover:text-white">0843636561</a>
                </p>
                <p class="text-base text-gray-400">
                    <span class="font-medium text-white">Email:</span> <a href="mailto:baobang21032004@gmail.com"
                        class="hover:text-white">baobang21032004@gmail.com</a>
                </p>
            </div>
        </div>

        <div class="mt-12 pt-8 border-t border-gray-700">
            <p class="text-base text-center text-gray-400">
                &copy; <?php echo date("Y"); ?> BOOKAZA Online Book Store. Tất cả bản quyền đã được bảo lưu.
            </p>
        </div>
    </div>
</footer>
</div>