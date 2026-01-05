<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$current_page = $_SERVER['REQUEST_URI'];
// Kiểm tra nếu không phải trang login hoặc register thì mới cho sticky
$is_auth_page = (strpos($current_page, '/login') !== false || strpos($current_page, '/register') !== false);
?>

<!-- Phần này sẽ bị cuốn đi khi cuộn trang -->
<div class="w-full bg-gray-100 border-b border-gray-200 hidden md:block relative" style="z-index: 9999;">
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
                    <div class="relative" id="user-menu-container">
                        <button id="user-menu-btn"
                            class="flex items-center gap-2 text-gray-600 font-medium hover:text-[#2C67C8] transition-colors focus:outline-none">
                            <span>Chào, <?= htmlspecialchars($_SESSION['user']['full_name']) ?></span>
                            <i class="fa-solid fa-caret-down text-xs"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div id="user-menu-dropdown" style="width: 250px;"
                            class="absolute right-0 top-full mt-2 bg-white rounded-lg shadow-xl border border-gray-100 hidden z-50 overflow-hidden">
                            <!-- User Info Section -->
                            <div class="py-2 border-b border-gray-100">
                                <a href="/profile"
                                    class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#2C67C8] transition-colors">
                                    <i class="fa-regular fa-user w-5 text-center"></i>
                                    Hồ sơ của tôi
                                </a>
                                <a href="/chat"
                                    class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#2C67C8] transition-colors">
                                    <i class="fa-regular fa-comment-dots w-5 text-center"></i>
                                    Tin nhắn
                                </a>
                                <a href="/wallet"
                                    class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#2C67C8] transition-colors">
                                    <i class="fa-solid fa-wallet w-5 text-center"></i>
                                    Tiền của tôi
                                </a>
                                <a href="/reviews"
                                    class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#2C67C8] transition-colors">
                                    <i class="fa-regular fa-star w-5 text-center"></i>
                                    Đánh giá của tôi
                                </a>
                                <a href="/profile/orders"
                                    class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#2C67C8] transition-colors">
                                    <i class="fa-solid fa-bag-shopping w-5 text-center"></i>
                                    Đơn mua
                                </a>
                            </div>

                            <!-- Selling Section -->
                            <div class="py-2 border-b border-gray-100">
                                <div class="px-4 py-1 text-xs font-bold text-gray-400 uppercase tracking-wider">Bán hàng
                                </div>
                                <a href="/products/create"
                                    class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#2C67C8] transition-colors">
                                    <i class="fa-solid fa-plus w-5 text-center"></i>
                                    Thêm sản phẩm
                                </a>
                                <a href="/shop"
                                    class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#2C67C8] transition-colors">
                                    <i class="fa-solid fa-box-open w-5 text-center"></i>
                                    Tất cả sản phẩm
                                </a>
                                <a href="/shop/orders"
                                    class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-[#2C67C8] transition-colors">
                                    <i class="fa-solid fa-clipboard-list w-5 text-center"></i>
                                    Đơn bán
                                </a>
                            </div>

                            <!-- Logout -->
                            <div class="py-1 bg-gray-50">
                                <form action="/logout" method="POST" class="m-0 p-0">
                                    <button type="submit"
                                        class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors text-left font-medium">
                                        <i class="fa-solid fa-right-from-bracket w-5 text-center"></i>
                                        Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const menuBtn = document.getElementById('user-menu-btn');
                            const menuDropdown = document.getElementById('user-menu-dropdown');
                            const container = document.getElementById('user-menu-container');

                            if (menuBtn && menuDropdown) {
                                // Toggle click
                                menuBtn.addEventListener('click', function (e) {
                                    e.stopPropagation();
                                    menuDropdown.classList.toggle('hidden');
                                });

                                // Close when clicking outside
                                document.addEventListener('click', function (e) {
                                    if (!container.contains(e.target)) {
                                        menuDropdown.classList.add('hidden');
                                    }
                                });
                            }
                        });
                    </script>


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
<header class="w-full z-[1100] bg-white font-sans shadow-sm <?= !$is_auth_page ? 'sticky top-0' : '' ?>">
    <div class="bg-white pb-3">
        <div class="max-w-[1200px] mx-auto px-4 pt-4">
            <div class="flex flex-col md:flex-row items-center gap-4 md:gap-8">

                <a href="/" class="flex items-center gap-2 flex-shrink-0 group no-underline">
                    <img src="/images/logouni.png" alt="Zoldify" class="h-16 w-auto object-contain">
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
                        <?php if (!empty($topKeywords)): ?>
                            <?php foreach ($topKeywords as $index => $kw): ?>
                                <a href="/search?q=<?= urlencode($kw['keyword']) ?>"
                                    class="<?= $index >= 2 ? 'hidden sm:inline' : '' ?> hover:text-[#2C67C8]">
                                    <?= htmlspecialchars(ucfirst($kw['keyword'])) ?>
                                </a>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div
                    class="flex items-center gap-4 md:gap-8 flex-shrink-0 w-full md:w-auto justify-center md:justify-end">

                    <a href="/cart" class="relative group p-1 hidden md:block">
                        <i
                            class="fa-solid fa-cart-shopping text-gray-600 text-2xl group-hover:text-[#2C67C8] transition-colors"></i>
                        <?php if (($cartCount ?? 0) > 0): ?>
                            <span
                                class="absolute -top-1 -right-2 bg-[#EE4D2D] text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full border border-white">
                                <?= $cartCount > 99 ? '99+' : $cartCount ?>
                            </span>
                        <?php endif; ?>
                    </a>

                    <a href="/products/create"
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