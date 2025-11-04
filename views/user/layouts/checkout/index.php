<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
print_r($data);

?>

<div class="container mx-auto px-2 sm:px-4 py-6 sm:py-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6">Thanh toán</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4 text-sm" role="alert">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 text-sm"
                role="alert">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">

            <div class="bg-white rounded-lg shadow p-4 sm:p-6 h-max order-2 md:order-1">
                <h2 class="text-lg font-semibold mb-4">Thông tin giao hàng</h2>

                <form action="?controller=checkout&action=place_order" method="POST" id="checkoutForm">
                    <div class="space-y-4">

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Người nhận</label>
                            <input type="text" name="delivery_name"
                                value="<?= htmlspecialchars($user['username'] ?? '') ?>"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                            <input type="text" name="delivery_phone"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm"
                                required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Địa chỉ</label>
                            <textarea name="delivery_address" rows="3"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm"
                                required><?= htmlspecialchars($user['addresses'][0]['address'] ?? '') ?></textarea>
                        </div>

                        <?php
                        // Hidden inputs giữ nguyên
                        $items = $items ?? [];
                        foreach ($items as $index => $item):
                            ?>
                            <input type="hidden" name="items[<?= $index ?>][product_id]"
                                value="<?= htmlspecialchars($item['product_id'] ?? '') ?>">
                            <input type="hidden" name="items[<?= $index ?>][quantity]"
                                value="<?= htmlspecialchars($item['quantity'] ?? '') ?>">
                            <input type="hidden" name="items[<?= $index ?>][price]"
                                value="<?= htmlspecialchars($item['price'] ?? '') ?>">
                            <input type="hidden" name="items[<?= $index ?>][discount_percent]"
                                value="<?= htmlspecialchars($item['discount_percent'] ?? 0) ?>">
                            <input type="hidden" name="items[<?= $index ?>][discount_amount]"
                                value="<?= htmlspecialchars($item['discount_amount'] ?? 0) ?>">
                            <input type="hidden" name="items[<?= $index ?>][subtotal]"
                                value="<?= htmlspecialchars($item['subtotal'] ?? 0) ?>">
                        <?php endforeach; ?>

                        <input type="hidden" name="total" value="<?= htmlspecialchars($subtotal ?? 0) ?>">
                        <input type="hidden" name="totalDiscount" value="<?= htmlspecialchars($totalDiscount ?? 0) ?>">
                        <input type="hidden" name="subtotal" value="<?= htmlspecialchars($total ?? 0) ?>">
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-lg shadow p-4 sm:p-6 md:col-span-2 order-1 md:order-2">
                <h2 class="text-xl font-semibold mb-4">Chi tiết đơn hàng</h2>

                <div class="divide-y max-h-[60vh] md:max-h-[80vh] overflow-y-auto">
                    <?php foreach ($items as $item): ?>
                        <div class="py-4 sm:py-6">
                            <div class="flex items-start gap-4 sm:gap-6">
                                <div class="w-20 h-20 sm:w-24 sm:h-24 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden">
                                    <?php if (!empty($item['image'])): ?>
                                        <img src="<?= htmlspecialchars((defined('BASE_URL') ? BASE_URL : '') . 'public/products/' . ltrim($item['image'], '/')) ?>"
                                            alt="<?= htmlspecialchars($item['title']) ?>" class="w-full h-full object-cover">
                                    <?php endif; ?>
                                </div>

                                <div class="flex-grow">
                                    <div class="font-semibold text-base sm:text-xl leading-tight break-words">
                                        <?= htmlspecialchars($item['title']) ?>
                                    </div>

                                    <div class="mt-2 space-y-1 text-sm">
                                        <div class="text-gray-600">
                                            Số lượng: <?= htmlspecialchars($item['quantity']) ?>
                                        </div>

                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                            <span class="text-gray-600">Đơn giá:</span>
                                            <?php if ($item['discount_percent'] > 0): ?>
                                                <span class="line-through text-gray-400 text-xs">
                                                    <?= number_format($item['price']) ?>đ
                                                </span>
                                                <span class="text-red-600 font-medium">
                                                    <?= number_format($item['discounted_price']) ?>đ
                                                </span>
                                                <span class="bg-red-100 text-red-600 text-xs px-2 py-0.5 rounded">
                                                    -<?= number_format($item['discount_percent'], 1) ?>%
                                                </span>
                                            <?php else: ?>
                                                <span class="font-medium">
                                                    <?= number_format($item['price']) ?>đ
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <?php if ($item['discount_amount'] > 0): ?>
                                            <div class="text-red-600 text-xs">
                                                Tiết kiệm: <?= number_format($item['discount_amount'] * $item['quantity']) ?>đ
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="text-right flex-shrink-0">
                                    <div class="font-bold text-lg sm:text-xl text-amber-600">
                                        <?= number_format($item['subtotal']) ?>đ
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="border-t mt-4 pt-4 sm:mt-6 sm:pt-6">
                    <div class="space-y-3 text-sm sm:text-base">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">Tạm tính:</span>
                            <span class="font-medium"><?= number_format($subtotal) ?>đ</span>
                        </div>

                        <?php if ($totalDiscount > 0): ?>
                            <div class="flex justify-between items-center text-red-600">
                                <span>Giảm giá:</span>
                                <span>-<?= number_format($totalDiscount) ?>đ</span>
                            </div>
                        <?php endif; ?>

                        <div class="pt-4 border-t">
                            <div class="flex justify-between items-center text-xl sm:text-2xl font-extrabold">
                                <span>Thành tiền:</span>
                                <span class="text-amber-600 text-2xl sm:text-3xl"><?= number_format($total) ?>đ</span>
                            </div>
                            <div class="text-xs sm:text-sm text-gray-500 text-right mt-1">
                                (Đã bao gồm VAT)
                            </div>
                        </div>
                    </div>

                    <button type="submit" form="checkoutForm" class="w-full mt-6 bg-amber-500 text-white px-6 py-3 sm:py-4 rounded-lg font-semibold text-base sm:text-lg
                                     hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500">
                        Đặt hàng
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>