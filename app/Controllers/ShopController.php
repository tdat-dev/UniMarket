<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\User;

class ShopController extends BaseController
{
    public function index()
    {
        $userId = $_GET['id'] ?? null;

        // If no ID provided, check if logged in -> My Shop
        if (!$userId) {
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            if (isset($_SESSION['user']['id'])) {
                $userId = $_SESSION['user']['id'];
                // Optional: flag to show 'edit' controls in view
            } else {
                 header('Location: /login');
                 exit;
            }
        }

        $userModel = new User();
        $seller = $userModel->find($userId);

        if (!$seller) {
            echo "Không tìm thấy cửa hàng";
            exit;
        }

        $productModel = new Product();
        $products = $productModel->getByUserId($userId);

        $this->view('shop/index', [
            'seller' => $seller,
            'products' => $products,
            'isOwner' => (isset($_SESSION['user']['id']) && $_SESSION['user']['id'] == $userId)
        ]);
    }

    public function orders()
    {
         if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        // Mock orders/sales data
        $this->view('shop/orders', [
            'pageTitle' => 'Đơn bán hàng'
        ]);
    }
}
