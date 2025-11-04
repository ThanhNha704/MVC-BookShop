<?php 
// Component này yêu cầu biến $flashSaleBook, $saleEndTimeJS phải tồn tại
if (empty($flashSaleBook)) {
    return; // Không hiển thị nếu không có sách
}
// print_r($data);
// LẤY MỨC GIẢM GIÁ CỐ ĐỊNH TỪ LOGIC TRANG CHỦ
$discountPercent = $flashSaleBook['discount'] ?? 0; // Lấy 30% đã tính toán
$oldPrice = number_format($flashSaleBook['old_price'], 0, ',', '.') . 'đ';
$salePrice = number_format($flashSaleBook['sale_price'], 0, ',', '.') . 'đ';
$bookId = $flashSaleBook['id'];
?>

<section class="mb-12 bg-amber-400 text-white rounded-xl shadow-2xl overflow-hidden">
    <div class="grid md:grid-cols-3 items-center">
        
        <div class="md:col-span-1 p-6 text-center bg-amber-500 h-full flex flex-col justify-center items-center">
            <h2 class="text-4xl font-extrabold tracking-wider mb-2">FLASH SALE</h2>
            <p class="text-xl font-semibold mb-4">Giảm giá chớp nhoáng!</p>
            <i class="fas fa-bolt text-4xl text-yellow-100 animate-pulse"></i>
        </div>

        <div class="md:col-span-2 p-6 flex flex-col md:flex-row items-center gap-6">
            
            <a href="index.php?controller=product&action=details&id=<?= $bookId ?>" class="flex-shrink-0">
                <img src="<?= BASE_URL . 'public/products/' . htmlspecialchars($flashSaleBook['image']) ?>" 
     alt="<?= htmlspecialchars($flashSaleBook['title']) ?>" 
     class="w-32 h-48 object-cover rounded-lg shadow-xl border-4 border-white transform hover:scale-105 transition duration-300"
     onerror="this.src='<?= BASE_URL . 'public/products/placeholder.jpg' ?>'">
            </a>

            <div class="flex-grow space-y-3 text-center md:text-left">
                
                <p class="text-xs font-semibold uppercase text-yellow-100">Sale còn <?= $discountPercent ?>%</p>
                
                <h3 class="text-2xl font-bold line-clamp-2">
                    <a href="index.php?controller=product&action=details&id=<?= $bookId ?>" class="hover:underline">
                        <?= htmlspecialchars($flashSaleBook['title']) ?>
                    </a>
                </h3>

                <div class="flex items-center justify-center md:justify-start space-x-3">
                    <span class="text-3xl font-extrabold text-yellow-100"><?= $salePrice ?></span>
                    <span class="text-lg line-through text-amber-200"><?= $oldPrice ?></span>
                </div>

                <div id="countdown-timer" class="flex justify-center md:justify-start space-x-2 text-3xl font-bold pt-2">
                    <span class="bg-amber-700 p-2 rounded">00</span><span class="p-2">:</span>
                    <span class="bg-amber-700 p-2 rounded">00</span><span class="p-2">:</span>
                    <span class="bg-amber-700 p-2 rounded">00</span>
                </div>

                <a href="index.php?controller=product&action=details&id=<?= $bookId ?>"
                   class="inline-block mt-4 px-6 py-2 bg-white text-amber-600 font-bold rounded-full 
                          shadow-lg hover:bg-gray-100 transition duration-300">
                    MUA NGAY!
                </a>
            </div>

        </div>

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const endTimeString = "<?= $saleEndTimeJS ?>";
        const saleEndTime = new Date(endTimeString).getTime();
        const timerElement = document.getElementById('countdown-timer');

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = saleEndTime - now;

            if (distance < 0) {
                clearInterval(interval);
                // Sửa màu chữ khi kết thúc sale
                timerElement.innerHTML = '<span class="text-yellow-100 text-xl">ĐÃ KẾT THÚC!</span>'; 
                return;
            }

            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            const formatTime = (t) => String(t).padStart(2, '0');

            // Đảm bảo span có nền amber-700 như trong HTML
            timerElement.innerHTML = `
                <span class="bg-amber-700 p-2 rounded">${formatTime(hours)}</span>
                <span class="p-2">:</span>
                <span class="bg-amber-700 p-2 rounded">${formatTime(minutes)}</span>
                <span class="p-2">:</span>
                <span class="bg-amber-700 p-2 rounded">${formatTime(seconds)}</span>
            `;
        }

        updateCountdown();
        const interval = setInterval(updateCountdown, 1000);
    });
</script>
