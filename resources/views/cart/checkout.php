<?php
include __DIR__ . '/../partials/head.php';
include __DIR__ . '/../partials/header.php';

// Calculate totals from passed products
$grandTotal = 0;
if (!empty($products)) {
    foreach ($products as $item) {
        if (isset($item['cart_quantity']) && isset($item['price'])) {
            // Ensure both values are numbers
            $price = is_numeric($item['price']) ? (float) $item['price'] : 0;
            $qty = is_numeric($item['cart_quantity']) ? (int) $item['cart_quantity'] : 0;
            $grandTotal += $price * $qty;
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
                <!-- Address Section -->
                <div class="bg-white rounded-sm shadow-sm p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-base font-medium text-[#EE4D2D] flex items-center gap-2">
                            <i class="fa-solid fa-location-dot"></i> Địa chỉ nhận hàng
                        </h3>
                        <a href="/profile" class="text-blue-600 text-sm hover:underline">Thay đổi</a>
                    </div>
                    <div class="flex flex-col gap-1 text-sm text-gray-800">
                         <?php if (!empty($user['address'])): ?>
                            <div class="font-bold">
                                <?= htmlspecialchars($user['full_name'] ?? $_SESSION['user']['username'] ?? 'Người dùng') ?> 
                                (<?= htmlspecialchars($user['phone_number'] ?? 'Chưa có SĐT') ?>)
                            </div>
                            <div><?= htmlspecialchars($user['address']) ?></div>
                        <?php else: ?>
                            <div class="text-red-500">Bạn chưa cập nhật địa chỉ nhận hàng.</div>
                            <a href="/profile" class="text-blue-600 underline">Cập nhật ngay</a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Products -->
                <div class="bg-white rounded-sm shadow-sm overflow-hidden">
                    <div class="p-4 border-b bg-gray-50 text-sm font-medium text-gray-500">
                        Sản phẩm đã chọn
                    </div>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $item):
                            $price = is_numeric($item['price']) ? (float) $item['price'] : 0;
                            $qty = is_numeric($item['cart_quantity']) ? (int) $item['cart_quantity'] : 0;
                            $itemTotal = $price * $qty;
                            ?>
                            <div class="flex gap-4 p-4 border-b last:border-0 items-center item-row">
                                <div class="w-16 h-16 border rounded-sm overflow-hidden flex-shrink-0">
                                    <img src="/uploads/<?= htmlspecialchars($item['image']) ?>"
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-800 line-clamp-2">
                                        <?= htmlspecialchars($item['name']) ?>
                                    </h4>
                                    <span class="text-xs text-gray-500">Loại: Tiêu chuẩn</span>
                                    <div class="text-xs text-gray-400 mt-1">Kho: <?= $item['quantity'] ?></div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="text-sm text-gray-600 hidden sm:block"><?= number_format($item['price'], 0, ',', '.') ?>đ</span>
                                    <div class="flex items-center border border-gray-300 rounded-sm">
                                        <button type="button"
                                            class="btn-decrease px-2 py-1 hover:bg-gray-100 border-r border-gray-300 min-w-[24px]">-</button>
                                        <input type="number" name="quantities[<?= $item['id'] ?>]"
                                            value="<?= $item['cart_quantity'] ?>"
                                            class="w-12 text-center text-sm outline-none input-quantity [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                                            data-price="<?= $item['price'] ?>" data-max="<?= $item['quantity'] ?>" readonly>
                                        <button type="button"
                                            class="btn-increase px-2 py-1 hover:bg-gray-100 border-l border-gray-300 min-w-[24px]">+</button>
                                    </div>
                                </div>
                                <div class="text-sm font-bold text-[#EE4D2D] w-32 text-right item-total">
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
                        <span class="font-medium"
                            id="grand-total"><?= number_format($grandTotal, 0, ',', '.') ?>đ</span>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-600">Phí vận chuyển</span>
                        <span class="font-medium text-green-600">Miễn phí</span>
                    </div>

                    <div class="flex justify-between items-center mb-6 pt-4 border-t">
                        <span class="text-base font-medium text-gray-800">Tổng thanh toán</span>
                        <span class="text-xl font-bold text-[#EE4D2D]"
                            id="final-total"><?= number_format($grandTotal, 0, ',', '.') ?>đ</span>
                    </div>

                    <div class="space-y-3">
                        <div
                            class="flex items-center gap-3 p-3 border rounded-sm cursor-pointer border-[#EE4D2D] bg-[#FFF5F1]">
                            <input type="radio" name="payment_method" checked
                                class="text-[#EE4D2D] focus:ring-[#EE4D2D]">
                            <span class="text-sm font-medium">Thanh toán khi nhận hàng (COD)</span>
                        </div>
                    </div>

                    <button type="submit" id="btn-order"
                        class="w-full mt-6 py-3 bg-[#EE4D2D] text-white font-bold rounded-sm hover:bg-[#d73211] transition-transform active:scale-[0.98] shadow-md">
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

<script>
    // Toast Notification logic - Global scope
    const showToast = (message, type = 'error') => {
        const toast = document.createElement('div');
        toast.className = `fixed top-24 right-5 z-50 px-6 py-3 rounded shadow-lg text-white transform transition-all duration-300 translate-x-full opacity-0 flex items-center gap-2 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'
            }`;

        const icon = type === 'success' ? '<i class="fa-solid fa-check-circle"></i>' : '<i class="fa-solid fa-circle-exclamation"></i>';
        toast.innerHTML = `${icon} <span>${message}</span>`;

        document.body.appendChild(toast);

        // Animate in
        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-full', 'opacity-0');
        });

        // Remove after 3 seconds
        setTimeout(() => {
            toast.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    };

    document.addEventListener('DOMContentLoaded', function () {
        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $error): ?>
                showToast('<?= addslashes($error) ?>', 'error');
            <?php endforeach; ?>
        <?php endif; ?>

        const formatCurrency = (amount) => {
            return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
        };

        const updateTotals = () => {
            let grandTotal = 0;

            document.querySelectorAll('.item-row').forEach(row => {
                const input = row.querySelector('.input-quantity');
                const price = parseInt(input.dataset.price);
                const qty = parseInt(input.value) || 0;
                const itemTotalEl = row.querySelector('.item-total');

                const itemTotal = price * qty;
                grandTotal += itemTotal;

                itemTotalEl.textContent = formatCurrency(itemTotal);
            });

            const grandTotalEl = document.getElementById('grand-total');
            const finalTotalEl = document.getElementById('final-total');

            if (grandTotalEl) grandTotalEl.textContent = formatCurrency(grandTotal);
            if (finalTotalEl) finalTotalEl.textContent = formatCurrency(grandTotal);

            const btnSubmit = document.getElementById('btn-order');
            if (btnSubmit) {
                if (grandTotal === 0) {
                    btnSubmit.disabled = true;
                    btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    btnSubmit.disabled = false;
                    btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }
        };

        document.querySelectorAll('.btn-decrease').forEach(btn => {
            btn.addEventListener('click', function () {
                const input = this.nextElementSibling;
                let val = parseInt(input.value) || 0;
                if (val > 0) {
                    val--;
                    input.value = val;
                    updateTotals();
                }
            });
        });

        document.querySelectorAll('.btn-increase').forEach(btn => {
            btn.addEventListener('click', function () {
                const input = this.previousElementSibling;
                let val = parseInt(input.value) || 0;
                const max = parseInt(input.dataset.max) || 999;

                if (val < max) {
                    val++;
                    input.value = val;
                    updateTotals();
                } else {
                    showToast(`Số lượng tối đa cho sản phẩm này là ${max} sản phẩm`, 'error');
                }
            });
        });

        // Initial check
        updateTotals();
    });
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>