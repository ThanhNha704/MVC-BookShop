<?php
require_once __DIR__ . '/../../../config.php';
function ProductItem($id, $image, $title, $price)
{
    if (is_string($image)) {
        $image = json_decode($image, true) ?? [];
    }
    ?>

    <a href="" class="col-span-3 bg-gray-800">
        <div class="overflow-hidden">
            <img class="hover:scale-110 transition ease-in-out w-full h-[250px] object-cover borderImage slickHoverZoom"
                src="<?= BASE_URL . htmlspecialchars($image[0]) ?>" alt="<?php echo htmlspecialchars($title); ?>">

            <p class="pt-3 pb-1 text-sm">
                <?php echo htmlspecialchars($title); ?>
            </p>

            <p class="text-sm font-medium">
                <?php echo number_format(htmlspecialchars($price), 0, ',', '.'); ?> VND

            </p>
        </div>
        <button class="btn">Thêm vào giỏ hàng</button>
    </a>
    <?php
}
?>