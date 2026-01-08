<?php
include __DIR__ . '/../partials/head.php';
include __DIR__ . '/../partials/header.php';

// Tìm tên category đang lọc
$currentCatName = '';
if (!empty($categoryId)) {
    foreach ($categories ?? [] as $cat) {
        if ($cat['id'] == $categoryId) {
            $currentCatName = $cat['name'];
            break;
        }
        foreach ($cat['children'] ?? [] as $child) {
            if ($child['id'] == $categoryId) {
                $currentCatName = $child['name'];
                break 2;
            }
        }
    }
}

// Query string cho pagination (giữ tất cả filter khi chuyển trang)
$queryParams = [];
if (!empty($categoryId))
    $queryParams['category_id'] = $categoryId;
if (!empty($sort) && $sort !== 'newest')
    $queryParams['sort'] = $sort;
if (!empty($priceMin))
    $queryParams['price_min'] = $priceMin;
if (!empty($priceMax))
    $queryParams['price_max'] = $priceMax;
$queryString = !empty($queryParams) ? '&' . http_build_query($queryParams) : '';

// Base URL cho sort (giữ category và price filter)
$sortBaseParams = [];
if (!empty($categoryId))
    $sortBaseParams['category_id'] = $categoryId;
if (!empty($priceMin))
    $sortBaseParams['price_min'] = $priceMin;
if (!empty($priceMax))
    $sortBaseParams['price_max'] = $priceMax;
$sortBaseUrl = '/products?' . http_build_query($sortBaseParams);
?>

<main class="bg-gray-100 min-h-screen pb-20 md:pb-10">
    <div class="max-w-[1200px] mx-auto px-4 pt-6">

        <!-- Layout 2 cột: Sidebar + Content -->
        <div class="flex gap-4">

            <!-- ========== SIDEBAR FILTER (Bên trái) ========== -->
            <aside class="w-[190px] flex-shrink-0 hidden lg:block">

                <!-- Tất Cả Danh Mục -->
                <div class="bg-white rounded shadow-sm mb-3">
                    <div class="px-3 py-2.5 border-b font-semibold text-sm text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-list text-xs"></i>
                        Tất Cả Danh Mục
                    </div>
                    <div class="py-2">
                        <?php foreach ($categories ?? [] as $cat): ?>
                            <a href="/products?category_id=<?= $cat['id'] ?>"
                                class="block px-3 py-1.5 text-sm hover:text-[#2C67C8] transition-colors
                                      <?= ($categoryId ?? 0) == $cat['id'] ? 'text-[#2C67C8] font-medium' : 'text-gray-700' ?>">
                                <?= htmlspecialchars($cat['name']) ?>
                            </a>
                            <?php if (!empty($cat['children'])): ?>
                                <?php foreach ($cat['children'] as $child): ?>
                                    <a href="/products?category_id=<?= $child['id'] ?>"
                                        class="block px-3 py-1 pl-6 text-[13px] hover:text-[#2C67C8] transition-colors
                                              <?= ($categoryId ?? 0) == $child['id'] ? 'text-[#2C67C8] font-medium' : 'text-gray-500' ?>">
                                        <?= htmlspecialchars($child['name']) ?>
                                    </a>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Bộ Lọc Tìm Kiếm -->
                <div class="bg-white rounded shadow-sm mb-3">
                    <div class="px-3 py-2.5 border-b font-semibold text-sm text-gray-800 flex items-center gap-2">
                        <i class="fa-solid fa-filter text-xs"></i>
                        Bộ Lọc Tìm Kiếm
                    </div>

                    <!-- Khoảng Giá -->
                    <form method="GET" action="/products" class="p-3 border-b">
                        <!-- Giữ lại category_id và sort khi submit -->
                        <?php if (!empty($categoryId)): ?>
                            <input type="hidden" name="category_id" value="<?= $categoryId ?>">
                        <?php endif; ?>
                        <?php if (!empty($sort) && $sort !== 'newest'): ?>
                            <input type="hidden" name="sort" value="<?= htmlspecialchars($sort) ?>">
                        <?php endif; ?>

                        <div class="text-sm text-gray-700 mb-2">Khoảng Giá</div>
                        <div class="flex gap-2 items-center">
                            <input type="number" name="price_min" placeholder="₫ TỪ" value="<?= $priceMin ?? '' ?>"
                                class="w-full border rounded px-2 py-1.5 text-xs focus:outline-none focus:border-[#2C67C8]">
                            <span class="text-gray-400">-</span>
                            <input type="number" name="price_max" placeholder="₫ ĐẾN" value="<?= $priceMax ?? '' ?>"
                                class="w-full border rounded px-2 py-1.5 text-xs focus:outline-none focus:border-[#2C67C8]">
                        </div>
                        <button type="submit"
                            class="w-full mt-2 bg-[#2C67C8] text-white text-sm py-1.5 rounded hover:bg-[#1e4a8f] transition-colors">
                            ÁP DỤNG
                        </button>
                    </form>

                    <!-- Tình Trạng -->
                    <div class="p-3 border-b">
                        <div class="text-sm text-gray-700 mb-2">Tình Trạng</div>
                        <label
                            class="flex items-center gap-2 text-sm text-gray-600 mb-1.5 cursor-pointer hover:text-[#2C67C8]">
                            <input type="checkbox" class="accent-[#2C67C8]"> Mới
                        </label>
                        <label
                            class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer hover:text-[#2C67C8]">
                            <input type="checkbox" class="accent-[#2C67C8]"> Đã sử dụng
                        </label>
                    </div>

                    <!-- Đánh Giá -->
                    <div class="p-3">
                        <div class="text-sm text-gray-700 mb-2">Đánh Giá</div>
                        <div class="space-y-1">
                            <?php for ($star = 5; $star >= 3; $star--): ?>
                                <label class="flex items-center gap-1 text-sm cursor-pointer hover:text-[#2C67C8]">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i
                                            class="fa-solid fa-star text-xs <?= $i <= $star ? 'text-yellow-400' : 'text-gray-300' ?>"></i>
                                    <?php endfor; ?>
                                    <span class="text-gray-500 text-xs ml-1">trở lên</span>
                                </label>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>

                <!-- Nút Xóa Tất Cả -->
                <?php if (!empty($categoryId)): ?>
                    <a href="/products"
                        class="block w-full text-center bg-[#2C67C8] text-white text-sm py-2 rounded hover:bg-[#1e4a8f] transition-colors">
                        XOÁ TẤT CẢ
                    </a>
                <?php endif; ?>
            </aside>

            <!-- ========== MAIN CONTENT (Bên phải) ========== -->
            <div class="flex-1 min-w-0">

                <!-- Thanh Sort -->
                <div class="bg-[#ededed] rounded px-4 py-2.5 mb-3 flex items-center gap-2 flex-wrap">
                    <span class="text-sm text-gray-600">Sắp xếp theo</span>

                    <a href="<?= $sortBaseUrl ?>&sort=popular"
                        class="px-4 py-1.5 text-sm rounded transition-colors 
                              <?= ($sort ?? 'newest') == 'popular' ? 'bg-[#2C67C8] text-white' : 'bg-white text-gray-700 hover:bg-gray-50' ?>">
                        Phổ Biến
                    </a>
                    <a href="<?= $sortBaseUrl ?>&sort=newest"
                        class="px-4 py-1.5 text-sm rounded transition-colors 
                              <?= ($sort ?? 'newest') == 'newest' ? 'bg-[#2C67C8] text-white' : 'bg-white text-gray-700 hover:bg-gray-50' ?>">
                        Mới Nhất
                    </a>
                    <a href="<?= $sortBaseUrl ?>&sort=bestseller"
                        class="px-4 py-1.5 text-sm rounded transition-colors 
                              <?= ($sort ?? 'newest') == 'bestseller' ? 'bg-[#2C67C8] text-white' : 'bg-white text-gray-700 hover:bg-gray-50' ?>">
                        Bán Chạy
                    </a>
                    <!-- Custom Dropdown Giá -->
                    <div class="relative group">
                        <button type="button" class="px-4 py-1.5 text-sm rounded flex items-center gap-1.5 transition-colors
                                       <?= in_array($sort ?? '', ['price_asc', 'price_desc'])
                                           ? 'bg-[#2C67C8] text-white'
                                           : 'bg-white text-gray-700 hover:bg-gray-50' ?>">
                            <?php if (($sort ?? '') == 'price_asc'): ?>
                                Giá: Thấp → Cao
                            <?php elseif (($sort ?? '') == 'price_desc'): ?>
                                Giá: Cao → Thấp
                            <?php else: ?>
                                Giá
                            <?php endif; ?>
                            <i
                                class="fa-solid fa-chevron-down text-[10px] transition-transform group-hover:rotate-180"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <!-- Dropdown Menu -->
                        <div class="absolute top-full right-0 mt-1 w-56 bg-white rounded shadow-lg border border-gray-200 
                                    opacity-0 invisible group-hover:opacity-100 group-hover:visible 
                                    transition-all duration-200 z-50">
                            <a href="<?= $sortBaseUrl ?>&sort=price_asc" class="block px-4 py-2 text-sm text-gray-700 whitespace-nowrap hover:bg-blue-600 hover:text-white transition-colors
                                      <?= ($sort ?? '') == 'price_asc' ? 'bg-blue-50 text-blue-600' : '' ?>">
                                Giá: Thấp → Cao
                            </a>
                            <a href="<?= $sortBaseUrl ?>&sort=price_desc" class="block px-4 py-2 text-sm text-gray-700 whitespace-nowrap hover:bg-blue-600 hover:text-white transition-colors
                                      <?= ($sort ?? '') == 'price_desc' ? 'bg-blue-50 text-blue-600' : '' ?>">
                                Giá: Cao → Thấp
                            </a>
                        </div>
                    </div>

                    <!-- Phân trang mini -->
                    <div class="ml-auto flex items-center gap-2 text-sm">
                        <span class="text-gray-500"><?= $currentPage ?>/<?= $totalPages ?></span>
                        <div class="flex">
                            <?php if ($currentPage > 1): ?>
                                <a href="/products?page=<?= $currentPage - 1 ?><?= $queryString ?>"
                                    class="w-8 h-8 flex items-center justify-center bg-white border hover:bg-gray-50">
                                    <i class="fa-solid fa-chevron-left text-xs"></i>
                                </a>
                            <?php else: ?>
                                <span class="w-8 h-8 flex items-center justify-center bg-gray-100 border text-gray-300">
                                    <i class="fa-solid fa-chevron-left text-xs"></i>
                                </span>
                            <?php endif; ?>
                            <?php if ($currentPage < $totalPages): ?>
                                <a href="/products?page=<?= $currentPage + 1 ?><?= $queryString ?>"
                                    class="w-8 h-8 flex items-center justify-center bg-white border hover:bg-gray-50">
                                    <i class="fa-solid fa-chevron-right text-xs"></i>
                                </a>
                            <?php else: ?>
                                <span class="w-8 h-8 flex items-center justify-center bg-gray-100 border text-gray-300">
                                    <i class="fa-solid fa-chevron-right text-xs"></i>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Breadcrumb khi đang lọc -->
                <?php if (!empty($categoryId)): ?>
                    <div class="bg-white rounded px-4 py-2 mb-3 text-sm flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <a href="/products" class="text-blue-600 hover:underline">Tất cả</a>
                            <span class="text-gray-400">›</span>
                            <span class="text-gray-700"><?= htmlspecialchars($currentCatName) ?></span>
                        </div>
                        <span class="text-gray-500"><?= count($products) ?> sản phẩm</span>
                    </div>
                <?php endif; ?>

                <!-- Product Grid -->
                <?php if (empty($products)): ?>
                    <div class="bg-white rounded p-10 text-center">
                        <i class="fa-solid fa-box-open text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Không có sản phẩm nào.</p>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-2.5">
                        <?php foreach ($products as $item): ?>
                            <?php include __DIR__ . '/../partials/product_card.php'; ?>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination Bottom -->
                    <div class="flex justify-center mt-8 gap-1">
                        <?php if ($currentPage > 1): ?>
                            <a href="/products?page=<?= $currentPage - 1 ?><?= $queryString ?>"
                                class="w-10 h-10 flex items-center justify-center bg-white border text-gray-600 hover:bg-gray-50 rounded">
                                <i class="fa-solid fa-chevron-left text-xs"></i>
                            </a>
                        <?php endif; ?>

                        <?php
                        // Hiển thị tối đa 5 trang
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($totalPages, $currentPage + 2);
                        ?>

                        <?php if ($startPage > 1): ?>
                            <a href="/products?page=1<?= $queryString ?>"
                                class="w-10 h-10 flex items-center justify-center bg-white border text-gray-600 hover:bg-gray-50 rounded">1</a>
                            <?php if ($startPage > 2): ?>
                                <span class="w-10 h-10 flex items-center justify-center text-gray-400">...</span>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <a href="/products?page=<?= $i ?><?= $queryString ?>"
                                class="w-10 h-10 flex items-center justify-center border rounded transition-colors
                                      <?= $i == $currentPage ? 'bg-[#2C67C8] border-[#2C67C8] text-white' : 'bg-white text-gray-600 hover:bg-gray-50' ?>">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>

                        <?php if ($endPage < $totalPages): ?>
                            <?php if ($endPage < $totalPages - 1): ?>
                                <span class="w-10 h-10 flex items-center justify-center text-gray-400">...</span>
                            <?php endif; ?>
                            <a href="/products?page=<?= $totalPages ?><?= $queryString ?>"
                                class="w-10 h-10 flex items-center justify-center bg-white border text-gray-600 hover:bg-gray-50 rounded"><?= $totalPages ?></a>
                        <?php endif; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <a href="/products?page=<?= $currentPage + 1 ?><?= $queryString ?>"
                                class="w-10 h-10 flex items-center justify-center bg-white border text-gray-600 hover:bg-gray-50 rounded">
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>