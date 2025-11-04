<?php
// views/admin/vouchers/index.php
?>
<div class="flex-1">

    <h2 class="text-2xl font-bold  text-gray-800">Quản lý Voucher & Khuyến mãi Sản phẩm</h2>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h3 class="text-xl font-semibold mb-4">Danh sách Voucher (Số lượng, Thời hạn)</h3>
            <a href="#"
                class="mb-4 inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition-colors">+
                Thêm Voucher</a>

            <ul class="space-y-3 mt-4">
                <li class="p-3 border rounded flex justify-between items-center">
                    <div>
                        <p class="font-bold">SALE20 (20%)</p>
                        <p class="text-sm text-gray-500">Còn lại: 50 | HSD: 2025-12-31</p>
                    </div>
                    <a href="#" class="text-indigo-600 hover:text-indigo-900">Sửa</a>
                </li>
            </ul>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg">
            <h3 class="text-xl font-semibold mb-4">Khuyến mãi Trực tiếp Sản phẩm</h3>
            <p class="text-gray-600 mb-4">Thiết lập giá sale hoặc phần trăm giảm giá cho các sản phẩm cụ thể.</p>
            <button class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors">Tạo Khuyến
                mãi Sản phẩm</button>
        </div>
    </div>

</div>