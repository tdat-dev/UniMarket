<?php
include __DIR__ . '/../partials/head.php';
include __DIR__ . '/../partials/header.php';
?>

<main class="bg-gray-100 min-h-screen pb-10">
    <div class="max-w-[1200px] mx-auto px-4 pt-8 space-y-6">

        <div class="bg-white rounded-sm shadow-sm p-5">
            <h1 class="text-xl font-medium text-gray-800 mb-6 uppercase border-l-4 border-[#2C67C8] pl-3">Tất cả sản
                phẩm</h1>

            <?php if (empty($products)): ?>
                <p class="text-gray-500 text-center py-10">Không có sản phẩm nào.</p>
            <?php else: ?>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                    <?php foreach ($products as $item): ?>
                        <?php include __DIR__ . '/../partials/product_card.php'; ?>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <div class="flex justify-center mt-10 gap-2">
                    <?php if ($currentPage > 1): ?>
                        <a href="/products?page=<?= $currentPage - 1 ?>"
                            class="px-3 py-1 bg-white border border-gray-300 text-gray-600 hover:bg-gray-50 rounded-sm">
                            <i class="fa-solid fa-chevron-left"></i>
                        </a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="/products?page=<?= $i ?>"
                            class="px-3 py-1 border rounded-sm transition-colors <?= $i == $currentPage ? 'bg-[#2C67C8] border-[#2C67C8] text-white' : 'bg-white border-gray-300 text-gray-600 hover:bg-gray-50' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <a href="/products?page=<?= $currentPage + 1 ?>"
                            class="px-3 py-1 bg-white border border-gray-300 text-gray-600 hover:bg-gray-50 rounded-sm">
                            <i class="fa-solid fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>