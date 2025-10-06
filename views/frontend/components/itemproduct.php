<a href="index.php?controllers=product&action=detail&id=<?= $product['id'] ?>">
    <div
        class="product-card border border-gray-300 rounded-lg p-4 hover:shadow-lg transition-shadow duration-300 relative">
        <!-- Hình ảnh sản phẩm -->
        <div class="product-image mb-4">
            <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>"
                class="w-full h-48 object-cover rounded-md">
        </div>

        <!-- Tên sản phẩm -->
        <h2 class="product-name text-lg font-semibold mb-2">
            <?= htmlspecialchars($product['name']) ?>
        </h2>

        <!-- Giá sản phẩm -->
        <p class="product-price text-amber-500 font-bold mb-2">
            <?= number_format($product['price'], 0, ',', '.') ?> VND
        </p>

        <!-- Mô tả ngắn -->
        <p class="product-description text-gray-600 text-sm mb-4">
            <?= htmlspecialchars($product['short_description']) ?>
        </p>

        <!-- Nút Thêm vào giỏ hàng -->
        <button
            class="add-to-cart-btn bg-amber-500 text-white px-4 py-2 rounded hover:bg-amber-600 transition-colors duration-300 absolute bottom-4 left-1/2 transform -translate-x-1/2">
            Thêm vào giỏ hàng
        </button>
    </div>
</a>