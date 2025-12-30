<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$current_page = $_SERVER['REQUEST_URI'];
// Kiểm tra nếu không phải trang login hoặc register thì mới cho sticky
$is_auth_page = (strpos($current_page, '/login') !== false || strpos($current_page, '/register') !== false);
?>

<!-- Phần này sẽ bị cuốn đi khi cuộn trang -->
<div class="w-full bg-gray-100 border-b border-gray-200 hidden md:block">
    <div class="max-w-[1200px] mx-auto px-4">
        <div class="h-[34px] flex items-center justify-end gap-6 text-[13px] text-gray-600">
            <a href="#" class="flex items-center gap-1 hover:text-[#2C67C8] transition-colors">
                <i class="fa-regular fa-bell"></i>
                <span>Thông Báo</span>
            </a>
            <a href="#" class="flex items-center gap-1 hover:text-[#2C67C8] transition-colors">
                <i class="fa-regular fa-circle-question"></i>
                <span>Hỗ Trợ</span>
            </a>
            <div class="flex items-center gap-3">
                <?php if (isset($_SESSION['user'])): ?>
                    <div class="flex items-center gap-3">
                        <span class="text-gray-600 font-medium">
                            Chào, <?= htmlspecialchars($_SESSION['user']['full_name']) ?>
                        </span>

                        <form action="/logout" method="POST" class="inline-block m-0 p-0">
                            <button type="submit"
                                class="hover:text-[#2C67C8] font-medium transition-colors text-red-500 bg-transparent border-none cursor-pointer flex items-center">
                                <i class="fa-solid fa-right-from-bracket mr-1"></i> Đăng xuất
                            </button>
                        </form>
                    </div>

                <?php else: ?>
                    <a href="/register" class="hover:text-[#2C67C8] font-medium transition-colors">Đăng Ký</a>
                    <span class="h-[14px] w-[1px] bg-gray-300"></span>
                    <a href="/login" class="hover:text-[#2C67C8] font-medium transition-colors">Đăng Nhập</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Thẻ Header này sẽ dính vào đỉnh màn hình khi cuộn xuống -->
<header class="w-full z-50 bg-white font-sans shadow-sm <?= !$is_auth_page ? 'sticky top-0' : '' ?>">
    <div class="bg-white pb-3">
        <div class="max-w-[1200px] mx-auto px-4 pt-4">
            <div class="flex flex-col md:flex-row items-center gap-4 md:gap-8">

                <a href="/" class="flex items-center gap-2 flex-shrink-0 group no-underline">
                    <img src="/images/logo.png" alt="Unizify" class="h-16 w-auto object-contain">
                </a>

                <div class="flex-1 relative">
                    <form action="/search" method="GET"
                        class="flex h-[44px] border border-gray-300 rounded-lg overflow-hidden hover:border-[#2C67C8] focus-within:border-[#2C67C8] focus-within:ring-2 focus-within:ring-[#2C67C8]/20 transition-all">
                        <input type="text" name="q" id="search-input"
                            placeholder="Tìm giáo trình, đồ gia dụng, quần áo..."
                            value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" autocomplete="off"
                            class="flex-1 px-4 text-sm text-[#333] placeholder-gray-400 focus:outline-none bg-transparent">

                        <button type="submit"
                            class="w-[70px] bg-gradient-to-b from-[#2C67C8] to-[#1990AA] flex items-center justify-center hover:opacity-90 transition-opacity">
                            <i class="fa-solid fa-magnifying-glass text-white text-lg"></i>
                        </button>
                    </form>

                    <!-- Dropdown gợi ý -->
                    <div id="search-suggestions"
                        class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg mt-1 hidden z-50 max-h-[300px] overflow-y-auto">
                    </div>

                    <div class="flex flex-wrap gap-x-4 mt-2 text-xs text-gray-500 pl-1 justify-center md:justify-start">
                        <a href="/search?q=Sục Crocs" class="hover:text-[#2C67C8]">Sục Crocs</a>
                        <a href="/search?q=Áo Khoác" class="hover:text-[#2C67C8]">Áo Khoác</a>
                        <a href="/search?q=Giáo trình C++" class="hidden sm:inline hover:text-[#2C67C8]">Giáo trình
                            C++</a>
                    </div>
                </div>

                <div
                    class="flex items-center gap-4 md:gap-8 flex-shrink-0 w-full md:w-auto justify-center md:justify-end">

                    <a href="/cart" class="relative group p-1 hidden md:block">
                        <i
                            class="fa-solid fa-cart-shopping text-gray-600 text-2xl group-hover:text-[#2C67C8] transition-colors"></i>
                        <?php
                        $cartCount = 0;
                        if (isset($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $qty) {
                                $cartCount += $qty;
                            }
                        }
                        ?>
                        <?php if ($cartCount > 0): ?>
                            <span
                                class="absolute -top-1 -right-2 bg-[#EE4D2D] text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border border-white">
                                <?= $cartCount > 99 ? '99+' : $cartCount ?>
                            </span>
                        <?php endif; ?>
                    </a>

                    <a href="#"
                        class="w-full md:w-auto justify-center px-6 py-2.5 bg-gradient-to-r from-[#2C67C8] to-[#1990AA] text-white font-bold rounded-lg shadow-md hover:shadow-lg transition-all transform flex items-center gap-2 no-underline">
                        <i class="fa-solid fa-plus text-sm"></i>
                        <span>Đăng Bán</span>
                    </a>
                </div>

            </div>
        </div>
    </div>
</header>

</html>