<div class="container mx-auto py-10 px-4">
    <h1 class="text-4xl font-bold text-red-600 mb-6">Trang Quản Trị (ADMIN DASHBOARD)</h1>
    <p class="text-xl text-gray-700">Chào mừng, <?= $_SESSION['user_name'] ?? 'Admin' ?>.</p>
    <p class="mt-4">Đây là nơi bạn có thể quản lý sách, đơn hàng và người dùng.</p>
    <a href="index.php?controller=authen&action=logout" class="mt-6 inline-block px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-700">Đăng Xuất</a>
</div>