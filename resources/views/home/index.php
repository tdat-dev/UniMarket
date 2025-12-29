<?php 
include __DIR__ . '/../partials/head.php';
include __DIR__ . '/../partials/header.php'; 
?>

<main class="bg-gray-100 min-h-screen pb-10">
    <div class="max-w-[1200px] mx-auto px-4 pt-8 space-y-6">

        <!-- DANH MỤC -->
        <div class="bg-white rounded-sm shadow-sm">
            <div class="h-[60px] px-5 flex items-center border-b border-gray-100">
                <h2 class="text-gray-500 font-medium uppercase text-base">DANH MỤC</h2>
            </div>
            <div class="p-0 relative group">
                <!-- Navigation Arrows (Hidden by default, show on hover if needed, but for now static grid) -->

                <div class="grid grid-cols-10 gap-0">
                    <?php
                    $categories = [
                        ['name' => 'Thời Trang Nam', 'img' => 'https://placehold.co/80x80/png?text=Ao+Nam'],
                        ['name' => 'Điện Thoại', 'img' => 'https://placehold.co/80x80/png?text=Dien+Thoai'],
                        ['name' => 'Điện Tử', 'img' => 'https://placehold.co/80x80/png?text=TV'],
                        ['name' => 'Laptop', 'img' => 'https://placehold.co/80x80/png?text=Laptop'],
                        ['name' => 'Máy Ảnh', 'img' => 'https://placehold.co/80x80/png?text=Camera'],
                        ['name' => 'Đồng Hồ', 'img' => 'https://placehold.co/80x80/png?text=Dong+Ho'],
                        ['name' => 'Giày Dép', 'img' => 'https://placehold.co/80x80/png?text=Giay'],
                        ['name' => 'Gia Dụng', 'img' => 'https://placehold.co/80x80/png?text=Am+Sieu+Toc'],
                        ['name' => 'Thể Thao', 'img' => 'https://placehold.co/80x80/png?text=Bong+Da'],
                        ['name' => 'Xe Cộ', 'img' => 'https://placehold.co/80x80/png?text=Xe+May'],
                        ['name' => 'Thời Trang Nữ', 'img' => 'https://placehold.co/80x80/png?text=Ao+Nu'],
                        ['name' => 'Mẹ & Bé', 'img' => 'https://placehold.co/80x80/png?text=Ghe+An'],
                        ['name' => 'Nhà Cửa', 'img' => 'https://placehold.co/80x80/png?text=Noi'],
                        ['name' => 'Sắc Đẹp', 'img' => 'https://placehold.co/80x80/png?text=Son'],
                        ['name' => 'Sức Khỏe', 'img' => 'https://placehold.co/80x80/png?text=Thuoc'],
                        ['name' => 'Giày Nữ', 'img' => 'https://placehold.co/80x80/png?text=Giay+Cao+Got'],
                        ['name' => 'Túi Ví', 'img' => 'https://placehold.co/80x80/png?text=Tui+Xach'],
                        ['name' => 'Phụ Kiện', 'img' => 'https://placehold.co/80x80/png?text=That+Lung'],
                        ['name' => 'Sách', 'img' => 'https://placehold.co/80x80/png?text=Sach'],
                        ['name' => 'Khác', 'img' => 'https://placehold.co/80x80/png?text=Khac'],
                    ];
                    foreach ($categories as $cat):
                    ?>
                    <a href="#"
                        class="flex flex-col items-center justify-center h-[150px] border-r border-b border-gray-50 hover:shadow-md transition-shadow group/item">
                        <div
                            class="w-[70%] aspect-square rounded-full overflow-hidden mb-2 transition-transform group-hover/item:-translate-y-1">
                            <img src="<?= $cat['img'] ?>" alt="<?= $cat['name'] ?>" class="w-full h-full object-cover">
                        </div>
                        <span class="text-[13px] text-gray-800 text-center px-2 leading-4"><?= $cat['name'] ?></span>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- TÌM KIẾM HÀNG ĐẦU -->
        <div class="bg-white rounded-sm shadow-sm p-5">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-[#2C67C8] font-medium uppercase text-base">TÌM KIẾM HÀNG ĐẦU</h2>
                <a href="#" class="text-[#2C67C8] text-sm flex items-center gap-1">
                    Xem Tất Cả <i class="fa-solid fa-chevron-right text-xs"></i>
                </a>
            </div>

            <div class="grid grid-cols-6 gap-4">
                <?php
                $topProducts = [
                    ['name' => 'Áo Ngực Không Dây', 'sold' => '45k+', 'img' => 'https://placehold.co/200x200/png?text=Ao+Nguc'],
                    ['name' => 'Áo Thun', 'sold' => '83k+', 'img' => 'https://placehold.co/200x200/png?text=Ao+Thun'],
                    ['name' => 'Bao Cao Su', 'sold' => '54k+', 'img' => 'https://placehold.co/200x200/png?text=Bao+Cao+Su'],
                    ['name' => 'Áo Lót Nữ Không Gọng', 'sold' => '66k+', 'img' => 'https://placehold.co/200x200/png?text=Ao+Lot'],
                    ['name' => 'Bút Mực Gel', 'sold' => '77k+', 'img' => 'https://placehold.co/200x200/png?text=But+Bi'],
                    ['name' => 'Áo Babydoll Nữ Tay Bèo', 'sold' => '71k+', 'img' => 'https://placehold.co/200x200/png?text=Ao+Babydoll'],
                ];
                foreach ($topProducts as $prod):
                ?>
                <a href="#" class="block relative group">
                    <div class="relative aspect-square bg-gray-100 mb-3 overflow-hidden">
                        <!-- HOT Badge -->
                        <div class="absolute top-0 left-0 z-10 w-8 h-10 bg-gradient-to-b from-yellow-400 to-red-600 flex flex-col items-center justify-start pt-1"
                            style="clip-path: polygon(0 0, 100% 0, 100% 100%, 50% 85%, 0 100%);">
                            <span class="text-white font-bold text-[10px] leading-3">HOT</span>
                            <i class="fa-solid fa-fire text-white text-[10px]"></i>
                        </div>

                        <img src="<?= $prod['img'] ?>" alt="<?= $prod['name'] ?>" class="w-full h-full object-cover">

                        <!-- Sold Overlay -->
                        <div class="absolute bottom-0 left-0 w-full bg-gray-400/80 py-1">
                            <p class="text-white text-center text-xs font-medium">Bán <?= $prod['sold'] ?> / tháng</p>
                        </div>
                    </div>
                    <h3 class="text-gray-800 text-base font-medium capitalize line-clamp-2"><?= $prod['name'] ?></h3>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- GỢI Ý HÔM NAY -->
        <div class="mt-10">
            <div class="bg-white sticky top-[110px] z-40 border-b border-gray-200">
                <div class="flex justify-center">
                    <div class="py-4 px-10 border-b-4 border-[#2C67C8] cursor-pointer">
                        <h2 class="text-[#2C67C8] font-medium uppercase text-base">GỢI Ý HÔM NAY</h2>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-6 gap-3 mt-4">
                <?php
                // Generate some random products for suggestions
                for($i=1; $i<=12; $i++):
                ?>
                <a href="#"
                    class="bg-white hover:shadow-md hover:-translate-y-[1px] transition-all duration-100 rounded-sm overflow-hidden border border-transparent hover:border-[#2C67C8]">
                    <div class="aspect-square relative">
                        <img src="https://placehold.co/200x200/png?text=Product+<?= $i ?>"
                            class="w-full h-full object-cover">
                        <?php if($i % 3 == 0): ?>
                        <div class="absolute top-0 right-0 bg-yellow-400 text-white text-xs font-bold px-1 py-0.5">
                            -43%
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-2">
                        <div class="text-xs text-gray-800 line-clamp-2 mb-2 min-h-[32px]">
                            Sản phẩm demo gợi ý hôm nay chất lượng cao <?= $i ?>
                        </div>
                        <div class="flex justify-between items-end">
                            <div class="text-[#ee4d2d] text-base font-medium">
                                <span
                                    class="text-xs underline">đ</span><?= number_format(rand(10000, 500000), 0, ',', '.') ?>
                            </div>
                            <div class="text-xs text-gray-500">Đã bán <?= rand(10, 999) ?></div>
                        </div>
                    </div>
                </a>
                <?php endfor; ?>
            </div>

            <div class="flex justify-center mt-8">
                <a href="#"
                    class="bg-white border border-gray-300 text-gray-600 px-10 py-2 hover:bg-gray-50 transition-colors rounded-sm text-sm">
                    Xem Thêm
                </a>
            </div>
        </div>

    </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>