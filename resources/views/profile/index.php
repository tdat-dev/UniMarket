<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<main class="bg-gray-50 min-h-screen pb-12">
    <!-- Profile Header / Cover -->
    <div class="h-48 bg-gradient-to-r from-cyan-500 to-blue-500 relative">
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16">
        <!-- User Info Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="px-6 py-6 md:flex md:items-center md:justify-between">
                <div class="flex items-center">
                    <div class="relative flex-shrink-0">
                        <img class="h-24 w-24 rounded-full ring-4 ring-white bg-white object-cover" src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user']['full_name']) ?>&background=random&size=128" alt="">
                         <button class="absolute bottom-0 right-0 bg-gray-100 p-1.5 rounded-full text-gray-600 hover:text-blue-600 border border-white shadow-sm" title="Đổi ảnh đại diện">
                            <i class="fa-solid fa-camera text-xs"></i>
                        </button>
                    </div>
                    <div class="ml-5">
                        <h1 class="text-2xl font-bold text-gray-900"><?= htmlspecialchars($_SESSION['user']['full_name']) ?></h1>
                        <p class="text-sm text-gray-500">Thành viên từ 2024</p>
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
                        <span class="block text-lg font-bold text-gray-800">0đ</span>
                        <span class="text-xs text-gray-500 uppercase tracking-wide">Số dư</span>
                    </div>
                </div>
            </div>

            <!-- Navigation Tabs -->
            <div class="bg-gray-50 px-6 border-t border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <a href="/profile" class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                        <i class="fa-regular fa-id-card"></i> Thông tin
                    </a>
                    <a href="/wallet" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
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
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <h2 class="text-lg font-medium text-gray-900 mb-6 pb-2 border-b">Thông tin cá nhân</h2>
            
            <form action="/profile/update" method="POST" class="max-w-3xl">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tên đăng nhập</label>
                        <input type="text" value="<?= htmlspecialchars($_SESSION['user']['username'] ?? 'user123') ?>" disabled class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-500 shadow-sm focus:outline-none sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Họ và tên</label>
                        <input type="text" name="fullname" value="<?= htmlspecialchars($_SESSION['user']['full_name']) ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($_SESSION['user']['email'] ?? 'email@example.com') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại</label>
                        <input type="tel" name="phone" value="<?= htmlspecialchars($_SESSION['user']['phone'] ?? '09xxxxxxxx') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>

                    <div class="md:col-span-2">
                         <label class="block text-sm font-medium text-gray-700 mb-2">Giới tính</label>
                         <div class="flex gap-6">
                             <div class="flex items-center">
                                 <input id="gender-male" name="gender" type="radio" value="male" checked class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                 <label for="gender-male" class="ml-2 block text-sm text-gray-700">Nam</label>
                             </div>
                             <div class="flex items-center">
                                 <input id="gender-female" name="gender" type="radio" value="female" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                 <label for="gender-female" class="ml-2 block text-sm text-gray-700">Nữ</label>
                             </div>
                             <div class="flex items-center">
                                 <input id="gender-other" name="gender" type="radio" value="other" class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500">
                                 <label for="gender-other" class="ml-2 block text-sm text-gray-700">Khác</label>
                             </div>
                         </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="button" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mr-3">Hủy</button>
                    <button type="submit" class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
