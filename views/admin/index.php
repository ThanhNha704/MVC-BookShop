<?php
// Giả định các biến dữ liệu được truyền vào từ Controller
// Dữ liệu này sẽ được Controller fetch từ database (Model) và truyền vào.
$totalRevenue = $data['total_revenue'] ?? 125000000; // Doanh thu tổng
$totalOrders = $data['total_orders'] ?? 1560; // Tổng đơn hàng
$newUsers = $data['new_users'] ?? 45; // Người dùng mới
$newReviews = $data['new_reviews'] ?? 78; // Đánh giá mới
$recentOrders = $data['recent_orders'] ?? [
    ['id' => 'DH9021', 'user' => 'Nguyễn Văn A', 'total' => 545000, 'status' => 'Đã giao'],
    ['id' => 'DH9020', 'user' => 'Trần Thị B', 'total' => 1200000, 'status' => 'Đang xử lý'],
    ['id' => 'DH9019', 'user' => 'Lê Văn C', 'total' => 350000, 'status' => 'Đã hủy'],
    ['id' => 'DH9018', 'user' => 'Phạm Thu D', 'total' => 780000, 'status' => 'Đã giao'],
];

// Hàm format tiền tệ
function format_currency($amount)
{
    return number_format($amount, 0, ',', '.') . '₫';
}
?>

<div class="flex-1 bg-white p-6">

    <h2 class="text-3xl font-extrabold text-gray-800 border-b pb-3">Tổng Quan Quản Trị</h2>
    <p class="text-gray-600">Tổng quát về hoạt động kinh doanh của bạn.</p>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <div class="bg-white p-6 rounded-xl shadow-lg border-b-4 border-amber-500">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-500">Tổng Doanh Thu</span>
                <span class="text-2xl text-amber-500">📈</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 mt-2"><?= format_currency($totalRevenue) ?></p>
            <p class="text-xs text-green-500 mt-1">+12% so với tháng trước</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg border-b-4 border-blue-500">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-500">Tổng Đơn Hàng</span>
                <span class="text-2xl text-blue-500">📦</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 mt-2"><?= number_format($totalOrders) ?></p>
            <p class="text-xs text-red-500 mt-1">-3% so với tháng trước</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg border-b-4 border-green-500">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-500">Người Dùng Mới</span>
                <span class="text-2xl text-green-500">🧑‍🤝‍🧑</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 mt-2"><?= $newUsers ?></p>
            <p class="text-xs text-green-500 mt-1">+25% so với tuần trước</p>
        </div>

        <div class="bg-white p-6 rounded-xl shadow-lg border-b-4 border-purple-500">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-500">Đánh Giá Mới</span>
                <span class="text-2xl text-purple-500">⭐</span>
            </div>
            <p class="text-3xl font-bold text-gray-900 mt-2"><?= $newReviews ?></p>
            <p class="text-xs text-gray-500 mt-1">Trong 24 giờ qua</p>
        </div>

    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Biểu đồ Doanh thu 6 tháng gần nhất</h3>
        <div class="h-64 bg-gray-50 flex items-center justify-center text-gray-500 rounded border border-dashed">
            [Biểu đồ Doanh thu sẽ được hiển thị ở đây]
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-lg">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Đơn Hàng Gần Đây</h3>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã
                            Đơn</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách
                            Hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng
                            Tiền</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng
                            Thái</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $order['id'] ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= $order['user'] ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-semibold">
                                <?= format_currency($order['total']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                $statusClass = [
                                    'Đã giao' => 'bg-green-100 text-green-800',
                                    'Đang xử lý' => 'bg-yellow-100 text-yellow-800',
                                    'Đã hủy' => 'bg-red-100 text-red-800',
                                ][$order['status']] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $statusClass ?>">
                                    <?= $order['status'] ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4 text-right">
            <a href="?controller=admin&action=orders"
                class="text-sm font-medium text-amber-600 hover:text-amber-800">Xem tất cả đơn hàng &rarr;</a>
        </div>
    </div>

</div>