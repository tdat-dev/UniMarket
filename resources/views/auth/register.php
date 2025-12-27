<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng kí</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Link to Tailwind CSS -->
    <link rel="stylesheet" href="../../../css/app.css">
</head>
<body class="font-roboto bg-gray-50">

    <div class="min-h-screen flex flex-col">
        <!-- Main Content -->
        <div class="flex flex-1 w-full">
            <!-- Left Side: Image (Hidden on mobile, visible on lg screens) -->
            <div class="hidden lg:flex lg:w-1/2 bg-blue-50 items-center justify-center relative overflow-hidden">
                 <div class="w-3/4">
                    <img src="../../../images/homepage-with-text.png" alt="register image" class="w-full h-auto object-contain">
                </div>
            </div>

            <!-- Right Side: Login Form -->
            <div class="w-full lg:w-1/2 flex flex-col items-center justify-center p-8 bg-white shadow-lg lg:shadow-none">
                <div class="w-full max-w-md">
                    <div class="mb-8 text-center">
                        <img src="../../../images/UniMarketHead.svg" alt="UniMarket Logo" class="h-12 mx-auto mb-4">
                        <h2 class="text-2xl font-bold text-gray-800">Đăng ký</h2>
                    </div>
                    
                    <form action="" method="post" class="space-y-4">
                        <div class="flex gap-4">
                            <div class="w-1/2">
                                <input type="text" name="username" placeholder="Tên đăng nhập" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="w-1/2">
                                <input type="text" name="branch" placeholder="Ngành học" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                        
                        <div>
                            <input type="text" name="school" placeholder="Trường học" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <input type="email" name="email" placeholder="Email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div>
                            <input type="number" name="phone" placeholder="Số điện thoại" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        
                        <div class="relative">
                            <input type="password" name="password" id="password-register" placeholder="Mật khẩu" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <span class="absolute right-3 top-1/2 transform -translate-y-1/2 cursor-pointer text-gray-500" id="togglePasswordRegister">👁️</span>
                        </div>
                        
                        <input type="submit" name="submit" value="ĐĂNG KÝ" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition duration-300 cursor-pointer">
                        
                        <div class="flex items-center my-4">
                            <div class="flex-grow border-t border-gray-300"></div>
                            <span class="mx-4 text-gray-500 text-sm">hoặc</span>
                            <div class="flex-grow border-t border-gray-300"></div>
                        </div>
                        
                        <a href="" class="flex items-center justify-center w-full border border-gray-300 py-3 rounded-lg hover:bg-gray-50 transition duration-300">
                            <img src="../../../images/google.png" alt="Google" class="w-5 h-5 mr-2">
                            <span class="text-gray-700 font-medium">Google</span>
                        </a>
                        
                        <div class="text-center mt-6">
                            <p class="text-gray-600">Đã có tài khoản? <a href="../login" class="text-blue-600 font-bold hover:underline">Đăng nhập</a></p>
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
                            <a href="#" class="bg-white p-1 border rounded shadow-sm"><img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa" class="h-6"></a>
                            <a href="#" class="bg-white p-1 border rounded shadow-sm"><img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard" class="h-6"></a>
                            <a href="#" class="bg-white p-1 border rounded shadow-sm"><img src="https://upload.wikimedia.org/wikipedia/commons/4/40/JCB_logo.svg" alt="JCB" class="h-6"></a>
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
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=https://unimarket.test" alt="QR Code" class="w-full h-full">
                            </div>
                            <div class="flex flex-col gap-2 justify-center">
                                <a href="#"><img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg" alt="App Store" class="h-8"></a>
                                <a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play" class="h-8"></a>
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
    </div>

    <script>
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePasswordRegister');
    const password = document.getElementById('password-register');

    if (togglePassword && password) {
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
    }
    </script>

</body>
</html>