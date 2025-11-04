<?php
// PHẦN LOGIC: Lấy Dữ liệu và Tính toán (Đã kiểm tra an toàn)
const FLASH_SALE_DISCOUNT_PERCENT = 30;

// RẤT QUAN TRỌNG: Kiểm tra $books phải là mảng và KHÔNG rỗng trước khi dùng array_rand/array_slice
if (isset($books) && is_array($books) && !empty($books)) {
    // 1. Logic cho Best Sellers (Lấy 8 sách đầu tiên)
    $bestSellers = array_slice($books, 0, 8);

    // 2. Logic cho Flash Sale (Cố định 1 sản phẩm theo ID)
$fixedFlashSaleId = 5; // 🔹 Thay ID này bằng ID sách bạn muốn hiển thị Flash Sale
$flashSaleBook = null;

// Tìm sách có ID tương ứng trong danh sách $books
foreach ($books as $book) {
    if ($book['id'] == $fixedFlashSaleId) {
        $flashSaleBook = $book;
        break;
    }
}

// Nếu tìm thấy sản phẩm hợp lệ, xử lý giảm giá
if ($flashSaleBook) {
    $originalPrice = $flashSaleBook['price'] ?? 0;
    $flashSaleBook['sale_price'] = round($originalPrice * (100 - FLASH_SALE_DISCOUNT_PERCENT) / 100);
    $flashSaleBook['old_price'] = $originalPrice;
    $flashSaleBook['discount'] = FLASH_SALE_DISCOUNT_PERCENT;

    // Thời gian kết thúc sale (12 giờ)
    if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['flashsale_end_time'])) {
    // Chỉ tạo thời gian kết thúc 1 lần (12 tiếng kể từ lần đầu)
    $_SESSION['flashsale_end_time'] = time() + (12 * 60 * 60);
}

// Lấy thời gian kết thúc từ session
$saleEndTime = $_SESSION['flashsale_end_time'];
$saleEndTimeJS = date('Y-m-d\TH:i:sP', $saleEndTime);
}

} else {
    // Khởi tạo các biến mặc định nếu không có dữ liệu
    $bestSellers = [];
    $flashSaleBook = null;
    $saleEndTimeJS = null; // Khởi tạo biến này để tránh lỗi "Undefined variable" trong View
}
?>

<main class="w-[90%] mx-auto py-8">

    <section class="mb-12 py-12 px-6 rounded-xl shadow-xl bg-white overflow-hidden">
        <div class="grid md:grid-cols-2 gap-10 items-center">

            <div class="space-y-5 z-20">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight text-gray-900">
                    <span class="block">Khám Phá Thế Giới</span>
                    <span class="block mt-1">Tri Thức Tại</span>
                    <span class="block text-amber-600">BOOKAZA</span>
                </h1>

                <p class="text-xs lg:text-lg text-gray-700 max-w-lg">
                    Hàng ngàn đầu sách chọn lọc về kinh doanh, kỹ năng, văn học và khoa học đang chờ bạn.
                </p>
            </div>

            <div class="flex justify-center md:justify-end z-10">
                <img src="./public/products/banner_sach.jpg" alt="Banner Sách Bookaza" class="w-full rounded-lg">
            </div>

            <a href="?controller=product" class="w-max inline-block px-5 py-2 bg-amber-500 text-white text-md lg:text-lg font-semibold 
                      rounded-full shadow-lg hover:bg-amber-600 transition duration-300 transform hover:scale-105">
                Xem Tất Cả Sách Ngay →
            </a>

        </div>
    </section>

    <?php
    // CHỈ HIỂN THỊ KHI CÓ DỮ LIỆU ĐƯỢC TÍNH TOÁN
    if ($flashSaleBook !== null) {
        require __DIR__ . '/../../components/FlashSale.php';
    }
    ?>

    <?php
    if (!empty($bestSellers)) {
        require __DIR__ . '/../../components/BestSeller.php';
    }
    ?>
    <?php if (strtotime($saleEndTimeJS) > time()): ?>
    <!-- Hiển thị flash sale bình thường -->
<?php else: ?>
    <div class="text-center py-10 bg-gray-100 rounded-xl shadow-md">
        <h2 class="text-3xl font-bold text-gray-600">🎉 FLASH SALE ĐÃ KẾT THÚC</h2>
        <p class="text-gray-500 mt-3">Vui lòng quay lại sau để xem ưu đãi tiếp theo!</p>
    </div>
<?php endif; ?>

</main>
