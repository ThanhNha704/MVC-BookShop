<?php
// views/admin/reviews/index.php 
$reviews = $data['reviews'] ?? [];
$currentSearch = htmlspecialchars($_GET['search'] ?? '');

?>

<div class="flex-1 bg-white p-6 overflow-scroll">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Quản lý Đánh giá</h2>

    <?php
    if (isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <?= htmlspecialchars($_SESSION['success']);
            unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <?= htmlspecialchars($_SESSION['error']);
            unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    <div class="flex justify-between items-center bg-white p-4 rounded-xl shadow-sm border border-gray-200">
        <form action="" method="GET" class="flex items-center w-full max-w-md">
            <input type="hidden" name="controller" value="admin">
            <input type="hidden" name="action" value="reviews">
            <input type="text" name="search" placeholder="Tìm kiếm theo Tên SP (ví dụ: nhà giả kim)..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500 shadow-inner transition duration-150"
                value="<?= $currentSearch ?>">
            <button type="submit"
                class="ml-2 px-4 py-2 bg-amber-500 text-white font-semibold rounded-lg hover:bg-amber-600 transition duration-150 shadow-md">
                Tìm
            </button>
        </form>
    </div>
    <div class="overflow-x-auto overflow-y-auto h-full w-full rounded-lg shadow-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0 z-10 border-b border-gray-300">
                <tr>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Rating
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Nội
                        dung
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Ngày
                        tạo
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Người
                        đánh giá</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Sách
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Trạng
                        thái</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-600 uppercase tracking-wider">Hành
                        động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($reviews)): ?>
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                            Không có đánh giá nào.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($reviews as $review):
                        $status = strtolower($review['status']);

                        // Ánh xạ trạng thái DB sang tiếng Việt
                        $vietnamese_status = '';
                        $color_class = '';
                        switch ($status) {
                            case 'hiện':
                                $vietnamese_status = 'Hiện';
                                $color_class = 'bg-green-100 text-green-800';
                                break;
                            case 'ẩn':
                                $vietnamese_status = 'Ẩn';
                                $color_class = 'bg-red-100 text-red-800';
                                break;
                            default:
                                $vietnamese_status = 'Lỗi trạng thái';
                                $color_class = 'bg-gray-100 text-gray-800';
                                break;
                        }
                        ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-medium text-gray-900">
                                <?= htmlspecialchars($review['id']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-yellow-500 font-bold">
                                <?= htmlspecialchars($review['rating']) ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate"
                                title="<?= htmlspecialchars($review['comment']) ?>">
                                <?= htmlspecialchars(substr($review['comment'], 0, 50)) ?>
                                <?= (strlen($review['comment']) > 50) ? '...' : '' ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                <?= htmlspecialchars(date('Y-m-d H:i', strtotime($review['created_at']))) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                <?= htmlspecialchars($review['reviewer_name']) ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                <?= htmlspecialchars($review['book_title']) ?>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 inline-flex text-xs text-center leading-5 font-semibold rounded-full <?= $color_class ?>">
                                    <?= $vietnamese_status ?>
                                </span>
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">

                                <?php if ($status !== 'hiện'): ?>
                                    <a href="?controller=admin&action=updateStatusReview&id=<?= $review['id'] ?>&status=visible"
                                        class="text-green-600 hover:text-green-900 mr-3">Hiện</a>
                                <?php endif; ?>

                                <?php if ($status !== 'ẩn'): ?>
                                    <a href="?controller=admin&action=updateStatusReview&id=<?= $review['id'] ?>&status=hidden"
                                        class="text-yellow-600 hover:text-yellow-900 mr-3">Ẩn</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>