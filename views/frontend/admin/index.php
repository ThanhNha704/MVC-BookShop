<?php
// views/admin/index.php

// Dữ liệu giả lập
$totalProducts = 150;
$totalOrders = 45;
$newUsers = 12;

// Bán chạy/bán ế
$bestSeller = "Harry Potter 7";
$worstSeller = "Tự truyện G.G.";
?>

<div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
    
    <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-blue-500 hover:shadow-xl transition-shadow">
        <p class="text-sm font-medium text-gray-500">Tổng Sản phẩm</p>
        <p class="text-3xl font-bold text-gray-900 mt-1"><?= $totalProducts ?></p>
        <span class="text-xs text-blue-500">Xem chi tiết</span>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-green-500 hover:shadow-xl transition-shadow">
        <div>
            <p class="text-sm font-medium text-gray-500">Tổng Đơn hàng</p>
            <p class="text-3xl font-bold text-gray-900 mt-1"><?= $totalOrders ?></p>
        </div>
        <span class="text-xs text-green-500">Xem đơn hàng mới</span>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-yellow-500 hover:shadow-xl transition-shadow">
        <div>
            <p class="text-sm font-medium text-gray-500">Người dùng mới (Tuần)</p>
            <p class="text-3xl font-bold text-gray-900 mt-1"><?= $newUsers ?></p>
        </div>
        <span class="text-xs text-yellow-500">Quản lý người dùng</span>
    </div>
    
    <div class="bg-white p-6 rounded-xl shadow-lg border-l-4 border-red-500 hover:shadow-xl transition-shadow">
        <div>
            <p class="text-sm font-medium text-gray-500">Bán chạy nhất</p>
            <p class="text-xl font-bold text-gray-900 mt-1 truncate" title="<?= $bestSeller ?>"><?= $bestSeller ?></p>
        </div>
        <span class="text-xs text-red-500">Xem báo cáo</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-lg">
        <h3 class="text-xl font-semibold mb-4 text-gray-800">Doanh thu theo Tháng (Năm 2025)</h3>
        <div class="flex space-x-2 mb-4">
            <button class="px-3 py-1 bg-amber-700 text-white rounded text-sm hover:bg-amber-600">Tháng</button>
            <button class="px-3 py-1 bg-gray-200 text-gray-700 rounded text-sm hover:bg-gray-300">Tuần</button>
            <button class="px-3 py-1 bg-gray-200 text-gray-700 rounded text-sm hover:bg-gray-300">Năm</button>
        </div>
        <div class="h-80 bg-gray-50 flex items-center justify-center rounded-lg border border-dashed text-gray-500">
            [Vùng hiển thị Biểu đồ (Chart.js / ApexCharts)]
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg">
        <h3 class="text-xl font-semibold mb-4 text-gray-800">Sản phẩm bán ế nhất</h3>
        <p class="text-gray-600 mb-4">Cần xem xét khuyến mãi hoặc ngừng bán.</p>
        <div class="bg-red-50 p-3 rounded-lg border border-red-200">
            <p class="font-bold text-red-700 mb-1">Tên sản phẩm:</p>
            <p class="text-lg"><?= $worstSeller ?></p>
            <p class="text-sm text-red-600 mt-2">Đã bán: 3 cuốn (Trong tháng)</p>
        </div>
        <a href="?controller=admin&action=products" class="mt-4 block text-amber-700 hover:underline text-sm">Quản lý Sản phẩm</a>
    </div>
</div>