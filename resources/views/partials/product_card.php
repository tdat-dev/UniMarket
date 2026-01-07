<a href="/product-detail?id=<?= $item['id'] ?>"
    class="block bg-white rounded-sm shadow-sm hover:shadow-md transition-shadow group">
    <div class="aspect-square relative overflow-hidden">
        <img src="/uploads/<?= !empty($item['image']) ? $item['image'] : 'default.png' ?>"
            class="w-full h-full object-cover transition-transform group-hover:scale-105">
    </div>
    <div class="p-3">
        <div class="text-xs text-gray-800 line-clamp-2 mb-2 min-h-[32px] group-hover:text-[#2C67C8] transition-colors">
            <?= htmlspecialchars($item['name']) ?>
        </div>
        <div class="flex justify-between items-end">
            <div class="text-[#ee4d2d] text-base font-medium"> <!-- Shopee/Lazada style red -->
                <span class="text-xs underline">đ</span><?= number_format((float) $item['price'], 0, ',', '.') ?>
            </div>
            <div class="text-xs text-gray-500">Đã bán <?= number_format($item['sold_count'] ?? 0) ?></div> <!-- Real sold count -->
        </div>
    </div>
</a>