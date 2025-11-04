<?php
// PHẦN LOGIC: Lấy Dữ liệu và Tính toán (Đã kiểm tra an toàn)
const FLASH_SALE_DISCOUNT_PERCENT = 30;

// RẤT QUAN TRỌNG: Kiểm tra $books phải là mảng và KHÔNG rỗng trước khi dùng array_rand/array_slice
if (isset($books) && is_array($books) && !empty($books)) {
    // 1. Logic cho Best Sellers (Lấy 8 sách đầu tiên)
    $bestSellers = array_slice($books, 0, 8);

    // 2. Logic cho Flash Sale (Chọn ngẫu nhiên 1 sách)
    $randomKey = array_rand($books);
    $flashSaleBook = $books[$randomKey];
    $originalPrice = $flashSaleBook['price'] ?? 0;

    // TÍNH TOÁN: Áp dụng mức giảm cố định 30%
    $flashSaleBook['sale_price'] = round($originalPrice * (100 - FLASH_SALE_DISCOUNT_PERCENT) / 100);
    $flashSaleBook['old_price'] = $originalPrice;

    // Truyền mức giảm giá cố định này để hiển thị % trong component
    $flashSaleBook['discount'] = FLASH_SALE_DISCOUNT_PERCENT;

    // Tính thời gian kết thúc sale (Ví dụ: 12 giờ)
    $saleEndTime = time() + (12 * 60 * 60);
    $saleEndTimeJS = date('Y-m-d\TH:i:sP', $saleEndTime);

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

</main>