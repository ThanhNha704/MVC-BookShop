<?php
if (session_status() == PHP_SESSION_NONE) session_start();
$orders = $data['orders'] ?? [];
?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Đơn hàng của tôi</h1>

    <?php if (empty($orders)): ?>
        <div class="text-gray-600">Bạn chưa có đơn hàng nào.</div>
    <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($orders as $order): ?>
                <div class="bg-white p-4 rounded shadow flex justify-between items-center">
                    <div>
                        <div class="font-medium">Mã đơn: #<?= htmlspecialchars($order['id']) ?></div>
                        <div class="text-sm text-gray-600">Ngày: <?= htmlspecialchars($order['created_at']) ?></div>
                    </div>
                    <div class="text-right">
                        <div class="font-semibold text-lg"><?= number_format($order['subtotal']) ?>đ</div>
                        <div class="text-sm text-gray-600">Trạng thái: <?= htmlspecialchars($order['status']) ?></div>
                        <a href="?controller=orders&action=detail&id=<?= htmlspecialchars($order['id']) ?>" class="text-amber-600 mt-2 inline-block">Xem chi tiết</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>