<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: white;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .register {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            width: 100%;
            max-width: 480px;
        }

        .registr-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .registr-logo img {
            height: 100px;

        }

        .register-form h2 {
            color: #2C3E50;
            font-size: 32px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 25px;
        }

        .register-form input[type="text"],
        .register-form input[type="email"],
        .register-form input[type="number"],
        .register-form input[type="password"] {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #E8ECF1;
            border-radius: 10px;
            font-size: 15px;
            color: #2C3E50;
            transition: all 0.3s;
            font-family: 'Roboto', sans-serif;
            margin-bottom: 18px;
        }

        .register-form input:focus {
            outline: none;
            border-color: #5B8DEE;
            box-shadow: 0 0 0 3px rgba(91, 141, 238, 0.1);
        }

        .register-form input::placeholder {
            color: #A0AEC0;
        }

        .register-form br {
            display: none;
        }

        .input-row {
            display: flex;
            gap: 15px;
            margin-bottom: 18px;
        }

        .input-row input {
            flex: 1;
            margin-bottom: 0 !important;
        }

        .register-form input[type="submit"] {
            width: 100%;
            padding: 15px;
            background: #5B8DEE;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
        }

        .register-form input[type="submit"]:hover {
            background: #4A7BD8;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(91, 141, 238, 0.3);
        }

        .or {
            display: flex;
            align-items: center;
            margin: 25px 0;
            color: #A0AEC0;
            font-size: 14px;
            font-weight: 500;
            text-align: center;
        }

        .or::before,
        .or::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #E8ECF1;
        }

        .or::before {
            margin-right: 15px;
        }

        .or::after {
            margin-left: 15px;
        }

        .google-register {
            width: 100%;
            padding: 14px;
            background: white;
            border: 2px solid #E8ECF1;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            color: #2C3E50;
            font-size: 15px;
            font-weight: 500;
        }
        
        .google-register:hover {
            border-color: #5B8DEE;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .google-register img {
            width: 22px;
            height: 22px;
        }

        .links {
            text-align: center;
            margin-top: 25px;
        }

        .links .hhhh {
            display: inline;
            color: #7F8C9A;
            font-size: 14px;
        }

        .links .Register {
            color: #5B8DEE;
            text-decoration: none;
            font-weight: 600;
            margin-left: 5px;
            font-size: 14px;
        }

        .links .Register:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register">
        <div class="registr-logo">
           <img src="../../../public/images/UniMarketHead.svg" alt="logo"> 
        </div>
        <div class="register-form">
            <form action="" method="post">
                <h2>Đăng Ký</h2>
                <div class="input-row">
                    <input type="text" name="username" placeholder="Tên đăng nhập">
                    <input type="text" name="branch" placeholder="Ngành học">
                </div>
                <input type="text" name="school" placeholder="Trường học">
                <br>
                <input type="email" name="email" placeholder="Email">
                <br>
                <input type="number" name="phone" placeholder="Số điện thoại">
                <br>
                <input type="password" name="password" placeholder="Mật khẩu">
                <br>
                <input type="submit" name="submit" value="Đăng Ký">
            
        </div>
        <p class="or">Hoặc</p>
        <a href="" class="google-register">
            <img src="../../../public/images/google.png" alt="Google">
                    <span>Google</span>
        </a>
        <div class="links">
                    <p class="hhhh">Đã có tài khoản?</p>
                    <a href="" class="Register">Đăng nhập</a>
                </div>
             </form>
    </div>

</body>
</html>