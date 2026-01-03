<header class="h-16 bg-white shadow-sm flex items-center justify-between px-6">
    <!-- Left: Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <i class="fa-solid fa-home"></i>
        <span>/</span>
        <span class="text-gray-800 font-medium">
            <?= $title ?? 'Dashboard' ?>
        </span>
    </div>

    <!-- Right: User menu -->
    <div class="flex items-center gap-4">
        <!-- Notifications -->
        <button class="relative p-2 text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-bell"></i>
            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>

        <!-- User dropdown -->
        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <div class="text-sm font-medium text-gray-800">
                    <?= $_SESSION['user']['full_name'] ?? 'Admin' ?>
                </div>
                <div class="text-xs text-gray-500">Administrator</div>
            </div>
            <div
                class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-medium">
                <?= strtoupper(substr($_SESSION['user']['full_name'] ?? 'A', 0, 1)) ?>
            </div>

            <!-- Logout -->
            <form action="/logout" method="POST" class="inline">
                <button type="submit" class="p-2 text-gray-500 hover:text-red-500" title="Đăng xuất">
                    <i class="fa-solid fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</header>