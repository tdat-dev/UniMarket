<?php
namespace App\Controllers;

use App\Services\AuthService;
use App\Validators\AuthValidator;

class AuthController extends BaseController
{
    // --- LOGIN ---
    public function login() {
        if (isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }
        $this->view('auth/login');
    }

    public function processLogin() {
        // 1. Validate
        $validator = new AuthValidator();
        $errors = $validator->validateLogin($_POST);

        if (!empty($errors)) {
            $this->view('auth/login', ['errors' => $errors]);
            return;
        }

        // 2. Xử lý Login qua Service
        $authService = new AuthService();
        $email = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($authService->loginUser($email, $password)) {
            header('Location: /');
            exit;
        } else {
            $this->view('auth/login', [
                'errors' => ['login' => 'Email hoặc mật khẩu không chính xác'],
                'old' => ['username' => $email]
            ]);
        }
    }

    // --- REGISTER ---
    public function register() {
        $this->view('auth/register');
    }

    public function processRegister() {
        // 1. Validate
        $validator = new AuthValidator();
        $errors = $validator->validateRegister($_POST);

        if (!empty($errors)) {
            $this->view('auth/register', ['errors' => $errors]);
            return;
        }

        // 2. Xử lý Register qua Service
        $authService = new AuthService();
        $result = $authService->registerUser($_POST);

        if ($result['success']) {
            header('Location: /login'); // Đăng ký xong chuyển về Login
            exit;
        } else {
            // Lỗi nghiệp vụ (ví dụ: Trùng email)
            $this->view('auth/register', [
                'errors' => ['email' => $result['message']]
            ]);
        }
    }

    

    public function logout() {
        session_destroy();
        header('Location: /');
        exit;
    }
}