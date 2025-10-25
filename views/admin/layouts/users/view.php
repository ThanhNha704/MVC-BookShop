<?php
$user = $data['user'];
$statusLabels = $data['statusLabels'];
$addresses = $data['addresses'] ?? [];

function getStatusClass($status)
{
    switch ($status) {
        case 0:
            return 'bg-gray-100 text-gray-800';
        case 1:
            return 'bg-green-100 text-green-800';
        case 2:
            return 'bg-yellow-100 text-yellow-800';
        case 3:
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Nút quay lại -->
        <div class="mb-6">
            <a href="?controller=admin&action=users" class="text-amber-600 hover:text-amber-800 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Quay lại danh sách người dùng
            </a>
        </div>

        <!-- Thông tin người dùng -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Thông tin chi tiết người dùng</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Thông tin cơ bản -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">ID</label>
                        <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($user['id']) ?></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tên người dùng</label>
                        <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($user['username']) ?></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($user['email']) ?></p>
                    </div>
                </div>

                <!-- Thông tin tài khoản -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Vai trò</label>
                        <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($user['role']) ?></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Ngày tạo tài khoản</label>
                        <p class="mt-1 text-sm text-gray-900"><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?>
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
                        <form action="?controller=admin&action=updateUserStatus" method="POST" class="mt-1">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <select name="status" onchange="this.form.submit()"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 sm:text-sm">
                                <?php foreach ($statusLabels as $value => $label): ?>
                                    <option value="<?= $value ?>" <?= ($user['status'] ?? 0) == $value ? 'selected' : '' ?>
                                        class="<?= getStatusClass($value) ?>">
                                        <?= htmlspecialchars($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách địa chỉ -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">Địa chỉ giao hàng</h2>

            <?php if (empty($addresses)): ?>
                <p class="text-gray-500 italic">Người dùng chưa có địa chỉ giao hàng nào.</p>
            <?php else: ?>
                <div class="grid grid-cols-1 gap-4">
                    <?php foreach ($addresses as $address): ?>
                        <div
                            class="border rounded-lg p-4 <?= $address['is_default'] ? 'border-amber-500 bg-amber-50' : 'border-gray-200' ?>">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center">
                                    <span
                                        class="font-medium text-gray-900"><?= htmlspecialchars($address['recipient_full_name']) ?></span>
                                    <?php if ($address['is_default']): ?>
                                        <span class="ml-2 px-2 py-1 text-xs bg-amber-100 text-amber-800 rounded">Mặc định</span>
                                    <?php endif; ?>
                                </div>
                                <span class="text-sm text-gray-500">ID: <?= htmlspecialchars($address['id']) ?></span>
                            </div>

                            <div class="text-sm text-gray-600">
                                <p class="mb-1">SĐT: <?= htmlspecialchars($address['phone_number']) ?></p>
                                <p><?= htmlspecialchars($address['address_line']) ?></p>
                                <p>
                                    <?= htmlspecialchars($address['ward']) ?>,
                                    <?= htmlspecialchars($address['district']) ?>,
                                    <?= htmlspecialchars($address['city']) ?>
                                </p>
                            </div>

                            <div class="mt-2 text-xs text-gray-500">
                                Cập nhật lần cuối: <?= date('d/m/Y H:i', strtotime($address['updated_at'])) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</div>