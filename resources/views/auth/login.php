<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../../../css/styles.css">
</head>

<body>

    <header class="main-content">
        <div class="main-img">
            <div class="main-img-content">
                <img src="../../../images/homepage-with-text.svg" alt="login image" class="main-illustration">
            </div>
        </div>
        <div class="main-login">
            <div class="login-logo">
                <img src="../../../images/UniMarketHead.svg" alt="UniMarket Logo">
            </div>
            <div class="login-form">
                <h2>Đăng nhập</h2>
                <form action="" method="post">
                    <div class="input-wrapper">
                        <input type="text" name="username" placeholder="Email/Số điện thoại/Tên đăng nhập">
                    </div>
                    <div class="input-wrapper password-wrapper">
                        <input type="password" name="password" id="password" placeholder="Mật khẩu">
                        <span class="eye-icon" id="togglePassword">👁️</span>
                    </div>
                    <input type="submit" name="submit" value="ĐĂNG NHẬP">
                    <a href="" name="forgot-password">Quên mật khẩu</a>
                    <p class="or"><span>HOẶC</span></p>
                    <a href="" class="google-login">
                        <div class="google-g">
                            <img src="../../../images/google.png" alt="Google">
                        </div>
                        <span>Google</span>
                    </a>
                    <div class="links">
                        <p class="hhh">Chưa có tài khoản?</p>
                        <a href="../register" class="Register">Đăng ký</a>
                    </div>
                </form>
            </div>
        </div>
    </header>
    <?php include __DIR__ . '/../partials/footer.php'; ?>
    <script>
        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        
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