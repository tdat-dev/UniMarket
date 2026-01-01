<?php include __DIR__ . '/../partials/head.php'; ?>
<?php 
// Fake seller check 
if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit;
}
?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<main class="bg-gray-50 min-h-screen pb-12">
    <!-- Profile Header / Cover -->
    <div class="h-48 bg-gradient-to-r from-yellow-400 to-orange-500 relative">
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16">
        <!-- User Info Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="px-6 py-6 md:flex md:items-center md:justify-between">
                <div class="flex items-center">
                    <div class="relative flex-shrink-0">
                        <img class="h-24 w-24 rounded-full ring-4 ring-white bg-white object-cover" src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']['full_name']) ?>&background=random&size=128" alt="">
                    </div>
                    <div class="ml-5">
                        <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($_SESSION['user']['full_name']) ?></h1>
                         <p class="text-sm text-gray-500">Đánh giá của tôi</p>
                    </div>
                </div>
            </div>

            <!-- Navigation Tabs -->
            <div class="bg-gray-50 px-6 border-t border-gray-200">
                <nav class="-mb-px flex gap-8" aria-label="Tabs">
                    <a href="/profile" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                        <i class="fa-regular fa-id-card"></i> Thông tin
                    </a>
                    <a href="/wallet" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                         <i class="fa-solid fa-wallet"></i> Ví & Giao dịch
                    </a>
                    <a href="/reviews" class="border-orange-500 text-orange-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                         <i class="fa-regular fa-star"></i> Đánh giá
                    </a>
                    <a href="/shop/orders" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                         <i class="fa-solid fa-box"></i> Quản lý đơn hàng
                    </a>
                </nav>
            </div>
        </div>

        <!-- Content Area -->
        <!-- Tabs -->
             <div class="flex border-b border-gray-100">
                <button onclick="switchTab('unreviewed')" id="tab-unreviewed" class="flex-1 py-4 text-center font-medium border-b-2 bg-orange-50/50 text-orange-600 border-orange-600">
                    Chưa đánh giá (<?= count($unreviewed ?? []) ?>)
                </button>
                 <button onclick="switchTab('reviewed')" id="tab-reviewed" class="flex-1 py-4 text-center font-medium text-gray-500 hover:text-orange-600 hover:bg-gray-50 transition-colors border-b-2 border-transparent">
                    Đã đánh giá (<?= count($reviews ?? []) ?>)
                </button>
            </div>

            <div class="p-0">
                <!-- Unreviewed Content -->
                <div id="content-unreviewed" class="block">
                     <?php if (empty($unreviewed)): ?>
                        <div class="p-12 text-center text-gray-500">
                            <p>Không có sản phẩm nào cần đánh giá.</p>
                        </div>
                     <?php else: ?>
                        <ul class="divide-y divide-gray-100">
                            <?php foreach ($unreviewed as $item): ?>
                                <li class="p-6 hover:bg-gray-50 transition">
                                    <div class="flex gap-4">
                                        <img src="/uploads/<?= htmlspecialchars($item['product_image'] ?? 'default.png') ?>" class="w-16 h-16 object-cover rounded-md border border-gray-200">
                                        <div class="flex-1">
                                            <h4 class="font-bold text-gray-900"><?= htmlspecialchars($item['product_name']) ?></h4>
                                            <p class="text-sm text-gray-500 mb-3">Ngày mua: <?= date('d/m/Y', strtotime($item['order_date'])) ?></p>
                                            
                                            <!-- Review Form -->
                                            <form action="/reviews/store" method="POST" class="bg-gray-50 p-4 rounded-md border border-gray-200">
                                                <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                                <div class="mb-2">
                                                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Đánh giá sao</label>
                                                    <select name="rating" class="border rounded px-2 py-1 text-sm">
                                                        <option value="5">5 Sao - Tuyệt vời</option>
                                                        <option value="4">4 Sao - Tốt</option>
                                                        <option value="3">3 Sao - Bình thường</option>
                                                        <option value="2">2 Sao - Tệ</option>
                                                        <option value="1">1 Sao - Rất tệ</option>
                                                    </select>
                                                </div>
                                                <div class="mb-2">
                                                    <textarea name="comment" rows="2" class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:border-orange-500" placeholder="Chia sẻ trải nghiệm của bạn..."></textarea>
                                                </div>
                                                <button type="submit" class="bg-orange-600 text-white text-sm px-4 py-1.5 rounded hover:bg-orange-700">Gửi đánh giá</button>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                     <?php endif; ?>
                </div>

                <!-- Reviewed Content -->
                <div id="content-reviewed" class="hidden">
                    <?php if (empty($reviews)): ?>
                        <div class="p-12 text-center">
                            <p class="text-gray-500 mt-1 mb-6">Bạn chưa có đánh giá nào.</p>
                        </div>
                    <?php else: ?>
                        <ul class="divide-y divide-gray-100">
                            <?php foreach ($reviews as $review): ?>
                                <li class="p-6 hover:bg-gray-50 flex gap-4">
                                    <img src="/uploads/<?= htmlspecialchars($review['product_image'] ?? 'default.png') ?>" class="w-16 h-16 object-cover rounded-md border border-gray-200">
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-bold text-gray-900"><?= htmlspecialchars($review['product_name']) ?></h4>
                                                <div class="flex items-center mt-1 mb-2">
                                                    <?php for($i=1; $i<=5; $i++): ?>
                                                        <i class="fa-solid fa-star text-xs <?= $i <= $review['rating'] ? 'text-yellow-400' : 'text-gray-200' ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                            <span class="text-xs text-gray-400"><?= date('d/m/Y', strtotime($review['created_at'])) ?></span>
                                        </div>
                                        <p class="text-sm text-gray-600"><?= htmlspecialchars($review['comment']) ?></p>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function switchTab(tab) {
    if(tab === 'unreviewed') {
        document.getElementById('content-unreviewed').classList.remove('hidden');
        document.getElementById('content-reviewed').classList.add('hidden');
        
        document.getElementById('tab-unreviewed').classList.add('text-orange-600', 'border-orange-600', 'bg-orange-50/50');
        document.getElementById('tab-unreviewed').classList.remove('text-gray-500', 'border-transparent');
        
        document.getElementById('tab-reviewed').classList.remove('text-orange-600', 'border-orange-600', 'bg-orange-50/50');
        document.getElementById('tab-reviewed').classList.add('text-gray-500', 'border-transparent');
    } else {
        document.getElementById('content-unreviewed').classList.add('hidden');
        document.getElementById('content-reviewed').classList.remove('hidden');
        
        document.getElementById('tab-reviewed').classList.add('text-orange-600', 'border-orange-600', 'bg-orange-50/50');
        document.getElementById('tab-reviewed').classList.remove('text-gray-500', 'border-transparent');
        
        document.getElementById('tab-unreviewed').classList.remove('text-orange-600', 'border-orange-600', 'bg-orange-50/50');
        document.getElementById('tab-unreviewed').classList.add('text-gray-500', 'border-transparent');
    }
}
</script>
    </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
