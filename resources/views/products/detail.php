<?php
include __DIR__ . '/../partials/head.php';
include __DIR__ . '/../partials/header.php';
?>

<main class="bg-gray-100 min-h-screen pb-10">
    <div class="max-w-[1200px] mx-auto px-4 pt-4 space-y-6">

        <!-- Breadcrumb -->
        <div class="text-sm text-gray-500">
            <a href="/" class="hover:text-[#2C67C8]">Trang chủ</a>
            <span class="mx-2">></span>
            <a href="/products" class="hover:text-[#2C67C8]">Sản phẩm</a>
            <span class="mx-2">></span>
            <span class="text-gray-800 truncate"><?= htmlspecialchars($product['name']) ?></span>
        </div>

        <!-- Main Product Section -->
        <div class="bg-white rounded-sm shadow-sm p-4">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">

                <!-- Left: Image Gallery -->
                <div class="md:col-span-5">
                    <div
                        class="relative w-full aspect-square bg-gray-100 rounded-sm overflow-hidden border border-gray-200">
                        <img src="/uploads/<?= htmlspecialchars($product['image'] ?? '') ?>"
                            alt="<?= htmlspecialchars($product['name'] ?? '') ?>"
                            class="w-full h-full object-contain cursor-zoom-in"
                            onclick="window.open(this.src, '_blank')">
                    </div>
                    <!-- Thumbnails (Placeholder for now since we only have 1 image) -->
                    <div class="flex gap-2 mt-4 overflow-x-auto">
                        <?php for ($i = 0; $i < 5; $i++): ?>
                            <div
                                class="w-20 h-20 border border-gray-200 hover:border-[#2C67C8] cursor-pointer rounded-sm overflow-hidden <?= $i === 0 ? 'border-[#2C67C8]' : '' ?>">
                                <img src="/uploads/<?= htmlspecialchars($product['image'] ?? '') ?>"
                                    class="w-full h-full object-cover">
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <!-- Right: Product Info -->
                <div class="md:col-span-7 space-y-6">
                    <h1 class="text-2xl font-medium text-gray-800 leading-snug">
                        <?= htmlspecialchars($product['name']) ?>
                    </h1>

                    <!-- Price -->
                    <div class="bg-gray-50 p-4 rounded-sm">
                        <span class="text-3xl font-bold text-[#EE4D2D]">
                            <?= number_format($product['price'], 0, ',', '.') ?>đ
                        </span>
                    </div>

                    <!-- Details -->
                    <div class="space-y-4 text-sm text-gray-600">
                        <div class="flex items-center">
                            <span class="w-32 text-gray-500">Vận chuyển:</span>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-truck-fast text-[#2C67C8]"></i>
                                <span>Từ <?= htmlspecialchars($seller['address'] ?? 'Hồ Chí Minh') ?></span>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <span class="w-32 text-gray-500">Tình trạng:</span>
                            <span>Đã sử dụng - Tốt</span>
                        </div>
                        <div class="flex items-center">
                            <span class="w-32 text-gray-500">Số lượng:</span>
                            <div class="flex items-center border border-gray-300 rounded-sm">
                                <button type="button" id="btn-decrease"
                                    class="px-3 py-1 border-r border-gray-300 hover:bg-gray-50 min-w-[32px]">-</button>
                                <input type="number" id="input-quantity" value="1" min="1"
                                    max="<?= $product['quantity'] ?>"
                                    class="w-14 text-center outline-none bg-white font-medium [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                                <button type="button" id="btn-increase"
                                    class="px-3 py-1 border-l border-gray-300 hover:bg-gray-50 min-w-[32px]">+</button>
                            </div>
                            <span class="ml-3 text-gray-400"><?= $product['quantity'] ?> sản phẩm có sẵn</span>
                            <input type="hidden" id="max-stock" value="<?= $product['quantity'] ?>">
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-4 pt-4">
                        <?php if ($product['quantity'] > 0 && $product['status'] === 'active'): ?>
                            <form action="/cart/add" method="POST" class="flex gap-4 w-full">
                                <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                <input type="hidden" name="quantity" value="1" id="input-quantity-submit">

                                <button type="submit" name="action" value="add"
                                    class="flex-1 px-6 py-3 bg-[#2C67C8] text-white font-medium rounded-sm hover:bg-[#F97316] transition-colors flex items-center justify-center gap-2">
                                    <i class="fa-solid fa-cart-shopping"></i> Thêm vào giỏ hàng
                                </button>
                                <button type="submit" name="action" value="buy"
                                    class="flex-1 px-8 py-3 bg-[#2C67C8] text-white font-medium rounded-sm hover:bg-[#F97316] transition-colors shadow-sm text-center">
                                    Mua ngay
                                </button>
                            </form>
                        <?php else: ?>
                            <button disabled
                                class="w-full px-8 py-3 bg-gray-300 text-gray-500 font-medium rounded-sm cursor-not-allowed">
                                Sản phẩm đã hết hàng
                            </button>
                        <?php endif; ?>
                    </div>

                    <!-- Policies -->
                    <div class="border-t pt-6 mt-6 grid grid-cols-2 gap-4 text-xs text-gray-500">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-shield-halved text-[#2C67C8] text-base"></i>
                            <span>Cam kết nhận hàng hoặc hoàn tiền</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-globe text-[#2C67C8] text-base"></i>
                            <span>Nền tảng mua bán đồ cũ vì môi trường</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Seller Info -->
        <div class="bg-white rounded-sm shadow-sm p-4">
            <div class="flex items-center gap-4">
                <div class="relative">
                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($seller['full_name']) ?>&background=random&size=128"
                        alt="<?= htmlspecialchars($seller['full_name']) ?>"
                        class="w-16 h-16 rounded-full border border-gray-200">
                    <div class="absolute bottom-0 right-0 w-4 h-4 bg-green-500 rounded-full border-2 border-white">
                    </div>
                </div>
                <div class="flex-1">
                    <h3 class="font-medium text-lg text-gray-800 flex items-center gap-2">
                        <?= htmlspecialchars($seller['full_name']) ?>
                        <i class="fa-solid fa-circle-check text-blue-500 text-sm" title="Đã xác minh"></i>
                    </h3>
                    <div class="text-sm text-gray-500 flex items-center gap-4 mt-1">
                        <span><i class="fa-solid fa-box mr-1"></i> 26 sản phẩm</span>
                        <span><i class="fa-solid fa-star mr-1 text-yellow-500"></i> 4.8 (15 đánh giá)</span>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="/chat?user_id=<?= $seller['id'] ?>"
                        class="px-4 py-2 border border-[#2C67C8] text-[#2C67C8] rounded-sm hover:bg-blue-50 font-medium text-sm flex items-center gap-1">
                        <i class="fa-regular fa-comment-dots"></i> Chat ngay
                    </a>
                    <a href="/shop?id=<?= $seller['id'] ?>"
                        class="px-4 py-2 bg-gray-100 text-gray-600 rounded-sm hover:bg-gray-200 font-medium text-sm flex items-center gap-1">
                        <i class="fa-solid fa-store"></i> Xem Shop
                    </a>
                </div>
            </div>
        </div>

        <!-- Product Description -->
        <div class="bg-white rounded-sm shadow-sm p-6">
            <h2 class="text-lg font-medium text-gray-800 bg-gray-50 p-3 mb-4 rounded-sm">Mô tả sản phẩm</h2>
            <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed whitespace-pre-line">
                <?= htmlspecialchars($product['description'] ?? '') ?>
            </div>
        </div>

        <!-- Related Products -->
        <div class="bg-white rounded-sm shadow-sm p-5">
            <h2 class="text-lg font-medium text-gray-800 mb-6 uppercase">Sản phẩm tương tự</h2>
            <?php if (empty($relatedProducts)): ?>
                <p class="text-gray-500 text-center py-4">Không có sản phẩm tương tự nào.</p>
            <?php else: ?>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                    <?php foreach ($relatedProducts as $item): ?>
                        <div
                            class="group bg-white border border-transparent hover:border-[#2C67C8] hover:shadow-md transition-all duration-200 rounded-sm overflow-hidden relative">
                            <a href="/product-detail?id=<?= $item['id'] ?>" class="block">
                                <!-- Image -->
                                <div class="relative pt-[100%] overflow-hidden bg-gray-100">
                                    <img src="/public/uploads/<?= htmlspecialchars($item['image'] ?? '') ?>"
                                        alt="<?= htmlspecialchars($item['name'] ?? '') ?>"
                                        class="absolute top-0 left-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

                                    <!-- Badges (Ví dụ: Freeship, Giảm giá) -->
                                    <div
                                        class="absolute top-0 left-0 bg-[#00bfa5] text-white text-[10px] font-bold px-1.5 py-0.5 rounded-br-sm">
                                        Freeship</div>
                                </div>

                                <!-- Info -->
                                <div class="p-2 space-y-1">
                                    <h3
                                        class="text-xs text-gray-700 font-normal line-clamp-2 leading-tight h-8 group-hover:text-[#2C67C8] transition-colors">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </h3>

                                    <div class="flex items-center justify-between pt-1">
                                        <div class="text-[#EE4D2D] font-medium text-sm">
                                            <?= number_format($item['price'], 0, ',', '.') ?><span
                                                class="text-xs align-top">₫</span>
                                        </div>
                                        <div class="text-[10px] text-gray-400">Đã bán 0</div>
                                    </div>

                                    <!-- Location -->
                                    <div class="flex items-center justify-between pt-2">
                                        <span class="text-[10px] text-gray-400 font-light truncate w-full">TP. Hồ Chí
                                            Minh</span>
                                    </div>
                                </div>
                            </a>

                            <!-- Hover Action (Tìm kiếm tương tự) -->
                            <div
                                class="absolute bottom-0 left-0 w-full bg-[#2C67C8] text-white text-center text-xs py-1.5 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                Tìm sản phẩm tương tự
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const btnDecrease = document.getElementById('btn-decrease');
        const btnIncrease = document.getElementById('btn-increase');
        const inputQuantity = document.getElementById('input-quantity');
        const maxStock = parseInt(document.getElementById('max-stock').value) || 0;
        const inputSubmit = document.getElementById('input-quantity-submit');
        const btnSubmitCart = document.querySelector('button[value="add"]');
        const btnSubmitBuy = document.querySelector('button[value="buy"]');

        function updateQuantity(val) {
            let newVal = parseInt(val);
            if (isNaN(newVal) || newVal < 1) newVal = 1;
            if (newVal > maxStock) newVal = maxStock;

            inputQuantity.value = newVal;
            if (inputSubmit) inputSubmit.value = newVal; // Update hidden input for form

            // Validate functionality if stock is somehow 0 (though covered by PHP)
            if (maxStock <= 0) {
                inputQuantity.value = 0;
                if (btnSubmitCart) btnSubmitCart.disabled = true;
                if (btnSubmitBuy) btnSubmitBuy.disabled = true;
            }
        }

        if (btnDecrease) {
            btnDecrease.addEventListener('click', function () {
                updateQuantity(parseInt(inputQuantity.value) - 1);
            });
        }

        if (btnIncrease) {
            btnIncrease.addEventListener('click', function () {
                updateQuantity(parseInt(inputQuantity.value) + 1);
            });
        }

        if (inputQuantity) {
            inputQuantity.addEventListener('change', function () {
                updateQuantity(this.value);
            });
        }

        // Initial check
        updateQuantity(inputQuantity.value);
    });
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>