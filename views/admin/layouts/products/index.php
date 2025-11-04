<?php
// views/admin/products/index.php
if (!function_exists('format_currency')) {
    function format_currency($amount)
    {
        return number_format($amount, 0, ',', '.') . '₫';
    }
}

// Lấy dữ liệu sản phẩm. Nếu không có, gán mảng rỗng.
$listProduct = $data['products'] ?? [];
// print_r($listProduct);
?>

<<<<<<< HEAD
<div class="flex-1 bg-white p-6 overflow-scroll">
=======
<div class="flex-1 bg-white p-6">
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Quản lý Sản phẩm</h2>

    <div class="flex justify-between items-center mb-4 gap-4">
        <h3 class="text-xl font-semibold hidden sm:block">Danh sách Sản phẩm</h3>

        <div class="flex-1 max-w-lg relative">
            <form action="" method="GET" id="searchForm">
                <input type="hidden" name="controller" value="admin">
                <input type="hidden" name="action" value="products">
                <input type="text" name="search" id="searchInput" placeholder="Tìm kiếm theo tên sản phẩm..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 shadow-sm"
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" autocomplete="off">
            </form>
            <div id="searchSuggestions"
                class="absolute w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden z-50 max-h-60 overflow-y-auto">
            </div>
        </div>

        <a href="?controller=admin&action=addProduct"
            class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors whitespace-nowrap">
            + Thêm Sản phẩm mới
        </a>
    </div>

    <div class="overflow-x-auto overflow-y-auto h-full w-full rounded-lg shadow-lg">
<<<<<<< HEAD
        <table class="min-w-full divide-y divide-gray-200 bg-white">
            <thead class="bg-gray-50 sticky top-0 z-10 border-b border-gray-300">
                <tr>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase">ID</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase">Tên Sản
                        phẩm</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase">Giá</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase">Tồn kho
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase">Hành động
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300">
                <?php if (empty($listProduct)): ?>
                    <tr class="hover:bg-gray-100">
=======
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0 z-10 border-b border-gray-400">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Tên Sản
                        phẩm</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Giá</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Tồn kho
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Hành động
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-300 h-full">
                <?php if (empty($listProduct)): ?>
                    <tr>
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            Không tìm thấy sản phẩm nào.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($listProduct as $product): // Đổi $value thành $product để dễ đọc hơn ?>
<<<<<<< HEAD
                        <tr class="hover:bg-gray-100">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium text-gray-900">
=======
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
                                <?= htmlspecialchars($product['id']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <?= htmlspecialchars($product['title']); ?>
                            </td>
<<<<<<< HEAD
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">
                                <?= format_currency($product['price']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-700">
                                <?= number_format($product['quantity']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium">
=======
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <?= format_currency($product['price']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <?= number_format($product['quantity']); ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
                                <a href="?controller=admin&action=editProduct&id=<?= $product['id'] ?>"
                                    class="text-indigo-600 hover:text-indigo-900 mr-3">Sửa</a>
                                <form action="?controller=admin&action=toggleProductStatus" method="POST" class="inline">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <?php if ($product['is_visible'] ?? true): ?>
                                        <button type="submit" name="is_visible" value="hide"
                                            class="text-amber-600 hover:text-amber-900">
                                            Ẩn
                                        </button>
                                    <?php else: ?>
                                        <button type="submit" name="is_visible" value="show"
                                            class="text-green-600 hover:text-green-900">
                                            Hiện
                                        </button>
                                    <?php endif; ?>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</div>