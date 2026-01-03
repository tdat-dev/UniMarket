<?php
include __DIR__ . '/../partials/head.php';
include __DIR__ . '/../partials/header.php';
?>

<main class="bg-gray-100 min-h-screen py-8">
    <div class="max-w-3xl mx-auto px-4">
        <!-- Card Container -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                    <i class="fa-solid fa-plus-circle"></i>
                    Đăng tin bán sản phẩm
                </h2>
                <p class="text-blue-200 text-sm mt-1">Điền thông tin để đăng bán sản phẩm của bạn</p>
            </div>

            <!-- Form -->
            <form action="/products/store" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                
                <?php if (isset($errors) && !empty($errors)): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
                        <div class="flex items-start">
                            <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5 mr-2"></i>
                            <div>
                                <h3 class="text-red-800 font-semibold text-sm">Có lỗi xảy ra:</h3>
                                <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= htmlspecialchars($error) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Tên sản phẩm -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">
                        Tên sản phẩm <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" required 
                        value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                        placeholder="VD: Giày thể thao Nike Air Max"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>

                <!-- Giá -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">
                        Giá bán (VNĐ) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="price" required min="0" step="1000"
                        value="<?= htmlspecialchars($old['price'] ?? '') ?>"
                        placeholder="VD: 500000"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>

                <!-- Số lượng -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">
                        Số lượng <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="quantity" required min="1" value="1"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>

                <!-- Danh mục -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">
                        Danh mục <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <option value="">-- Chọn danh mục --</option>
                        <option value="1">Sách</option>
                        <option value="2">Đồ điện tử</option>
                        <option value="3">Đồ học tập</option>
                        <option value="4">Thời trang</option>
                        <option value="5">Phụ kiện</option>
                        <option value="6">Khác</option>
                    </select>
                </div>

                <!-- Tình trạng -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">
                        Tình trạng
                    </label>
                    <input type="text" name="condition" maxlength="30"
                        placeholder="VD: Mới 100%, Đã qua sử dụng..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>

                <!-- Mô tả -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">
                        Mô tả sản phẩm
                    </label>
                    <textarea name="description" rows="5"
                        placeholder="Nhập mô tả chi tiết về sản phẩm: tình trạng, nguồn gốc, lý do bán..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
                </div>

                <!-- Hình ảnh -->
                <div class="space-y-2">
                    <label class="block text-sm font-semibold text-gray-700">
                        Hình ảnh sản phẩm <span class="text-red-500">*</span>
                    </label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 transition-colors">
                        <input type="file" name="images[]" accept="image/*" multiple required
                            class="hidden" id="imageInput">
                        <label for="imageInput" class="cursor-pointer">
                            <i class="fa-solid fa-cloud-arrow-up text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-600 font-medium">Click để chọn ảnh</p>
                            <p class="text-gray-400 text-sm mt-1">Hỗ trợ nhiều ảnh, tối đa 5MB/ảnh</p>
                        </label>
                        <div id="imagePreview" class="mt-4 grid grid-cols-4 gap-2"></div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-4 border-t">
                    <button type="submit" 
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md transition-colors flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i>
                        Đăng tin
                    </button>
                    <a href="/" 
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-colors flex items-center justify-center gap-2">
                        <i class="fa-solid fa-xmark"></i>
                        Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
// Preview images
document.getElementById('imageInput').addEventListener('change', function(e) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    
    Array.from(e.target.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const div = document.createElement('div');
            div.className = 'relative group';
            div.innerHTML = `
                <img src="${e.target.result}" class="w-full h-24 object-cover rounded border border-gray-200">
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all rounded flex items-center justify-center">
                    <i class="fa-solid fa-check-circle text-white text-2xl opacity-0 group-hover:opacity-100"></i>
                </div>
            `;
            preview.appendChild(div);
        }
        reader.readAsDataURL(file);
    });
});
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>