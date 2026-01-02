<?php include __DIR__ . '/../partials/head.php'; ?>
<?php 
if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit;
}
?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<main class="bg-gray-50 min-h-screen pb-12">
    <!-- Profile Header / Cover -->
    <div class="h-48 bg-gradient-to-r from-cyan-500 to-blue-500 relative">
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16">
        <!-- User Info Card -->
        <!-- User Info Card -->
        <?php $activeTab = 'orders'; include __DIR__ . '/../partials/profile_card.php'; ?>


        <!-- Content Area -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
             <!-- Order Tabs -->
            <div class="flex border-b border-gray-200 overflow-x-auto">
                <a href="/profile/orders?status=all" class="px-6 py-4 text-sm font-medium whitespace-nowrap <?= $currentStatus == 'all' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50/50' : 'text-gray-500 hover:text-blue-600' ?>">
                    Tất cả (<?= $counts['all'] ?? 0 ?>)
                </a>
                <a href="/profile/orders?status=pending" class="px-6 py-4 text-sm font-medium whitespace-nowrap <?= $currentStatus == 'pending' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50/50' : 'text-gray-500 hover:text-blue-600' ?>">
                    Chờ xác nhận (<?= $counts['pending'] ?? 0 ?>)
                </a>
                <a href="/profile/orders?status=shipping" class="px-6 py-4 text-sm font-medium whitespace-nowrap <?= $currentStatus == 'shipping' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50/50' : 'text-gray-500 hover:text-blue-600' ?>">
                    Đang giao (<?= $counts['shipping'] ?? 0 ?>)
                </a>
                <a href="/profile/orders?status=completed" class="px-6 py-4 text-sm font-medium whitespace-nowrap <?= $currentStatus == 'completed' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50/50' : 'text-gray-500 hover:text-blue-600' ?>">
                    Đã giao (<?= $counts['completed'] ?? 0 ?>)
                </a>
                <a href="/profile/orders?status=cancelled" class="px-6 py-4 text-sm font-medium whitespace-nowrap <?= $currentStatus == 'cancelled' ? 'text-blue-600 border-b-2 border-blue-600 bg-blue-50/50' : 'text-gray-500 hover:text-blue-600' ?>">
                    Đã hủy (<?= $counts['cancelled'] ?? 0 ?>)
                </a>
            </div>

            <!-- Search & Filter -->
            <div class="p-4 bg-gray-50 border-b border-gray-100 flex flex-wrap gap-4">
                <div class="relative flex-1 min-w-[250px]">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" placeholder="Tìm đơn hàng..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

             <!-- Orders List -->
            <div class="divide-y divide-gray-100">
                <?php if (empty($orders)): ?>
                    <div class="p-12 text-center text-gray-500">
                        <i class="fa-solid fa-basket-shopping text-4xl mb-4 text-gray-300"></i>
                         <p>Bạn chưa mua đơn hàng nào.</p>
                         <a href="/" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">Mua sắm ngay</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                        <div class="p-6 hover:bg-gray-50 transition">
                            <div class="flex flex-wrap justify-between items-start mb-4 gap-2">
                                <div class="flex gap-3 items-center">
                                    <span class="font-bold text-blue-600">#ORD-<?= $order['id'] ?></span>
                                    <span class="text-xs text-gray-500"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span>
                                     <span class="px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        <?= $order['status'] == 'pending' ? 'bg-yellow-100 text-yellow-800' : '' ?>
                                        <?= $order['status'] == 'shipping' ? 'bg-blue-100 text-blue-800' : '' ?>
                                        <?= $order['status'] == 'completed' ? 'bg-green-100 text-green-800' : '' ?>
                                        <?= $order['status'] == 'cancelled' ? 'bg-red-100 text-red-800' : '' ?>
                                     ">
                                        <?php 
                                            // Translate status if needed, or use mapping
                                            $statusMap = [
                                                'pending' => 'Chờ xác nhận',
                                                'shipping' => 'Đang giao',
                                                'completed' => 'Đã giao',
                                                'cancelled' => 'Đã hủy'
                                            ];
                                            echo $statusMap[$order['status']] ?? ucfirst($order['status']);
                                        ?>
                                    </span>
                                </div>
                                <div class="text-sm font-bold text-red-600"><?= number_format($order['total_amount'] ?? 0, 0, ',', '.') ?>đ</div>
                            </div>

                            <div class="flex flex-col gap-3">
                                <?php foreach ($order['items'] as $item): ?>
                                    <div class="flex gap-4">
                                        <div class="w-16 h-16 bg-gray-100 rounded-md flex-shrink-0 overflow-hidden border border-gray-200">
                                             <img src="/uploads/<?= htmlspecialchars($item['product_image'] ?? 'default.png') ?>" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                             <h4 class="text-sm font-medium text-gray-900"><?= htmlspecialchars($item['product_name']) ?></h4>
                                             <p class="text-xs text-gray-500">x <?= $item['quantity'] ?></p>
                                             <p class="text-xs font-medium text-gray-700 mt-1"><?= number_format($item['price'] ?? 0, 0, ',', '.') ?>đ</p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <div class="mt-4 flex justify-end gap-3 border-t pt-4 border-gray-50">
                                 <button class="px-4 py-2 border border-blue-600 text-blue-600 text-sm font-medium rounded-md hover:bg-blue-50">Mua lại</button>
                                 <button class="px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-50">Chi tiết đơn hàng</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
