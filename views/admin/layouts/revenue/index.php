<<<<<<< HEAD
<?php
// views/admin/revenue/index.php
print_r($data);
?>
<div class="flex-1 grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-lg">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Báo cáo & Thống kê Doanh thu</h2>
        <h3 class="text-xl font-semibold mb-4">Doanh thu theo Tuần/Tháng/Năm</h3>

=======
<?php 
// views/admin/revenue/index.php
?>
<h2 class="text-2xl font-bold mb-6 text-gray-800">Báo cáo & Thống kê Doanh thu</h2>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-lg">
        <h3 class="text-xl font-semibold mb-4">Doanh thu theo Tuần/Tháng/Năm</h3>
        
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
        <div class="flex space-x-2 mb-4">
            <button class="px-3 py-1 bg-amber-700 text-white rounded text-sm hover:bg-amber-600">Tháng này</button>
            <button class="px-3 py-1 bg-gray-200 text-gray-700 rounded text-sm hover:bg-gray-300">Năm nay</button>
            <select class="p-1 border rounded text-sm">
                <option>2025</option>
                <option>2024</option>
            </select>
        </div>
<<<<<<< HEAD

=======
        
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
        <div class="h-96 bg-gray-50 flex items-center justify-center rounded-lg border border-dashed text-gray-500">
            [Vùng hiển thị Biểu đồ Doanh thu chi tiết]
        </div>
    </div>
<<<<<<< HEAD

=======
    
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
    <div class="bg-white p-6 rounded-xl shadow-lg">
        <h3 class="text-xl font-semibold mb-4">Top Sản phẩm</h3>
        <div class="mb-4">
            <p class="font-bold text-green-700">Bán chạy nhất:</p>
            <ol class="list-decimal list-inside ml-2 text-gray-600">
                <li>Sản phẩm A (150 cuốn)</li>
                <li>Sản phẩm B (120 cuốn)</li>
            </ol>
        </div>
        <div class="mb-4">
            <p class="font-bold text-red-700">Bán ế nhất:</p>
            <ol class="list-decimal list-inside ml-2 text-gray-600">
                <li>Sản phẩm X (3 cuốn)</li>
                <li>Sản phẩm Y (5 cuốn)</li>
            </ol>
        </div>
    </div>
</div>