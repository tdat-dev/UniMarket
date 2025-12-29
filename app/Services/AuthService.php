<?php
namespace App\Services;

use App\Models\User;

class AuthService {
    public function registerUser($data) {
        $userModel = new User();

        // 1. Kiểm tra email trùng
        if ($userModel->checkEmailExists($data['email'])) {
            return ['success' => false, 'message' => 'Email đã được sử dụng'];
        }

        // 2. Đăng ký
        // Lưu ý: Password đã được hash bên trong Model User->register() rồi 
        // hoặc bạn hash tại đây nếu muốn kiểm soát chặt hơn.
        // Ở đây mình giả định Model của bạn đã lo việc insert.
        $userModel->register([
            'full_name' => $data['username'], // Map từ name='username' của form sang 'full_name' của DB
            'email'     => $data['email'],
            'password'  => $data['password'],
            'phone_number' => $data['phone'] ?? '',
            'address'   => $data['school'] ?? '',
            'major_id'  => 1 
        ]);

        return ['success' => true];
    }

    public function loginUser($email, $password) {
        $userModel = new User();
        $user = $userModel->login($email, $password);
        
        if ($user) {
            // Lưu session
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['user'] = [
                'id' => $user['id'],
                'full_name' => $user['full_name'],
                'email' => $user['email'],
                'role' => $user['role']
            ];
            return true;
        }
        return false;
    }
}