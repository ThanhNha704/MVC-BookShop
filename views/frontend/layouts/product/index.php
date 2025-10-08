<div class="max-w-[90%] mx-auto product-list grid sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4 p-3">
    <!-- Lấy 5 sản phẩm được seller -->
    <!-- <div class="col-span-2"></div> -->

    <?php
    $listItem = $data['books'];
    // print_r($listItem);
    // $listItem as $key => $value
    foreach ($listItem as $key => $value) { ?>
        <a href=""
            class="mx-auto transition ease-in-out duration-300 hover:shadow-2xl shadow-md h-max w-full md:w-[90%] lg:w-[70%] col-span-2 
            flex flex-col justify-center item-center bg-stone-50 p-3 rounded-lg" title="<?= htmlspecialchars($value['title']); ?>">
            <div class="overflow-hidden flex-col justify-item-center item-center py-2 px-4">
                <img class="mx-auto shadow-md w-48 h-48 mb-2 px-3"
                    src="<?= BASE_URL . 'public/products/' . htmlspecialchars($value['image']) ?>"
                    alt="<?= htmlspecialchars($value['title']); ?>">

                <p class="font-sans py-2 text-xl h-18">
                    <?= htmlspecialchars($value['title']); ?>
                </p>

                <div class="font-sans relative grid grid-rows-2 gap-2 py-2">
                    <p class="text-sm text-xl font-bold col-span-1">
                        <?= number_format(
                            (htmlspecialchars($value['price'])
                                * (100 - $value['discount']) / 100)
                            ,
                            0,
                            ',',
                            '.'
                        ); ?> VND
                    </p>
                    <?php
                    if ($value['discount'] != 0) {
                        ?>
                        <p class="text-sm line-through text-xl font-bold">
                            <?= number_format(
                                htmlspecialchars($value['price'])
                                ,
                                0,
                                ',',
                                '.'
                            ); ?> VND
                        </p>
                        <p class="absolute z-10 h-max w-max flex justify-center items-center 
                        rounded-xl text-white bg-amber-500 px-1 py-2 text-xl font-bold right-5 top-2">
                            <?= '-' . $value['discount'] . '%'; ?>
                        </p>
                        <?php
                    }
                    ?>
                </div>
                <button class="font-sans text-md md:text-lg lg:text-xl hover:bg-amber-500 bg-amber-400 w-full rounded-full py-2">Thêm vào giỏ hàng</button>
            </div>
        </a>
        <?php
    }
    ?>
</div>