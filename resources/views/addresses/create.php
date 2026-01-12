<?php
include __DIR__ . '/../partials/head.php';
include __DIR__ . '/../partials/header.php';

// HERE Maps API Key
$hereApiKey = 'LWsRBnOZXXoE0HZp6R9Ijj5B1YneBem6xT_2CNSsrpc';

// Old input for form repopulation
$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);

$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);

$redirectTo = $_GET['redirect_to'] ?? '';
?>

<main class="bg-gray-100 min-h-screen pb-20 md:pb-10">
    <div class="max-w-[600px] mx-auto px-4 pt-4">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="/" class="hover:text-[#2C67C8]">Trang chủ</a>
            <span>&gt;</span>
            <a href="/addresses" class="hover:text-[#2C67C8]">Địa chỉ</a>
            <span>&gt;</span>
            <span class="text-gray-800">Thêm mới</span>
        </div>

        <h1 class="text-2xl font-medium text-gray-800 mb-6">Thêm địa chỉ mới</h1>

        <!-- Form -->
        <form action="/addresses/store" method="POST" class="bg-white rounded-lg shadow-sm p-6 space-y-5"
            id="address-form">
            <?php if ($redirectTo): ?>
                <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($redirectTo) ?>">
            <?php endif; ?>

            <!-- Label -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Tên gợi nhớ <span class="text-red-500">*</span>
                </label>
                <div class="flex gap-2 flex-wrap">
                    <?php
                    $presetLabels = ['Nhà riêng', 'Công ty', 'Trường học', 'Nhà bạn'];
                    $currentLabel = $old['label'] ?? '';
                    ?>
                    <?php foreach ($presetLabels as $preset): ?>
                        <button type="button"
                            class="label-preset px-3 py-1.5 text-sm border rounded-full transition-colors
                                       <?= $currentLabel === $preset ? 'bg-[#EE4D2D] text-white border-[#EE4D2D]' : 'hover:border-[#EE4D2D] hover:text-[#EE4D2D]' ?>"
                            data-value="<?= $preset ?>">
                            <?= $preset ?>
                        </button>
                    <?php endforeach; ?>
                </div>
                <input type="text" name="label" id="label" value="<?= htmlspecialchars($currentLabel ?: 'Nhà riêng') ?>"
                    class="mt-2 w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EE4D2D] focus:border-transparent outline-none"
                    placeholder="Hoặc nhập tên khác...">
            </div>

            <!-- Recipient Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Họ và tên người nhận <span class="text-red-500">*</span>
                </label>
                <input type="text" name="recipient_name"
                    value="<?= htmlspecialchars($old['recipient_name'] ?? ($_SESSION['user']['full_name'] ?? '')) ?>"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EE4D2D] focus:border-transparent outline-none <?= isset($errors['recipient_name']) ? 'border-red-500' : '' ?>"
                    placeholder="Nhập họ tên người nhận hàng">
                <?php if (isset($errors['recipient_name'])): ?>
                    <p class="text-red-500 text-sm mt-1"><?= $errors['recipient_name'] ?></p>
                <?php endif; ?>
            </div>

            <!-- Phone -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Số điện thoại <span class="text-red-500">*</span>
                </label>
                <input type="tel" name="phone_number"
                    value="<?= htmlspecialchars($old['phone_number'] ?? ($_SESSION['user']['phone_number'] ?? '')) ?>"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EE4D2D] focus:border-transparent outline-none <?= isset($errors['phone_number']) ? 'border-red-500' : '' ?>"
                    placeholder="VD: 0901234567">
                <?php if (isset($errors['phone_number'])): ?>
                    <p class="text-red-500 text-sm mt-1"><?= $errors['phone_number'] ?></p>
                <?php endif; ?>
            </div>

            <hr class="border-gray-200">

            <!-- Province/District/Ward - Cascading Dropdowns -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Province -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Tỉnh/Thành phố <span class="text-red-500">*</span>
                    </label>
                    <select name="province" id="province"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EE4D2D] focus:border-transparent outline-none bg-white <?= isset($errors['province']) ? 'border-red-500' : '' ?>">
                        <option value="">-- Chọn Tỉnh/TP --</option>
                    </select>
                    <input type="hidden" name="province_id" id="province_id"
                        value="<?= htmlspecialchars($old['province_id'] ?? '') ?>">
                    <?php if (isset($errors['province'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors['province'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- District -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Quận/Huyện <span class="text-red-500">*</span>
                    </label>
                    <select name="district" id="district"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EE4D2D] focus:border-transparent outline-none bg-white <?= isset($errors['district']) ? 'border-red-500' : '' ?>"
                        disabled>
                        <option value="">-- Chọn Quận/Huyện --</option>
                    </select>
                    <input type="hidden" name="district_id" id="district_id"
                        value="<?= htmlspecialchars($old['district_id'] ?? '') ?>">
                    <?php if (isset($errors['district'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors['district'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Ward -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Phường/Xã
                    </label>
                    <select name="ward" id="ward"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EE4D2D] focus:border-transparent outline-none bg-white"
                        disabled>
                        <option value="">-- Chọn Phường/Xã --</option>
                    </select>
                    <input type="hidden" name="ward_id" id="ward_id"
                        value="<?= htmlspecialchars($old['ward_id'] ?? '') ?>">
                </div>
            </div>

            <!-- Street Address -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Địa chỉ chi tiết <span class="text-red-500">*</span>
                </label>
                <input type="text" name="street_address" id="street_address"
                    value="<?= htmlspecialchars($old['street_address'] ?? '') ?>"
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EE4D2D] focus:border-transparent outline-none <?= isset($errors['street_address']) ? 'border-red-500' : '' ?>"
                    placeholder="Số nhà, tên đường, tòa nhà, căn hộ...">
                <?php if (isset($errors['street_address'])): ?>
                    <p class="text-red-500 text-sm mt-1"><?= $errors['street_address'] ?></p>
                <?php endif; ?>
            </div>

            <!-- HERE Maps Search (Optional - for coordinates) -->
            <div class="bg-gray-50 rounded-lg p-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    <i class="fa-solid fa-magnifying-glass text-gray-400 mr-1"></i>
                    Tìm địa chỉ trên bản đồ <span class="text-xs text-gray-500">(tùy chọn - để lấy tọa độ)</span>
                </label>
                <div class="relative">
                    <input type="text" id="address-search"
                        class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#EE4D2D] focus:border-transparent outline-none"
                        placeholder="Nhập để tìm kiếm và lấy tọa độ..." autocomplete="off">
                    <div id="search-loading" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                        <i class="fa-solid fa-spinner fa-spin text-gray-400"></i>
                    </div>
                </div>
                <div id="suggestions"
                    class="hidden bg-white border border-gray-200 rounded-lg shadow-lg mt-1 max-h-48 overflow-y-auto">
                </div>
            </div>

            <!-- Hidden fields -->
            <input type="hidden" name="full_address" id="full_address"
                value="<?= htmlspecialchars($old['full_address'] ?? '') ?>">
            <input type="hidden" name="latitude" id="latitude" value="<?= htmlspecialchars($old['latitude'] ?? '') ?>">
            <input type="hidden" name="longitude" id="longitude"
                value="<?= htmlspecialchars($old['longitude'] ?? '') ?>">
            <input type="hidden" name="here_place_id" id="here_place_id"
                value="<?= htmlspecialchars($old['here_place_id'] ?? '') ?>">

            <!-- Default checkbox -->
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_default" id="is_default" value="1"
                    class="w-4 h-4 text-[#EE4D2D] border-gray-300 rounded focus:ring-[#EE4D2D]"
                    <?= !empty($old['is_default']) ? 'checked' : '' ?>>
                <label for="is_default" class="text-sm text-gray-700">Đặt làm địa chỉ mặc định</label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4">
                <a href="/addresses"
                    class="flex-1 py-3 border border-gray-300 text-gray-700 rounded-lg text-center hover:bg-gray-50 transition-colors">
                    Hủy
                </a>
                <button type="submit"
                    class="flex-1 py-3 bg-[#EE4D2D] text-white font-medium rounded-lg hover:bg-[#d73211] transition-colors">
                    Lưu địa chỉ
                </button>
            </div>
        </form>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', async function () {
        // ============= DVHCVN Data =============
        let dvhcvnData = [];

        // Elements - declare first
        const provinceSelect = document.getElementById('province');
        const districtSelect = document.getElementById('district');
        const wardSelect = document.getElementById('ward');
        const provinceIdInput = document.getElementById('province_id');
        const districtIdInput = document.getElementById('district_id');
        const wardIdInput = document.getElementById('ward_id');
        const fullAddressInput = document.getElementById('full_address');
        const streetAddressInput = document.getElementById('street_address');

        // Old values for repopulation - MUST declare before initProvinceDropdown is called
        const oldProvinceId = '<?= htmlspecialchars($old['province_id'] ?? '') ?>';
        const oldDistrictId = '<?= htmlspecialchars($old['district_id'] ?? '') ?>';
        const oldWardId = '<?= htmlspecialchars($old['ward_id'] ?? '') ?>';

        // Show loading state
        provinceSelect.innerHTML = '<option value="">⏳ Đang tải dữ liệu...</option>';

        // Load DVHCVN data
        try {
            console.log('⏳ Loading DVHCVN data...');
            const response = await fetch('/data/dvhcvn.json');

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const json = await response.json();
            dvhcvnData = json.data || json;

            if (!Array.isArray(dvhcvnData) || dvhcvnData.length === 0) {
                throw new Error('Invalid data format');
            }

            console.log('✅ Loaded DVHCVN data:', dvhcvnData.length, 'provinces');
            initProvinceDropdown();
        } catch (error) {
            console.error('❌ Failed to load DVHCVN data:', error);
            provinceSelect.innerHTML = '<option value="">❌ Lỗi tải dữ liệu - Thử lại sau</option>';
            alert('Không thể tải dữ liệu địa chỉ. Vui lòng refresh trang.');
            return;
        }

        // Initialize Province dropdown
        function initProvinceDropdown() {
            provinceSelect.innerHTML = '<option value="">-- Chọn Tỉnh/TP --</option>';

            dvhcvnData.forEach(province => {
                const opt = document.createElement('option');
                opt.value = province.name;
                opt.dataset.id = province.level1_id;
                opt.textContent = province.name;
                if (province.level1_id === oldProvinceId) {
                    opt.selected = true;
                }
                provinceSelect.appendChild(opt);
            });

            // If old value exists, trigger change
            if (oldProvinceId) {
                provinceSelect.dispatchEvent(new Event('change'));
            }
        }

        // Province change -> load Districts
        provinceSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const provinceId = selectedOption?.dataset?.id || '';

            provinceIdInput.value = provinceId;

            // Reset district and ward
            districtSelect.innerHTML = '<option value="">-- Chọn Quận/Huyện --</option>';
            wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
            districtSelect.disabled = true;
            wardSelect.disabled = true;
            districtIdInput.value = '';
            wardIdInput.value = '';

            if (!provinceId) return;

            // Find province data
            const province = dvhcvnData.find(p => p.level1_id === provinceId);
            if (!province || !province.level2s) return;

            // Populate districts
            districtSelect.disabled = false;
            province.level2s.forEach(district => {
                const opt = document.createElement('option');
                opt.value = district.name;
                opt.dataset.id = district.level2_id;
                opt.textContent = district.name;
                if (district.level2_id === oldDistrictId) {
                    opt.selected = true;
                }
                districtSelect.appendChild(opt);
            });

            // If old value exists, trigger change
            if (oldDistrictId) {
                districtSelect.dispatchEvent(new Event('change'));
            }

            updateFullAddress();
        });

        // District change -> load Wards
        districtSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const districtId = selectedOption?.dataset?.id || '';
            const provinceId = provinceIdInput.value;

            districtIdInput.value = districtId;

            // Reset ward
            wardSelect.innerHTML = '<option value="">-- Chọn Phường/Xã --</option>';
            wardSelect.disabled = true;
            wardIdInput.value = '';

            if (!districtId || !provinceId) return;

            // Find district data
            const province = dvhcvnData.find(p => p.level1_id === provinceId);
            const district = province?.level2s?.find(d => d.level2_id === districtId);
            if (!district || !district.level3s) return;

            // Populate wards
            wardSelect.disabled = false;
            district.level3s.forEach(ward => {
                const opt = document.createElement('option');
                opt.value = ward.name;
                opt.dataset.id = ward.level3_id;
                opt.textContent = ward.name;
                if (ward.level3_id === oldWardId) {
                    opt.selected = true;
                }
                wardSelect.appendChild(opt);
            });

            updateFullAddress();
        });

        // Ward change
        wardSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            wardIdInput.value = selectedOption?.dataset?.id || '';
            updateFullAddress();
        });

        // Update full address
        function updateFullAddress() {
            const parts = [
                streetAddressInput.value,
                wardSelect.value,
                districtSelect.value,
                provinceSelect.value
            ].filter(p => p && p.trim());

            fullAddressInput.value = parts.join(', ');
        }

        // Update on street address change
        streetAddressInput.addEventListener('input', updateFullAddress);

        // ============= Label Preset Buttons =============
        const labelInput = document.getElementById('label');
        document.querySelectorAll('.label-preset').forEach(btn => {
            btn.addEventListener('click', function () {
                labelInput.value = this.dataset.value;
                document.querySelectorAll('.label-preset').forEach(b => {
                    b.classList.remove('bg-[#EE4D2D]', 'text-white', 'border-[#EE4D2D]');
                });
                this.classList.add('bg-[#EE4D2D]', 'text-white', 'border-[#EE4D2D]');
            });
        });

        // ============= HERE Maps Search (Optional) =============
        const HERE_API_KEY = '<?= $hereApiKey ?>';
        const searchInput = document.getElementById('address-search');
        const suggestionsContainer = document.getElementById('suggestions');
        const loadingIndicator = document.getElementById('search-loading');
        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');
        const herePlaceIdInput = document.getElementById('here_place_id');

        function debounce(func, wait) {
            let timeout;
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func(...args), wait);
            };
        }

        async function searchAddress(query) {
            if (!query || query.length < 2) {
                suggestionsContainer.classList.add('hidden');
                return;
            }

            loadingIndicator.classList.remove('hidden');

            try {
                const response = await fetch(
                    `https://autosuggest.search.hereapi.com/v1/autosuggest?q=${encodeURIComponent(query)}&at=10.8231,106.6297&in=countryCode:VNM&limit=6&lang=vi&apiKey=${HERE_API_KEY}`
                );
                const data = await response.json();
                displaySuggestions(data.items || []);
            } catch (error) {
                console.error('HERE API Error:', error);
            } finally {
                loadingIndicator.classList.add('hidden');
            }
        }

        function displaySuggestions(items) {
            if (!items.length) {
                suggestionsContainer.classList.add('hidden');
                return;
            }

            suggestionsContainer.innerHTML = items.map(item => `
            <div class="suggestion-item p-3 hover:bg-gray-50 cursor-pointer border-b last:border-0" data-item='${JSON.stringify(item)}'>
                <div class="font-medium text-gray-800 text-sm">${item.title}</div>
                <div class="text-xs text-gray-500">${item.address?.label || ''}</div>
            </div>
        `).join('');

            suggestionsContainer.classList.remove('hidden');

            document.querySelectorAll('.suggestion-item').forEach(el => {
                el.addEventListener('click', () => {
                    const item = JSON.parse(el.dataset.item);
                    searchInput.value = item.title;
                    suggestionsContainer.classList.add('hidden');

                    if (item.position) {
                        latitudeInput.value = item.position.lat || '';
                        longitudeInput.value = item.position.lng || '';
                    }
                    herePlaceIdInput.value = item.id || '';
                });
            });
        }

        const debouncedSearch = debounce(searchAddress, 300);
        searchInput.addEventListener('input', (e) => debouncedSearch(e.target.value));

        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !suggestionsContainer.contains(e.target)) {
                suggestionsContainer.classList.add('hidden');
            }
        });
    });
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>