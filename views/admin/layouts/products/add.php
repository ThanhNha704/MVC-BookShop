<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Lấy dữ liệu cũ nếu có lỗi validation
$oldInput = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Nút quay lại -->
        <div class="mb-6">
            <a href="?controller=admin&action=products" class="text-amber-600 hover:text-amber-800 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Quay lại danh sách sản phẩm
            </a>
        </div>

        <!-- Form thêm sản phẩm -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Thêm Sản phẩm mới</h1>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?= htmlspecialchars($_SESSION['error']) ?></span>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form action="?controller=admin&action=addProduct" method="POST" enctype="multipart/form-data" class="space-y-6">
                <!-- Tên sách -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Tên sách <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" required
                        value="<?= htmlspecialchars($oldInput['title'] ?? '') ?>"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                </div>

                <!-- Tác giả -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Tác giả <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="author" required
                        value="<?= htmlspecialchars($oldInput['author'] ?? '') ?>"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                </div>

                <!-- Giá & Giảm giá -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Giá <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="price" min="0" step="1000" required
                            value="<?= htmlspecialchars($oldInput['price'] ?? '') ?>"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">
                            Giảm giá (%)
                        </label>
                        <input type="number" name="discount" min="0" max="100" step="1"
                            value="<?= htmlspecialchars($oldInput['discount'] ?? '0') ?>"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                    </div>
                </div>

                <!-- Số lượng -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Số lượng <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="quantity" min="0" required
                        value="<?= htmlspecialchars($oldInput['quantity'] ?? '') ?>"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500">
                </div>

                <!-- Mô tả -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Mô tả</label>
                    <textarea name="description" rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500"
                    ><?= htmlspecialchars($oldInput['description'] ?? '') ?></textarea>
                </div>

                <!-- Ảnh sản phẩm -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ảnh sản phẩm</label>
                    <input type="file" name="image" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-amber-50 file:text-amber-700
                        hover:file:bg-amber-100">
                    <p class="mt-1 text-sm text-gray-500">PNG, JPG hoặc GIF (Tối đa 2MB)</p>
                </div>

                <!-- Trạng thái hiển thị -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_visible" value="1"
                        <?= isset($oldInput['is_visible']) ? 'checked' : '' ?>
                        class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                    <label class="ml-2 block text-sm text-gray-900">
                        Hiển thị sản phẩm
                    </label>
                </div>

                <!-- Nút submit -->
                <div class="flex justify-end space-x-3">
                    <a href="?controller=admin&action=products"
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        Hủy
                    </a>
                    <button type="submit"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-amber-600 hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                        Thêm sản phẩm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>