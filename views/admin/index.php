<?php
// print_r($data);
// print_r($data['recentOrders']);
// Giả định các biến dữ liệu được truyền vào từ Controller
// Dữ liệu này sẽ được Controller fetch từ database (Model) và truyền vào.
$totalRevenue = $data['totalRevenue'] ?? 125000000; // Doanh thu tổng
$totalOrders = $data['totalOrders'] ?? 1560; // Tổng đơn hàng
$newUsers = $data['newUsers'] ?? 45; // Người dùng mới
$newReviews = $data['newReviews'] ?? 78; // Đánh giá mới
$recentOrders = $data['recentOrders'] ?? [];

// Hàm format tiền tệ
function format_currency($amount)
{
    return number_format($amount, 0, ',', '.') . '₫';
}
?>

<div class="flex-1 bg-white p-6 overflow-scroll">

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
                    <?php foreach ($recentOrders as $order => $value): ?>

                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $value['id'] ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?= $value['customer_name'] ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-semibold">
                                <?= format_currency($value['total']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                $statusClass = [
                                    'Chờ xác nhận' => 'bg-yellow-100 text-yellow-800',
                                    'Xác nhận' => 'bg-green-100 text-green-800',
                                    'Đang giao' => 'bg-blue-100 text-blue-800',
                                    'Đã giao' => 'bg-indigo-100 text-indigo-800',
                                    'Thành công' => 'bg-emerald-100 text-emerald-800',
                                    'Đã hủy' => 'bg-red-100 text-red-800',
                                ][$value['status']] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span
                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?= $statusClass ?>">
                                    <?= $value['status'] ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-4 py-2 text-right">
            <a href="?controller=admin&action=orders"
                class="text-md font-medium text-amber-600 hover:text-amber-800">Xem tất cả đơn hàng &rarr;</a>
        </div>
    </div>
</div>