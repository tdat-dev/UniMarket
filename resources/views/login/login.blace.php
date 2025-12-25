
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .main-content {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px;
            gap: 50px;
            min-height: calc(100vh - 85px);
            
        }
        .main-img{
            background: linear-gradient(90deg, #2C67C8 0%, #1990AA 100%);
            flex: 1;
            width: 350px;
            height: 550px;
            overflow: hidden;
            
        } 
        .main-img img{
            width: 100%;
            height: 100%;
            object-fit: cover;

        }
        .main-login{
            flex: 1;
            max-width: 400px;
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border: 1px solid #e0e0e0;
        }
        .main-login h2 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }
        .main-login input[type="text"],
        .main-login input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }
        .main-login input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #4a7ba7;
            border: none;
            border-radius: 4px;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 15px;
        }
        .main-login input[type="submit"]:hover {
            background-color: #3a6a97;
        }
        .or {
            text-align: center;
            color: #666;
            margin: 20px 0;
            position: relative;
        }
        .or::before,
        .or::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 40%;
            height: 1px;
            background-color: #ccc;
        }
        .or::before {
            left: 0;
        }
        .or::after {
            right: 0;
        }
        .google-login {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 12px;
            border: 1px solid #dadce0;
            border-radius: 4px;
            background-color: #fff;
            text-decoration: none;
            color: #3c4043;
            font-size: 14px;
            font-weight: 500;
            gap: 12px;
            transition: background-color 0.2s, box-shadow 0.2s;
            margin-bottom: 20px;
        }
        .google-login:hover {
            background-color: #f8f9fa;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .google-login img {
            width: 18px;
            height: 18px;
        }
        .links {
            text-align: center;
        }
        .forgot-password, .Register {
            text-decoration: none;
            font-size: 14px;
            margin: 0 10px;
        }
        .forgot-password {
            color: #4a7ba7;
        }
        .Register{
            color: #4a7ba7;
        }
        .Register:hover {
            text-decoration: underline;
        }
        a[name="forgot-password"] {
            color: #4a7ba7;
            text-decoration: none;
            font-size: 14px;
            display: block;
            /* text-align: center; */
            margin-bottom: 15px;
        }
        .links{
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
        }
        .hhh{
            font-size: 14px;
            color: #666;    
        }
        .footer {
            background-color: #f5f5f5;
            padding: 40px 50px;
            display: flex;
            gap: 100px;
        }
        .service h3 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #333;
        }
        .service ul {
            list-style: none;
        }
        .service ul li {
            margin-bottom: 10px;
        }
        .service ul li a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
        }
        .service ul li a:hover {
            color: #4a7ba7;
        }
        .pay h3 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #333;
        }
        .pay ul {
            list-style: none;
            display: flex;
            gap: 15px;
            align-items: center;
        }
        .pay ul li {
            margin-bottom: 0;
        }
        .pay ul li a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
        }
        .pay ul li a img {
            width: 50px;
            height: auto;
        }
        .pay ul li a:hover {
            color: #4a7ba7;
        }
        .monitor h3 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #333;
        }
        .monitor ul {
            list-style: none;
        }
        .monitor ul li {
            margin-bottom: 10px;
        }
        .monitor ul li a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
        }
        .monitor ul li a:hover {
            color: #4a7ba7;
        }
        .download h3 {
            font-size: 18px;
            margin-bottom: 15px;
            color: #333;
        }
        .download-content {
            display: flex;
            gap: 15px;
            align-items: flex-start;
        }
        .qr-code {
            width: 80px;
            height: 80px;
            background-color: #fff;
            border: 1px solid #ddd;
            padding: 5px;
        }
        .qr-code img {
            width: 100%;
            height: 100%;
        }
        .store-links {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .store-links a {
            display: block;
        }
        .store-links a img {
            width: 120px;
            height: auto;
        }
        .footer{
            position: relative;
            left: 250px;

        }
        .login-logo{
            text-align: center;
            margin-bottom: 10px;
        }
        .login-form h2{
            text-align: center;
            margin-bottom: 20px;
        }
        .copyright {
            background-color: #f5f5f5;
            padding: 20px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid #e0e0e0;
            font-size: 14px;
            color: #666;
        }
        .copyright-text {
            margin: 0;
        }
        .country {
            margin: 0;
            position: relative;
            right: 700px;

        }
        .chan-trang{
            background-color: #f5f5f5;
        }
        .policies {
            padding: 20px 50px;
        }
        .policies ul {
            list-style: none;
            display: flex;
            justify-content: center;
            gap: 30px;
            margin: 0;
            padding: 0;
        }
        .policies ul li a {
            color: #666;
            text-decoration: none;
            font-size: 12px;
            font-weight: 500;
        }
        .policies ul li a:hover {
            color: #4a7ba7;
        }
        .footer-bottom {
            background-color: #f5f5f5;
            padding: 20px 50px;
            /* border-top: 1px solid #e0e0e0; */
        }
        .company-info {
            text-align: center;
            font-size: 12px;
            color: #666;
            line-height: 1.8;
        }
        .company-info p {
            margin: 5px 0;
        }
        .policies, .footer-bottom {
            background-color: rgba(184, 177, 177, 0.65);;
            
        }
  
  </style>
</head>
<body>
    
    <header class="main-content">
        <div class="main-img">
            <img src="../../../public/images/homepage-with-text.png  " alt="login image">
        </div>
        <div class="main-login">
            <div class="login-logo">
                <img src="../../../public/images/UniMarketHead.svg" alt="UniMarket Logo" width="150">
            </div>
            <div class="login-form">
            <h2>Đăng nhập</h2>
            <form action="" method="post">
                <input type="text" name="username" placeholder="Email/Số điện thoại/Tên đăng nhập">
                <br>
                <input type="password" name="password" placeholder="Mật khẩu">
                <input type="submit" name="submit" value="Đăng nhập">
            </div>
                <a href="" name="forgot-password">Quên mật khẩu</a>
                <p class="or">Hoặc</p>
                <a href="" class="google-login">
                    <img src="../../../public/images/google.png" alt="Google">
                    <span>Google</span>
                </a>
                <div class="links">
                    <p class="hhh">Chưa có tài khoản?</p>
                    <a href="" class="Register">Đăng kí</a>
                </div>
            </form>
        </div>
    </header>
    <main class="footer">
        <div class="service">
            <h3 class="dich-vu">Dịch vụ khách hàng</h3>
            <ul>
                <li><a href="#">Trung Tâm trợ giúp</a></li>
                <li><a href="#">Hướng dẫn mua hàng</a></li>
                <li><a href="#">Hướng dẫn bán hàng</a></li>
                <li><a href="#">Đơn hàng</a></li>
                <li><a href="#">Trả hàng/hoàn tiền</a></li>
                <li><a href="#">Liên hệ</a></li>
                <li><a href="#">Chính sách bảo hành</a></li>
            </ul>
        </div>
        <div class="pay">
            <h3 class="thanh-toan">Thanh toán</h3>
            <ul>
                <li><a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" alt="Visa"></a></li>
                <li><a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" alt="Mastercard"></a></li>
                <li><a href="#"><img src="https://upload.wikimedia.org/wikipedia/commons/4/40/JCB_logo.svg" alt="JCB"></a></li>
        </div>
        <div class="monitor">
            <h3 class="Theo-doi">Theo dõi</h3>
            <ul>
                <li>
                    <i class="fa-brands fa-facebook"></i>
                    <a href="#">Facebook</a></li>
                <li>
                    <i class="fa-brands fa-square-instagram"></i>    
                    <a href="#">Instagram</a></li>
                <li>
                    <i class="fa-brands fa-linkedin"></i>
                    <a href="#">LinkedIn</a></li>
            </ul>
        </div>
        <div class="download">
            <h3 class="tai">Tải ứng dụng UniMarket</h3>
            <div class="download-content">
                <div class="qr-code">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=https://unimarket.test" alt="QR Code">
                </div>
                <div class="store-links">
                    <a href="#">
                        <img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg" alt="App Store">
                    </a>
                    <a href="#">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play">
                    </a>
                    <a href="#">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/9/94/Huawei_AppGallery_Logo.svg" alt="AppGallery">
                    </a>
                </div>
            </div>
        </div>
    </main>
        <div class="copyright">
        <p class="copyright-text">© 2025 UniMarket. Tất cả các quyền được bảo lưu.</p>
        <p class="country">Quốc gia & Khu vực: Việt Nam</p>
    </div>
   <footer class="chan-trang">
    <div class="policies">
        <ul>
            <li><a href="#">CHÍNH SÁCH BẢO MẬT</a></li>
            <li><a href="#">QUY CHẾ HOẠT ĐỘNG</a></li>
            <li><a href="#">CHÍNH SÁCH VẬN CHUYỂN</a></li>
            <li><a href="#">CHÍNH SÁCH TRẢ HÀNG VÀ HOÀN TIỀN</a></li>
        </ul>
    </div>
    <div class="footer-bottom">
        <div class="company-info">
            <p><strong>Công ty TNHH Shopee</strong></p>
            <p>Địa chỉ: Tầng 4-5-6, Tòa nhà Capital Place, số 29 đường Liễu Giai, Phường Ngọc Hà, Thành phố Hà Nội, Việt Nam</p>
            <p>Chăm sóc khách hàng: Gọi tổng đài Shopee (miễn phí) hoặc Trò chuyện với Shopee ngay trên Trung tâm trợ giúp</p>
            <p>Chịu Trách Nhiệm Quản Lý Nội Dung: Nguyễn Bùi Anh Tuấn</p>
            <p>Mã số doanh nghiệp: 0106773786 do Sở Kế hoạch và Đầu tư TP Hà Nội cấp lần đầu ngày 10/02/2015</p>
            <p>© 2015 - Bản quyền thuộc về Công ty TNHH Shopee</p>
        </div>
    </div>
   </footer>

</body> 
</html>
<!-- dhndhhhshh --