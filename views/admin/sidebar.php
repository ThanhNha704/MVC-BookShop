<<<<<<< HEAD
<?php
// Giả định các biến này đã được định nghĩa ở đâu đó
$current_controller = $current_controller ?? 'admin';
$current_action = $current_action ?? 'index';

$active_class = 'bg-amber-700 text-white shadow-md transform scale-100';
$inactive_class = 'hover:bg-amber-700/70 text-amber-100 hover:text-white transform hover:scale-[1.02]';
?>


<div class="flex flex-1 sticky left-0 top-0 h-screen">

    <aside id="sidebar" class="mt-15 md:mt-0 w-64 bg-amber-600 text-white flex-col shadow-lg z-40 overflow-y-auto 
                    fixed inset-y-0 left-0 transform -translate-x-full transition-transform duration-300 
                    md:relative md:translate-x-0 md:flex md:flex-shrink-0">

        <div class="text-right px-4 pt-4 pb-2 md:hidden">
            <button id="close-menu"
                class="text-amber-100 hover:text-white text-3xl transition duration-300 hover:rotate-90">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div
            class="px-6 pb-6 md:pt-6 text-2xl font-extrabold text-white border-b border-amber-800 tracking-wider flex-shrink-0">
=======
<div class="flex h-screen bg-gray-100">
    <aside class="mb-10 w-64 bg-amber-600 text-white flex flex-col shadow-lg z-20 overflow-scroll">
        <div class="p-6 text-2xl font-extrabold text-white border-b border-amber-800 tracking-wider">
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
            BOOKAZA ADMIN
        </div>

        <nav class="flex-grow p-4 space-y-2">
            <a href="?controller=admin&action=index"
<<<<<<< HEAD
                class="flex items-center p-3 rounded-lg font-medium transition-colors duration-200 
                            <?= ($current_controller == 'admin' && $current_action == 'index') ? $active_class : $inactive_class ?>">
                <span class="mr-3 text-lg">📊</span> Dashboard
            </a>
            <h3 class="mt-4 pt-2 text-sm font-semibold text-amber-300 border-t border-amber-600/50">QUẢN LÝ DỮ LIỆU</h3>
            <a href="?controller=admin&action=products"
                class="flex items-center p-3 rounded-lg font-medium transition-colors duration-200 <?= ($current_controller == 'admin' && $current_action == 'products') ? $active_class : $inactive_class ?>"><span
                    class="mr-3 text-lg">📚</span> Sản phẩm</a>
            <a href="?controller=admin&action=orders"
                class="flex items-center p-3 rounded-lg font-medium transition-colors duration-200 <?= ($current_controller == 'admin' && $current_action == 'orders') ? $active_class : $inactive_class ?>"><span
                    class="mr-3 text-lg">📦</span> Đơn hàng</a>
            <a href="?controller=admin&action=users"
                class="flex items-center p-3 rounded-lg font-medium transition-colors duration-200 <?= ($current_controller == 'admin' && $current_action == 'users') ? $active_class : $inactive_class ?>"><span
                    class="mr-3 text-lg">🧑‍💻</span> Người dùng</a>
            <a href="?controller=admin&action=reviews"
                class="flex items-center p-3 rounded-lg font-medium transition-colors duration-200 <?= ($current_controller == 'admin' && $current_action == 'reviews') ? $active_class : $inactive_class ?>"><span
                    class="mr-3 text-lg">⭐</span> Đánh giá</a>
            <h3 class="mt-4 pt-2 text-sm font-semibold text-amber-300 border-t border-amber-600/50">TIẾP THỊ & BÁO CÁO
            </h3>
            <!-- <a href="?controller=admin&action=vouchers"
                class="flex items-center p-3 rounded-lg font-medium transition-colors duration-200 <?= ($current_controller == 'admin' && $current_action == 'vouchers') ? $active_class : $inactive_class ?>"><span
                    class="mr-3 text-lg">🎁</span> Voucher & KM</a> -->
            <a href="?controller=admin&action=revenue"
                class="flex items-center p-3 rounded-lg font-medium transition-colors duration-200 <?= ($current_controller == 'admin' && $current_action == 'revenue') ? $active_class : $inactive_class ?>"><span
                    class="mr-3 text-lg">📈</span> Doanh thu</a>
        </nav>
    </aside>

    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden md:hidden"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const openMenuBtn = document.getElementById('open-menu-sidebar'); // Đã đổi ID
            const closeMenuBtn = document.getElementById('close-menu');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                sidebar.classList.add('translate-x-0');
                overlay.classList.remove('hidden');
            }

            function closeSidebar() {
                sidebar.classList.remove('translate-x-0');
                sidebar.classList.add('-translate-x-full');
                // Chờ transition hoàn tất trước khi ẩn overlay hoàn toàn (300ms)
                setTimeout(() => {
                    overlay.classList.add('hidden');
                }, 300);
            }

            // Gán sự kiện
            if (openMenuBtn && sidebar && overlay) {
                openMenuBtn.addEventListener('click', openSidebar);
            }
            if (closeMenuBtn) {
                closeMenuBtn.addEventListener('click', closeSidebar);
            }
            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }
        });
    </script>
=======
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

    </aside>
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
