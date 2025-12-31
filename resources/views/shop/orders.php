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
    <div class="h-48 bg-gradient-to-r from-gray-700 to-gray-900 relative">
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
                         <p class="text-sm text-gray-500">Quản lý bán hàng</p>
                    </div>
                </div>
                <div class="mt-4 md:mt-0 flex gap-3">
                     <button class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                         <i class="fa-solid fa-file-export mr-1"></i> Xuất Excel
                     </button>
                      <a href="/products/create" class="px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700 shadow-sm">
                         <i class="fa-solid fa-plus mr-1"></i> Đăng bán mới
                     </a>
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
                    <a href="/reviews" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                         <i class="fa-regular fa-star"></i> Đánh giá
                    </a>
                    <a href="/shop/orders" class="border-gray-800 text-gray-900 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2">
                         <i class="fa-solid fa-box"></i> Quản lý đơn hàng
                    </a>
                </nav>
            </div>
        </div>

        <!-- Content Area -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
             <!-- Order Tabs -->
            <div class="flex border-b border-gray-200 overflow-x-auto">
                <a href="#" class="px-6 py-4 text-sm font-medium text-blue-600 border-b-2 border-blue-600 whitespace-nowrap bg-blue-50/50">
                    Tất cả (2)
                </a>
                <a href="#" class="px-6 py-4 text-sm font-medium text-gray-500 hover:text-blue-600 whitespace-nowrap">
                    Chờ xác nhận (1)
                </a>
                <a href="#" class="px-6 py-4 text-sm font-medium text-gray-500 hover:text-blue-600 whitespace-nowrap">
                    Chờ lấy hàng (0)
                </a>
                <a href="#" class="px-6 py-4 text-sm font-medium text-gray-500 hover:text-blue-600 whitespace-nowrap">
                    Đang giao (1)
                </a>
                <a href="#" class="px-6 py-4 text-sm font-medium text-gray-500 hover:text-blue-600 whitespace-nowrap">
                    Đã giao (0)
                </a>
            </div>

            <!-- Search & Filter -->
            <div class="p-4 bg-gray-50 border-b border-gray-100 flex flex-wrap gap-4">
                <div class="relative flex-1 min-w-[250px]">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" placeholder="Tìm đơn hàng..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

             <!-- Orders List (Mockup) -->
            <div class="divide-y divide-gray-100">
                <!-- Order Item -->
                <div class="p-6 hover:bg-gray-50 transition">
                    <div class="flex flex-wrap justify-between items-start mb-4 gap-2">
                        <div class="flex gap-3 items-center">
                            <span class="font-bold text-blue-600">#ORD-2025-001</span>
                            <span class="text-xs text-gray-500">31/12/2025</span>
                             <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                Chờ xác nhận
                            </span>
                        </div>
                        <div class="text-sm font-bold text-red-600">45.000đ</div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-16 h-16 bg-gray-200 rounded-md flex-shrink-0 overflow-hidden">
                             <img src="https://via.placeholder.com/150" class="w-full h-full object-cover">
                        </div>
                        <div>
                             <h4 class="text-sm font-medium text-gray-900">Giáo trình Giải tích 1</h4>
                             <p class="text-xs text-gray-500 mt-1">x 1</p>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end gap-3">
                         <button class="px-3 py-1.5 border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50">Chi tiết</button>
                         <button class="px-3 py-1.5 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 shadow-sm">Xác nhận</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
