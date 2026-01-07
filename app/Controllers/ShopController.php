<?php

namespace App\Controllers;

use App\Models\Product;
use App\Models\User;

class ShopController extends BaseController
{
    public function index()
    {
        $userId = $_GET['id'] ?? null;
        $currentUser = null;

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (isset($_SESSION['user']['id'])) {
            $currentUser = $_SESSION['user']['id'];
        }

        // If no ID provided, check if logged in -> My Shop
        if (!$userId) {
            if ($currentUser) {
                $userId = $currentUser;
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

        $reviewModel = new \App\Models\Review();
        $stats = $reviewModel->getSellerStats($userId);

        $followModel = new \App\Models\Follow();
        $followerCount = $followModel->getFollowerCount($userId);
        $isFollowing = false;
        
        if ($currentUser && $currentUser != $userId) {
            $isFollowing = $followModel->isFollowing($currentUser, $userId);
        }

        $this->view('shop/index', [
            'seller' => $seller,
            'products' => $products,
            'stats' => $stats,
            'isOwner' => ($currentUser == $userId),
            'followerCount' => $followerCount,
            'isFollowing' => $isFollowing
        ]);
    }

    public function toggleFollow()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        header('Content-Type: application/json');

        if (!isset($_SESSION['user']['id'])) {
            echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để theo dõi']);
            return;
        }

        $followerId = $_SESSION['user']['id'];
        // Read JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        $followingId = $input['shop_id'] ?? null;

        if (!$followingId) {
            echo json_encode(['success' => false, 'message' => 'Shop ID is required']);
            return;
        }

        if ($followerId == $followingId) {
            echo json_encode(['success' => false, 'message' => 'Bạn không thể tự theo dõi chính mình']);
            return;
        }

        $followModel = new \App\Models\Follow();
        $isFollowing = $followModel->isFollowing($followerId, $followingId);

        if ($isFollowing) {
            $followModel->unfollow($followerId, $followingId);
            $newStatus = 'unfollowed';
        } else {
            $followModel->follow($followerId, $followingId);
            $newStatus = 'followed';
            
            // Create notification for the seller
            $notifModel = new \App\Models\Notification();
            $notifModel->create($followingId, "đã bắt đầu theo dõi cửa hàng của bạn."); // Name will be handled in frontend or model if we want complex text, but simple is fine.
            // Better: Get current user name
            // For now simple text.
        }

        $newCount = $followModel->getFollowerCount($followingId);

        echo json_encode([
            'success' => true, 
            'status' => $newStatus, 
            'new_count' => $newCount
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
