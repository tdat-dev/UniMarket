<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng kí</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../css/styles.css">
</head>

<body>

    <header class="main-content">
        <div class="main-img">
            <div class="main-img-content">
                <img src="../../../images/homepage-with-text.png" alt="register image" class="main-illustration">
            </div>
        </div>
        <div class="main-login">
            <div class="login-logo">
                <img src="../../../images/UniMarketHead.svg" alt="UniMarket Logo">
            </div>
            <div class="login-form">
                <h2>Đăng ký</h2>
                <form action="" method="post" class="register-form">
                    <div class="input-row">
                        <div class="input-wrapper">
                            <input type="text" name="username" placeholder="Tên đăng nhập">
                        </div>
                        <div class="input-wrapper">
                            <input type="text" name="branch" placeholder="Ngành học">
                        </div>
                    </div>
                    <div class="input-wrapper">
                        <input type="text" name="school" placeholder="Trường học">
                    </div>
                    <div class="input-wrapper">
                        <input type="email" name="email" placeholder="Email">
                    </div>
                    <div class="input-wrapper">
                        <input type="number" name="phone" placeholder="Số điện thoại">
                    </div>
                    <div class="input-wrapper password-wrapper">
                        <input type="password" name="password" id="password-register" placeholder="Mật khẩu">
                        <span class="eye-icon" id="togglePasswordRegister">👁️</span>
                    </div>
                    <input type="submit" name="submit" value="ĐĂNG KÝ">
                    <p class="or"><span>hoặc</span></p>
                    <a href="" class="google-login">
                        <div class="google-g">
                            <img src="../../../images/google.png" alt="Google">
                        </div>
                        <span>Google</span>
                    </a>
                    <div class="links">
                        <p class="hhh">Đã có tài khoản?</p>
                        <a href="../login" class="Register">Đăng nhập</a>
                    </div>
                </form>
            </div>
        </div>
    </header>
    <?php include __DIR__ . '/../partials/footer.php'; ?>
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