<?php
// views/admin/users/index.php
?>

<<<<<<< HEAD
<div class="flex-1 bg-gray-50 p-8 overflow-scroll">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Quản lý Người dùng (Khách hàng & Nhân viên)</h2>
    <h3 class="text-xl font-semibold mb-4">Danh sách Khách hàng</h3>

    <div class="overflow-x-auto overflow-y-auto h-full w-full rounded-lg shadow-lg">
        <table class="min-w-full divide-y divide-gray-200 bg-white">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Tên
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Email
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Role
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Trạng
                        thái</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Hành
                        động
=======
<div class="bg-white p-6 flex-1">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Quản lý Người dùng (Khách hàng & Nhân viên)</h2>
    <h3 class="text-xl font-semibold mb-4">Danh sách Khách hàng</h3>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng
                        thái (Khóa)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($data['users'] as $user): ?>
                    <tr>
<<<<<<< HEAD
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium"><?= htmlspecialchars($user['id']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                            <?= htmlspecialchars($user['username']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm"><?= htmlspecialchars($user['email']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center"><?= htmlspecialchars($user['role']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
=======
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium"><?= htmlspecialchars($user['id']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm"><?= htmlspecialchars($user['username']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm"><?= htmlspecialchars($user['email']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm"><?= htmlspecialchars($user['role']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
                            <form action="?controller=admin&action=updateUserStatus" method="POST" class="inline-flex">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <select name="status" onchange="this.form.submit()"
                                    class="rounded-md border-gray-300 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm">
                                    <?php foreach ($data['statusLabels'] as $value => $label): ?>
                                        <option value="<?= $value ?>" <?= ($user['status'] ?? 0) == $value ? 'selected' : '' ?>
                                            class="<?= $value == 1 ? 'text-green-800' : ($value == 3 ? 'text-red-800' : '') ?>">
                                            <?= htmlspecialchars($label) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>
<<<<<<< HEAD
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
=======
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
                            <a href="?controller=admin&action=viewUser&id=<?= $user['id'] ?>"
                                class="text-blue-600 hover:text-blue-900">Xem chi tiết</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<<<<<<< HEAD
<!-- <div class="bg-white p-6 rounded-xl shadow-lg mt-6">
    <h3 class="text-xl font-semibold mb-4">Quản lý Đánh giá (Review)</h3>
    <p class="text-gray-600">Hiển thị các đánh giá và cho phép xóa hoặc ẩn (khóa) các đánh giá không phù hợp.</p>
</div> -->
=======
<div class="bg-white p-6 rounded-xl shadow-lg mt-6">
    <h3 class="text-xl font-semibold mb-4">Quản lý Đánh giá (Review)</h3>
    <p class="text-gray-600">Hiển thị các đánh giá và cho phép xóa hoặc ẩn (khóa) các đánh giá không phù hợp.</p>
</div>
</div>
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
