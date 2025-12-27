<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <!-- Link to Tailwind CSS -->
    <link rel="stylesheet" href="../../../css/app.css">
</head>

<body class="font-roboto bg-[#4e89ff] min-h-screen flex flex-col justify-between overflow-x-hidden">

    <div class="flex-grow flex items-center justify-center py-10">
        <div class="container mx-auto px-4 flex flex-col lg:flex-row items-center justify-center gap-10 lg:gap-40">

            <!-- Left Side: Illustration & Text -->
            <div class="hidden lg:flex flex-col items-center justify-center lg:w-3/5">
                <div class="relative w-full max-w-5xl">
                    <!-- Assuming the image contains the phone and items. If text is separate, we add it below -->
                    <img src="../../../images/homepage-with-text.svg" alt="UniMarket Illustration"
                        class="w-full h-auto object-contain drop-shadow-2xl">
                </div>
            </div>

            <!-- Right Side: Login Form -->
            <div class="w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 md:p-10">
                <div class="text-center mb-6">
                    <img src="../../../images/UniMarketHead.svg" alt="UniMarket Logo" class="h-24 mx-auto mb-4">
                    <h2 class="text-3xl font-bold text-gray-800">Đăng nhập</h2>
                </div>

                <form action="" method="post" class="space-y-4">
                    <div>
                        <input type="text" name="username" placeholder="Email/Số điện thoại/Tên đăng nhập"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-700 placeholder-gray-400 bg-gray-50">
                    </div>

                    <div class="relative">
                        <input type="password" name="password" id="password" placeholder="Mật khẩu"
                            class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-700 placeholder-gray-400 bg-gray-50">
                        <span
                            class="absolute right-4 top-1/2 transform -translate-y-1/2 cursor-pointer text-gray-400 hover:text-gray-600"
                            id="togglePassword">
                            <i class="fa-regular fa-eye"></i>
                        </span>
                    </div>

                    <input type="submit" name="submit" value="ĐĂNG NHẬP"
                        class="w-full bg-[#5A88FF] text-white font-bold py-3 rounded-lg hover:bg-blue-600 transition duration-300 cursor-pointer shadow-md uppercase tracking-wide text-sm">

                    <div class="text-center pt-2">
                        <a href="" name="forgot-password"
                            class="text-[#5A88FF] hover:text-blue-700 text-sm font-medium">Quên mật khẩu</a>
                    </div>

                    <div class="flex items-center my-4">
                        <div class="flex-grow border-t border-gray-200"></div>
                        <span class="mx-4 text-gray-400 text-xs font-medium uppercase">HOẶC</span>
                        <div class="flex-grow border-t border-gray-200"></div>
                    </div>

                    <a href=""
                        class="flex items-center justify-center w-full border border-gray-300 py-3 rounded-lg hover:bg-gray-50 transition duration-300 group bg-white">
                        <img src="../../../images/google.png" alt="Google" class="w-5 h-5 mr-3">
                        <span class="text-gray-700 font-medium group-hover:text-gray-900">Google</span>
                    </a>

                    <div class="text-center mt-6">
                        <p class="text-gray-500 text-sm">Chưa có tài khoản? <a href="../register"
                                class="text-[#5A88FF] font-bold hover:underline">Đăng ký</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 pt-10 pb-6">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Service -->
                <div>
                    <h3 class="font-bold text-gray-800 mb-4">DỊCH VỤ KHÁCH HÀNG</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li><a href="#" class="hover:text-blue-600">Trung Tâm Trợ Giúp Uni</a></li>
                        <li><a href="#" class="hover:text-blue-600">Hướng Dẫn Mua Hàng/Đặt Hàng</a></li>
                        <li><a href="#" class="hover:text-blue-600">Hướng Dẫn Bán Hàng</a></li>
                        <li><a href="#" class="hover:text-blue-600">Đơn Hàng</a></li>
                        <li><a href="#" class="hover:text-blue-600">Trả Hàng/Hoàn Tiền</a></li>
                        <li><a href="#" class="hover:text-blue-600">Liên Hệ Uni</a></li>
                        <li><a href="#" class="hover:text-blue-600">Chính Sách Bảo Hành</a></li>
                    </ul>
                </div>

                <!-- Pay -->
                <div>
                    <h3 class="font-bold text-gray-800 mb-4">THANH TOÁN</h3>
                    <div class="flex gap-2 flex-wrap">
                        <a href="#" class="bg-white p-1 border rounded shadow-sm"><img
                                src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa"
                                class="h-6"></a>
                        <a href="#" class="bg-white p-1 border rounded shadow-sm"><img
                                src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg"
                                alt="Mastercard" class="h-6"></a>
                        <a href="#" class="bg-white p-1 border rounded shadow-sm"><img
                                src="https://upload.wikimedia.org/wikipedia/commons/4/40/JCB_logo.svg" alt="JCB"
                                class="h-6"></a>
                    </div>
                </div>

                <!-- Monitor -->
                <div>
                    <h3 class="font-bold text-gray-800 mb-4">THEO DÕI UNIMARKET</h3>
                    <ul class="space-y-2 text-sm text-gray-600">
                        <li class="flex items-center gap-2">
                            <i class="fa-brands fa-facebook text-lg"></i>
                            <a href="#" class="hover:text-blue-600">Facebook</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-brands fa-square-instagram text-lg"></i>
                            <a href="#" class="hover:text-blue-600">Instagram</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fa-brands fa-linkedin text-lg"></i>
                            <a href="#" class="hover:text-blue-600">LinkedIn</a>
                        </li>
                    </ul>
                </div>

                <!-- Download -->
                <div>
                    <h3 class="font-bold text-gray-800 mb-4">TẢI ỨNG DỤNG UNIMARKET</h3>
                    <div class="flex gap-4">
                        <div class="w-24 h-24 bg-gray-100 p-1">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=https://unimarket.test"
                                alt="QR Code" class="w-full h-full">
                        </div>
                        <div class="flex flex-col gap-2 justify-center">
                            <a href="#"><img
                                    src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg"
                                    alt="App Store" class="h-8"></a>
                            <a href="#"><img
                                    src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg"
                                    alt="Google Play" class="h-8"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Copyright -->
        <div class="container mx-auto px-4 mt-8 pt-6 border-t border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
                <p>© 2025 UniMarket. Tất cả các quyền được bảo lưu.</p>
                <div class="flex items-center gap-2 mt-2 md:mt-0">
                    <span>Quốc gia & Khu vực:</span>
                    <span class="text-gray-700">Việt Nam</span>
                </div>
            </div>
        </div>
    </footer>

    <script>
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    if (togglePassword && password) {
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            // Toggle icon class if using FontAwesome
            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            } else {
                // Fallback text content if no icon tag
                this.textContent = type === 'password' ? '👁️' : '🙈';
            }
        });
    }
    </script>

</body>

</html>