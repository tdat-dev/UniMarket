<?php
include __DIR__ . '/../partials/head.php';
include __DIR__ . '/../partials/header.php';
?>

<!-- Main Layout with Modern Gradient Background -->
<main
    class="bg-gradient-to-br from-slate-50 to-gray-100 min-h-screen pb-32 md:pb-32 font-sans selection:bg-indigo-100 selection:text-indigo-700">
    <div class="max-w-5xl mx-auto pt-10 px-4 sm:px-6">

        <!-- Page Header -->
        <div class="mb-8 text-center sm:text-left">
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Đăng bán sản phẩm</h1>
            <p class="text-slate-500 mt-2 text-sm">Điền thông tin chi tiết để sản phẩm của bạn tiếp cận hàng nghìn người
                mua.</p>
        </div>

        <form id="productForm" action="/products/store" method="POST" enctype="multipart/form-data">

            <!-- Alert Errors -->
            <?php if (isset($errors) && !empty($errors)): ?>
                <div
                    class="bg-red-50/80 backdrop-blur-sm border border-red-100 rounded-xl p-4 mb-6 shadow-sm animate-fade-in-down">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fa-solid fa-circle-exclamation text-red-500 text-lg"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-semibold text-red-800">Một vài thông tin cần chỉnh sửa:</h3>
                            <ul class="mt-1 text-sm text-red-600 list-disc list-inside space-y-1">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- MAIN CONTAINER: Glassmorphism Card style -->
            <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-100">

                <!-- SECTION 1: MEDIA UPLOAD -->
                <div class="p-8 border-b border-slate-100">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <i class="fa-solid fa-images text-indigo-500"></i> Hình ảnh & Video
                            </h2>
                            <p class="text-sm text-slate-400 mt-1">Đăng tải tối đa 9 ảnh. Ảnh đầu tiên sẽ là ảnh bìa.
                            </p>
                        </div>
                        <!-- Helper Tooltip -->
                        <div class="group relative">
                            <span
                                class="text-xs font-medium text-indigo-500 bg-indigo-50 px-3 py-1.5 rounded-full cursor-help hover:bg-indigo-100 transition-colors">
                                <i class="fa-regular fa-lightbulb mr-1"></i> Mẹo hay
                            </span>
                            <div
                                class="absolute bottom-full right-0 mb-2 w-64 bg-slate-800 text-white text-xs rounded-lg py-2 px-3 shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                                Hãy chụp ảnh ở nơi đủ sáng. Ảnh rõ nét giúp bán nhanh hơn 30%.
                                <div class="absolute -bottom-1 right-4 w-2 h-2 bg-slate-800 transform rotate-45"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Area -->
                    <div class="relative group">
                        <input type="file" name="images[]" id="imageInput" accept="image/*" multiple
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                        <div
                            class="border-2 border-dashed border-slate-300 rounded-xl h-[240px] flex flex-col items-center justify-center bg-slate-50/50 group-hover:bg-indigo-50/30 group-hover:border-indigo-400 group-hover:scale-[1.005] transition-all duration-300 ease-out">
                            <div
                                class="w-16 h-16 mb-4 rounded-full bg-white shadow-md flex items-center justify-center text-slate-400 group-hover:text-indigo-600 group-hover:scale-110 transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                            </div>
                            <span class="text-slate-600 font-semibold text-base group-hover:text-indigo-700">Kéo thả
                                hoặc nhấn để tải ảnh lên</span>
                            <span class="text-slate-400 text-xs mt-2">Hỗ trợ: JPG, PNG, JPEG (Tối đa 5MB)</span>
                        </div>
                    </div>

                    <p class="text-sm text-red-500 mt-3 hidden font-medium flex items-center gap-2 animate-pulse"
                        id="imgError">
                        <i class="fa-solid fa-circle-xmark"></i>
                        Vui lòng chọn ít nhất 2 ảnh để sản phẩm đáng tin cậy hơn.
                    </p>

                    <!-- Preview Grid -->
                    <div id="imagePreviewGrid"
                        class="mt-6 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 empty:hidden">
                        <!-- JS renders here -->
                    </div>
                </div>

                <!-- SECTION 1.5: PICKUP ADDRESS -->
                <div class="p-8 border-b border-slate-100">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-location-dot text-indigo-500"></i> Địa chỉ lấy hàng
                            <span class="text-red-500">*</span>
                        </h2>
                        <a href="/addresses" class="text-sm text-indigo-600 hover:underline">Quản lý địa chỉ</a>
                    </div>
                    <p class="text-sm text-slate-400 mb-4">Shipper GHN sẽ đến địa chỉ này để lấy hàng khi có đơn.</p>

                    <?php if (!empty($addresses)): ?>
                        <?php if (empty($hasValidGHNAddress)): ?>
                            <!-- Warning: No valid GHN address -->
                            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
                                <div class="flex items-start gap-3">
                                    <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-0.5"></i>
                                    <div>
                                        <p class="text-sm font-medium text-amber-800">Chưa có địa chỉ hợp lệ cho GHN</p>
                                        <p class="text-xs text-amber-600 mt-1">Vui lòng cập nhật địa chỉ với thông tin Tỉnh/Quận/Phường để shipper có thể đến lấy hàng.</p>
                                        <a href="/addresses/edit?id=<?= $addresses[0]['id'] ?? '' ?>" 
                                           class="inline-flex items-center gap-1 mt-2 text-xs text-amber-700 hover:underline font-medium">
                                            <i class="fa-solid fa-pen-to-square"></i> Cập nhật ngay
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Address Selection -->
                        <div class="space-y-3">
                            <?php foreach ($addresses as $index => $addr): 
                                $hasGHN = !empty($addr['ghn_district_id']) && !empty($addr['ghn_ward_code']);
                            ?>
                                <label class="address-option flex items-start gap-3 p-4 border rounded-xl cursor-pointer hover:border-indigo-400 transition-all <?= $addr['is_default'] ? 'border-indigo-500 bg-indigo-50/50' : 'border-slate-200' ?> <?= !$hasGHN ? 'opacity-60' : '' ?>">
                                    <input type="radio" name="pickup_address_id" value="<?= $addr['id'] ?>" 
                                           class="mt-1 text-indigo-600 focus:ring-indigo-500"
                                           <?= $addr['is_default'] ? 'checked' : '' ?>
                                           <?= !$hasGHN ? 'disabled' : '' ?>>
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-semibold text-slate-800"><?= htmlspecialchars($addr['recipient_name']) ?></span>
                                            <span class="text-slate-300">|</span>
                                            <span class="text-slate-600 text-sm"><?= htmlspecialchars($addr['phone_number']) ?></span>
                                            <?php if ($addr['is_default']): ?>
                                                <span class="px-2 py-0.5 text-xs bg-indigo-600 text-white rounded-full">Mặc định</span>
                                            <?php endif; ?>
                                            <?php if (!$hasGHN): ?>
                                                <span class="px-2 py-0.5 text-xs bg-amber-100 text-amber-700 rounded-full">Chưa có mã GHN</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-xs text-slate-400 mb-1"><?= htmlspecialchars($addr['label']) ?></div>
                                        <div class="text-sm text-slate-600"><?= htmlspecialchars($addr['full_address'] ?: $addr['street_address']) ?></div>
                                    </div>
                                    <?php if (!$hasGHN): ?>
                                        <a href="/addresses/edit?id=<?= $addr['id'] ?>" 
                                           class="text-xs text-indigo-600 hover:underline whitespace-nowrap"
                                           onclick="event.stopPropagation()">Cập nhật</a>
                                    <?php endif; ?>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <!-- Add new address link -->
                        <div class="mt-4 pt-4 border-t border-slate-100">
                            <a href="/addresses/create?redirect_to=<?= urlencode('/products/create') ?>" 
                               class="inline-flex items-center gap-2 text-sm text-indigo-600 hover:underline font-medium">
                                <i class="fa-solid fa-plus"></i> Thêm địa chỉ mới
                            </a>
                        </div>
                    <?php else: ?>
                        <!-- No addresses -->
                        <div class="text-center py-8 bg-slate-50/50 rounded-xl border-2 border-dashed border-slate-200">
                            <div class="w-16 h-16 mx-auto mb-4 bg-slate-100 rounded-full flex items-center justify-center">
                                <i class="fa-solid fa-location-dot text-2xl text-slate-400"></i>
                            </div>
                            <p class="text-slate-600 font-medium mb-2">Bạn chưa có địa chỉ lấy hàng</p>
                            <p class="text-sm text-slate-400 mb-4">Vui lòng thêm địa chỉ để shipper GHN có thể đến lấy hàng.</p>
                            <a href="/addresses/create?redirect_to=<?= urlencode('/products/create') ?>" 
                               class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-colors font-medium">
                                <i class="fa-solid fa-plus"></i> Thêm địa chỉ ngay
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- SECTION 2: BASIC INFO -->
                <div class="p-8 border-b border-slate-100 bg-slate-50/30">
                    <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-indigo-500"></i> Thông tin cơ bản
                    </h2>

                    <!-- Tên sản phẩm & Character Count -->
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-sm font-bold text-slate-700">Tên sản phẩm <span
                                    class="text-red-500">*</span></label>
                            <span class="text-xs text-slate-400 font-medium bg-slate-100 px-2 py-0.5 rounded-md"><span
                                    id="nameCount" class="text-slate-600">0</span>/90</span>
                        </div>
                        <input type="text" name="name" id="inputName" maxlength="90" required
                            value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                            placeholder="Ví dụ: Giáo trình Kinh tế vĩ mô - NEU (Mới 99%)..."
                            class="w-full h-12 px-4 border border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none text-sm font-medium placeholder-slate-400 transition-all shadow-sm">
                    </div>

                    <!-- Danh mục (Category Picker Custom) -->
                    <div class="mb-8 relative z-50" id="categoryContainer">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Danh mục sản phẩm <span
                                class="text-red-500">*</span></label>

                        <!-- Trigger Input -->
                        <div class="relative cursor-pointer group" id="categoryTrigger">
                            <input type="text" id="categoryDisplay" readonly placeholder="Chọn danh mục phù hợp..."
                                class="w-full h-12 pl-4 pr-10 border border-slate-200 rounded-xl bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none text-sm text-slate-700 placeholder-slate-400 font-medium transition-all shadow-sm cursor-pointer hover:border-indigo-300">
                            <div
                                class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-slate-400 group-hover:text-indigo-500 transition-colors">
                                <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300"
                                    id="categoryArrow"></i>
                            </div>
                            <input type="hidden" name="category_id" id="inputCategoryId" required>
                        </div>

                        <!-- Modern Dropdown Panel - 2 Column Layout -->
                        <div id="categoryPanel"
                            class="hidden absolute left-0 w-full mt-2 bg-white border border-slate-200 rounded-xl shadow-2xl z-[100] overflow-hidden"
                            style="top: 100%;">

                            <div class="flex">
                                <!-- Parent Categories (Left) -->
                                <div class="w-1/2 border-r border-slate-100">
                                    <div class="p-3 border-b border-slate-100">
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Danh mục
                                            chính</span>
                                    </div>
                                    <div id="parentCategoryList"
                                        class="max-h-[300px] overflow-y-auto scrollbar-thin scrollbar-thumb-slate-200">
                                        <!-- JS renders here -->
                                    </div>
                                </div>

                                <!-- Child Categories (Right) -->
                                <div class="w-1/2 bg-slate-50/50">
                                    <div class="p-3 border-b border-slate-100">
                                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Danh mục
                                            con</span>
                                    </div>
                                    <div id="childCategoryList"
                                        class="max-h-[300px] overflow-y-auto p-2 scrollbar-thin scrollbar-thumb-slate-200">
                                        <div
                                            class="h-full flex flex-col items-center justify-center text-slate-300 space-y-2 py-10">
                                            <i class="fa-solid fa-arrow-left text-xl"></i>
                                            <span class="text-sm">Chọn danh mục bên trái</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                    <!-- Tình trạng (Interactive Cards) -->
                    <div class="mb-2">
                        <label class="block text-sm font-bold text-slate-700 mb-4">Tình trạng sản phẩm <span
                                class="text-red-500">*</span></label>
                        <input type="hidden" name="condition" id="inputCondition" required>

                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                            <!-- Card Items defined in JS to map Icons -->
                            <!-- Will be rendered by JS static structure for SEO but enhanced loop later. 
                                  Let's keep hardcoded HTML for structure control but clean classnames -->

                            <!-- Mới -->
                            <div class="condition-card group cursor-pointer border border-slate-200 rounded-xl p-4 flex flex-col items-center justify-center text-center gap-3 hover:border-indigo-500 hover:shadow-lg hover:shadow-indigo-500/10 hover:-translate-y-1 transition-all duration-300 bg-white relative"
                                onclick="selectCondition(this, 'Mới')">
                                <div
                                    class="w-10 h-10 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center text-lg group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                                    <i class="fa-solid fa-certificate"></i>
                                </div>
                                <span class="text-sm font-bold text-slate-700 group-hover:text-indigo-600">Mới
                                    100%</span>
                                <span class="text-[10px] text-slate-400 block px-2 leading-relaxed">Nguyên tem mác, chưa
                                    qua sử dụng</span>
                            </div>

                            <!-- Như mới -->
                            <div class="condition-card group cursor-pointer border border-slate-200 rounded-xl p-4 flex flex-col items-center justify-center text-center gap-3 hover:border-indigo-500 hover:shadow-lg hover:shadow-indigo-500/10 hover:-translate-y-1 transition-all duration-300 bg-white relative"
                                onclick="selectCondition(this, 'Như mới')">
                                <div
                                    class="w-10 h-10 rounded-full bg-teal-50 text-teal-500 flex items-center justify-center text-lg group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                                    <i class="fa-solid fa-sparkles"></i>
                                </div>
                                <span class="text-sm font-bold text-slate-700 group-hover:text-indigo-600">Như
                                    mới</span>
                                <span class="text-[10px] text-slate-400 block px-2 leading-relaxed">Đã mở hộp, mới 99%
                                    chưa dùng</span>
                            </div>

                            <!-- Tốt -->
                            <div class="condition-card group cursor-pointer border border-slate-200 rounded-xl p-4 flex flex-col items-center justify-center text-center gap-3 hover:border-indigo-500 hover:shadow-lg hover:shadow-indigo-500/10 hover:-translate-y-1 transition-all duration-300 bg-white relative"
                                onclick="selectCondition(this, 'Tốt')">
                                <div
                                    class="w-10 h-10 rounded-full bg-green-50 text-green-500 flex items-center justify-center text-lg group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                                    <i class="fa-regular fa-thumbs-up"></i>
                                </div>
                                <span class="text-sm font-bold text-slate-700 group-hover:text-indigo-600">Tốt</span>
                                <span class="text-[10px] text-slate-400 block px-2 leading-relaxed">Dùng tốt, xước dăm
                                    không đáng kể</span>
                            </div>

                            <!-- Trung bình -->
                            <div class="condition-card group cursor-pointer border border-slate-200 rounded-xl p-4 flex flex-col items-center justify-center text-center gap-3 hover:border-indigo-500 hover:shadow-lg hover:shadow-indigo-500/10 hover:-translate-y-1 transition-all duration-300 bg-white relative"
                                onclick="selectCondition(this, 'Trung bình')">
                                <div
                                    class="w-10 h-10 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center text-lg group-hover:bg-indigo-500 group-hover:text-white transition-colors">
                                    <i class="fa-solid fa-layer-group"></i>
                                </div>
                                <span class="text-sm font-bold text-slate-700 group-hover:text-indigo-600">Trung
                                    bình</span>
                                <span class="text-[10px] text-slate-400 block px-2 leading-relaxed">Ngoại hình cũ, chức
                                    năng ổn định</span>
                            </div>

                            <!-- Kém -->
                            <div class="condition-card group cursor-pointer border border-slate-200 rounded-xl p-4 flex flex-col items-center justify-center text-center gap-3 hover:border-indigo-500 hover:shadow-lg hover:shadow-indigo-500/10 hover:-translate-y-1 transition-all duration-300 bg-white relative"
                                onclick="selectCondition(this, 'Kém')">
                                <div
                                    class="w-10 h-10 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center text-lg group-hover:bg-slate-600 group-hover:text-white transition-colors">
                                    <i class="fa-solid fa-screwdriver-wrench"></i>
                                </div>
                                <span class="text-sm font-bold text-slate-700 group-hover:text-slate-600">Xác / Linh
                                    kiện</span>
                                <span class="text-[10px] text-slate-400 block px-2 leading-relaxed">Hỏng hóc, bán giá
                                    xác để lấy đồ</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 3: SALES INFO -->
                <div class="p-8">
                    <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-tag text-indigo-500"></i> Chi tiết bán hàng
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                        <!-- Giá -->
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Giá mong muốn (VNĐ) <span
                                    class="text-red-500">*</span></label>
                            <!-- Giá Wrapper -->
                            <div
                                class="flex items-center rounded-xl border border-slate-200 overflow-hidden focus-within:border-indigo-500 focus-within:ring-4 focus-within:ring-indigo-500/10 transition-all shadow-sm group bg-white">
                                <!-- Prefix Symbol -->
                                <div
                                    class="w-14 h-12 flex items-center justify-center bg-slate-50 border-r border-slate-200 text-slate-500 font-bold group-hover:bg-indigo-50 group-hover:text-indigo-600 group-hover:border-indigo-100 transition-colors text-lg">
                                    ₫
                                </div>
                                <!-- Input Field -->
                                <input type="text" id="displayPrice" required placeholder="0"
                                    class="flex-1 h-12 px-4 border-none outline-none text-lg font-bold text-slate-800 placeholder-slate-300 bg-transparent focus:ring-0">
                                <input type="hidden" name="price" id="realPrice" required>
                            </div>

                            <!-- Quick Select Price Tags -->
                            <div class="mt-3 flex gap-2 overflow-x-auto pb-1 scrollbar-none">
                                <button type="button" onclick="setPrice(50000)"
                                    class="text-xs border border-slate-200 rounded-full px-3 py-1 text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition-colors whitespace-nowrap">50.000</button>
                                <button type="button" onclick="setPrice(100000)"
                                    class="text-xs border border-slate-200 rounded-full px-3 py-1 text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition-colors whitespace-nowrap">100.000</button>
                                <button type="button" onclick="setPrice(200000)"
                                    class="text-xs border border-slate-200 rounded-full px-3 py-1 text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition-colors whitespace-nowrap">200.000</button>
                                <button type="button" onclick="setPrice(500000)"
                                    class="text-xs border border-slate-200 rounded-full px-3 py-1 text-slate-500 hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition-colors whitespace-nowrap">500.000</button>
                            </div>
                        </div>
                    </div>

                    <!-- Kho -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Số lượng kho <span
                                class="text-red-500">*</span></label>
                        <div class="relative flex items-center max-w-[150px]">
                            <button type="button" onclick="adjustQuantity(-1)"
                                class="w-10 h-10 rounded-l-xl bg-slate-50 border border-slate-200 hover:bg-white hover:text-indigo-600 transition-colors flex items-center justify-center">
                                <i class="fa-solid fa-minus"></i>
                            </button>
                            <input type="number" name="quantity" id="inputQuantity" required min="1" value="1"
                                class="w-full h-10 border-t border-b border-slate-200 text-center font-bold text-slate-700 focus:outline-none focus:ring-0 appearance-none m-0">
                            <button type="button" onclick="adjustQuantity(1)"
                                class="w-10 h-10 rounded-r-xl bg-slate-50 border border-slate-200 hover:bg-white hover:text-indigo-600 transition-colors flex items-center justify-center">
                                <i class="fa-solid fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mô tả -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Mô tả chi tiết</label>
                    <textarea name="description" rows="6"
                        class="w-full p-4 border border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/10 outline-none text-sm text-slate-700 leading-relaxed placeholder-slate-400 transition-all shadow-sm resize-y"
                        placeholder="Mô tả kỹ về sản phẩm (Xuất xứ, thời gian đã sử dụng, lý do bán...)..."></textarea>
                </div>

                <!-- Action Buttons (inside container) -->
                <div class="p-8 border-t border-slate-100 flex items-center justify-end gap-4">
                    <button type="button" onclick="history.back()"
                        class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 hover:border-slate-300 transition-all">
                        Hủy bỏ
                    </button>
                    <button type="submit"
                        class="px-8 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200 flex items-center gap-2">
                        <span>Đăng bán ngay</span>
                        <i class="fa-solid fa-arrow-right text-sm"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</main>

<!-- JS Logic - External File -->
<script>
    // Pass category data from PHP to JS
    window.categoryData = <?= json_encode($categories) ?>;
</script>
<script src="/js/product-create.js"></script>
<?php include __DIR__ . '/../partials/footer.php'; ?>