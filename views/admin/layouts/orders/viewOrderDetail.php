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
?>

<div class="flex-1 bg-gray-50 p-8 overflow-y-scroll">
    <div class="max-w-3xl mx-auto mb-10">
        <h1 class="text-3xl font-extrabold mb-2 text-gray-900">Chi tiết Đơn hàng #<?= htmlspecialchars($order['id']) ?>
        </h1>
        <p class="text-gray-500 mb-6">Thông tin chi tiết về đơn hàng và các sản phẩm đã đặt.</p>

        <a href="?controller=admin&action=orders"
            class="inline-flex items-center text-amber-600 hover:text-amber-800 font-medium mb-8">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Quay lại Danh sách Đơn hàng
        </a>
        <table class="divide-y divide-gray-200 h-full">

            <div class="bg-white p-6 rounded-xl shadow-lg mb-8 border border-gray-200">
                <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Thông tin Chung</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">

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

                    <div class="flex gap-3">
                        <dt class="font-medium text-gray-500">Trạng thái:</dt>
                        <dd>
                            <span
                                class="px-3 py-2 text-xs leading-5 font-semibold rounded-full border <?= get_status_classes($order['status']) ?>">
                                <?= ucfirst(htmlspecialchars($order['status'])) ?>
                            </span>
                        </dd>
                    </div>

                    <div class="flex flex-col">
                        <dt class="font-medium text-gray-500">Tổng giá trị đơn hàng:</dt>
                        <dd class="font-extrabold text-2xl text-green-600"><?= format_currency($order['total']) ?></dd>
                    </div>

                </dl>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
                <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Các sản phẩm đã đặt</h2>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Sản phẩm</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Tác giả</th>
                                <th
                                    class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Giá / cuốn</th>
                                <th
                                    class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    SL</th>
                                <th
                                    class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php $grandTotal = 0; ?>
                            <?php if (!empty($order['details'])): ?>
                                <?php foreach ($order['details'] as $item): ?>
                                    <?php
                                    $subTotal = $item['quantity'] * $item['price'];
                                    $grandTotal += $subTotal;
                                    ?>
                                    <tr class="hover:bg-gray-50 transition duration-150">
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($item['book_title']) ?>
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-600">
                                            <?= htmlspecialchars($item['book_author']) ?>
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm text-gray-800">
                                            <?= format_currency($item['price']) ?>
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm font-bold text-gray-900">
                                            <?= htmlspecialchars($item['quantity']) ?>
                                        </td>
                                        <td class="px-4 py-3 text-right text-sm font-semibold text-green-600">
                                            <?= format_currency($subTotal) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-sm text-gray-500 italic">Không có sản
                                        phẩm
                                        nào trong đơn hàng này.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="bg-gray-100 border-t border-gray-300">
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-right text-base font-bold text-gray-800">Tổng cộng
                                    (Đã
                                    thanh toán):</td>
                                <td class="px-4 py-3 text-right text-xl font-extrabold text-green-700">
                                    <?= format_currency($order['total']) ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </table>
    </div>
</div>
</div>