<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

$order = $data['order'] ?? null;
$order_status = strtolower($order['status'] ?? ''); 

if (!$order) {
    echo '<div class="container mx-auto px-4 py-8">Đơn hàng không tồn tại.</div>';
    return;
}
// print_r($data);
// Định nghĩa trạng thái cần thiết
$STATUS_PENDING = 'chờ xác nhận';

// Các hàm tính giá (giữ nguyên)
function calculate_original_price($discounted_price, $discount_percent) {
    if ($discount_percent > 0) {
        return $discounted_price / (1 - ($discount_percent / 100));
    }
    return $discounted_price;
}

function calculate_unit_discount_amount($original_price, $discounted_price) {
    return $original_price - $discounted_price;
}

?>
<div class="container w-auto mx-auto px-1 lg:px-4 py-8">
    <h1 class="text-xl md:text-2xl font-bold mb-4 px-2 md:px-0">Chi tiết đơn hàng #<?= htmlspecialchars($order['id']) ?></h1>

    <div class="bg-white p-4 md:p-6 rounded shadow">
        
        <div class="w-full mb-6 border-b pb-4 text-sm md:text-base">
            <div><strong>Khách hàng:</strong> <?= htmlspecialchars($order['customer_name'] ?? '') ?></div>
            <div><strong>Email:</strong> <?= htmlspecialchars($order['email'] ?? '') ?></div>

            <div id="display-shipping-info">
                <div>
                    <strong>Địa chỉ giao hàng:</strong>
                    <?= htmlspecialchars($order['recipient_name'] ?? '') ?> -
                    <?= htmlspecialchars($order['address_text'] ?? '') ?>
                    (SĐT: <?= htmlspecialchars($order['phone_number'] ?? '') ?>)

                    <?php if ($order_status === $STATUS_PENDING): ?>
                        <a href="javascript:void(0)" id="edit-link"
                           class="text-amber-500 hover:text-amber-700 ml-2 text-xs font-medium border-b border-dashed border-blue-500">
                            [Chỉnh sửa]
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($order_status === $STATUS_PENDING): ?>
                <div id="edit-form" class="hidden mt-4 p-4 border rounded bg-gray-50">
                    <form action="?controller=orders&action=updateShipping&id=<?= htmlspecialchars($order['id']) ?>" method="POST">
                        <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['id']) ?>">
                        <h3 class="font-bold mb-2 text-gray-700">Chỉnh sửa Địa chỉ Giao hàng</h3>

                        <div class="mb-2">
                            <label for="recipient_name" class="block text-xs font-medium text-gray-700">Tên người nhận</label>
                            <input type="text" id="recipient_name" name="recipient_name" 
                                   value="<?= htmlspecialchars($order['recipient_name'] ?? '') ?>" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-1.5">
                        </div>

                        <div class="mb-2">
                            <label for="phone_number" class="block text-xs font-medium text-gray-700">Số điện thoại</label>
                            <input type="tel" id="phone_number" name="phone_number" 
                                   value="<?= htmlspecialchars($order['phone_number'] ?? '') ?>" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-1.5">
                        </div>

                        <div class="mb-4">
                            <label for="address_text" class="block text-xs font-medium text-gray-700">Địa chỉ chi tiết</label>
                            <textarea id="address_text" name="address_text" rows="2" required
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm p-1.5"><?= htmlspecialchars($order['address_text'] ?? '') ?></textarea>
                        </div>
                        
                        <div class="flex justify-end gap-2">
                            <button type="button" id="cancel-edit" class="px-3 py-1.5 text-gray-700 bg-gray-200 rounded hover:bg-gray-300 text-xs font-medium">Hủy</button>
                            <button type="submit" class="px-3 py-1.5 bg-amber-500 text-white rounded hover:bg-amber-600 text-xs font-medium">Lưu Thay Đổi</button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
            
            <div><strong>Trạng thái:</strong> 
                <span class="font-semibold 
                    <?php 
                        if ($order_status === $STATUS_PENDING) echo 'text-yellow-600';
                        else if ($order_status === 'đã xác nhận' || $order_status === 'thành công') echo 'text-green-600';
                        else if ($order_status === 'đã hủy') echo 'text-red-600';
                        else echo 'text-gray-800';
                    ?>">
                    <?= htmlspecialchars($order['status'] ?? '') ?>
                </span>
            </div>
            <div><strong>Ngày:</strong> <?= htmlspecialchars($order['created_at'] ?? '') ?></div>
        </div>

        <div class="w-full overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr class="text-left text-gray-500 uppercase tracking-wider text-xs font-semibold">
                        <th class="py-2 px-1">SẢN PHẨM</th> 
                        <th class="py-2 px-1 text-center w-12 sm:w-16">SL</th> 
                        <th class="py-2 px-1 text-center w-16 sm:w-20">GIẢM (%)</th>
                        <th class="py-2 px-1 text-right w-24 sm:w-32">ĐƠN GIÁ</th>
                        <th class="py-2 px-1 text-right w-24 sm:w-32">THÀNH TIỀN</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm">
                    <?php 
                    $total_discount_amount = 0;
                    
                    if (is_array($order['details'] ?? null)):
                        foreach ($order['details'] as $d):     
                            // Tiền đã giảm từng cuốn:                        
                            $discount_amount = $d['price'] * ($d['discount_percent'] / 100); 
                            // Tiền còn lại từng cuốn
                            $discounted_price = $d['price'] - $discount_amount; 
                            $total_discount_amount += $discount_amount * $d['quantity'];

                    ?>

                        <tr class="align-top">
                            <!-- Sản phẩm -->
                            <td class="py-2 px-1 flex items-start gap-2"> 
                                <div class="w-10 h-10 sm:w-12 sm:h-12 flex-shrink-0 bg-gray-100 rounded-md overflow-hidden"> 
                                    <?php if (!empty($d['book_image'])): ?>
                                        <img src="<?= 'public/products/' . $d['book_image'] ?>"
                                             alt="<?= htmlspecialchars($d['book_title']) ?>" class="w-full h-full object-cover">
                                    <?php endif; ?>
                                </div>
                                <div class="flex-grow">
                                    <div class="font-medium text-gray-900 text-xs sm:text-sm leading-tight break-words">
                                        <?= htmlspecialchars($d['book_title']) ?>
                                    </div>
                                    <?php if ($d['discount_percent'] > 0): ?>
                                        <div class="text-xs text-red-500">Giảm: <?= number_format($discount_amount) ?>đ/sp</div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <!-- Số lượng -->
                            <td class="py-2 px-1 text-center text-gray-700 text-xs sm:text-sm">
                                <?= htmlspecialchars($d['quantity']) ?>
                            </td>
                            <!-- % giảm -->
                            <td class="py-2 px-1 text-center text-red-600 font-semibold text-xs sm:text-sm">
                                <?php if ($d['discount_percent'] > 0): ?>
                                    -<?= number_format($d['discount_percent'], 0) ?>%
                                <?php else: ?>
                                    0%
                                <?php endif; ?>
                            </td>
                            <!-- Đơn giá -->
                            <td class="py-2 px-1 text-right text-xs sm:text-sm">
                                <?php if ($d['discount_percent'] > 0): ?>
                                    <div class="line-through text-gray-400"><?= number_format($d['price']) ?>đ</div>
                                    <div class="font-semibold text-red-600"><?= number_format($discounted_price) ?>đ</div>
                                <?php else: ?>
                                    <div class="font-semibold text-gray-800"><?= number_format($discounted_price) ?>đ</div>
                                <?php endif; ?>
                            </td>
                            <!-- Thành tiền -->
                            <td class="py-2 px-1 text-right font-bold text-amber-600 text-xs sm:text-sm">
                                <?= number_format($d['final_item_subtotal']) ?>đ
                            </td>
                        </tr>
                    <?php 
                        endforeach; 
                    endif; 
                    ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-6 pt-4 border-t border-gray-200">
            <div class="flex justify-between items-center mb-1 text-sm md:text-base">
                <div class="text-gray-600">Tổng tiền hàng (chưa giảm):</div>
                <div class="text-right font-medium"><?= number_format($d['total']) ?>đ</div> 
            </div>
            
            <div class="flex justify-between items-center mb-1 text-sm md:text-base">
                <div class="text-red-600">Giảm giá sản phẩm:</div>
                <div class="text-right font-medium text-red-600">-<?= number_format($total_discount_amount) ?>đ</div>
            </div>

            <?php if (!empty($order['discount_code_amount']) && $order['discount_code_amount'] > 0): ?>
            <div class="flex justify-between items-center mb-1 text-sm md:text-base">
                <div class="text-red-600">Giảm giá mã coupon:</div>
                <div class="text-right font-medium text-red-600">-<?= number_format($order['discount_code_amount']) ?>đ</div>
            </div>
            <?php endif; ?>

            <div class="flex justify-between items-center pt-2 border-t mt-2">
                <div class="text-xl md:text-2xl font-extrabold">Thành tiền:</div>
                <div class="text-right text-amber-600 text-2xl md:text-3xl font-extrabold"><?= number_format($order['final_total'] ?? $order['subtotal'] ?? 0) ?>đ</div>
            </div>
        </div>

        <hr class="my-6">

        <div class="flex flex-wrap justify-end gap-3">
            
            <?php 
            if ($order_status === $STATUS_PENDING): 
            ?>
                <button type="button" id="open-cancel-modal"
                   class="px-4 py-2 bg-red-500 text-white font-semibold rounded hover:bg-red-600 transition duration-150 text-sm">
                    Hủy Đơn Hàng
                </button>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php if ($order_status === $STATUS_PENDING): ?>
<div id="cancel-confirmation-modal" class="fixed inset-0 z-50 hidden items-center justify-center">
    <div class="bg-gray-500 p-6 rounded-lg shadow-2xl w-full max-w-sm border border-gray-700">
        <div class="text-gray-300 mb-6 leading-relaxed">
            Bạn có chắc chắn muốn hủy đơn hàng #<?= htmlspecialchars($order['id']) ?> không? Hành động này **không thể hoàn tác**.
        </div>
        <div class="flex justify-end gap-3">
            <button id="modal-cancel-button" type="button" class="px-5 py-2 bg-gray-600 text-white font-medium rounded hover:bg-gray-700 transition duration-150">
                Cancel
            </button>
            <a href="?controller=orders&action=cancel&id=<?= htmlspecialchars($order['id']) ?>" id="modal-ok-button" class="px-5 py-2 bg-blue-600 text-white font-medium rounded hover:bg-blue-700 transition duration-150">
                OK
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if ($order_status === $STATUS_PENDING): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // === LOGIC CHO FORM CHỈNH SỬA INLINE ===
        const editLink = document.getElementById('edit-link');
        const cancelEditButton = document.getElementById('cancel-edit');
        const displayInfo = document.getElementById('display-shipping-info');
        const editForm = document.getElementById('edit-form');

        if (editLink && cancelEditButton && displayInfo && editForm) {
            editLink.addEventListener('click', function() {
                displayInfo.classList.add('hidden');
                editForm.classList.remove('hidden');
            });

            cancelEditButton.addEventListener('click', function() {
                editForm.classList.add('hidden');
                displayInfo.classList.remove('hidden');
            });
        }
        
        // === LOGIC CHO MODAL XÁC NHẬN HỦY ĐƠN HÀNG ===
        const openModalButton = document.getElementById('open-cancel-modal');
        const modal = document.getElementById('cancel-confirmation-modal');
        const modalCancelButton = document.getElementById('modal-cancel-button');

        if (openModalButton && modal && modalCancelButton) {
            // Hiển thị modal khi click nút Hủy Đơn Hàng
            openModalButton.addEventListener('click', function() {
                modal.classList.remove('hidden');
                modal.classList.add('flex'); // Dùng flex để căn giữa
            });

            // Ẩn modal khi click nút Cancel hoặc click ra ngoài
            modalCancelButton.addEventListener('click', function() {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            });

            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                }
            });
        }
    });
</script>
<?php endif; ?>