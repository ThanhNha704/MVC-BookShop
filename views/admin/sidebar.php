    <div class="flex flex-1 overflow-hidden">
        <aside class="w-64 bg-amber-600 text-white flex flex-col shadow-lg z-20 overflow-y-auto flex-shrink-0">
            <div class="p-6 text-2xl font-extrabold text-white border-b border-amber-800 tracking-wider">
                BOOKAZA ADMIN
            </div>

            <nav class="flex-grow p-4 space-y-2">
                <a href="?controller=admin&action=index"
                    class="flex items-center p-3 rounded-lg font-medium transition-colors 
                    <?= ($current_controller == 'admin' && $current_action == 'index') ? $active_class : $inactive_class ?>">
                    <span class="mr-3 text-lg">📊</span> Dashboard
                </a>

                <h3 class="mt-4 pt-2 text-sm font-semibold text-amber-300 border-t border-amber-600/50">QUẢN LÝ DỮ LIỆU
                </h3>

                <a href="?controller=admin&action=products"
                    class="flex items-center p-3 rounded-lg font-medium transition-colors 
                    <?= ($current_controller == 'admin' && $current_action == 'products') ? $active_class : $inactive_class ?>">
                    <span class="mr-3 text-lg">📚</span> Sản phẩm
                </a>

                <a href="?controller=admin&action=orders"
                    class="flex items-center p-3 rounded-lg font-medium transition-colors 
                    <?= ($current_controller == 'admin' && $current_action == 'orders') ? $active_class : $inactive_class ?>">
                    <span class="mr-3 text-lg">📦</span> Đơn hàng
                </a>

                <a href="?controller=admin&action=users"
                    class="flex items-center p-3 rounded-lg font-medium transition-colors 
                    <?= ($current_controller == 'admin' && $current_action == 'users') ? $active_class : $inactive_class ?>">
                    <span class="mr-3 text-lg">🧑‍💻</span> Người dùng
                </a>

                <a href="?controller=admin&action=reviews"
                    class="flex items-center p-3 rounded-lg font-medium transition-colors 
                    <?= ($current_controller == 'admin' && $current_action == 'reviews') ? $active_class : $inactive_class ?>">
                    <span class="mr-3 text-lg">⭐</span> Đánh giá
                </a>

                <h3 class="mt-4 pt-2 text-sm font-semibold text-amber-300 border-t border-amber-600/50">TIẾP THỊ & BÁO
                    CÁO</h3>

                <a href="?controller=admin&action=vouchers"
                    class="flex items-center p-3 rounded-lg font-medium transition-colors 
                    <?= ($current_controller == 'admin' && $current_action == 'vouchers') ? $active_class : $inactive_class ?>">
                    <span class="mr-3 text-lg">🎁</span> Voucher & KM
                </a>

                <a href="?controller=admin&action=revenue"
                    class="flex items-center p-3 rounded-lg font-medium transition-colors 
                    <?= ($current_controller == 'admin' && $current_action == 'revenue') ? $active_class : $inactive_class ?>">
                    <span class="mr-3 text-lg">📈</span> Doanh thu
                </a>

            </nav>

            <div class="mt-auto p-4 border-t border-amber-800 flex-shrink-0">
                <a href="index.php"
                    class="flex items-center p-3 text-amber-100 hover:bg-amber-600 rounded-lg transition-colors">
                    <span class="mr-3 text-lg">🏠</span> Quay lại trang chính
                </a>
            </div>
        </aside>