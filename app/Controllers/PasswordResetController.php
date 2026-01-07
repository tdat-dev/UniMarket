<?php

namespace App\Controllers;

use App\Models\User;
use App\Services\EmailService;
use Exception;

class PasswordResetController
{
    private $userModel;
    private $emailService;

    public function __construct()
    {
        $this->userModel = new User();
        $this->emailService = new EmailService();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function showForgotForm()
    {
        // Nếu đã login thì redirect home
        if (isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }
        
        $step = $_SESSION['reset_step'] ?? 'email';
        require __DIR__ . '/../../resources/views/auth/forgot-password.php';
    }

    public function sendResetOtp()
    {
        $email = $_POST['email'] ?? '';

        if (empty($email)) {
             $_SESSION['error'] = 'Vui lòng nhập email';
             header('Location: /forgot-password');
             exit;
        }

        $user = $this->userModel->findByEmailFull($email);

        if (!$user) {
            $_SESSION['error'] = 'Email không tồn tại trong hệ thống';
            header('Location: /forgot-password');
            exit;
        }

        // Check lock
        if ($user['password_reset_locked_until'] && strtotime($user['password_reset_locked_until']) > time()) {
             $minutesLeft = ceil((strtotime($user['password_reset_locked_until']) - time()) / 60);
             $_SESSION['error'] = "Bạn đã nhập sai quá 5 lần. Vui lòng thử lại sau $minutesLeft phút.";
             header('Location: /forgot-password');
             exit;
        }

        // Generate OTP
        $otp = (string) rand(100000, 999999);
        
        // Save to DB
        $this->userModel->savePasswordResetToken($user['id'], $otp);

        // Send Email
        $sent = $this->emailService->sendPasswordResetEmail($user['email'], $user['full_name'], $otp);

        if ($sent) {
            $_SESSION['reset_email'] = $email;
            $_SESSION['reset_step'] = 'verify';
            $_SESSION['success'] = 'Mã xác nhận đã được gửi đến email của bạn';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra khi gửi email. Vui lòng thử lại.';
        }

        header('Location: /forgot-password');
        exit;
    }

    public function verifyOtp()
    {
        $otp = $_POST['otp'] ?? '';
        $email = $_SESSION['reset_email'] ?? '';

        if (!$email) {
            header('Location: /forgot-password');
            exit;
        }

        $user = $this->userModel->findByEmailFull($email);
        
        // Check lock again
        if ($user['password_reset_locked_until'] && strtotime($user['password_reset_locked_until']) > time()) {
             $minutesLeft = ceil((strtotime($user['password_reset_locked_until']) - time()) / 60);
             $_SESSION['error'] = "Tài khoản đang bị khóa. Thử lại sau $minutesLeft phút.";
             header('Location: /forgot-password');
             exit;
        }

        // Verify
        if ($user['password_reset_token'] === $otp && strtotime($user['password_reset_expires_at']) > time()) {
            // Success
            $_SESSION['reset_verified'] = true;
            unset($_SESSION['reset_step']); // Clear step to exit loop
            header('Location: /reset-password');
            exit;
        } else {
            // Fail
            $isLocked = $this->userModel->incrementResetAttempts($user['id']);
            if ($isLocked) {
                $_SESSION['error'] = 'Bạn đã nhập sai quá 5 lần. Vui lòng chờ 5 phút.';
            } else {
                $_SESSION['error'] = 'Mã xác nhận không đúng hoặc đã hết hạn.';
            }
            header('Location: /forgot-password');
            exit;
        }
    }

    public function showResetForm()
    {
        if (empty($_SESSION['reset_verified']) || empty($_SESSION['reset_email'])) {
            header('Location: /forgot-password');
            exit;
        }
        require __DIR__ . '/../../resources/views/auth/reset-password.php';
    }

    public function resetPassword()
    {
        if (empty($_SESSION['reset_verified']) || empty($_SESSION['reset_email'])) {
            header('Location: /forgot-password');
            exit;
        }

        $password = $_POST['password'] ?? '';
        $confirm = $_POST['password_confirm'] ?? '';

        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Mật khẩu phải có ít nhất 6 ký tự';
            header('Location: /reset-password');
            exit;
        }

        if ($password !== $confirm) {
            $_SESSION['error'] = 'Mật khẩu xác nhận không khớp';
            header('Location: /reset-password');
            exit;
        }

        $user = $this->userModel->findByEmailFull($_SESSION['reset_email']);
        
        $this->userModel->updatePassword($user['id'], $password);
        $this->userModel->clearPasswordResetToken($user['id']);

        // Clean session
        unset($_SESSION['reset_email']);
        unset($_SESSION['reset_verified']);
        unset($_SESSION['reset_step']);

        $_SESSION['success'] = 'Đổi mật khẩu thành công. Vui lòng đăng nhập.';
        header('Location: /login');
        exit;
    }
}
