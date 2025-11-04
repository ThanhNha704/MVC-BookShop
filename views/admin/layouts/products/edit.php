<?php
// views/admin/products/edit.php
$product = $data['product'];
// print_r($product);

function format_price($amount)
{
    return number_format($amount, 0, '', ''); // Định dạng giá không dấu phẩy, không thập phân
}
?>

<div class="flex-1 bg-gray-50 p-8 overflow-scroll">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-lg">
        <h1 class="text-3xl font-extrabold mb-2 text-gray-900">Sửa Sản phẩm: #<?= htmlspecialchars($product['id']) ?>
        </h1>
        <p class="text-gray-500 mb-6">Chỉnh sửa thông tin chi tiết cho cuốn sách
            "<?= htmlspecialchars($product['title']) ?>".</p>

        <a href="?controller=admin&action=products"
            class="inline-flex items-center text-amber-600 hover:text-amber-800 font-medium mb-8">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Quay lại Danh sách Sản phẩm
        </a>

        <form action="?controller=admin&action=updateProduct" method="POST" enctype="multipart/form-data"
            class="space-y-6">

            <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">

            <?php if (!empty($product['image'])): ?>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ảnh hiện tại</label>
                    <div class="w-48 h-48 relative border rounded-lg overflow-hidden">
                        <img src="public/products/<?= htmlspecialchars($product['image']) ?>"
                            alt="<?= htmlspecialchars($product['title']) ?>" class="w-full h-full object-cover">
                    </div>
                </div>
            <?php endif; ?>

            <div class="border rounded-lg p-4 bg-gray-50">
                <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Tải lên ảnh mới</label>
                <input type="file" name="image" id="image" accept="image/*" class="block w-full text-sm text-gray-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-full file:border-0
                              file:text-sm file:font-semibold
                              file:bg-amber-50 file:text-amber-700
                              hover:file:bg-amber-100">
                <p class="mt-2 text-sm text-gray-500">
                    Chọn ảnh mới để thay thế ảnh hiện tại. Bỏ qua nếu không muốn thay đổi ảnh.
                </p>
            </div>

            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Tên Sản phẩm</label>
                <input type="text" name="title" id="title" required value="<?= htmlspecialchars($product['title']) ?>"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div>
                <label for="author" class="block text-sm font-medium text-gray-700">Tác giả</label>
                <input type="text" name="author" id="author" value="<?= htmlspecialchars($product['author']) ?>"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
            </div>

            <div class="grid grid-cols-4 gap-6">
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700">Giá (VNĐ)</label>
                    <input type="number" name="price" id="price" required min="0" step="1000"
                        value="<?= format_price($product['price']) ?>"
                        class="px-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                </div>

                <div>
                    <label for="discount" class="block text-sm font-medium text-gray-700">Giảm giá (%)</label>
                    <input type="number" name="discount" id="discount" min="0" max="100"
                        value="<?= htmlspecialchars($product['discount']) ?>"
                        class="px-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                </div>

                <div>
                    <label for="quantity" class="block text-sm font-medium text-gray-700">Số lượng tồn</label>
                    <input type="number" name="quantity" id="quantity" required min="0"
                        value="<?= htmlspecialchars($product['quantity']) ?>"
                        class="px-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                </div>

                <div>
                    <label for="is_visible" class="block text-sm font-medium text-gray-700">Trạng thái hiển thị</label>
                    <select name="is_visible" id="is_visible"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                        <option value="1" <?= ($product['is_visible'] ?? true) ? 'selected' : '' ?>>Hiện</option>
                        <option value="0" <?= !($product['is_visible'] ?? true) ? 'selected' : '' ?>>Ẩn</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Mô tả chi tiết</label>
                <textarea name="description" id="description" rows="5"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500"><?= htmlspecialchars($product['description']) ?></textarea>
            </div>

            <div class="pt-5 border-t border-gray-200">
                <button type="submit"
                    class="px-6 py-3 bg-amber-600 text-white font-semibold rounded-lg hover:bg-amber-700 transition duration-150 shadow-md">
                    Lưu Thay đổi
                </button>
            </div>
        </form>
    </div>
</div>
</div>