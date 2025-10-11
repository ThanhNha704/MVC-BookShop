<?php 
$item = $data['book'];
$current_price = $item['price'] * (100 - $item['discount']) / 100;
?>
<section class="container mx-auto py-10 px-4 md:px-0">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">
        <div class="md:col-span-1 flex justify-center">
            <div class="w-full max-w-sm overflow-hidden rounded-lg shadow-xl">
                <img src="<?= BASE_URL . 'public/products/' . htmlspecialchars($item['image']) ?>"
                    alt="<?= htmlspecialchars($item['title']) ?>" class="w-full h-auto object-cover" role="img"
                    aria-label="Bìa sách chi tiết: <?= htmlspecialchars($item['title']) ?>">
            </div>
        </div>

        <div class="md:col-span-2">

            <h1 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-2">
                <?= htmlspecialchars($item['title']) ?>
            </h1>

            <p class="text-xl text-gray-600 mb-4 border-b pb-3">
                Tác giả: <span class="font-semibold text-blue-600"><?= htmlspecialchars($item['author']) ?></span>
            </p>

            <!-- <div class="flex items-center mb-6" role="region" aria-label="Đánh giá sản phẩm">
                <span class="text-xl text-yellow-500 mr-2" role="img" aria-label="Sao đánh giá">★★★★☆</span>
                <span class="text-lg font-semibold text-gray-700"><?= $item['rating'] ?></span>
                <span class="text-gray-500 ml-2">(128 đánh giá)</span>
            </div> -->

            <div class="bg-gray-100 p-4 rounded-lg mb-6">
                <?php if ($item['discount'] > 0): ?>
                    <div class="flex items-baseline mb-1">
                        <p class="text-4xl font-extrabold text-red-600 mr-4">
                            <?= number_format($current_price, 0, ',', '.') ?> VND
                        </p>
                        <span class="text-xl line-through text-gray-500">
                            <?= number_format($item['price'], 0, ',', '.') ?> VND
                        </span>
                    </div>
                    <span class="text-lg font-bold text-white bg-red-500 px-3 py-1 rounded-full">
                        Giảm <?= $item['discount'] ?>%
                    </span>
                <?php else: ?>
                    <p class="text-4xl font-extrabold text-red-600">
                        <?= number_format($item['price'], 0, ',', '.') ?> VND
                    </p>
                <?php endif; ?>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 mb-6">
                <button
                    class="flex-1 bg-green-600 text-white text-xl font-bold py-3 rounded-lg hover:bg-green-700 transition duration-300 shadow-md">
                    <i class="fas fa-cart-plus mr-2"></i> THÊM VÀO GIỎ
                </button>
                <button
                    class="flex-1 bg-red-600 text-white text-xl font-bold py-3 rounded-lg hover:bg-red-700 transition duration-300 shadow-md">
                    MUA NGAY
                </button>
            </div>

            <h3 class="text-2xl font-semibold text-gray-800 mb-3 border-b pb-2">Mô tả</h3>
            <p class="text-gray-700 leading-relaxed">
                <?= nl2br(htmlspecialchars($item['description'])) ?>
            </p>

        </div>
    </div>

</section>