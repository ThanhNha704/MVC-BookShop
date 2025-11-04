<?php
// views/admin/orders/viewOrderDetail.php

$order = $data['order'] ?? null;

if (!$order) {
    echo '<div class="p-8 text-center text-red-500">Không tìm thấy chi tiết đơn hàng này.</div>';
    return;
}

function format_currency($amount)
{
    $amount = is_numeric($amount) ? $amount : 0;
    return number_format($amount, 0, ',', '.') . '₫';
}

function get_status_classes($status)
{
    $status = strtolower($status);
    if ($status === 'chờ xác nhận')
        return 'bg-yellow-100 text-yellow-800 border-yellow-400';
    if ($status === 'xác nhận')
        return 'bg-green-100 text-green-800 border-green-400';
    if ($status === 'đang giao')
        return 'bg-blue-100 text-blue-800 border-blue-400';
    if ($status === 'đã giao')
        return 'bg-indigo-100 text-indigo-800 border-indigo-400';
    if ($status === 'thành công')
        return 'bg-emerald-100 text-emerald-800 border-emerald-400';
    if ($status === 'đã hủy')
        return 'bg-red-100 text-red-800 border-red-400';
    return 'bg-gray-100 text-gray-800 border-gray-400';
}

// Hàm tính giá sau giảm dựa trên cấu trúc dữ liệu của bạn (price là giá gốc)
<<<<<<< HEAD
function calculate_discounted_price($original_price, $discount_percent)
{
    return $original_price * (1 - ($discount_percent / 100));
}
// Hàm tính tổng tiền giảm giá cho 1 đơn vị
function calculate_unit_discount_amount($original_price, $discount_percent)
{
=======
function calculate_discounted_price($original_price, $discount_percent) {
    return $original_price * (1 - ($discount_percent / 100));
}
// Hàm tính tổng tiền giảm giá cho 1 đơn vị
function calculate_unit_discount_amount($original_price, $discount_percent) {
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
    return $original_price * ($discount_percent / 100);
}
?>

<div class="flex-1 bg-gray-50 p-8 overflow-y-scroll">
<<<<<<< HEAD
    <!-- <?php print_r($data['order']); ?> -->

    <div class="max-w-4xl mx-auto mb-10">
        <h1 class="text-3xl font-extrabold mb-2 text-gray-900">Chi tiết Đơn hàng #<?= htmlspecialchars($order['id']) ?>
        </h1>
=======
    <div class="max-w-4xl mx-auto mb-10">
        <h1 class="text-3xl font-extrabold mb-2 text-gray-900">Chi tiết Đơn hàng #<?= htmlspecialchars($order['id']) ?></h1>
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
        <p class="text-gray-500 mb-6">Thông tin chi tiết về đơn hàng và các sản phẩm đã đặt.</p>

        <a href="?controller=admin&action=orders"
            class="inline-flex items-center text-amber-600 hover:text-amber-800 font-medium mb-8 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Quay lại Danh sách Đơn hàng
        </a>

        <div class="bg-white p-6 rounded-xl shadow-lg mb-8 border border-gray-200">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Thông tin Chung</h2>
            <dl class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 text-sm">

                <div class="flex flex-col">
                    <dt class="font-medium text-gray-500">Mã Đơn hàng:</dt>
                    <dd class="font-bold text-amber-600">#<?= htmlspecialchars($order['id']) ?></dd>
                </div>

                <div class="flex flex-col">
                    <dt class="font-medium text-gray-500">Khách hàng:</dt>
                    <dd class="text-gray-900"><?= htmlspecialchars($order['customer_name'] ?? 'Khách lẻ') ?></dd>
                </div>

                <div class="flex flex-col">
                    <dt class="font-medium text-gray-500">Ngày đặt hàng:</dt>
                    <dd class="text-gray-900"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></dd>
                </div>

                <div class="flex flex-col">
                    <dt class="font-medium text-gray-500">Email:</dt>
                    <dd class="text-gray-900"><?= htmlspecialchars($order['email'] ?? 'N/A') ?></dd>
                </div>

                <div class="flex gap-3 items-center">
                    <dt class="font-medium text-gray-500">Trạng thái:</dt>
                    <dd>
                        <span
                            class="px-3 py-1 text-xs leading-5 font-semibold rounded-full border <?= get_status_classes($order['status']) ?>">
                            <?= ucfirst(htmlspecialchars($order['status'])) ?>
                        </span>
                    </dd>
                </div>

                <div class="flex flex-col">
                    <dt class="font-medium text-gray-500">Tổng giá trị đơn hàng:</dt>
<<<<<<< HEAD
                    <dd class="font-extrabold text-2xl text-green-600"><?= format_currency($order['final_total']) ?>
                    </dd>
=======
                    <dd class="font-extrabold text-2xl text-green-600"><?= format_currency($order['total']) ?></dd>
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
                </div>

                <div class="md:col-span-3 mt-4 pt-4 border-t">
                    <dt class="font-medium text-gray-500 mb-2">Thông tin giao hàng:</dt>
                    <dd class="text-gray-900 p-3 bg-gray-50 rounded">
<<<<<<< HEAD
                        <p class="font-semibold text-base">Họ tên:
                            <?= htmlspecialchars($order['recipient_name'] ?? '') ?> </p>
                        <p>Số điện thoạt: (<?= htmlspecialchars($order['phone_number'] ?? '') ?>)</p>
                        <p class="text-sm mt-1">Địa chỉ: <?= nl2br(htmlspecialchars($order['address_text'] ?? '')) ?>
                        </p>
=======
                        <p class="font-semibold text-base"><?= htmlspecialchars($order['recipient_name'] ?? '') ?> (<?= htmlspecialchars($order['phone_number'] ?? '') ?>)</p>
                        <p class="text-sm mt-1"><?= nl2br(htmlspecialchars($order['address_text'] ?? '')) ?></p>
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
                    </dd>
                </div>

            </dl>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
            <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Các sản phẩm đã đặt</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
<<<<<<< HEAD
                            <th
                                class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-5/12">
                                SẢN PHẨM</th>
                            <th
                                class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider w-2/12">
                                GIÁ / CUỐN</th>
                            <th
                                class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-1/12">
                                SL</th>
                            <th
                                class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider w-4/12">
                                THÀNH TIỀN</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php
=======
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-5/12">SẢN PHẨM</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider w-2/12">GIÁ / CUỐN</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-1/12">SL</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider w-4/12">THÀNH TIỀN</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php 
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
                        $orderSubtotal = 0;
                        $orderTotalDiscount = 0;
                        ?>
                        <?php if (!empty($order['details'])): ?>
                            <?php foreach ($order['details'] as $item): ?>
                                <?php
<<<<<<< HEAD
                                // Tính giá đã giảm
                                $discounted_price = $item['price'] * (1 - $item['discount_percent'] / 100);
                                // Tính số tiền giảm cho 1 cuốn
                                $discount_amount = $item['price'] * ($item['discount_percent'] / 100);
                                ?>
                                <tr class="align-top hover:bg-gray-50 transition duration-150">
                                    <!-- Sản phẩm -->
=======
                                $originalPrice = (float)($item['price'] ?? 0);
                                $discountPercent = (float)($item['discount_percent'] ?? 0);
                                $quantity = (int)($item['quantity'] ?? 0);

                                // Tính toán giá sau giảm
                                $discountedPrice = calculate_discounted_price($originalPrice, $discountPercent);
                                // Tính tổng tiền giảm giá cho 1 đơn vị
                                $unitDiscountAmount = calculate_unit_discount_amount($originalPrice, $discountPercent);
                                
                                // Thành tiền (đã giảm) và Tạm tính (chưa giảm)
                                $lineTotal = $discountedPrice * $quantity;
                                $lineOriginalTotal = $originalPrice * $quantity;
                                $lineDiscountAmount = $unitDiscountAmount * $quantity;

                                $orderSubtotal += $lineOriginalTotal; // Tạm tính (giá gốc)
                                $orderTotalDiscount += $lineDiscountAmount; // Tổng giảm giá

                                $bookImage = $item['book_image'] ?? '';
                                ?>
                                <tr class="align-top hover:bg-gray-50 transition duration-150">
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                        <div class="flex items-start gap-3">
                                            <div class="w-16 h-16 flex-shrink-0 bg-gray-100 rounded overflow-hidden">
                                                <?php if (!empty($bookImage)): ?>
<<<<<<< HEAD
                                                    <img src="<?= htmlspecialchars((defined('BASE_URL') ? BASE_URL : '') . 'public/products/' . $item['book_image']) ?>"
                                                        alt="<?= htmlspecialchars($item['book_title']) ?>"
                                                        class="w-full h-full object-cover">
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900">
                                                    <?= htmlspecialchars($item['book_title']) ?></div>
                                                <div class="text-xs text-gray-500">Tác giả:
                                                    <?= htmlspecialchars($item['book_author'] ?? 'N/A') ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- Giá -->
                                    <td class="px-4 py-3 text-right text-sm">
                                        <?php if ($item['discount_percent'] > 0): ?>
                                            <div class="text-xs line-through text-gray-400"><?= format_currency($item['price']) ?>
                                            </div>
                                            <div class="font-bold text-amber-600"><?= format_currency($discounted_price) ?></div>
                                            <div class="text-xs text-red-500 font-medium">Giảm:
                                                -<?= format_currency($discount_amount) ?></div>
                                        <?php else: ?>
                                            <div class="font-bold text-gray-800"><?= format_currency($item['price']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <!-- Số lượng -->
                                    <td class="px-4 py-3 text-center text-sm font-bold text-gray-900">
                                        <?= htmlspecialchars($item['quantity']) ?>
                                    </td>
                                    <!-- Thành tiền -->
                                    <td class="px-4 py-3 text-right text-sm font-semibold text-green-700">
                                        <?= format_currency($item['final_item_subtotal']) ?>
=======
                                                    <img src="<?= htmlspecialchars((defined('BASE_URL') ? BASE_URL : '') . 'public/products/' . ltrim($bookImage, '/')) ?>"
                                                        alt="<?= htmlspecialchars($item['book_title']) ?>" class="w-full h-full object-cover">
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900"><?= htmlspecialchars($item['book_title']) ?></div>
                                                <div class="text-xs text-gray-500">Tác giả: <?= htmlspecialchars($item['book_author'] ?? 'N/A') ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-right text-sm">
                                        <?php if ($discountPercent > 0): ?>
                                            <div class="text-xs line-through text-gray-400"><?= format_currency($originalPrice) ?></div>
                                            <div class="font-bold text-amber-600"><?= format_currency($discountedPrice) ?></div>
                                            <div class="text-xs text-red-500 font-medium">Giảm: -<?= format_currency($unitDiscountAmount) ?></div>
                                        <?php else: ?>
                                            <div class="font-bold text-gray-800"><?= format_currency($originalPrice) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3 text-center text-sm font-bold text-gray-900">
                                        <?= htmlspecialchars($quantity) ?>
                                    </td>
                                    <td class="px-4 py-3 text-right text-sm font-semibold text-green-700">
                                        <?= format_currency($lineTotal) ?>
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-4 py-4 text-center text-sm text-gray-500 italic">
                                    Không có sản phẩm nào trong đơn hàng này.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
<<<<<<< HEAD

=======
            
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
            <div class="mt-6 pt-4 border-t border-gray-200">
                <div class="flex justify-end">
                    <div class="w-full sm:w-1/2 space-y-2">
                        <div class="flex justify-between items-center text-gray-700">
                            <span class="font-medium">Tạm tính (Giá gốc):</span>
<<<<<<< HEAD
                            <span class="font-medium"><?= format_currency($order['total']) ?></span>
                        </div>

                        <?php if ($order['discount'] > 0): ?>
                            <div class="flex justify-between items-center text-red-600">
                                <span class="font-medium">Tổng tiền giảm giá:</span>
                                <span class="font-medium">-<?= format_currency($order['discount']) ?></span>
                            </div>
=======
                            <span class="font-medium"><?= format_currency($orderSubtotal) ?></span>
                        </div>
                        
                        <?php if ($orderTotalDiscount > 0): ?>
                        <div class="flex justify-between items-center text-red-600">
                            <span class="font-medium">Tổng tiền giảm giá:</span>
                            <span class="font-medium">-<?= format_currency($orderTotalDiscount) ?></span>
                        </div>
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
                        <?php endif; ?>

                        <div class="pt-3 border-t border-gray-300 flex justify-between items-center">
                            <span class="text-xl font-extrabold text-gray-800">Thành tiền:</span>
                            <span class="text-right text-green-700 text-2xl font-extrabold">
<<<<<<< HEAD
                                <?= format_currency($order['final_total']) ?>
=======
                                <?= format_currency($order['total']) ?>
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>