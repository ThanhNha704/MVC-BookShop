<div class="max-w-[90%] mx-auto text-2xl font-bold p-2">
    <p class="w-fit border-b-2 border-amber-500">DANH SÁCH SẢN PHẨM</p>
</div>

<div class="mx-auto product-list grid grid-cols-4 md:grid-cols-6 xl:grid-cols-8 gap-4 lg:gap-8 p-3 my-3">
    <?php
    $listItem = $data['books'];
    foreach ($listItem as $key => $value) {
        $currentPrice = $value['price'] * (100 - ($value['discount'] ?? 0)) / 100;
        $discountPercent = $value['discount'] ?? 0;
        ?>

        <a href="?controller=product&action=details&id=<?= $value['id'] ?>" class="relative mx-auto transition ease-in-out duration-300 hover:shadow-2xl shadow-md w-full col-span-2 
            flex flex-1 flex-col justify-center bg-stone-50 p-2 rounded-lg border-1 border-amber-100"
            title="<?= htmlspecialchars($value['title']); ?>">

            <div class="h-max overflow-hidden flex flex-col justify-start items-center p-2">

                <img class="mx-auto shadow-md w-max h-32 md:h-40 lg:h-48 object-cover mb-4"
                    src="<?= BASE_URL . 'public/products/' . htmlspecialchars($value['image']) ?>"
                    alt="<?= htmlspecialchars($value['title']); ?>">

                <p class="flex-1 w-full p-2 font-sans text-xl h-max line-clamp-2">
                    <?= htmlspecialchars($value['title']); ?>
                </p>

                <div class="h-max font-sans gap-1 py-2 w-full mt-auto p-2">

                    <p class="text-xl font-bold text-red-600">
                        <?= number_format(
                            (htmlspecialchars($currentPrice))
                            ,
                            0,
                            ',',
                            '.'
                        ); ?> VND
                    </p>
                </div>
                <?php
                if ($discountPercent != 0) {
                    ?>
                    <p class="text-sm line-through text-gray-500 font-normal w-full px-2">
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
                } else { ?>
                    <div class="h-6"></div>
                    <?php
                }
                ?>
            </div>
        </a>
        <?php
    }
    ?>
</div>