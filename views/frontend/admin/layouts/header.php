<?php
// views/admin/layouts/header.php

$userName = $_SESSION['user_name'] ?? 'Admin';
?>

<header class="h-16 bg-white shadow-md flex items-center justify-between px-6 z-10 sticky top-0">
    
    <h1 class="text-xl font-semibold text-gray-800">
        <?php 
            // Hiển thị tiêu đề động dựa trên Action
            $pageTitle = [
                'index' => 'Dashboard Tổng quan',
                'products' => 'Quản lý Sản phẩm',
                'orders' => 'Quản lý Đơn hàng',
                'users' => 'Quản lý Người dùng',
                'reviews' => 'Quản lý Đánh giá',
                'vouchers' => 'Quản lý Voucher & Khuyến mãi',
                'revenue' => 'Báo cáo Doanh thu'
            ];
            echo $pageTitle[$_GET['action'] ?? 'index'] ?? 'Trang Quản Trị';
        ?>
    </h1>

    <div class="flex items-center space-x-4">
        
        <div class="text-gray-700 font-medium">
            Xin chào, <span class="font-bold text-amber-700"><?= htmlspecialchars($userName) ?></span>
        </div>

        <a href="?controller=auth&action=logout" 
           class="flex items-center p-2 rounded-lg bg-red-500 text-white hover:bg-red-600 transition-colors duration-150 text-sm">
            <span class="mr-1">➡️</span> Đăng xuất
        </a>
    </div>
</header>