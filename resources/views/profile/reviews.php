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
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
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
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
             <!-- Tabs -->
             <div class="flex border-b border-gray-100">
                <button class="flex-1 py-4 text-center text-orange-600 font-medium border-b-2 border-orange-600 bg-orange-50/50">
                    Chưa đánh giá (0)
                </button>
                 <button class="flex-1 py-4 text-center text-gray-500 font-medium hover:text-orange-600 hover:bg-gray-50 transition-colors">
                    Đã đánh giá (0)
                </button>
            </div>

            <div class="p-0">
                <?php if (empty($reviews)): ?>
                    <div class="p-12 text-center">
                        <div class="w-24 h-24 bg-orange-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fa-regular fa-star text-4xl text-orange-300"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Chưa có đánh giá nào</h3>
                        <p class="text-gray-500 mt-1 mb-6">Hãy mua sắm và chia sẻ trải nghiệm của bạn với mọi người.</p>
                        <a href="/products" class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                            Tiếp tục mua sắm
                        </a>
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
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
