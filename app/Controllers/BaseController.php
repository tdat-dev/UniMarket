<?php

namespace App\Controllers;

use App\Models\Cart;
use App\Models\SearchKeyword;
use App\Core\RedisCache;
use \App\Models\Setting;

class BaseController
{

    public function __construct()
    {
        // Khởi tạo session nếu cần
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->checkUserLocked();
        $this->checkMaintenance();
    }

    /**
     * Lấy số lượng sản phẩm trong giỏ hàng
     */
    protected function getCartCount()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $cartCount = 0;

        if (isset($_SESSION['user']['id'])) {
            // Đã đăng nhập -> Đếm từ Database
            $cartModel = new Cart();
            $cartCount = $cartModel->countItems($_SESSION['user']['id']);
        } else {
            // Chưa đăng nhập -> Đếm từ Session
            if (isset($_SESSION['cart'])) {
                foreach ($_SESSION['cart'] as $item) {
                    $qty = is_array($item) ? ($item['quantity'] ?? 1) : $item;
                    $cartCount += $qty;
                }
            }
        }

        return $cartCount;
    }

    /**
     * Lấy top keywords phổ biến (có cache 5 phút)
     * Ưu tiên dùng Redis, fallback về Session nếu Redis không khả dụng
     */
    protected function getTopKeywords()
    {
        $cacheKey = 'top_keywords';
        $cacheTTL = 300; // 5 phút

        // Thử dùng Redis trước
        $redis = RedisCache::getInstance();

        if ($redis->isAvailable()) {
            // Redis khả dụng → Dùng Redis
            $topKeywords = $redis->get($cacheKey);

            if ($topKeywords === null) {
                // Cache miss → Query DB
                $keywordModel = new SearchKeyword();
                $topKeywords = $keywordModel->getTopKeywords(4);

                // Lưu vào Redis
                $redis->set($cacheKey, $topKeywords, $cacheTTL);
            }

            return $topKeywords;
        }

        // Redis không khả dụng → Fallback về Session cache
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (
            !isset($_SESSION['cached_top_keywords']) ||
            !isset($_SESSION['keywords_cache_time']) ||
            (time() - $_SESSION['keywords_cache_time']) > $cacheTTL
        ) {
            // Cache hết hạn → Query DB
            $keywordModel = new SearchKeyword();
            $topKeywords = $keywordModel->getTopKeywords(4);

            // Lưu vào session
            $_SESSION['cached_top_keywords'] = $topKeywords;
            $_SESSION['keywords_cache_time'] = time();
        } else {
            // Dùng cache
            $topKeywords = $_SESSION['cached_top_keywords'];
        }

        return $topKeywords;
    }

    /**
     * Render view với data
     */
    protected function view($viewPath, $data = [])
    {
        // Tự động thêm cartCount và topKeywords vào mọi view
        $data['cartCount'] = $this->getCartCount();
        $data['topKeywords'] = $this->getTopKeywords();

        extract($data);

        // Tìm file view .php
        $viewFile = __DIR__ . '/../../resources/views/' . $viewPath . '.php';

        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            echo "View not found: $viewPath";
        }
    }

    /**
     * Kiểm tra user hiện tại có bị khóa không
     * Nếu bị khóa -> logout và redirect về login
     */
    protected function checkUserLocked()
    {
        if (isset($_SESSION['user']['id'])) {
            $userModel = new \App\Models\User();
            $user = $userModel->find($_SESSION['user']['id']);

            // Nếu user bị khóa hoặc không tồn tại
            if (!$user || !empty($user['is_locked'])) {
                // Xóa session
                unset($_SESSION['user']);
                session_destroy();

                // Redirect về login với thông báo
                $_SESSION['error'] = 'Tài khoản của bạn đã bị khóa.';
                header('Location: /login');
                exit;
            }
        }
    }

    /**
     * Kiểm tra chế độ bảo trì
     * Nếu bật -> hiển thị trang bảo trì (trừ admin)
     */
    protected function checkMaintenance()
    {
        // Bỏ qua nếu là admin
        if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin') {
            return;
        }

        // Bỏ qua các route admin và login
        $currentUri = $_SERVER['REQUEST_URI'] ?? '/';
        if (strpos($currentUri, '/admin') === 0 || strpos($currentUri, '/login') === 0) {
            return;
        }

        // Kiểm tra maintenance mode
        $settingModel = new Setting();
        $maintenanceMode = $settingModel->get('maintenance_mode', '0');

        if ($maintenanceMode === '1') {
            $message = $settingModel->get('maintenance_message', 'Website đang bảo trì, vui lòng quay lại sau.');

            // Hiển thị trang bảo trì
            http_response_code(503);
            include __DIR__ . '/../../resources/views/maintenance.php';
            exit;
        }
    }
}
