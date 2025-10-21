<?php 
// views/admin/users/index.php
?>

<div class="bg-white p-6 flex-1">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Quản lý Người dùng (Khách hàng & Nhân viên)</h2>
    <h3 class="text-xl font-semibold mb-4">Danh sách Khách hàng</h3>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái (Khóa)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">1</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">nh</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">tnthanhnha04@gmail.com</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">user</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Hoạt động</span></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="#" class="text-red-600 hover:text-red-900">Khóa</a>
                    </td>
                </tr>
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">4</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">admin</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">truongnha474@gmail.com</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">admin</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Bị khóa</span></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <a href="#" class="text-green-600 hover:text-green-900">Mở khóa</a>
                    </td>
                </tr>
                </tbody>
        </table>
    </div>
</div>

<div class="bg-white p-6 rounded-xl shadow-lg mt-6">
    <h3 class="text-xl font-semibold mb-4">Quản lý Đánh giá (Review)</h3>
    <p class="text-gray-600">Hiển thị các đánh giá và cho phép xóa hoặc ẩn (khóa) các đánh giá không phù hợp.</p>
</div>