
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
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
        .logo {
            background-color: #ffffff;
            padding: 20px 50px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .login-logo {
            display: flex;
            align-items: center;
            gap: 15px;
            position: relative;
            left: 80px;
            top: 10px;

        }
        .login-logo img {
            height: 45px;
            width: auto;
            transform: scale(4.0);
        }
        .name-logo {
            font-size: 28px;
            font-weight: bold;
            color: #4a7ba7;
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
            flex: 1;
            max-width: 500px;
        } 
        .main-img img{
            width: 100%;
            height: auto;
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
        }
        .pay ul li {
            margin-bottom: 10px;
        }
        .pay ul li a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
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
        .download ul {
            list-style: none;
        }
        .download ul li {
            margin-bottom: 10px;
        }
        .download ul li a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
        }
        .download ul li a:hover {
            color: #4a7ba7;
        }
        .footer{
            position: relative;
            left: 250px;

        }

    </style>
</head>
<body>
    <header class="logo">
        <div class="login-logo">
            <img src="../../../public/images/logo.svg" alt="logo">
            <span class="name-logo">UniMarket</span>
        </div>
    </header>
    <main class="main-content">
        <div class="main-img">
            <img src="../../../public/images/homepage-with-text.png  " alt="login image">
        </div>
        <div class="main-login">
            <h2>Đăng nhập</h2>
            <form action="" method="post">
                <input type="text" name="username" placeholder="Email/Số điện thoại/Tên đăng nhập">
                <br>
                <input type="password" name="password" placeholder="Mật khẩu">
                <input type="submit" name="submit" value="Đăng nhập">
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
    </main>
    <footer class="footer">
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
        </div>
        <div class="monitor">
            <h3 class="Theo-doi">Theo dõi</h3>
            <ul>
                <li>
                    <i class="fa-brands fa-facebook"></i>
                    <a href="#">Facebook</a></li>
                <li><a href="#">Instagram</a></li>
                <li><a href="#">LinkedIn</a></li>
            </ul>
        </div>
        <div class="download">
            <h3 class="tai">Tải ứng dụng</h3>
        </div>
    </footer>
</body> 
</html>
<!-- dhndhhhshh --