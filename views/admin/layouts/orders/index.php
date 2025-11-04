<?php
// views/admin/orders/index.php (Sử dụng Modal và AJAX)

function format_currency($amount)
{
    $amount = is_numeric($amount) ? $amount : 0;
    return number_format($amount, 0, ',', '.') . '₫';
}

$orders = $data['orders'] ?? [];
$statuses = $data['statuses'] ?? [];
$currentSearch = htmlspecialchars($_GET['search'] ?? '');

// Bản đồ ánh xạ trạng thái sang Tailwind classes
function get_status_classes($status)
{
    if ($status === 'Chờ xác nhận')
        return 'bg-yellow-100 text-yellow-800 border-yellow-400';
    if ($status === 'Xác nhận')
        return 'bg-green-100 text-green-800 border-green-400';
    if ($status === 'Đang giao')
        return 'bg-blue-100 text-blue-800 border-blue-400';
    if ($status === 'Đã giao')
        return 'bg-indigo-100 text-indigo-800 border-indigo-400';
    if ($status === 'Thành công')
        return 'bg-emerald-100 text-emerald-800 border-emerald-400';
    if ($status === 'Đã hủy')
        return 'bg-red-100 text-red-800 border-red-400';
    return 'bg-gray-100 text-gray-800 border-gray-400';
}
?>

<<<<<<< HEAD
<div class="flex-1 bg-gray-50 p-8 overflow-scroll">

    <h2 class="text-2xl font-bold mb-6 text-gray-800">Quản lý Đơn hàng</h2>
    <p class="text-gray-500 mb-6">Theo dõi và cập nhật trạng thái các đơn hàng của khách hàng.</p>

    <div class="flex justify-between items-center bg-white p-4 rounded-xl shadow-sm border border-gray-200">
        <form action="" method="GET" class="flex items-center w-full max-w-md">
            <input type="hidden" name="controller" value="admin">
            <input type="hidden" name="action" value="orders">
            <input type="text" name="search" placeholder="Tìm kiếm theo Mã ĐH (ví dụ: 1004)..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 shadow-inner transition duration-150"
                value="<?= $currentSearch ?>">
            <button type="submit"
                class="ml-2 px-4 py-2 bg-amber-500 text-white font-semibold rounded-lg hover:bg-amber-600 transition duration-150 shadow-md">
                Tìm
            </button>
        </form>
    </div>
    <div class="overflow-x-auto overflow-y-auto h-full w-full rounded-lg shadow-lg">
        <table class="min-w-full divide-y divide-gray-200 bg-white">
            <thead class="bg-gray-50 sticky top-0 z-10 border-b border-gray-300">
                <tr>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Mã
                        ĐH</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Khách hàng</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Tổng tiền</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider w-1/5">
                        Trạng thái</th>
                    <th class="px-6 py-3 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                        Hành động</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300">

                <?php if (empty($orders)): ?>
                    <tr class="hover:bg-gray-100">
                        <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500 italic">
                            Hiện không có đơn hàng nào khớp với tìm kiếm.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <tr class="hover:bg-gray-100">
                            <!-- Mã đh -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-bold text-amber-600">
                                <?= htmlspecialchars($order['id']) ?>
                            </td>
                            <!-- Tên KH -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-800">
                                <?= htmlspecialchars($order['customer_name'] ?? 'Khách lẻ') ?>
                            </td>
                            <!-- Tổng tiền -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-extrabold text-green-600">
                                <?= format_currency($order['final_total']) ?>
                            </td>
                            <!-- Trạng thái -->
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <span id="status-tag-<?= $order['id'] ?>"
                                    class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border <?= get_status_classes($order['status']) ?>">
                                    <?= ucfirst(htmlspecialchars($order['status'])) ?>
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center space-x-3">
                                <button type="button"
                                    class="text-blue-600 hover:text-blue-800 font-medium update-status-btn transition duration-150"
                                    data-id="<?= $order['id'] ?>"
                                    data-current-status="<?= htmlspecialchars($order['status']) ?>"
                                    data-customer="<?= htmlspecialchars($order['customer_name'] ?? 'Khách lẻ') ?>">
                                    Sửa Trạng thái
                                </button>
                                <a href="?controller=admin&action=viewOrderDetail&id=<?= $order['id'] ?>"
                                    class="text-gray-600 hover:text-gray-800 font-medium">Chi tiết</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
=======
<div class="flex-1 relative bg-gray-50 h-full flex flex-col overflow-hidden">

    <div class="p-8 bg-gray-50 z-10 border-b border-gray-200 sticky top-0">
        <h1 class="text-3xl font-extrabold mb-2 text-gray-900">Quản lý Đơn hàng</h1>
        <p class="text-gray-500 mb-6">Theo dõi và cập nhật trạng thái các đơn hàng của khách hàng.</p>

        <div class="flex justify-between items-center bg-white p-4 rounded-xl shadow-sm border border-gray-200">
            <form action="" method="GET" class="flex items-center w-full max-w-md">
                <input type="hidden" name="controller" value="admin">
                <input type="hidden" name="action" value="orders">
                <input type="text" name="search" placeholder="Tìm kiếm theo Mã ĐH (ví dụ: 1004)..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 shadow-inner transition duration-150"
                    value="<?= $currentSearch ?>">
                <button type="submit"
                    class="ml-2 px-4 py-2 bg-amber-500 text-white font-semibold rounded-lg hover:bg-amber-600 transition duration-150 shadow-md">
                    Tìm
                </button>
            </form>
        </div>
    </div>
    <div class="overflow-y-scroll flex-1 p-8 pt-0">
        <div class="overflow-x-auto shadow-xl rounded-xl border border-gray-200 mb-10">
            <table class="min-w-full divide-y divide-gray-200 bg-white">
                <thead class="bg-gray-100 sticky top-0 z-[5]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Mã
                            ĐH</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Khách hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Tổng tiền</th>
                        <th
                            class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider w-1/5">
                            Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Hành động</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">

                    <?php if (empty($orders)): ?>
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500 italic">
                                Hiện không có đơn hàng nào khớp với tìm kiếm.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-amber-600">
                                    <?= htmlspecialchars($order['id']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                    <?= htmlspecialchars($order['customer_name'] ?? 'Khách lẻ') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-extrabold text-green-600">
                                    <?= format_currency($order['total']) ?>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span id="status-tag-<?= $order['id'] ?>"
                                        class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border <?= get_status_classes($order['status']) ?>">
                                        <?= ucfirst(htmlspecialchars($order['status']))?>
                                    </span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-3">
                                    <button type="button"
                                        class="text-blue-600 hover:text-blue-800 font-medium update-status-btn transition duration-150"
                                        data-id="<?= $order['id'] ?>"
                                        data-current-status="<?= htmlspecialchars($order['status']) ?>"
                                        data-customer="<?= htmlspecialchars($order['customer_name'] ?? 'Khách lẻ') ?>">
                                        Sửa Trạng thái
                                    </button>
                                    <a href="?controller=admin&action=viewOrderDetail&id=<?= $order['id'] ?>"
                                        class="text-gray-600 hover:text-gray-800 font-medium">Chi tiết</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
>>>>>>> 245b97721d11819e3b186cfce63d29945e072f6c
    </div>

    <!-- Form update status -->
    <div id="updateStatusModal"
        class="absolute flex inset-0 bg-opacity-75 hidden items-center justify-center z-50 transition-opacity duration-500 opacity-0">
        <div
            class="bg-white rounded-xl shadow-2xl w-full max-w-sm p-6 transform transition-transform duration-300 scale-95">
            <h3 class="text-xl font-bold text-gray-900 mb-4 border-b pb-2">Cập nhật Đơn hàng <span id="modalOrderId"
                    class="text-amber-600"></span></h3>

            <form id="statusUpdateForm">
                <input type="hidden" name="order_id" id="modalOrderInputId">

                <p class="text-sm text-gray-600 mb-4">Khách hàng: <strong id="modalCustomerName"
                        class="font-semibold text-gray-800"></strong></p>
                <div id="alert-message" class="hidden p-3 mb-4 text-sm rounded-lg" role="alert"></div>

                <div class="mb-6">
                    <label for="new_status" class="block text-sm font-medium text-gray-700 mb-2">Chọn trạng thái
                        mới</label>
                    <select id="new_status" name="status" required
                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 rounded-lg shadow-sm focus:ring-amber-500 focus:border-amber-500 transition duration-150">
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?= htmlspecialchars($status) ?>"><?= ucfirst(htmlspecialchars($status)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancelModalBtn"
                        class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100 transition duration-150 shadow-sm">
                        Hủy bỏ
                    </button>
                    <button type="submit" id="submitModalBtn"
                        class="px-4 py-2 bg-amber-500 text-white font-semibold rounded-lg hover:bg-amber-600 transition duration-150 shadow-md">
                        Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('updateStatusModal');
        const modalContent = modal.querySelector('div.bg-white');
        const selectStatus = document.getElementById('new_status');
        const modalOrderInputId = document.getElementById('modalOrderInputId');
        const modalOrderIdDisplay = document.getElementById('modalOrderId');
        const modalCustomerName = document.getElementById('modalCustomerName');
        const cancelButton = document.getElementById('cancelModalBtn');
        const submitButton = document.getElementById('submitModalBtn');
        const form = document.getElementById('statusUpdateForm');
        const alertMessage = document.getElementById('alert-message');

        // Bản đồ ánh xạ trạng thái và class (Tái sử dụng từ PHP)
        const statusMap = {
            'chờ xác nhận': 'bg-yellow-100 text-yellow-800 border-yellow-400',
            'xác nhận': 'bg-green-100 text-green-800 border-green-400',
            'đang giao': 'bg-blue-100 text-blue-800 border-blue-400',
            'đã giao': 'bg-indigo-100 text-indigo-800 border-indigo-400',
            'thành công': 'bg-emerald-100 text-emerald-800 border-emerald-400',
            'đã hủy': 'bg-red-100 text-red-800 border-red-400',
            'default': 'bg-gray-100 text-gray-800 border-gray-400'
        };
        const allColorClasses = Object.values(statusMap).join(' ').split(' ').filter(c => c.trim() !== '');

        // Hàm lấy class trạng thái
        function getStatusClassesJs(status) {
            return statusMap[status.toLowerCase()] || statusMap['default'];
        }

        // Hàm hiển thị thông báo trong Modal
        function showAlert(message, type = 'success') {
            alertMessage.textContent = message;
            alertMessage.className = 'p-3 mb-4 text-sm rounded-lg'; // Reset classes

            if (type === 'success') {
                alertMessage.classList.add('bg-green-100', 'text-green-800');
            } else if (type === 'error') {
                alertMessage.classList.add('bg-red-100', 'text-red-800');
            }
            alertMessage.classList.remove('hidden');
        }

        // Hàm mở Modal
        document.querySelectorAll('.update-status-btn').forEach(button => {
            button.addEventListener('click', function () {
                const orderId = this.getAttribute('data-id');
                const currentStatus = this.getAttribute('data-current-status');
                const customerName = this.getAttribute('data-customer');

                modalOrderIdDisplay.textContent = `#${orderId}`;
                modalOrderInputId.value = orderId;
                modalCustomerName.textContent = customerName;
                selectStatus.value = currentStatus.toLowerCase();
                alertMessage.classList.add('hidden'); // Ẩn thông báo cũ

                // Hiển thị Modal với hiệu ứng
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.add('opacity-100');
                    modalContent.classList.remove('scale-95');
                    modalContent.classList.add('scale-100');
                }, 10);
            });
        });

        // Hàm đóng Modal
        const closeModal = () => {
            modal.classList.remove('opacity-100');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        };

        cancelButton.addEventListener('click', closeModal);
        modal.addEventListener('click', function (e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Xử lý gửi Form bằng AJAX
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const orderId = modalOrderInputId.value;
            const newStatus = selectStatus.value;

            submitButton.disabled = true;
            submitButton.textContent = 'Đang xử lý...';
            submitButton.classList.add('opacity-50');

            fetch(`?controller=admin&action=updateOrderStatusAjax`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `order_id=${orderId}&status=${newStatus}`
            })
                .then(response => response.json())
                .then(data => {
                    submitButton.disabled = false;
                    submitButton.textContent = 'Lưu thay đổi';
                    submitButton.classList.remove('opacity-50');

                    if (data.success) {
                        // 1. Cập nhật Tag trạng thái trong bảng
                        const statusTag = document.getElementById(`status-tag-${orderId}`);
                        if (statusTag) {
                            // Xóa các class màu cũ
                            statusTag.classList.remove(...allColorClasses);
                            // Thêm class màu mới
                            const newClasses = getStatusClassesJs(newStatus);
                            statusTag.classList.add(...newClasses.split(' '));
                            statusTag.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
                        }

                        showAlert(data.message, 'success');
                        // Tự động đóng sau 1.5 giây
                        setTimeout(closeModal, 1500);

                    } else {
                        showAlert(data.message, 'error');
                    }
                })
                .catch(error => {
                    submitButton.disabled = false;
                    submitButton.textContent = 'Lưu thay đổi';
                    submitButton.classList.remove('opacity-50');
                    showAlert('Lỗi kết nối mạng hoặc lỗi máy chủ.', 'error');
                    console.error('AJAX Error:', error);
                });
        });
    });
</script>