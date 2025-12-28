<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/app.cs">
    <style>
    body {
        font-family: 'Roboto', sans-serif;
    }
    </style>
</head>

<body class="flex flex-col min-h-screen">

    <?php include __DIR__ . '/../partials/header.php'; ?>

    <div class="bg-[#4e89ff] flex-grow flex items-center justify-center py-10">
        <div
            class="w-full max-w-[1200px] mx-auto px-4 flex flex-col lg:flex-row items-center justify-between gap-10 lg:gap-20">

            <div class="hidden lg:flex flex-col items-start justify-center lg:w-1/2">
                <div class="relative w-full max-w-4xl"> <img src="../../../images/homepage-with-text.png"
                        alt="UniMarket Illustration"
                        class="w-full h-auto object-contain drop-shadow-2xl select-none pointer-events-none">
                </div>
            </div>

            <div class=" w-full max-w-md bg-white rounded-3xl shadow-2xl p-8 md:p-10">
                <div class="text-center mb-6">
                    <img src="../../../images/UniMarketHead.svg" alt="UniMarket Logo" class="h-24 mx-auto mb-4">
                    <h2 class="text-3xl font-bold text-gray-800">Chào mừng trở lại!</h2>
                    <p class="text-gray-400 text-sm mt-1">Đăng nhập để tiếp tục mua sắm</p>
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
                            class="text-[#5A88FF] hover:text-blue-700 text-sm font-medium">Quên mật khẩu?</a>
                    </div>

                    <div class="flex items-center my-4">
                        <div class="flex-grow border-t border-gray-200"></div>
                        <span class="mx-4 text-gray-400 text-xs font-medium uppercase">HOẶC</span>
                        <div class="flex-grow border-t border-gray-200"></div>
                    </div>

                    <a href=""
                        class="flex items-center justify-center w-full border border-gray-300 py-3 rounded-lg hover:bg-gray-50 transition duration-300 group bg-white">
                        <img src="../../../images/google.png" alt="Google" class="w-5 h-5 mr-3">
                        <span class="text-gray-700 font-medium group-hover:text-gray-900">Đăng nhập bằng
                            Google</span>
                    </a>

                    <div class="text-center mt-6">
                        <p class="text-gray-500 text-sm">Chưa có tài khoản? <a href="../register"
                                class="text-[#5A88FF] font-bold hover:underline">Đăng ký ngay</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../partials/footer.php'; ?>

    <script>
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');

    if (togglePassword && password) {
        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            const icon = this.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            }
        });
    }
    </script>

</body>

</html>