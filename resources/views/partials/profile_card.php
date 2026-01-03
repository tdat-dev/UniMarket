<div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
    <div class="px-6 pb-6 pt-2 md:flex md:items-end md:justify-between">
        <div class="flex items-end">
            <div class="relative flex-shrink-0 -mt-16">
                <?php 
                    $avatarUrl = !empty($_SESSION['user']['avatar']) 
                        ? '/uploads/avatars/' . $_SESSION['user']['avatar']
                        : 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['user']['full_name']) . '&background=random&size=128';
                ?>
                <img id="avatar-preview" class="h-32 w-32 rounded-full ring-4 ring-white bg-white object-cover" style="width: 128px; height: 128px;" src="<?= htmlspecialchars($avatarUrl) ?>" alt="">
                
                <!-- Avatar Upload Form -->
                <form id="avatar-form" action="/profile/avatar" method="POST" enctype="multipart/form-data" class="hidden">
                    <input type="file" name="avatar" id="avatar-input" accept="image/*" onchange="document.getElementById('avatar-form').submit()">
                </form>

                 <button onclick="document.getElementById('avatar-input').click()" class="absolute bottom-0 right-0 bg-gray-100 p-1.5 rounded-full text-gray-600 hover:text-blue-600 border border-white shadow-sm transition" title="Đổi ảnh đại diện">
                    <i class="fa-solid fa-camera text-xs"></i>
                </button>
            </div>
            <div class="mb-2" style="margin-left: 30px;">
                <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($_SESSION['user']['full_name']) ?></h1>
                <p class="text-sm text-gray-500">Thành viên từ <?= date('Y', strtotime($_SESSION['user']['created_at'] ?? 'now')) ?></p>
            </div>
        </div>
        <!-- Stats / Actions -->
        <div class="mt-4 md:mt-0 flex items-center gap-6">
            <div class="text-center">
                <span class="block text-lg font-bold text-gray-800">0</span>
                <span class="text-xs text-gray-500 uppercase tracking-wide">Đơn hàng</span>
            </div>
            <div class="text-center">
                <span class="block text-lg font-bold text-gray-800">0</span>
                <span class="text-xs text-gray-500 uppercase tracking-wide">Đánh giá</span>
            </div>
             <div class="text-center">
                <span class="block text-lg font-bold text-gray-800"><?= number_format($_SESSION['user']['balance'] ?? 0, 0, ',', '.') ?>đ</span>
                <span class="text-xs text-gray-500 uppercase tracking-wide">Số dư</span>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="bg-gray-50 px-6 border-t border-gray-200">
        <nav class="-mb-px flex gap-8" aria-label="Tabs">
            <a href="/profile" class="<?= ($activeTab ?? '') == 'info' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                <i class="fa-regular fa-id-card"></i> Thông tin
            </a>
            <a href="/wallet" class="<?= ($activeTab ?? '') == 'wallet' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                 <i class="fa-solid fa-wallet"></i> Ví & Giao dịch
            </a>
            <a href="/reviews" class="<?= ($activeTab ?? '') == 'reviews' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                 <i class="fa-regular fa-star"></i> Đánh giá
            </a>
            <a href="/profile/orders" class="<?= ($activeTab ?? '') == 'orders' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                 <i class="fa-solid fa-bag-shopping"></i> Đơn mua
            </a>
            <a href="/shop/orders" class="<?= ($activeTab ?? '') == 'shop_orders' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                 <i class="fa-solid fa-store"></i> Đơn bán
            </a>
        </nav>
    </div>
</div>
