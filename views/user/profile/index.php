<?php
// Giả định dữ liệu đã được Controller truyền vào biến $data
$user = $data['user'] ?? [];
$user_revenue = $data['user_revenue'] ?? 0;
$user_tier = $user['user_tier'];
$order_count = $data['order_count'] ?? 0; // Thêm số lượng đơn hàng đã hoàn thành
// print_r($data);

function formatCurrency($amount) {
    return number_format($amount, 0, ',', '.') . ' VNĐ';
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto bg-white rounded-xl shadow-2xl overflow-hidden">
        
        <div class="bg-amber-600 p-6 text-white">
            <h1 class="text-3xl font-extrabold mb-1">
                👋 Hồ sơ Thành viên
            </h1>
            <p class="text-amber-100">Quản lý thông tin cá nhân và kiểm tra hạng thành viên của bạn.</p>
        </div>

        <div class="p-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1 space-y-6">
                
                <div class="p-6 rounded-lg shadow-lg border border-amber-300">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-trophy text-amber-500 mr-3"></i> Hạng Thành viên
                    </h2>
                    
                    <div class="flex items-center space-x-3 mb-4">
                        <span class="inline-flex items-center px-4 py-1.5 text-lg font-bold rounded-full shadow-lg <?= htmlspecialchars($user_tier['class']) ?>">
                            <?php 
                                if ($user_tier['level'] == 'Diamond') echo '💎';
                                elseif ($user_tier['level'] == 'Gold') echo '🥇';
                                elseif ($user_tier['level'] == 'Silver') echo '🥈';
                                else echo '⭐';
                            ?>
                            <span class="ml-2"><?= htmlspecialchars($user_tier['level']) ?></span>
                        </span>
                    </div>

                    <p class="text-sm text-gray-600">
                        Tổng chi: 
                        <span class="font-bold text-green-600">
                            <?= formatCurrency($user_revenue) ?>
                        </span>
                    </p>
                    <p class="text-xs text-gray-500 mt-2 italic">
                        (Tham khảo mục Quyền lợi để biết ngưỡng nâng hạng)
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="p-2 bg-gray-50 rounded-lg border">
                        <p class="text-sm text-gray-500">Đơn hàng đã mua</p>
                        <p class="text-2xl font-bold text-amber-600 mt-1"><?= $order_count ?></p>
                    </div>
                    <div class="p-2 bg-gray-50 rounded-lg border">
                        <p class="text-sm text-gray-500">Đánh giá đã viết</p>
                        <p class="text-2xl font-bold text-amber-600 mt-1">
                            <?= $data['review_count'] ?? 0 ?>
                        </p>
                    </div>
                </div>

            </div>

            <div class="lg:col-span-2 space-y-8">
                
                <div class="p-6 border rounded-lg shadow">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex justify-between items-center">
                        Thông tin Cá nhân
                        <!-- <a href="?controller=user&action=edit_profile" class="text-sm text-blue-500 hover:underline">
                            <i class="fas fa-edit mr-1"></i> Chỉnh sửa
                        </a> -->
                    </h2>
                    <dl class="divide-y divide-gray-100">
                        <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">Tên người dùng</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2"><?= htmlspecialchars($user['username'] ?? 'N/A') ?></dd>
                        </div>
                        <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2"><?= htmlspecialchars($user['email'] ?? 'N/A') ?></dd>
                        </div>
                        <!-- <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">Tên đầy đủ</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2"><?= htmlspecialchars($user['full_name'] ?? 'Chưa cập nhật') ?></dd>
                        </div>
                        <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                            <dt class="text-sm font-medium text-gray-500">Số điện thoại</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2"><?= htmlspecialchars($user['phone'] ?? 'Chưa cập nhật') ?></dd>
                        </div> -->
                    </dl>
                </div>
                
                <!-- <div class="p-6 border rounded-lg shadow">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex justify-between items-center">
                        Địa chỉ Giao hàng
                        <a href="?controller=user&action=manage_addresses" class="text-sm text-blue-500 hover:underline">
                            <i class="fas fa-plus mr-1"></i> Quản lý
                        </a>
                    </h2>
                    
                    <?php if (empty($addresses)): ?>
                        <p class="text-gray-500 italic">Bạn chưa lưu địa chỉ nào. Vui lòng thêm địa chỉ mới.</p>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($addresses as $index => $address): ?>
                                <div class="p-3 border rounded-lg text-sm <?= ($address['is_default'] ?? 0) ? 'border-amber-500 bg-amber-50' : 'border-gray-200' ?>">
                                    <p class="font-semibold text-gray-800">
                                        <?= htmlspecialchars($address['recipient_name'] ?? 'N/A') ?> 
                                        <?php if ($address['is_default'] ?? 0): ?>
                                            <span class="ml-2 text-xs font-bold text-amber-800 bg-amber-200 px-2 py-0.5 rounded-full">
                                                Mặc định
                                            </span>
                                        <?php endif; ?>
                                    </p>
                                    <p class="text-gray-600 mt-1">
                                        <?= htmlspecialchars($address['phone'] ?? 'N/A') ?> | 
                                        <?= htmlspecialchars($address['address_line1'] ?? 'N/A') ?>, 
                                        <?= htmlspecialchars($address['city'] ?? 'N/A') ?>
                                    </p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div> -->
            </div>

        </div>
    </div>
</div>