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
                <img src="../../../images/homepage-with-text.png" alt="login image" class="main-illustration">
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
                    <p class="or"><span>hoặc</span></p>
                    <a href="" class="google-login">
                        <div class="google-g">
                            <img src="../../../images/google.png" alt="Google">
                        </div>
                        <span>Google</span>
                    </a>
                    <div class="links">
                        <p class="hhh">Chưa có tài khoản?</p>
                        <a href="register.php" class="Register">Đăng ký</a>
                    </div>
                </form>
            </div>
        </div>
    </header>
    <main class="footer">
        <div class="footer-container">
            <div class="service">
                <h3>DỊCH VỤ KHÁCH HÀNG</h3>
                <ul>
                    <li><a href="#">Trung Tâm Trợ Giúp Uni</a></li>
                    <li><a href="#">Hướng Dẫn Mua Hàng/Đặt Hàng</a></li>
                    <li><a href="#">Hướng Dẫn Bán Hàng</a></li>
                    <li><a href="#">Đơn Hàng</a></li>
                    <li><a href="#">Trả Hàng/Hoàn Tiền</a></li>
                    <li><a href="#">Liên Hệ Uni</a></li>
                    <li><a href="#">Chính Sách Bảo Hành</a></li>
                </ul>
            </div>
            <div class="pay">
                <h3>THANH TOÁN</h3>
                <ul>
                    <li><a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg"
                                alt="Visa"></a></li>
                    <li><a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg"
                                alt="Mastercard"></a></li>
                    <li><a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/4/40/JCB_logo.svg"
                                alt="JCB"></a></li>
                </ul>
            </div>
            <div class="monitor">
                <h3>THEO DÕI UNIMARKET</h3>
                <ul>
                    <li>
                        <i class="fa-brands fa-facebook"></i>
                        <a href="#">Facebook</a>
                    </li>
                    <li>
                        <i class="fa-brands fa-square-instagram"></i>
                        <a href="#">Instagram</a>
                    </li>
                    <li>
                        <i class="fa-brands fa-linkedin"></i>
                        <a href="#">LinkedIn</a>
                    </li>
                </ul>
            </div>
            <div class="download">
                <h3>TẢI ỨNG DỤNG UNIMARKET</h3>
                <div class="download-content">
                    <div class="qr-code">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=https://unimarket.test"
                            alt="QR Code">
                    </div>
                    <div class="store-links">
                        <a href="#">
                            <img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg"
                                alt="App Store">
                        </a>
                        <a href="#">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg"
                                alt="Google Play">
                        </a>
                        <a href="#">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/9/94/Huawei_AppGallery_Logo.svg"
                                alt="AppGallery">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div class="copyright">
        <div class="copyright-container">
            <p class="copyright-text">© 2025 UniMarket. Tất cả các quyền được bảo lưu.</p>
            <div class="country-section">
                <p class="country-label">Quốc gia & Khu vực:</p>
                <p class="country-value">Việt Nam</p>
            </div>
        </div>
    </div>
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