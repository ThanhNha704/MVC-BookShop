<?php
$item = $data['book'];
$current_price = $item['price'] * (100 - ($item['discount'] ?? 0)) / 100;
?>

<section class="container mx-auto py-12 px-4 md:px-0">
    <style>
        /* Hide native number input spinners (WebKit + Firefox) without JS */
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
            appearance: textfield;
        }
    </style>
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-10 items-start">

                <!-- Image -->
                <div class="md:col-span-1 flex justify-center">
                    <div class="w-full max-w-md bg-gray-50 rounded-lg overflow-hidden border">
                        <img src="<?= htmlspecialchars((defined('BASE_URL') ? BASE_URL : '') . 'public/products/' . $item['image']) ?>"
                            alt="<?= htmlspecialchars($item['title']) ?>" class="w-full h-96 object-cover">
                    </div>
                </div>

                <!-- Main info -->
                <div class="md:col-span-2">
                    <div class="mb-3">
                        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight">
                            <?= htmlspecialchars($item['title']) ?>
                        </h1>
                        <p class="mt-2 text-lg text-gray-600">Tác giả: <span
                                class="font-semibold text-amber-600"><?= htmlspecialchars($item['author']) ?></span></p>
                    </div>

                    <div class="flex items-center gap-4 mt-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <?php if (!empty($item['discount'])): ?>
                                <div class="flex items-baseline gap-4">
                                    <div>
                                        <div class="text-3xl md:text-4xl font-extrabold text-red-600">
                                            <?= number_format($current_price, 0, ',', '.') ?> ₫
                                        </div>
                                        <div class="text-sm text-gray-500 line-through">
                                            <?= number_format($item['price'], 0, ',', '.') ?> ₫
                                        </div>
                                    </div>
                                    <div class="ml-auto top-0">
                                        <span
                                            class="inline-block bg-red-600 text-white text-sm font-bold px-3 py-1 rounded-full">-<?= htmlspecialchars($item['discount']) ?>%</span>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="text-3xl md:text-4xl text-gray-900">
                                    <?= number_format($item['price'], 0, ',', '.') ?> ₫
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Actions: single form submits quantity; buy_now sent by button name -->
                    <div class="mt-6 space-y-4">
                        <form id="add_to_cart_form" action="?controller=cart&action=add" method="POST"
                            class="space-y-3">
                            <div class="flex items-center gap-4">
                                <label class="text-gray-600">Số lượng:</label>
                                <div class="flex items-center gap-1 border border-gray-300 bg-gray-50 rounded-lg p-1">
                                    <button type="button" data-action="dec"
                                        class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-200 rounded">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input id="productQuantity" type="number" name="quantity" min="1" value="1"
                                        max="<?= isset($item['quantity']) ? (int) $item['quantity'] : '' ?>"
                                        class="w-16 text-center border border-gray-300 rounded h-10 bg-white">
                                    <button type="button" data-action="inc"
                                        class="w-8 h-8 flex items-center justify-center text-gray-600 hover:bg-gray-200 rounded">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <?php if (isset($item['quantity'])): ?>
                                    <span class="text-sm text-gray-500">(Còn <?= (int) $item['quantity'] ?> sản phẩm)</span>
                                <?php endif; ?>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <input type="hidden" name="product_id" value="<?= (int) $item['id'] ?>">
                                <button type="submit"
                                    class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 rounded-lg shadow">
                                    <i class="fas fa-cart-plus mr-2"></i> Thêm vào giỏ
                                </button>

                                <button type="submit" name="buy_now" value="1"
                                    class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 rounded-lg shadow">
                                    <i class="fas fa-shopping-bag mr-2"></i> Mua ngay
                                </button>
                            </div>
                        </form>
                    </div>

                    <script>
                        // Minimal JS to make +/- buttons work and keep quantity within min/max
                        (function () {
                            var dec = document.querySelector('button[data-action="dec"]');
                            var inc = document.querySelector('button[data-action="inc"]');
                            var qty = document.getElementById('productQuantity');
                            if (!qty) return;

                            function clamp(v) {
                                var min = parseInt(qty.getAttribute('min')) || 1;
                                var max = parseInt(qty.getAttribute('max')) || Infinity;
                                v = Number(v) || min;
                                if (v < min) v = min;
                                if (v > max) v = max;
                                return v;
                            }

                            if (dec) dec.addEventListener('click', function (e) {
                                qty.value = clamp(parseInt(qty.value) - 1);
                            });
                            if (inc) inc.addEventListener('click', function (e) {
                                qty.value = clamp(parseInt(qty.value) + 1);
                            });
                        })();
                    </script>

                    <!-- Description -->
                    <div class="mt-8">
                        <h3 class="text-2xl font-semibold text-gray-900 mb-3">Mô tả sản phẩm</h3>
                        <div class="prose max-w-none text-gray-700">
                            <?= nl2br(htmlspecialchars($item['description'])) ?>
                        </div>
                    </div>

                    <!-- Reviews summary -->
                    <div class="mt-8">
                        <?php
                        $reviews = $data['reviews'] ?? $item['reviews'] ?? [];
                        $canReview = $data['canReview'] ?? false;
                        $review_count = $item['review_count'] ?? count($reviews);
                        $average = $item['average_rating'] ?? 0;
                        ?>

                        <div class="flex items-center justify-between">
                            <h3 class="text-2xl font-semibold text-gray-900">Đánh giá</h3>
                            <div class="text-right">
                                <div class="text-sm text-gray-500">Trung bình</div>
                                <div class="font-bold text-xl text-amber-600"><?= $average ?: '—' ?> ★</div>
                                <div class="text-sm text-gray-500">(<?= $review_count ?> đánh giá)</div>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <?php if ($canReview): ?>
                                    <form action="?controller=review&action=add" method="POST"
                                        class="space-y-4 border p-4 rounded-lg bg-gray-50">
                                        <input type="hidden" name="book_id" value="<?= (int) $item['id'] ?>">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Đánh giá</label>
                                            <select name="rating" class="mt-1 rounded border border-gray-300 w-32">
                                                <option value="5">5 - Rất tốt</option>
                                                <option value="4">4 - Tốt</option>
                                                <option value="3">3 - Trung bình</option>
                                                <option value="2">2 - Kém</option>
                                                <option value="1">1 - Rất kém</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="blocktext-sm font-medium text-gray-700">Bình luận</label>
                                            <textarea name="comment" rows="3"
                                                class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2"></textarea>
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded">Gửi đánh
                                                giá</button>
                                        </div>
                                    </form>
                                <?php else: ?>
                                    <p class="text-gray-600">Bạn chỉ có thể đánh giá sản phẩm này sau khi mua hàng.</p>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="text-gray-600">Vui lòng <a href="?controller=auth&action=showLoginForm"
                                        class="text-amber-600">đăng nhập</a> để đánh giá sản phẩm.</p>
                            <?php endif; ?>
                        </div>

                        <div class="mt-4 space-y-4">
                            <?php if (!empty($reviews)): ?>
                                <?php foreach ($reviews as $r): ?>
                                    <?php $username = htmlspecialchars($r['username'] ?? $r['user_name'] ?? 'Khách');
                                    $content = htmlspecialchars($r['content'] ?? $r['comment'] ?? '');
                                    $rating = isset($r['rating']) ? (int) $r['rating'] : 0;
                                    ?>
                                    <div class="border rounded-lg p-4 bg-gray-50">
                                        <div class="flex items-center justify-between">
                                            <div class="font-medium text-gray-800"><?= $username ?></div>
                                            <div class="text-amber-500">
                                                <?= str_repeat('★', $rating) . str_repeat('☆', max(0, 5 - $rating)) ?>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-gray-700"><?= nl2br($content) ?></div>
                                        <div class="mt-2 text-xs text-gray-500"><?= htmlspecialchars($r['created_at'] ?? '') ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-gray-600">Chưa có đánh giá cho sản phẩm này.</p>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>