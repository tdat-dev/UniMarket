<?php
include __DIR__ . '/../partials/head.php';
include __DIR__ . '/../partials/header.php';

// Calculate totals from passed products
$grandTotal = 0;
if (!empty($products)) {
    foreach ($products as $item) {
        if (isset($item['cart_quantity'])) {
            $grandTotal += $item['price'] * $item['cart_quantity'];
        }
    }
}
?>

<main class="bg-gray-100 min-h-screen pb-10">
    <div class="max-w-[1200px] mx-auto px-4 pt-4">
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="/" class="hover:text-[#2C67C8]">Trang chủ</a>
            <span>&gt;</span>
            <a href="/cart" class="hover:text-[#2C67C8]">Giỏ hàng</a>
            <span>&gt;</span>
            <span class="text-gray-800">Thanh toán</span>
        </div>

        <h1 class="text-2xl font-medium text-gray-800 mb-6">Thanh toán</h1>

        <!-- Form submit đến confirm để xử lý trừ kho -->
        <form action="/checkout/confirm" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Truyền lại các ID sản phẩm đã chọn để bước confirm biết cần mua gì -->
            <?php if (!empty($selected_ids)): ?>
                <?php foreach ($selected_ids as $id): ?>
                    <input type="hidden" name="selected_products[]" value="<?= htmlspecialchars($id) ?>">
                <?php endforeach; ?>
            <?php endif; ?>

            <!-- Order Details -->
            <div class="lg:col-span-8 space-y-4">
                <!-- Address Section (Mockup) -->
                <div class="bg-white rounded-sm shadow-sm p-6">
                    <h3 class="text-base font-medium text-[#EE4D2D] mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-location-dot"></i> Địa chỉ nhận hàng
                    </h3>
                    <div class="flex flex-col gap-1 text-sm text-gray-800">
                        <div class="font-bold">Nguyễn Anh Huy (+84) 999 999 999</div>
                        <div>Số 123, Đường 3/2, Phường Xuân Khánh, Quận Ninh Kiều, Cần Thơ</div>
                    </div>
                </div>

                <!-- Products -->
                <div class="bg-white rounded-sm shadow-sm overflow-hidden">
                    <div class="p-4 border-b bg-gray-50 text-sm font-medium text-gray-500">
                        Sản phẩm đã chọn
                    </div>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $item): 
                            $itemTotal = $item['price'] * $item['cart_quantity'];
                        ?>
                        <div class="flex gap-4 p-4 border-b last:border-0 items-center">
                            <div class="w-16 h-16 border rounded-sm overflow-hidden flex-shrink-0">
                                <img src="/uploads/<?= htmlspecialchars($item['image']) ?>" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-800 line-clamp-2"><?= htmlspecialchars($item['name']) ?></h4>
                                <span class="text-xs text-gray-500">Loại: Tiêu chuẩn</span>
                            </div>
                            <div class="text-sm text-gray-600">
                                <?= number_format($item['price'], 0, ',', '.') ?>đ x <?= $item['cart_quantity'] ?>
                            </div>
                            <div class="text-sm font-bold text-[#EE4D2D] w-32 text-right">
                                <?= number_format($itemTotal, 0, ',', '.') ?>đ
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="p-4 text-center text-gray-500">Không có sản phẩm nào được chọn.</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Summary & Payment -->
            <div class="lg:col-span-4 space-y-4">
                <div class="bg-white rounded-sm shadow-sm p-6 sticky top-4">
                    <h3 class="text-base font-medium text-gray-800 mb-4 pb-4 border-b">Chi tiết thanh toán</h3>
                    
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-600">Tổng tiền hàng</span>
                        <span class="font-medium"><?= number_format($grandTotal, 0, ',', '.') ?>đ</span>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-600">Phí vận chuyển</span>
                        <span class="font-medium text-green-600">Miễn phí</span>
                    </div>
                    
                     <div class="flex justify-between items-center mb-6 pt-4 border-t">
                        <span class="text-base font-medium text-gray-800">Tổng thanh toán</span>
                        <span class="text-xl font-bold text-[#EE4D2D]"><?= number_format($grandTotal, 0, ',', '.') ?>đ</span>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center gap-3 p-3 border rounded-sm cursor-pointer border-[#EE4D2D] bg-[#FFF5F1]">
                            <input type="radio" name="payment_method" checked class="text-[#EE4D2D] focus:ring-[#EE4D2D]">
                            <span class="text-sm font-medium">Thanh toán khi nhận hàng (COD)</span>
                        </div>
                    </div>

                    <button type="submit" class="w-full mt-6 py-3 bg-[#EE4D2D] text-white font-bold rounded-sm hover:bg-[#d73211] transition-transform active:scale-[0.98] shadow-md">
                        ĐẶT HÀNG
                    </button>
                    
                    <div class="mt-4 text-center">
                        <a href="/cart" class="text-sm text-gray-500 hover:text-[#EE4D2D]">Quay lại giỏ hàng</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
