<?php

namespace App\Controllers;

class ProfileController extends BaseController
{
    public function index()
    {
        // Mock user data if not in session, or just rely on session
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Ensure login
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $this->view('profile/index', [
            'pageTitle' => 'Hồ sơ của tôi'
        ]);
    }

    public function wallet()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $this->view('profile/wallet', [
            'pageTitle' => 'Ví của tôi'
        ]);
    }

    public function reviews()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $this->view('profile/reviews', [
            'pageTitle' => 'Đánh giá của tôi'
        ]);
    }
}
