<div class="max-w-[90%] mx-auto text-2xl font-bold p-2">
    <p class="w-fit border-b-2 border-amber-500">DANH SÁCH SẢN PHẨM</p>
</div>

<div class="mx-auto product-list grid sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-8 gap-4 p-3 my-3">
    <?php
    $listItem = $data['books'];
    foreach ($listItem as $key => $value) {
        $currentPrice = $value['price'] * (100 - ($value['discount'] ?? 0)) / 100;
        $discountPercent = $value['discount'] ?? 0;
        ?>

        <a href="?controller=product&action=details&id=<?= $value['id'] ?>" class="relative mx-auto transition ease-in-out duration-300 hover:shadow-2xl shadow-md h-max w-[90%] col-span-2 
            flex flex-1 flex-col justify-center bg-stone-50 p-3 rounded-lg border-1 border-amber-100"
            title="<?= htmlspecialchars($value['title']); ?>">

            <div class="overflow-hidden flex flex-col justify-start items-center py-2 px-1">

                <img class="mx-auto shadow-md w-max h-48 object-cover mb-4"
                    src="<?= BASE_URL . 'public/products/' . htmlspecialchars($value['image']) ?>"
                    alt="<?= htmlspecialchars($value['title']); ?>">

                <p class="font-sans py-2 text-xl h-18 line-clamp-2">
                    <?= htmlspecialchars($value['title']); ?>
                </p>

                <div class="font-sans grid grid-rows-2 gap-1 py-2 w-full mt-auto">

                    <p class="text-xl font-bold text-red-600 col-span-1">
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
                        <p class="text-sm line-through text-gray-500 font-normal">
                            <?= number_format(
                                htmlspecialchars($value['price'])
                                ,
                                0,
                                ',',
                                '.'
                            ); ?> VND
                        </p>
                        <p class="absolute z-10 h-max w-max flex justify-center items-center 
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