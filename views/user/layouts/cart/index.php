<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// print_r($_SESSION);
?>

<div class="container mx-auto px-1 lg:px-4 py-8">
    <style>
        /* Hide native number input spinners (WebKit + Firefox) without JS */
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
            appearance: textfield;
        }
    </style>
    <div class="max-w-[90%] mx-auto bg-white rounded-lg shadow-lg py-6 px-1 lg:px-6">
        <h1 class="text-2xl font-bold mb-4">Giỏ hàng của bạn</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (empty($items)): ?>
            <p class="text-gray-600">Giỏ hàng trống. Hãy thêm sản phẩm vào giỏ.</p>
            <a href="?controller=product" class="mt-4 inline-block text-amber-600">Tiếp tục mua sắm</a>
        <?php else: ?>
            <div class="w-full">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-1 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Sản
                                phẩm</th>
                            <th class="px-1 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Giá
                            </th>
                            <th class="px-1 py-4 text-left text-sm font-bold text-gray-700 uppercase tracking-wider">Số
                                lượng</th>
                            <th class="px-1 py-4 text-right text-sm font-bold text-gray-700 uppercase tracking-wider">Tạm
                                tính</th>
                            <th class="px-1 py-4"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($items as $item): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-1 py-6 flex items-center gap-4 w-full">
                                    <div class="w-24 h-24 bg-gray-100 hidden lg:flex rounded-lg overflow-hidden flex-shrink-0">
                                        <?php if (!empty($item['image'])): ?>
                                            <img src="<?= 'public/products/' . $item['image'] ?>" alt=""
                                                class="w-full h-full object-cover">
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="text-sm lg:text-lg font-semibold text-gray-900">
                                            <?= htmlspecialchars($item['title']) ?>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-1 py-6 text-sm lg:text-lg">
                                    <div class="space-y-1">
                                        <?php if ($item['discount_percent'] > 0): ?>
                                            <div class="text-gray-400 line-through">
                                                <?= number_format($item['price'], 0, ",", ".") ?>đ
                                            </div>
                                            <div class="text-base font-medium">
                                                <?= number_format($item['discounted_price'], 0, ",", ".") ?>đ
                                            </div>
                                            <div class="text-sm text-red-600">
                                                -<?= number_format($item['discount_percent'], 1, ",", ".") ?>%
                                            </div>
                                        <?php else: ?>
                                            <div class="text-base font-medium">
                                                <?= number_format($item['price'], 0, ",", ".") ?>đ
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td class="px-1 py-6 text-sm lg:text-lg">
                                    <form action="?controller=cart&action=update" method="POST" class="flex items-center">
                                        <button type="submit" class="hidden"></button>

                                        <input type="hidden" name="product_id"
                                            value="<?= htmlspecialchars($item['product_id']) ?>">

                                        <div
                                            class="w-max flex items-center gap-1 bg-gray-50 rounded-lg border border-gray-300 p-1 mr-2 text-xs md:text-base">

                                            <button type="submit" formaction="?controller=cart&action=update"
                                                onclick="this.closest('form').quantity.stepDown(); this.closest('form').submit(); return false;"
                                                class="w-max md:w-8 md:h-8 flex items-center justify-center text-gray-600 hover:bg-gray-200 rounded">
                                                <i class="fas fa-minus"></i>
                                            </button>

                                            <input type="number" name="quantity" min="0"
                                                value="<?= htmlspecialchars($item['quantity']) ?>"
                                                max="<?= isset($item['stock']) ? (int) $item['stock']+1 : '' ?>"
                                                class="w-10 min-h-full text-center border border-gray-300 rounded bg-white p-0 focus:ring-0 focus:outline-none text-sm md:text-base">

                                            <button type="submit" formaction="?controller=cart&action=update"
                                                onclick="this.closest('form').quantity.stepUp(); this.closest('form').submit(); return false;"
                                                class="w-max h-7 md:w-8 md:h-8 flex items-center justify-center text-gray-600 hover:bg-gray-200 rounded">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </form>
                                </td>

                                <td class="px-1 py-6 text-right text-sm lg:text-lg font-bold text-gray-900">
                                    <?= number_format($item['subtotal']) ?>đ
                                </td>

                                <td class="px-1 py-6 text-right">
                                    <form action="?controller=cart&action=remove" method="POST">
                                        <input type="hidden" name="product_id"
                                            value="<?= htmlspecialchars($item['product_id']) ?>">
                                        <button type="submit"
                                            class="text-red-600 text-sm lg:text-lg font-medium hover:text-red-700">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex justify-between items-center">
                <div>
                    <a href="?controller=product" class="text-amber-600">Tiếp tục mua sắm</a>
                    <form action="?controller=cart&action=clear" method="POST" class="inline-block ml-4">
                        <button type="submit" class="text-sm text-gray-600">Xóa toàn bộ</button>
                    </form>
                </div>

                <div class="text-right space-y-2">
                    <?php
                    $subtotal = $item['price'] * $item['quantity'];
                    $totalDiscount = $item['discount_amount'];
                    ?>
                    <div class="text-sm space-y-1">
                        <div class="flex justify-end items-center gap-4">
                            <span class="text-gray-600">Tạm tính:</span>
                            <span class="font-medium"><?= number_format($subtotal, 0, ",", ".") ?>đ</span>
                        </div>
                        <?php if ($totalDiscount > 0): ?>
                            <div class="flex justify-end items-center gap-4">
                                <span class="text-red-600">Giảm giá:</span>
                                <span class="text-red-600">-<?= number_format($totalDiscount, 0, ",", ".") ?>đ</span>
                            </div>
                        <?php endif; ?>
                        <div class="flex justify-end items-center gap-4 pt-2 border-t">
                            <span class="text-gray-900">Tổng cộng:</span>
                            <span class="text-2xl font-bold"><?= number_format($total, 0, ",", ".") ?>đ</span>
                        </div>
                    </div>
                    <a href="?controller=checkout"
                        class="mt-2 inline-block bg-amber-600 text-sm lg:text-lg text-white px-2 py-1 rounded hover:bg-amber-700">
                        Tiến hành thanh toán
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>