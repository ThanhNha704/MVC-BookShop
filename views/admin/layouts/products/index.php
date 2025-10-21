<?php
// views/admin/products/index.php

// Giả định hàm format_currency đã được định nghĩa
if (!function_exists('format_currency')) {
    function format_currency($amount)
    {
        return number_format($amount, 0, ',', '.') . '₫';
    }
}

// Lấy dữ liệu sản phẩm. Nếu không có, gán mảng rỗng.
$listProduct = $data['products'] ?? [];

// Thiết lập chiều cao cho khu vực cuộn
$tableMaxHeight = 'max-h-full'; // Tailwind CSS class cho chiều cao tối đa 96 (khoảng 24rem/384px)

?>

<div class="flex-1 bg-white p-6">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Quản lý Sản phẩm</h2>

    <div class="flex justify-between items-center mb-4 gap-4">
        <h3 class="text-xl font-semibold hidden sm:block">Danh sách Sản phẩm</h3>

        <form action="" method="GET" class="flex-1 max-w-lg">
            <!-- <input type="hidden" name="controller" value="admin">
            <input type="hidden" name="action" value="products"> -->
            <input type="text" name="search" placeholder="Tìm kiếm theo tên sản phẩm..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 shadow-sm"
                value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        </form>

        <a href="?controller=admin&action=addProduct"
            class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors whitespace-nowrap">
            + Thêm Sản phẩm mới
        </a>
    </div>

    <div class="overflow-x-auto overflow-y-auto <?= $tableMaxHeight ?> rounded-lg shadow-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0 z-10 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tên Sản
                        phẩm</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Giá</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tồn kho
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hành động
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200 h-full">
                <?php if (empty($listProduct)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            Không tìm thấy sản phẩm nào.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($listProduct as $product): // Đổi $value thành $product để dễ đọc hơn ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?= htmlspecialchars($product['id']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <?= htmlspecialchars($product['title']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <?= format_currency($product['price']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <?= number_format($product['quantity']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="?controller=admin&action=editProduct&id=<?= $product['id'] ?>"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">Sửa</a>
                                <a href="?controller=admin&action=deleteProduct&id=<?= $product['id'] ?>"
                                    class="text-red-600 hover:text-red-900"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>