<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<main class="bg-gray-50 min-h-screen pb-12">
    <!-- Profile Header / Cover -->
    <div class="h-48 bg-gradient-to-r from-cyan-500 to-blue-500 relative">
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16">
        <!-- User Info Card -->
        <!-- User Info Card -->
        <?php $activeTab = 'info'; include __DIR__ . '/../partials/profile_card.php'; ?>


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
                        <input type="tel" name="phone" value="<?= htmlspecialchars($_SESSION['user']['phone_number'] ?? $_SESSION['user']['phone'] ?? '') ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
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
