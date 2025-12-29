<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    public function login()
    {
        // Chỉ hiển thị form đăng nhập
        $this->view('auth/login');
    }

    public function processLogin()
    {
        $errors = [];
        $success = false;

        $email = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['username'] = 'Email không hợp lệ';
        }
        if ($password === '') {
            $errors['password'] = 'Vui lòng nhập mật khẩu';
        }

        if (empty($errors)) {
            $userModel = new \App\Models\User();
            $user = $userModel->login($email, $password);
            if ($user) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'full_name' => $user['full_name'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];
                $success = true;
                header('Location: /home/index');
                exit;
            } else {
                $errors['login'] = 'Email hoặc mật khẩu không đúng';
            }
        }

        $this->view('auth/login', [
            'errors' => $errors,
            'success' => $success
        ]);
    }

    public function register()
    {
        // Chỉ hiển thị form đăng ký
        $this->view('auth/register');
    }

    public function processRegister()
    {
        $errors = [];
        $success = false;

        $full_name = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        $phone_number = trim($_POST['phone_number'] ?? '');
        $address = trim($_POST['school'] ?? '');
        $major_id = null;

        if ($full_name === '') {
            $errors['username'] = 'Vui lòng nhập họ tên';
        }
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không hợp lệ';
        }
        if (strlen($password) < 6) {
            $errors['password'] = 'Mật khẩu phải từ 6 ký tự';
        }
        if ($password !== $password_confirm) {
            $errors['password_confirm'] = 'Mật khẩu nhập lại không khớp';
        }

        $userModel = new \App\Models\User();
        if ($userModel->checkEmailExists($email)) {
            $errors['email'] = 'Email đã được sử dụng';
        }

        if (empty($errors)) {
            $userModel->register([
                'full_name' => $full_name,
                'email' => $email,
                'password' => $password,
                'phone_number' => $phone_number,
                'address' => $address,
                'major_id' => $major_id
            ]);
            $success = true;
            header('Location: /home/index');
            exit;
        }

        $this->view('auth/register', [
            'errors' => $errors,
            'success' => $success
        ]);
    }
}