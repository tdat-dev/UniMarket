<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\GoogleOAuthService;
use App\Models\User;

/**
 * Google Auth Controller
 * 
 * Xử lý đăng nhập/đăng ký bằng Google OAuth.
 * 
 * @package App\Controllers
 */
class GoogleAuthController extends BaseController
{
    private GoogleOAuthService $googleService;
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->googleService = new GoogleOAuthService();
        $this->userModel = new User();
    }

    /**
     * Redirect đến trang đăng nhập Google
     */
    public function redirectToGoogle(): void
    {
        if (!$this->googleService->isConfigured()) {
            $_SESSION['error'] = 'Google OAuth chưa được cấu hình. Vui lòng liên hệ admin.';
            $this->redirect('/login');
        }

        $authUrl = $this->googleService->getAuthUrl();
        header('Location: ' . $authUrl);
        exit;
    }

    /**
     * Xử lý callback từ Google
     */
    public function handleGoogleCallback(): void
    {
        $code = $this->query('code');

        if ($code === null) {
            $_SESSION['error'] = 'Đăng nhập Google thất bại. Vui lòng thử lại.';
            $this->redirect('/login');
        }

        $googleUser = $this->googleService->getUserInfo($code);

        if ($googleUser === null) {
            $_SESSION['error'] = 'Không thể lấy thông tin từ Google. Vui lòng thử lại.';
            $this->redirect('/login');
        }

        $existingUser = $this->userModel->findByEmail($googleUser['email']);

        if ($existingUser !== null) {
            $this->loginExistingUser($existingUser);
        } else {
            $this->registerAndLoginNewUser($googleUser);
        }
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Đăng nhập user đã tồn tại
     */
    private function loginExistingUser(array $user): never
    {
        // Đánh dấu email đã xác minh (Google đã verify)
        $this->userModel->markAsVerified((int) $user['id']);

        $_SESSION['user'] = [
            'id' => $user['id'],
            'full_name' => $user['full_name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'email_verified' => true,
        ];

        $_SESSION['success'] = 'Đăng nhập thành công!';
        session_regenerate_id(true);

        $this->redirect('/');
    }

    /**
     * Đăng ký user mới từ Google và đăng nhập
     */
    private function registerAndLoginNewUser(array $googleUser): never
    {
        $newUserId = $this->registerGoogleUser($googleUser);

        if ($newUserId === 0) {
            $_SESSION['error'] = 'Đăng ký thất bại. Vui lòng thử lại.';
            $this->redirect('/register');
        }

        $newUser = $this->userModel->find($newUserId);

        if ($newUser === null) {
            $_SESSION['error'] = 'Đăng ký thất bại. Vui lòng thử lại.';
            $this->redirect('/register');
        }

        $_SESSION['user'] = [
            'id' => $newUser['id'],
            'full_name' => $newUser['full_name'],
            'email' => $newUser['email'],
            'role' => $newUser['role'],
            'email_verified' => true,
        ];

        $_SESSION['success'] = 'Đăng ký thành công! Chào mừng bạn đến với Zoldify.';
        session_regenerate_id(true);

        $this->redirect('/');
    }

    /**
     * Đăng ký user mới từ data Google
     * 
     * @return int User ID hoặc 0 nếu lỗi
     */
    private function registerGoogleUser(array $googleUser): int
    {
        $randomPassword = bin2hex(random_bytes(16));

        $userData = [
            'full_name' => $googleUser['full_name'],
            'email' => $googleUser['email'],
            'password' => $randomPassword,
            'phone_number' => null,
            'address' => null,
            'email_verified' => 1,
        ];

        try {
            return $this->userModel->register($userData);
        } catch (\Exception $e) {
            error_log("Register Google User Error: " . $e->getMessage());
            return 0;
        }
    }
}
