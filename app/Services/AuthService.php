<?php
namespace App\Services;

use App\Models\User;
use App\Models\Cart;

class AuthService
{
    public function registerUser($data)
    {
        $userModel = new User();

        // 1. Kiểm tra email trùng
        if ($userModel->checkEmailExists($data['email'])) {
            return ['success' => false, 'message' => 'Email đã được sử dụng'];
        }

        // 2. Đăng ký
        $userModel->register([
            'full_name' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone_number' => $data['phone'] ?? '',
            'address' => $data['school'] ?? '',
        ]);

        return ['success' => true];
    }

    public function loginUser($email, $password)
    {
        $userModel = new User();
        $user = $userModel->login($email, $password);

        if ($user) {
            // Lưu session
            if (session_status() === PHP_SESSION_NONE)
                session_start();

            // Lưu giỏ hàng từ session trước khi ghi đè
            $sessionCart = $_SESSION['cart'] ?? [];

            $_SESSION['user'] = [
                'id' => $user['id'],
                'full_name' => $user['full_name'],
                'email' => $user['email'],
                'role' => $user['role']
            ];

            // Merge giỏ hàng từ Session vào Database
            if (!empty($sessionCart)) {
                $cartModel = new Cart();
                $cartModel->mergeFromSession($user['id'], $sessionCart);

                // Xóa giỏ hàng trong session sau khi đã merge
                unset($_SESSION['cart']);
            }

            return true;
        }
        return false;
    }
}