<?php
namespace App\Controllers;

use App\Services\AuthService;
use App\Validators\AuthValidator;

class AuthController extends BaseController
{
    // --- LOGIN (HIỂN THỊ FORM) ---
    public function login()
    {
        if (isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }
        $this->view('auth/login');
    }

    // --- XỬ LÝ ĐĂNG NHẬP ---
    public function processLogin()
    {
        // 1. Validate dữ liệu đầu vào
        $validator = new AuthValidator();
        $errors = $validator->validateLogin($_POST);

        if (!empty($errors)) {
            $this->view('auth/login', ['errors' => $errors]);
            return;
        }

        // 2. Gọi Service để kiểm tra đăng nhập
        $authService = new AuthService();
        $email = trim($_POST['username'] ?? ''); // Form Login name="username"
        $password = $_POST['password'] ?? '';

        if ($authService->loginUser($email, $password)) {
            // Đăng nhập thành công -> Chuyển về Home (/)
            // Lưu ý: Route '/' chính là HomeController::index (tương đương home/index)
            header('Location: /');
            exit;
        } else {
            $this->view('auth/login', [
                'errors' => ['login' => 'Email hoặc mật khẩu không chính xác'],
                'old' => ['username' => $email]
            ]);
        }
    }

    // --- REGISTER (HIỂN THỊ FORM) ---
    public function register()
    {
        if (isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }
        $this->view('auth/register');
    }

    // --- XỬ LÝ ĐĂNG KÝ (CÓ AUTO LOGIN) ---
    public function processRegister()
    {
        // 1. Validate dữ liệu
        $validator = new AuthValidator();
        $errors = $validator->validateRegister($_POST);

        if (!empty($errors)) {
            $this->view('auth/register', ['errors' => $errors]);
            return;
        }

        // 2. Gọi Service xử lý đăng ký
        $authService = new AuthService();
        $result = $authService->registerUser($_POST);

        if ($result['success']) {
            // --- THAY ĐỔI Ở ĐÂY: TỰ ĐỘNG ĐĂNG NHẬP ---

            // Lấy email và password người dùng vừa nhập
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // Gọi hàm login luôn (tạo session ngay lập tức)
            $authService->loginUser($email, $password);

            // Chuyển hướng thẳng về Trang chủ (Home)
            header('Location: /login');
            exit;
        } else {
            // Lỗi nghiệp vụ (ví dụ: Trùng email)
            $this->view('auth/register', [
                'errors' => ['email' => $result['message']]

            ]);
        }
    }

    // --- ĐĂNG XUẤT ---
    public function logout()
    {
        // Khởi động session nếu chưa có để còn destroy nó
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_destroy(); // Xóa sạch dữ liệu đăng nhập

        // SỬA DÒNG NÀY: Chuyển về /login thay vì /
        header('Location: /login');
        exit;
    }
}