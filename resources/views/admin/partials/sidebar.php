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
        <a href="/admin/reports" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm
          <?= str_contains($_SERVER['REQUEST_URI'], '/admin/reports') ? 'active' : '' ?>">
            <i class="fa-solid fa-flag w-5"></i>
            <span>Báo cáo vi phạm</span>
        </a>

        <div class="border-t border-slate-700 my-4"></div>
        <div class="text-xs text-slate-400 uppercase tracking-wider mb-3 px-3">Tài chính</div>

        <!-- Wallet Management -->
        <a href="/admin/wallets"
            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm <?= str_contains($_SERVER['REQUEST_URI'], '/admin/wallets') && !str_contains($_SERVER['REQUEST_URI'], 'withdrawals') && !str_contains($_SERVER['REQUEST_URI'], 'escrow') ? 'active' : '' ?>">
            <i class="fa-solid fa-wallet w-5"></i>
            <span>Quản lý Ví</span>
        </a>

        <!-- Withdrawals -->
        <a href="/admin/wallets/withdrawals"
            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm <?= str_contains($_SERVER['REQUEST_URI'], '/withdrawals') ? 'active' : '' ?>">
            <i class="fa-solid fa-money-bill-transfer w-5"></i>
            <span>Yêu cầu Rút tiền</span>
        </a>

        <!-- Escrow -->
        <a href="/admin/wallets/escrow"
            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm <?= str_contains($_SERVER['REQUEST_URI'], '/escrow') ? 'active' : '' ?>">
            <i class="fa-solid fa-lock w-5"></i>
            <span>Escrow</span>
        </a>

        <div class="border-t border-slate-700 my-4"></div>
        <div class="text-xs text-slate-400 uppercase tracking-wider mb-3 px-3">Khác</div>

        <!-- Reviews -->
        <a href="/admin/reviews"
            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm <?= str_contains($_SERVER['REQUEST_URI'], '/admin/reviews') ? 'active' : '' ?>">
            <i class="fa-solid fa-star w-5"></i>
            <span>Đánh giá</span>
        </a>

        <!-- Analytics -->
        <a href="/admin/analytics"
            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm <?= str_contains($_SERVER['REQUEST_URI'], '/admin/analytics') ? 'active' : '' ?>">
            <i class="fa-solid fa-chart-simple w-5"></i>
            <span>Phân tích</span>
        </a>

        <!-- Notifications -->
        <a href="/admin/notifications/broadcast"
            class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg mb-1 text-sm <?= str_contains($_SERVER['REQUEST_URI'], '/admin/notifications') ? 'active' : '' ?>">
            <i class="fa-solid fa-bullhorn w-5"></i>
            <span>Gửi thông báo</span>
        </a>

        <div class="border-t border-slate-700 my-4"></div>

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