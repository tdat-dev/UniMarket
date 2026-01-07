<?php include __DIR__ . '/../partials/head.php'; ?>
<?php include __DIR__ . '/../partials/header.php'; ?>

<main class="bg-gray-50 min-h-screen pb-12">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
        <?php $activeTab = 'change_password';
        include __DIR__ . '/../partials/profile_card.php'; ?>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <h2 class="text-lg font-medium text-gray-900 mb-6 pb-2 border-b">Đổi mật khẩu</h2>

            <?php if (isset($_GET['success'])): ?>
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">Mật khẩu đã được cập nhật thành công!</span>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">
                        <?php
                        switch($_GET['error']) {
                            case 'wrong_password': echo 'Mật khẩu hiện tại không đúng.'; break;
                            case 'password_mismatch': echo 'Mật khẩu mới không khớp.'; break;
                            case 'password_short': echo 'Mật khẩu phải có ít nhất 6 ký tự.'; break;
                            default: echo 'Đã có lỗi xảy ra.';
                        }
                        ?>
                    </span>
                </div>
            <?php endif; ?>

            <form action="/profile/change-password/update" method="POST" class="max-w-xl">
                <div class="space-y-6">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Mật khẩu hiện tại</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="password" name="current_password" id="current_password" required
                                class="block w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <button type="button" onclick="togglePassword('current_password')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                <i class="fa-regular fa-eye" id="icon-current_password"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700">Mật khẩu mới</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="password" name="new_password" id="new_password" required
                                class="block w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <button type="button" onclick="togglePassword('new_password')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                <i class="fa-regular fa-eye" id="icon-new_password"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700">Xác nhận mật khẩu mới</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="password" name="confirm_password" id="confirm_password" required
                                class="block w-full px-3 py-2 pr-10 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <button type="button" onclick="togglePassword('confirm_password')"
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none">
                                <i class="fa-regular fa-eye" id="icon-confirm_password"></i>
                            </button>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit"
                            class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cập nhật mật khẩu
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
function togglePassword(fieldId) {
    const input = document.getElementById(fieldId);
    const icon = document.getElementById('icon-' + fieldId);
    
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = "password";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>
