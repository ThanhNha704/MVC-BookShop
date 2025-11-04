<?php

?>
<section class="mb-12 w-full mx-auto">
    <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b-4 border-amber-500 pb-2 inline-block">
        <i class="fas fa-star text-amber-500 mr-2"></i>Sách Bán Chạy Nhất
    </h2>

    <?php if (!empty($bestSellers)): ?>

        <div class="mx-auto product-list grid grid-cols-4 md:grid-cols-6 xl:grid-cols-8 gap-2 p-3 my-3">

            <?php
            // $bestSellers là mảng dữ liệu sách được truyền từ HomeController
            foreach ($bestSellers as $books => $value) {
                $currentPrice = $value['price'] * (100 - ($value['discount'] ?? 0)) / 100;
                $discountPercent = $value['discount'] ?? 0;
                ?>
                <a href="?controller=product&action=details&id=<?= $value['id'] ?>" class="relative mx-auto transition ease-in-out duration-300 hover:shadow-2xl shadow-md w-full col-span-2 
            flex flex-1 flex-col justify-center bg-stone-50 p-2 rounded-lg border-1 border-amber-100"
                    title="<?= htmlspecialchars($value['title']); ?>">

                    <div class="h-max overflow-hidden flex flex-col justify-start items-center p-1">

                        <img class="mx-auto shadow-md w-max h-32 md:h-40 lg:h-48 object-cover mb-4"
                            src="<?= BASE_URL . 'public/products/' . htmlspecialchars($value['image']) ?>"
                            alt="<?= htmlspecialchars($value['title']); ?>">

                        <p class="font-sans py-2 text-xl h-max line-clamp-2">
                            <?= htmlspecialchars($value['title']); ?>
                        </p>

                        <div class="h-max font-sans grid gap-1 py-2 w-full mt-auto">

                            <p class="text-sm md:text-lg lg:text-xl font-bold text-red-600 col-span-1">
                                <?= number_format(
                                    (htmlspecialchars($currentPrice))
                                    ,
                                    0,
                                    ',',
                                    '.'
                                ); ?> VND
                            </p>
                            <?php
                            if ($discountPercent != 0) {
                                ?>
                                <p class="h-max text-sm line-through text-gray-500 font-normal">
                                    <?= number_format(
                                        htmlspecialchars($value['price'])
                                        ,
                                        0,
                                        ',',
                                        '.'
                                    ); ?> VND
                                </p>
                                <p class="absolute text-xs md:text-md lg:text-xl z-10 h-max w-max flex justify-center items-center 
                        rounded-bl-xl text-white bg-red-600 px-3 py-1 text-xl font-bold right-0 top-0">
                                    <?= '-' . $value['discount'] . '%'; ?>
                                </p>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                </a>
                <?php
            }
            ?>
        </div>

        <div class="text-center mt-8">
            <a href="index.php?controller=product"
                class="text-lg text-amber-500 font-semibold hover:text-amber-700 transition duration-150 border-b-2 border-amber-500 pb-1">
                Xem tất cả sách <i class="fas fa-angle-right ml-1"></i>
            </a>
        </div>

    <?php else: ?>
        <p class="text-center text-gray-500 text-lg">Hiện chưa có sản phẩm bán chạy nào.</p>
    <?php endif; ?>
</section>