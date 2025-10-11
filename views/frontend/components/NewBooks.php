<section role="region" aria-labelledby="new-books-heading" class="container mx-auto py-8">

    <h2 class="text-2xl font-semibold text-gray-800 border-b-2 border-amber-500 inline-block pb-1 mb-6">
        Sách Mới Tiêu Biểu
    </h2>

    <div role="list" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-x-4 gap-y-8">

        <?php
        $listItem = $data['books'];
        // Bắt đầu LẶP QUA danh sách sách đã lấy từ SQL
        foreach ($listItem as $key => $value) { ?>
        
        <a href="?controller=product&action=details&id=<?=$value['id']?>"
            role="listitem" 
            class="mx-auto transition ease-in-out duration-300 hover:shadow-2xl shadow-md h-max w-full md:w-[90%] lg:w-[70%] col-span-2 
            flex flex-col justify-center item-center bg-stone-50 p-3 rounded-lg" title="<?= htmlspecialchars($value['title']); ?>">
            
            <div class="overflow-hidden flex-col justify-item-center item-center py-2 px-4">
                
                <img class="mx-auto shadow-md w-48 h-48 mb-2 px-3"
                    src="<?= BASE_URL . 'public/products/' . htmlspecialchars($value['image']) ?>"
                    alt="<?= htmlspecialchars($value['title']); ?>"
                    role="img"
                    aria-label="Bìa sách: <?= htmlspecialchars($value['title']); ?>">

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
                        rounded-xl text-white bg-amber-500 px-1 py-2 text-xl font-bold right-5 top-2"
                        role="status" aria-label="Giảm giá <?= $value['discount']; ?> phần trăm">
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
</section>