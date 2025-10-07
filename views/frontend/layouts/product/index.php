<div class="max-w-[90%] mx-auto product-list grid grid-cols-12 gap-4 p-3 text-lg">
    <!-- Lấy 5 sản phẩm được seller -->
    <!-- <div class="col-span-2"></div> -->

    <?php

    $listItem = $data['books'];

    // print_r($listItem);
    // $listItem as $key => $value
    foreach ($listItem as $key => $value) { ?>
        <a href="" class="h-full col-span-2 flex flex-col justify-center item-center bg-amber-300 p-3 rounded-lg">
            <div class="hover:scale-105 overflow-hidden flex-col justify-item-center item-center p-2 mb-1">
                <img class="transition ease-in-out w-full h-36 object-cover mb-2"
                    src="<?= BASE_URL . htmlspecialchars($image[0]) ?>"
                    alt="<?php echo htmlspecialchars($value['title']); ?>">

                <p class="pt-1 pb-1 text-xl h-18 font-bold">
                    <?php echo htmlspecialchars($value['title']); ?>
                </p>

                <p class="text-sm text-xl">
                    <?php echo number_format(htmlspecialchars($value['price']), 0, ',', '.'); ?> VND

                </p>
            </div>
            <button class="hover:bg-amber-600 bg-amber-500 w-full rounded-full p-2">Thêm vào giỏ hàng</button>
        </a>
        <?php
    }
    ?>
</div>