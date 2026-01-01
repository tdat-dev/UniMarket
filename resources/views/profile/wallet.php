<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<main class="bg-gray-50 min-h-screen pb-12">
    <!-- Profile Header / Cover -->
    <div class="h-48 bg-gradient-to-r from-emerald-500 to-teal-500 relative">
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16">
        <!-- User Info Card (Simplified for sub-pages) -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="px-6 py-6 md:flex md:items-center md:justify-between">
                <div class="flex items-center">
                    <div class="relative flex-shrink-0">
                        <img class="h-24 w-24 rounded-full ring-4 ring-white bg-white object-cover" src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']['full_name']) ?>&background=random&size=128" alt="">
                    </div>
                    <div class="ml-5">
                        <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($_SESSION['user']['full_name']) ?></h1>
                         <p class="text-sm text-gray-500">Ví của tôi</p>
                    </div>
                </div>
                <div class="mt-4 md:mt-0">
                     <button class="bg-emerald-600 px-4 py-2 rounded-md text-white font-medium text-sm hover:bg-emerald-700 transition shadow-sm">
                         <i class="fa-solid fa-plus mr-1"></i> Nạp tiền ngay
                     </button>
                </div>
            </div>

            <!-- Navigation Tabs -->
            <div class="bg-gray-50 px-6 border-t border-gray-200">
                <nav class="-mb-px flex gap-8" aria-label="Tabs">
                    <a href="/profile" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                        <i class="fa-regular fa-id-card"></i> Thông tin
                    </a>
                    <a href="/wallet" class="border-emerald-500 text-emerald-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                         <i class="fa-solid fa-wallet"></i> Ví & Giao dịch
                    </a>
                    <a href="/reviews" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                         <i class="fa-regular fa-star"></i> Đánh giá
                    </a>
                    <a href="/shop/orders" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                         <i class="fa-solid fa-box"></i> Quản lý đơn hàng
                    </a>
                </nav>
            </div>
        </div>

        <!-- Content Area -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Balance Card -->
            <div class="md:col-span-1">
                <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl shadow-lg p-6 text-white h-full relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full blur-2xl -mr-10 -mt-10"></div>
                    
                    <div class="relative z-10 flex flex-col justify-between h-full min-h-[180px]">
                        <div>
                             <p class="text-gray-400 text-sm font-medium uppercase tracking-wider">Số dư khả dụng</p>
                             <h3 class="text-3xl font-bold mt-1 tracking-tight"><?= number_format($balance, 0, ',', '.') ?> <span class="text-lg text-gray-400 font-normal">VNĐ</span></h3>
                        </div>
                        
                        <div class="flex gap-3 mt-6">
                             <button class="flex-1 bg-white/10 hover:bg-white/20 backdrop-blur-sm border border-white/10 py-2 rounded-lg text-sm font-medium transition cursor-pointer">
                                 Nạp tiền
                             </button>
                             <button class="flex-1 bg-transparent hover:bg-white/5 border border-white/20 py-2 rounded-lg text-sm font-medium transition cursor-pointer">
                                 Rút tiền
                             </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaction History -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800">Lịch sử giao dịch</h3>
                        <a href="#" class="text-sm text-blue-600 hover:underline">Xem tất cả</a>
                    </div>
                    
                    <div class="p-0">
                        <?php if (empty($transactions)): ?>
                        <div class="p-8 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-100 mb-3">
                                 <i class="fa-solid fa-clock-rotate-left text-gray-400"></i>
                            </div>
                            <p class="text-gray-500 text-sm">Chưa có giao dịch nào gần đây.</p>
                        </div>
                        <?php else: ?>
                            <ul class="divide-y divide-gray-100">
                                <?php foreach ($transactions as $t): ?>
                                    <li class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center <?= $t['type'] == 'deposit' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' ?>">
                                                <i class="fa-solid <?= $t['type'] == 'deposit' ? 'fa-arrow-down' : 'fa-arrow-up' ?>"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    <?= $t['type'] == 'deposit' ? 'Nạp tiền vào ví' : ($t['type'] == 'withdraw' ? 'Rút tiền' : 'Thanh toán đơn hàng') ?>
                                                </p>
                                                <p class="text-xs text-gray-500"><?= date('d/m/Y H:i', strtotime($t['created_at'])) ?></p>
                                            </div>
                                        </div>
                                        <span class="font-bold text-sm <?= $t['type'] == 'deposit' || $t['type'] == 'refund' ? 'text-green-600' : 'text-gray-900' ?>">
                                            <?= $t['type'] == 'deposit' || $t['type'] == 'refund' ? '+' : '-' ?><?= number_format($t['amount'], 0, ',', '.') ?>đ
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
