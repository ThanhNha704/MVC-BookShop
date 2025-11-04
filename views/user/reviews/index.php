<?php
// views/admin/reviews/index.php 

$reviews = $data['reviews'] ?? [];
// print_r($reviews);
?>

<div class="flex-1 bg-white p-6 overflow-scroll">
    <h2 class="text-2xl font-bold mb-6">Quản lý Đánh giá</h2>

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

    <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg overflow-y-scroll">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sách</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Người
                        đánh giá</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nội dung
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng
                        thái</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Hành
                        động</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($reviews)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                            Không có đánh giá nào.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($reviews as $review): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?= htmlspecialchars($review['id']) ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                <?= htmlspecialchars($review['book_title']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($review['reviewer_name']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-yellow-500 font-bold">
                                <?= htmlspecialchars($review['rating']) ?> / 5
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate"
                                title="<?= htmlspecialchars($review['comment']) ?>">
                                <?= htmlspecialchars(substr($review['comment'], 0, 50)) ?>        <?= (strlen($review['comment']) > 50) ? '...' : '' ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php
                                    $status = strtolower($review['status']);
                                    if ($status === 'approved')
                                        echo 'bg-green-100 text-green-800';
                                    else if ($status === 'pending')
                                        echo 'bg-yellow-100 text-yellow-800';
                                    else
                                        echo 'bg-red-100 text-red-800';
                                    ?>">
                                    <?= htmlspecialchars($review['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <?php if (strtolower($review['status']) !== 'approved'): ?>
                                    <a href="?controller=reviews&action=updateStatus&id=<?= $review['id'] ?>&status=approved"
                                        class="text-green-600 hover:text-green-900 mr-3">Duyệt</a>
                                <?php endif; ?>

                                <?php if (strtolower($review['status']) !== 'hidden'): ?>
                                    <a href="?controller=reviews&action=updateStatus&id=<?= $review['id'] ?>&status=hidden"
                                        class="text-yellow-600 hover:text-yellow-900 mr-3">Ẩn</a>
                                <?php endif; ?>

                                <a href="?controller=reviews&action=delete&id=<?= $review['id'] ?>"
                                    onclick="return confirm('Bạn có chắc chắn muốn xóa đánh giá #<?= $review['id'] ?> không?')"
                                    class="text-red-600 hover:text-red-900">Xóa</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>