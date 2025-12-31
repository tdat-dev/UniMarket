<?php include __DIR__ . '/../partials/head.php'; ?>
<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user'])) {
    header('Location: /login');
    exit;
}
?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<main class="bg-[#F8F9FA] min-h-screen py-10">
    <div class="max-w-[1000px] mx-auto px-4">
        
        <div class="mb-8 text-center max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-900">Đăng Bán Sản Phẩm Mới</h1>
            <p class="text-gray-500 mt-2">Chia sẻ sản phẩm của bạn với cộng đồng sinh viên. Hãy điền đầy đủ thông tin để thu hút người mua!</p>
        </div>

        <form action="/products/store" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            
            <!-- Left Column: Main Info -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Section: Basic Info -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                         <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-[#2C67C8]">
                             <i class="fa-solid fa-pen-to-square text-lg"></i>
                         </div>
                         <div>
                             <h3 class="font-bold text-gray-900 text-lg">Thông tin cơ bản</h3>
                             <p class="text-xs text-gray-500">Tên và phân loại sản phẩm</p>
                         </div>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Tên sản phẩm <span class="text-red-500">*</span></label>
                            <input type="text" name="name" placeholder="Ví dụ: Giáo trình Kinh tế lượng - NEU (Mới 99%)" class="w-full px-5 py-3 border border-gray-200 rounded-xl text-sm focus:border-[#2C67C8] focus:ring-4 focus:ring-blue-50 outline-none transition-all placeholder:text-gray-300">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                 <label class="block text-sm font-bold text-gray-700 mb-2">Danh mục <span class="text-red-500">*</span></label>
                                 <div class="relative">
                                     <select name="category_id" class="w-full px-5 py-3 border border-gray-200 rounded-xl text-sm focus:border-[#2C67C8] focus:ring-4 focus:ring-blue-50 outline-none appearance-none bg-white">
                                         <option value="">Chọn danh mục</option>
                                         <option value="1">Sách & Giáo trình</option>
                                         <option value="2">Đồ điện tử</option>
                                         <option value="3">Thời trang</option>
                                         <option value="4">Đồ gia dụng</option>
                                         <option value="5">Khác</option>
                                     </select>
                                     <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                                 </div>
                            </div>
                            <div>
                                 <label class="block text-sm font-bold text-gray-700 mb-2">Tình trạng <span class="text-red-500">*</span></label>
                                 <div class="relative">
                                     <select name="condition" class="w-full px-5 py-3 border border-gray-200 rounded-xl text-sm focus:border-[#2C67C8] focus:ring-4 focus:ring-blue-50 outline-none appearance-none bg-white">
                                         <option value="new">Mới 100%</option>
                                         <option value="like_new">Như mới (99%)</option>
                                         <option value="used_good">Tốt (80-90%)</option>
                                         <option value="used_fair">Khá (50-70%)</option>
                                     </select>
                                     <i class="fa-solid fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                                 </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Description -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                     <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                         <div class="w-10 h-10 rounded-full bg-orange-50 flex items-center justify-center text-orange-500">
                             <i class="fa-solid fa-align-left text-lg"></i>
                         </div>
                         <div>
                             <h3 class="font-bold text-gray-900 text-lg">Mô tả chi tiết</h3>
                             <p class="text-xs text-gray-500">Mô tả càng chi tiết càng dễ bán</p>
                         </div>
                    </div>
                    <div>
                        <textarea name="description" rows="8" placeholder="- Xuất xứ sản phẩm&#10;- Thời gian đã sử dụng&#10;- Các lỗi nhỏ nếu có&#10;- Lý do bán lại..." class="w-full px-5 py-3 border border-gray-200 rounded-xl text-sm focus:border-[#2C67C8] focus:ring-4 focus:ring-blue-50 outline-none transition-all placeholder:text-gray-300"></textarea>
                    </div>
                </div>

                <!-- Section: Price & Sale -->
                 <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                         <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                             <i class="fa-solid fa-tags text-lg"></i>
                         </div>
                         <div>
                             <h3 class="font-bold text-gray-900 text-lg">Thiết lập giá bán</h3>
                             <p class="text-xs text-gray-500">Giá cả hợp lý sẽ bán nhanh hơn</p>
                         </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Giá bán (VNĐ) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="number" name="price" placeholder="0" class="w-full pl-5 pr-12 py-3 border border-gray-200 rounded-xl text-sm font-bold text-[#EE4D2D] focus:border-[#EE4D2D] focus:ring-4 focus:ring-orange-50 outline-none transition-all placeholder:text-gray-300 placeholder:font-normal">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-medium">VNĐ</span>
                            </div>
                        </div>
                         <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Số lượng kho <span class="text-red-500">*</span></label>
                            <div class="flex items-center border border-gray-200 rounded-xl overflow-hidden w-fit">
                                <button type="button" class="px-3 py-3 bg-gray-50 hover:bg-gray-100 border-r border-gray-200 text-gray-500 transition-colors"><i class="fa-solid fa-minus text-xs"></i></button>
                                <input type="number" name="quantity" value="1" class="w-16 py-3 text-center text-sm font-bold text-gray-800 outline-none border-none">
                                <button type="button" class="px-3 py-3 bg-gray-50 hover:bg-gray-100 border-l border-gray-200 text-gray-500 transition-colors"><i class="fa-solid fa-plus text-xs"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Images & Submit -->
            <div class="lg:col-span-1 space-y-6">
                 <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-bold text-gray-900 mb-4">Hình ảnh sản phẩm</h3>
                    
                    <div class="space-y-4">
                        <!-- Cover Image Upload -->
                        <div class="w-full aspect-square rounded-2xl border-2 border-dashed border-[#2C67C8] bg-blue-50/30 flex flex-col items-center justify-center text-[#2C67C8] hover:bg-blue-50 transition-all cursor-pointer relative group overflow-hidden">
                            <input type="file" name="images[]" class="absolute inset-0 opacity-0 cursor-pointer z-10" multiple>
                            <div class="w-16 h-16 bg-white rounded-full shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-300">
                                <i class="fa-solid fa-camera text-2xl"></i>
                            </div>
                            <span class="text-sm font-bold">Thêm ảnh bìa</span>
                            <span class="text-xs opacity-70 mt-1">Kéo thả hoặc click để tải lên</span>
                        </div>

                        <div class="grid grid-cols-4 gap-2">
                             <div class="aspect-square rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center text-gray-300 hover:border-gray-300 hover:text-gray-400 transition-colors cursor-pointer">
                                 <i class="fa-solid fa-plus"></i>
                            </div>
                             <div class="aspect-square rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center text-gray-300 hover:border-gray-300 hover:text-gray-400 transition-colors cursor-pointer">
                                 <i class="fa-solid fa-plus"></i>
                            </div>
                             <div class="aspect-square rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center text-gray-300 hover:border-gray-300 hover:text-gray-400 transition-colors cursor-pointer">
                                 <i class="fa-solid fa-plus"></i>
                            </div>
                            <div class="aspect-square rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center text-gray-300 hover:border-gray-300 hover:text-gray-400 transition-colors cursor-pointer">
                                 <i class="fa-solid fa-plus"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 p-3 bg-yellow-50 rounded-lg flex gap-3 items-start">
                        <i class="fa-regular fa-lightbulb text-yellow-500 mt-1"></i>
                        <p class="text-xs text-yellow-700">Đăng từ 3-5 ảnh rõ nét ở các góc độ khác nhau để bán nhanh hơn.</p>
                    </div>
                 </div>

                 <!-- Actions sticky -->
                 <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-24">
                     <div class="flex items-center justify-between mb-4">
                         <span class="text-sm text-gray-500">Phí đăng tin</span>
                         <span class="font-bold text-gray-900">Miễn phí</span>
                     </div>
                     <button type="submit" class="w-full py-4 bg-gradient-to-r from-[#EE4D2D] to-[#ff7350] text-white font-bold rounded-xl shadow-lg shadow-orange-500/30 hover:shadow-orange-500/40 hover:-translate-y-1 transition-all active:scale-[0.98] text-sm uppercase tracking-wider mb-3">
                         Đăng Bán Ngay
                     </button>
                      <button type="button" class="w-full py-4 bg-gray-50 text-gray-600 font-bold rounded-xl hover:bg-gray-100 transition-all text-sm">
                         Lưu Nháp
                     </button>
                 </div>
            </div>

        </form>
    </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
