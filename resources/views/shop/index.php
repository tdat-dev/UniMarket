<?php
include __DIR__ . '/../partials/head.php';
include __DIR__ . '/../partials/header.php';
?>

<main class="bg-gray-50 min-h-screen pb-10">
    <div class="max-w-[1200px] mx-auto px-4 pt-6">

        <!-- Shop Header -->
        <div class="bg-white p-6 rounded-sm shadow-sm mb-6 flex items-center gap-6">
            <div class="relative">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($seller['full_name']) ?>&background=random&size=128" 
                     alt="<?= htmlspecialchars($seller['full_name']) ?>" 
                     class="w-20 h-20 rounded-full border-2 border-gray-100">
                <div class="absolute bottom-0 right-0 w-5 h-5 bg-green-500 rounded-full border-2 border-white"></div>
            </div>
            
            <div class="flex-1">
                <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                    <?= htmlspecialchars($seller['full_name']) ?>
                    <?php if(($seller['role'] ?? '') === 'admin'): ?>
                        <span class="bg-red-100 text-red-600 text-xs px-2 py-0.5 rounded-full">Admin</span>
                    <?php endif; ?>
                </h1>
                <div class="flex gap-6 mt-2 text-sm text-gray-500">
                    <span class="flex items-center gap-1"><i class="fa-solid fa-box"></i> <?= count($products) ?> Sản phẩm</span>
                    <span class="flex items-center gap-1"><i class="fa-solid fa-user-plus"></i> Đang theo dõi: 0</span>
                    <span class="flex items-center gap-1"><i class="fa-regular fa-star"></i> Đánh giá: 4.9/5.0</span>
                </div>
            </div>
            
            <div class="flex gap-3">
                <button class="px-6 py-2 border border-[#2C67C8] text-[#2C67C8] font-medium rounded-sm hover:bg-blue-50 transition-colors">
                    <i class="fa-solid fa-plus mr-1"></i> Theo dõi
                </button>
                <a href="/chat?user_id=<?= $seller['id'] ?>" class="px-6 py-2 bg-[#2C67C8] text-white font-medium rounded-sm hover:bg-blue-700 transition-colors shadow-sm">
                    <i class="fa-brands fa-rocketchat mr-1"></i> Chat
                </a>
            </div>
        </div>

        <!-- Product List -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
            <?php foreach ($products as $item): ?>
                <div class="group bg-white border border-transparent hover:border-[#2C67C8] hover:shadow-md transition-all duration-200 rounded-sm overflow-hidden relative">
                    <a href="/product-detail?id=<?= $item['id'] ?>" class="block">
                        <!-- Image -->
                        <div class="relative pt-[100%] overflow-hidden bg-gray-100">
                            <img src="/uploads/<?= htmlspecialchars($item['image']) ?>" 
                                 alt="<?= htmlspecialchars($item['name']) ?>" 
                                 class="absolute top-0 left-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <?php if ($item['quantity'] <= 0): ?>
                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center text-white text-xs font-bold uppercase tracking-wider">Hết hàng</div>
                            <?php endif; ?>
                        </div>

                        <!-- Info -->
                        <div class="p-2 space-y-1">
                            <h3 class="text-xs text-gray-700 font-normal line-clamp-2 leading-tight h-8 group-hover:text-[#2C67C8] transition-colors">
                                <?= htmlspecialchars($item['name']) ?>
                            </h3>
                            
                            <div class="flex items-center justify-between pt-1">
                                <div class="text-[#EE4D2D] font-medium text-sm">
                                    <?= number_format($item['price'], 0, ',', '.') ?><span class="text-xs align-top">₫</span>
                                </div>
                                <div class="text-[10px] text-gray-400">Đã bán 0</div>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        
        <?php if(empty($products)): ?>
            <div class="text-center text-gray-500 py-10">
                Shop này chưa đăng bán sản phẩm nào.
            </div>
        <?php endif; ?>

    </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
