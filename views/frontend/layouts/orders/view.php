<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();
$order = $data['order'] ?? null;
// print_r($order); // Dòng này có thể xóa sau khi debug
if (!$order) {
    echo '<div class="container mx-auto px-4 py-8">Đơn hàng không tồn tại.</div>';
    return;
}

// Hàm tính giá gốc (giả định $d['price'] là giá sau giảm, $d['discount'] là % giảm giá)
function calculate_original_price($discounted_price, $discount_percent) {
    if ($discount_percent > 0) {
        // Giá gốc = Giá đã giảm / (1 - %giảm / 100)
        return $discounted_price / (1 - ($discount_percent / 100));
    }
    return $discounted_price;
}

// Hàm tính tiền giảm giá cho 1 đơn vị
function calculate_unit_discount_amount($original_price, $discounted_price) {
    return $original_price - $discounted_price;
}
?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Chi tiết đơn hàng #<?= htmlspecialchars($order['id']) ?></h1>

    <div class="bg-white p-6 rounded shadow">
        <div class="mb-6 border-b pb-4">
            <div><strong>Khách hàng:</strong> <?= htmlspecialchars($order['customer_name'] ?? '') ?></div>
            <div><strong>Email:</strong> <?= htmlspecialchars($order['email'] ?? '') ?></div>
            <div><strong>Địa chỉ giao hàng:</strong>
                <?= htmlspecialchars($order['recipient_name'] ?? '') ?> -
                <?= htmlspecialchars($order['address_text'] ?? '') ?>
                (SĐT: <?= htmlspecialchars($order['phone_number'] ?? '') ?>)</div>
            <div><strong>Trạng thái:</strong> <?= htmlspecialchars($order['status'] ?? '') ?></div>
            <div><strong>Ngày:</strong> <?= htmlspecialchars($order['created_at'] ?? '') ?></div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="text-left text-gray-500 uppercase tracking-wider text-sm font-semibold">
                        <th class="py-3 px-2 w-5/12">SẢN PHẨM</th>
                        <th class="py-3 px-2 text-center w-1/12">SL</th>
                        <th class="py-3 px-2 text-center w-1/12">GIẢM (%)</th>
                        <th class="py-3 px-2 text-right w-2/12">ĐƠN GIÁ</th>
                        <th class="py-3 px-2 text-right w-2/12">THÀNH TIỀN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php 
                    $original_total = 0;
                    $total_discount_amount = 0;
                    foreach ($order['details'] as $d): 
                        $original_price = (float)($d['price'] ?? 0); // Giá gốc
                        $quantity = (int)($d['quantity'] ?? 1);
                        $discount_percent = (float)($d['discount_percent'] ?? 0); // Giảm giá (%)
                        $image_path = $d['book_image'] ?? ''; // Đường dẫn ảnh (giả định có field 'image')
                        $subtotal = (float)($d['subtotal'] ?? 0); // Thành tiền đã tính sẵn
                        
                        $unit_discount_amount = $original_price * ($discount_percent / 100); // Tiền giảm giá cho từng mặt hàng
                        
                        $discounted_price = $original_price * (1 - ($discount_percent / 100)); // Thành tiền của từng mặt hàng

                        $discounted_total = $discounted_price * $quantity;

                        $original_total += $original_price * $quantity; //Tổng tiền gốc tất cả mặt hàng

                        $total_discount_amount += $unit_discount_amount * $quantity; //Tổng tiền giảm tất cả mặt hàng
                    ?>

                        <tr class="align-top">
                            <td class="py-4 px-2 flex items-start gap-4">
                                <div class="w-16 h-16 flex-shrink-0 bg-gray-100 rounded-md overflow-hidden">
                                    <?php if (!empty($image_path)): ?>
                                        <img src="<?= 'public/products/' . $image_path ?>"
                                            alt="<?= htmlspecialchars($d['book_title']) ?>" class="w-full h-full object-cover">
                                    <?php endif; ?>
                                </div>
                                <div class="flex-grow">
                                    <div class="font-medium text-gray-900"><?= htmlspecialchars($d['book_title']) ?></div>
                                    <?php if ($discount_percent > 0): ?>
                                        <div class="text-xs text-red-500">Giảm: <?= number_format($unit_discount_amount) ?>đ/sp</div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            
                            <td class="py-4 px-2 text-center text-gray-700">
                                <?= htmlspecialchars($quantity) ?>
                            </td>

                            <td class="py-4 px-2 text-center text-red-600 font-semibold">
                                <?php if ($discount_percent > 0): ?>
                                    -<?= number_format($discount_percent, 0) ?>%
                                <?php else: ?>
                                    0%
                                <?php endif; ?>
                            </td>

                            <td class="py-4 px-2 text-right">
                                <?php if ($discount_percent > 0): ?>
                                    <div class="text-sm line-through text-gray-400"><?= number_format($original_price) ?>đ</div>
                                    <div class="font-semibold text-red-600"><?= number_format($discounted_price) ?>đ</div>
                                <?php else: ?>
                                    <div class="font-semibold text-gray-800"><?= number_format($discounted_price) ?>đ</div>
                                <?php endif; ?>
                            </td>
                            <td class="py-4 px-2 text-right font-bold text-amber-600">
                                <?= number_format($discounted_total) ?>đ
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-6 pt-4 border-t border-gray-200">
            <div class="flex justify-end items-center mb-1">
                <div class="text-gray-600 w-1/4">Tổng tiền hàng:</div>
                <div class="text-right font-medium w-1/4"><?= number_format($order['total']) ?>đ</div>
            </div>
            
            <?php if (!empty($order['discount']) && $order['discount'] > 0): ?>
            <div class="flex justify-end items-center mb-1">
                <div class="text-red-600 w-1/4">Tổng giảm giá:</div>
                <div class="text-right font-medium text-red-600 w-1/4">-<?= number_format($order['discount']) ?>đ</div>
            </div>
            <?php endif; ?>

            <div class="flex justify-end items-center pt-2 border-t mt-2">
                <div class="text-2xl font-extrabold w-1/4">Thành tiền:</div>
                <div class="text-right text-amber-600 text-3xl font-extrabold w-1/4"><?= number_format($order['subtotal'] ?? 0) ?>đ</div>
            </div>
        </div>


    </div>
</div>