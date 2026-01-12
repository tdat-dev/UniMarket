<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\Review;
use App\Models\Follow;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Notification;

/**
 * Shop Controller
 * 
 * Xử lý trang shop của sellers và quản lý đơn hàng bán.
 * 
 * @package App\Controllers
 */
class ShopController extends BaseController
{
    /**
     * Trang shop của seller
     */
    public function index(): void
    {
        $userId = $this->query('id');
        $currentUserId = $this->getUserId();

        // Nếu không có ID, hiển thị shop của user hiện tại
        if ($userId === null) {
            if ($currentUserId === null) {
                $this->redirect('/login');
            }
            $userId = $currentUserId;
        } else {
            $userId = (int) $userId;
        }

        $userModel = new User();
        $seller = $userModel->find($userId);

        if ($seller === null) {
            $this->view('errors/404', ['message' => 'Không tìm thấy cửa hàng']);
            return;
        }

        $productModel = new Product();
        $reviewModel = new Review();
        $followModel = new Follow();

        $isFollowing = false;
        if ($currentUserId !== null && $currentUserId !== $userId) {
            $isFollowing = $followModel->isFollowing($currentUserId, $userId);
        }

        $this->view('shop/index', [
            'seller' => $seller,
            'products' => $productModel->getByUserId($userId),
            'stats' => $reviewModel->getSellerStats($userId),
            'isOwner' => ($currentUserId === $userId),
            'followerCount' => $followModel->getFollowerCount($userId),
            'isFollowing' => $isFollowing,
        ]);
    }

    /**
     * API: Toggle follow shop
     */
    public function toggleFollow(): never
    {
        if (!$this->isAuthenticated()) {
            $this->jsonError('Bạn cần đăng nhập để theo dõi');
        }

        $followerId = $this->getUserId();
        $input = $this->getJsonInput();
        $followingId = (int) ($input['shop_id'] ?? 0);

        if ($followingId === 0) {
            $this->jsonError('Shop ID is required');
        }

        if ($followerId === $followingId) {
            $this->jsonError('Bạn không thể tự theo dõi chính mình');
        }

        $followModel = new Follow();
        $isFollowing = $followModel->isFollowing($followerId, $followingId);

        if ($isFollowing) {
            $followModel->unfollow($followerId, $followingId);
            $newStatus = 'unfollowed';
        } else {
            $followModel->follow($followerId, $followingId);
            $newStatus = 'followed';

            // Notify seller
            $notifModel = new Notification();
            $notifModel->create($followingId, 'Có người mới theo dõi cửa hàng của bạn.');
        }

        $this->jsonSuccess('OK', [
            'status' => $newStatus,
            'new_count' => $followModel->getFollowerCount($followingId),
        ]);
    }

    /**
     * Trang quản lý đơn bán hàng
     */
    public function orders(): void
    {
        $user = $this->requireAuth();
        $userId = (int) $user['id'];
        $status = $this->query('status', 'all');

        $orderModel = new Order();
        $allOrders = $orderModel->getBySellerId($userId);

        // Count by status
        $counts = [
            'all' => count($allOrders),
            'pending' => 0,
            'pending_payment' => 0,
            'paid' => 0,
            'shipping' => 0,
            'completed' => 0,
            'cancelled' => 0,
        ];

        $orders = [];
        foreach ($allOrders as $order) {
            $orderStatus = $order['status'];
            if (isset($counts[$orderStatus])) {
                $counts[$orderStatus]++;
            }

            if ($status === 'all' || $orderStatus === $status) {
                $orders[] = $order;
            }
        }

        // Enrich with items
        $orderItemModel = new OrderItem();
        foreach ($orders as &$order) {
            $order['items'] = $orderItemModel->getByOrderId((int) $order['id']);
        }

        $this->view('shop/orders', [
            'pageTitle' => 'Đơn bán hàng',
            'orders' => $orders,
            'currentStatus' => $status,
            'counts' => $counts,
        ]);
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateOrderStatus(): void
    {
        $user = $this->requireAuth();
        $userId = (int) $user['id'];

        $orderId = (int) $this->input('order_id', 0);
        $status = $this->input('status', '');

        if ($orderId > 0 && !empty($status)) {
            $orderModel = new Order();

            // Security check: ensure order belongs to seller
            $order = $orderModel->findWithDetails($orderId);
            if ($order !== null && (int) $order['seller_id'] === $userId) {
                $orderModel->updateStatus($orderId, $status);
            }
        }

        $this->redirect('/shop/orders');
    }
}
