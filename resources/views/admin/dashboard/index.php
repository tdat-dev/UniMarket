<!-- Dashboard Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    <!-- Total Users -->
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Tổng Users</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?= number_format($stats['total_users']) ?></h3>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-users text-blue-500 text-xl"></i>
            </div>
        </div>
        <p class="text-xs text-green-500 mt-3">
            <i class="fa-solid fa-arrow-up mr-1"></i>12% so với tháng trước
        </p>
    </div>

    <!-- Total Products -->
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Tổng Sản phẩm</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?= number_format($stats['total_products']) ?></h3>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-box text-green-500 text-xl"></i>
            </div>
        </div>
        <p class="text-xs text-green-500 mt-3">
            <i class="fa-solid fa-arrow-up mr-1"></i>8% so với tháng trước
        </p>
    </div>

    <!-- Total Orders -->
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-orange-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Đơn hàng</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?= number_format($stats['total_orders']) ?></h3>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-shopping-cart text-orange-500 text-xl"></i>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-3">
            <i class="fa-solid fa-minus mr-1"></i>Chưa có dữ liệu
        </p>
    </div>

    <!-- Total Revenue -->
    <div class="bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">Doanh thu</p>
                <h3 class="text-3xl font-bold text-gray-800 mt-1"><?= number_format($stats['total_revenue']) ?>đ</h3>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-dollar-sign text-purple-500 text-xl"></i>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-3">
            <i class="fa-solid fa-minus mr-1"></i>Chưa có dữ liệu
        </p>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-xl shadow-sm p-6 mb-8">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Hành động nhanh</h2>
    <div class="flex gap-4">
        <a href="/admin/products/create"
            class="flex items-center gap-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
            <i class="fa-solid fa-plus"></i>
            <span>Thêm sản phẩm</span>
        </a>
        <a href="/admin/users"
            class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
            <i class="fa-solid fa-users"></i>
            <span>Quản lý users</span>
        </a>
        <a href="/admin/orders"
            class="flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
            <i class="fa-solid fa-list"></i>
            <span>Xem đơn hàng</span>
        </a>
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-white rounded-xl shadow-sm p-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Hoạt động gần đây</h2>
    <div class="text-gray-500 text-sm text-center py-8">
        <i class="fa-solid fa-clock text-4xl mb-3 text-gray-300"></i>
        <p>Chưa có hoạt động nào</p>
    </div>
</div>