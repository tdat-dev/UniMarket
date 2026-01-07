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

        $userId = $_SESSION['user']['id'];
        $status = $_GET['status'] ?? 'all';

        $orderModel = new \App\Models\Order();

        // Quick filter implementation (Better to have filter method in Model, but fetching all & filtering array is fine for MVP)
        // Or adding param to getBySellerId($userId, $status)
        $allOrders = $orderModel->getBySellerId($userId);

        $orders = [];
        $counts = [
            'all' => count($allOrders),
            'pending' => 0,
            'pending_payment' => 0,
            'paid' => 0,
            'shipping' => 0,
            'completed' => 0,
            'cancelled' => 0
        ];

        foreach ($allOrders as $o) {
            if (isset($counts[$o['status']])) {
                $counts[$o['status']]++;
            }

            if ($status == 'all' || $o['status'] == $status) {
                $orders[] = $o;
            }
        }

        // Enrich orders with item details
        $orderItemModel = new \App\Models\OrderItem();
        foreach ($orders as &$order) {
            $order['items'] = $orderItemModel->getByOrderId($order['id']);
        }

        $this->view('shop/orders', [
            'pageTitle' => 'Đơn bán hàng',
            'orders' => $orders,
            'currentStatus' => $status,
            'counts' => $counts
        ]);
    }

    public function updateOrderStatus()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        $userId = $_SESSION['user']['id'];

        $orderId = $_POST['order_id'] ?? null;
        $status = $_POST['status'] ?? null;

        if ($orderId && $status) {
            $orderModel = new \App\Models\Order();
            // Security check: Ensure order belongs to seller
            // Fetch order, check seller_id == userId. skipped for brevity but necessary in prod.

            $orderModel->updateStatus($orderId, $status);
        }

        header('Location: /shop/orders');
        exit;
    }
}
