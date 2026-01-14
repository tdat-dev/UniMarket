<aside class="w-64 bg-gradient-to-b from-slate-800 to-slate-900 text-white flex-shrink-0">
    <!-- Logo -->
    <div class="h-16 flex items-center justify-center border-b border-slate-700">
        <a href="/admin" class="text-xl font-bold">
            <i class="fa-solid fa-store mr-2"></i>Zoldify Admin
        </a>
    </div>

    <!-- Navigation -->
    <nav class="mt-6 px-3">
        <div class="text-xs text-slate-400 uppercase tracking-wider mb-3 px-3">Menu chính</div>

        <!-- Dashboard -->
        <a href="/admin/dashboard"
            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm <?= strpos($_SERVER['REQUEST_URI'], '/admin/dashboard') !== false || $_SERVER['REQUEST_URI'] === '/admin' ? 'active' : '' ?>">
            <i class="fa-solid fa-chart-pie w-5"></i>
            <span>Dashboard</span>
        </a>

        <!-- Users -->
        <a href="/admin/users"
            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm <?= strpos($_SERVER['REQUEST_URI'], '/admin/users') !== false ? 'active' : '' ?>">
            <i class="fa-solid fa-users w-5"></i>
            <span>Quản lý Users</span>
        </a>

        <!-- Products -->
        <a href="/admin/products"
            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm <?= strpos($_SERVER['REQUEST_URI'], '/admin/products') !== false ? 'active' : '' ?>">
            <i class="fa-solid fa-box w-5"></i>
            <span>Quản lý Sản phẩm</span>
        </a>

        <!-- Categories -->
        <a href="/admin/categories"
            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm <?= strpos($_SERVER['REQUEST_URI'], '/admin/categories') !== false ? 'active' : '' ?>">
            <i class="fa-solid fa-folder w-5"></i>
            <span>Danh mục</span>
        </a>

        <!-- Orders -->
        <a href="/admin/orders"
            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm <?= strpos($_SERVER['REQUEST_URI'], '/admin/orders') !== false ? 'active' : '' ?>">
            <i class="fa-solid fa-shopping-cart w-5"></i>
            <span>Đơn hàng</span>
        </a>

        <div class="border-t border-slate-700 my-4"></div>

        <!-- Report Management -->
        <a href="/admin/reports"
          class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm
          <?= str_contains($_SERVER['REQUEST_URI'], '/admin/reports') ? 'active' : '' ?>">
          <i class="fa-solid fa-flag w-5"></i>
          <span>Báo cáo vi phạm</span>
        </a>


        <!-- Settings -->
        <a href="/admin/settings" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm">
            <i class="fa-solid fa-cog w-5"></i>
            <span>Cài đặt</span>
        </a>

        <!-- Back to Site -->
        <a href="/"
            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm text-slate-400 hover:text-white">
            <i class="fa-solid fa-arrow-left w-5"></i>
            <span>Về trang chủ</span>
        </a>
    </nav>
</aside>