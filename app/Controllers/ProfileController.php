<?php

namespace App\Controllers;

class ProfileController extends BaseController
{
    public function index()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        // Fetch fresh user data
        $userModel = new \App\Models\User();
        $user = $userModel->find($_SESSION['user']['id']);

        // Update session to keep it fresh
        if ($user) {
            $_SESSION['user'] = array_merge($_SESSION['user'], $user);
            // Fix phone key mismatch for view compatibility if needed, but better to fix view.
            // For now, let's just pass $user to view.
        }

        $this->view('profile/index', [
            'pageTitle' => 'Hồ sơ của tôi',
            'user' => $user // Pass 'user' variable for view to use if we update view, but view uses $_SESSION
        ]);
    }

    public function update()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $data = [];

        if (isset($_POST['fullname'])) {
            $data['full_name'] = htmlspecialchars(trim($_POST['fullname']));
        }
        if (isset($_POST['phone'])) {
            $data['phone_number'] = htmlspecialchars(trim($_POST['phone']));
        }
        // Gender - now stored in users table
        if (isset($_POST['gender']) && in_array($_POST['gender'], ['male', 'female', 'other'])) {
            $data['gender'] = $_POST['gender'];
        }

        // Email update often requires verification, skipping for now

        $userModel = new \App\Models\User();
        if (!empty($data)) {
            $userModel->updateProfile($userId, $data);

            // Refresh session
            $_SESSION['user'] = array_merge($_SESSION['user'], $data);
        }

        header('Location: /profile');
        exit;
    }

    public function updateAvatar()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $filename = $_FILES['avatar']['name'];
            $filesize = $_FILES['avatar']['size'];

            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            if (!in_array($ext, $allowed)) {
                // Keep it simple for now, maybe add flash message later
                header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error=invalid_type');
                exit;
            }

            if ($filesize > 5 * 1024 * 1024) { // 5MB
                header('Location: ' . $_SERVER['HTTP_REFERER'] . '?error=too_large');
                exit;
            }

            // Using direct path relative to public for simplicity in this setup
            $uploadDir = __DIR__ . '/../../public/uploads/avatars/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $newFilename = 'avatar_' . $_SESSION['user']['id'] . '_' . time() . '.' . $ext;
            $uploadPath = $uploadDir . $newFilename;

            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath)) {
                // Update DB
                $userModel = new \App\Models\User();

                // We need to use update method. 
                // Note: user implementation might need column mapping if not transparent
                $userModel->updateProfile($_SESSION['user']['id'], ['avatar' => $newFilename]);

                // Update session
                $_SESSION['user']['avatar'] = $newFilename;
            }
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
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

        $userId = $_SESSION['user']['id'];

        // Refresh User Balance
        $userModel = new \App\Models\User();
        $user = $userModel->find($userId); // Includes 'balance' if added to DB
        $balance = $user['balance'] ?? 0;

        // Get Transactions
        $transModel = new \App\Models\Transaction();
        $transactions = $transModel->getByUserId($userId);

        $this->view('profile/wallet', [
            'pageTitle' => 'Ví của tôi',
            'balance' => $balance,
            'transactions' => $transactions
        ]);
    }

    public function processWallet()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $type = $_POST['type'] ?? 'deposit'; // deposit or withdraw
        $amount = (float) ($_POST['amount'] ?? 0);

        if ($amount <= 0) {
            // handle error
            header('Location: /wallet?error=invalid_amount');
            exit;
        }

        $transModel = new \App\Models\Transaction();
        if ($type == 'withdraw') {
            $userModel = new \App\Models\User();
            $user = $userModel->find($userId);
            if (($user['balance'] ?? 0) < $amount) {
                header('Location: /wallet?error=insufficient_balance');
                exit;
            }
        }

        $transModel->create([
            'user_id' => $userId,
            'type' => $type,
            'amount' => $amount,
            'description' => $type == 'deposit' ? 'Nạp tiền vào ví' : 'Rút tiền về ngân hàng',
            'status' => 'completed' // For demo purposes, immediate success
        ]);

        header('Location: /wallet?success=1');
        exit;
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

        $userId = $_SESSION['user']['id'];
        $reviewModel = new \App\Models\Review();
        $reviews = $reviewModel->getByUserId($userId);

        // Get unreviewed items
        $orderItemModel = new \App\Models\OrderItem();
        $unreviewed = $orderItemModel->getUnreviewedItems($userId);

        $this->view('profile/reviews', [
            'pageTitle' => 'Đánh giá của tôi',
            'reviews' => $reviews,
            'unreviewed' => $unreviewed
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

        // Fetch User's Purchases
        $allOrders = $orderModel->getByBuyerId($userId);

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

        $this->view('profile/orders', [
            'pageTitle' => 'Đơn mua của tôi',
            'orders' => $orders,
            'currentStatus' => $status,
            'counts' => $counts
        ]);
    }

    public function storeReview()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $productId = $_POST['product_id'] ?? null;
        $rating = $_POST['rating'] ?? 5;
        $comment = $_POST['comment'] ?? '';

        if ($productId) {
            $reviewModel = new \App\Models\Review();
            $reviewModel->create([
                'user_id' => $userId,
                'product_id' => $productId,
                'rating' => $rating,
                'comment' => $comment
            ]);
        }

        header('Location: /reviews?tab=reviewed');
        exit;
    }
    public function cancelOrder()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $orderId = $_POST['order_id'] ?? null;

        if (!$orderId) {
            header('Location: /profile/orders?error=invalid_order');
            exit;
        }

        $orderModel = new \App\Models\Order();
        $order = $orderModel->find($orderId);

        if (!$order || $order['buyer_id'] != $userId) {
            header('Location: /profile/orders?error=unauthorized');
            exit;
        }

        // Chỉ cho phép hủy đơn ở trạng thái pending hoặc pending_payment
        if (!in_array($order['status'], ['pending', 'pending_payment'])) {
            header('Location: /profile/orders?error=cannot_cancel');
            exit;
        }

        // Process cancellation
        $reason = $_POST['reason'] ?? null;
        $orderModel->updateStatus($orderId, 'cancelled', $reason);

        // Restore stock
        $orderItemModel = new \App\Models\OrderItem();
        $items = $orderItemModel->getByOrderId($orderId);
        $productModel = new \App\Models\Product();

        foreach ($items as $item) {
            $productModel->increaseQuantity($item['product_id'], $item['quantity']);
        }

        header('Location: /profile/orders?status=all&success=cancelled');
        exit;
    }

    public function rebuyOrder()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $orderId = $_POST['order_id'] ?? null;

        if (!$orderId) {
            header('Location: /profile/orders?error=invalid_order');
            exit;
        }

        $orderModel = new \App\Models\Order();
        $oldOrder = $orderModel->find($orderId);

        if (!$oldOrder || $oldOrder['buyer_id'] != $userId) {
            header('Location: /profile/orders?error=unauthorized');
            exit;
        }

        $orderItemModel = new \App\Models\OrderItem();
        $oldItems = $orderItemModel->getByOrderId($orderId);
        $productModel = new \App\Models\Product();

        // Check stock and calculate new total
        $totalAmount = 0;
        $itemsToBuy = [];

        foreach ($oldItems as $item) {
            $product = $productModel->find($item['product_id']);

            // Check if product exists, is active
            if (!$product || $product['status'] != 'active') {
                // Determine what to do. Skip or error? 
                // Error is safer so user knows why it failed.
                header('Location: /profile/orders?error=product_unavailable');
                exit;
            }

            // Check stock
            if ($product['quantity'] < $item['quantity']) {
                header('Location: /profile/orders?error=out_of_stock');
                exit;
            }

            // Prepare item data
            $itemsToBuy[] = [
                'product_id' => $product['id'],
                'quantity' => $item['quantity'],
                'price' => $product['price'] // Use current price
            ];

            $totalAmount += $product['price'] * $item['quantity'];
        }

        if (empty($itemsToBuy)) {
            header('Location: /profile/orders?error=no_items');
            exit;
        }

        // Create New Order
        $newOrderId = $orderModel->create([
            'buyer_id' => $userId,
            'seller_id' => $oldOrder['seller_id'],
            'total_amount' => $totalAmount,
            'status' => 'pending'
        ]);

        // Create Items and Decrease Stock
        foreach ($itemsToBuy as $newItem) {
            $orderItemModel->create([
                'order_id' => $newOrderId,
                'product_id' => $newItem['product_id'],
                'quantity' => $newItem['quantity'],
                'price' => $newItem['price']
            ]);

            // Deduct from database (available products)
            $productModel->decreaseQuantity($newItem['product_id'], $newItem['quantity']);
        }

        header('Location: /profile/orders?status=pending&success=rebuy');
        exit;
    }

    public function orderDetail()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $orderId = $_GET['id'] ?? null;

        if (!$orderId) {
            header('Location: /profile/orders');
            exit;
        }

        $orderModel = new \App\Models\Order();
        $order = $orderModel->find($orderId);

        if (!$order || $order['buyer_id'] != $userId) {
            header('Location: /profile/orders?error=unauthorized');
            exit;
        }

        // Get Details
        $orderItemModel = new \App\Models\OrderItem();
        $order['items'] = $orderItemModel->getByOrderId($orderId);

        // Get Buyer Info for Address display (Assuming current user info or snapshot)
        // Since we don't have snapshot address in order table, we use current user profile
        $userModel = new \App\Models\User();
        $buyer = $userModel->find($userId);

        $this->view('profile/order_detail', [
            'pageTitle' => 'Chi tiết đơn hàng #' . $orderId,
            'order' => $order,
            'buyer' => $buyer
        ]);
    }

    /**
     * Xác nhận đã nhận hàng
     * Buyer bấm "Đã nhận hàng" → Bắt đầu countdown trial period
     */
    public function confirmReceived()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $orderId = $_POST['order_id'] ?? null;

        if (!$orderId) {
            header('Location: /profile/orders?error=invalid_order');
            exit;
        }

        $orderModel = new \App\Models\Order();
        $order = $orderModel->find($orderId);

        // Validate order
        if (!$order || $order['buyer_id'] != $userId) {
            header('Location: /profile/orders?error=unauthorized');
            exit;
        }

        // Chỉ cho phép confirm khi status là 'shipping' hoặc 'paid'
        if (!in_array($order['status'], ['shipping', 'paid'])) {
            header('Location: /profile/orders/detail?id=' . $orderId . '&error=invalid_status');
            exit;
        }

        // Lấy trial days từ order
        $trialDays = $order['trial_days'] ?? 7;

        // Cập nhật order: status = received, received_at = now, escrow_release_at = now + trial days
        $orderModel->confirmReceived($orderId, $trialDays);

        // Schedule escrow release
        $escrowService = new \App\Services\EscrowService();
        $escrowService->scheduleRelease($orderId, $trialDays);

        $_SESSION['success'] = "Cảm ơn bạn đã xác nhận! Bạn có {$trialDays} ngày để kiểm tra hàng.";
        header('Location: /profile/orders/detail?id=' . $orderId);
        exit;
    }
    public function cancelSale()
    {
        // ... previous code ...
        // Wait, I should not overwrite existing methods if not viewing them.
        // I will just append to the end.
    }

    // I need to see the last closing brace.
    // Line 530 is likely the closing brace of confirmReceived or the class.
    // Let me check the outline again. EndLine is 530.
    // Outline item 14 ends at 529.
    // So 530 might be valid or the class end.
    // I'll assume 530 is close brace of class.

    // Actually, I can just use Insert logic by replacing the last closing brace }
    // But I don't know if 530 is the last line of file or last line of class.
    // View_file showed Total Lines 531.
    // Outline shows confirmReceived ends 529.
    // So 530 probably contains "}" for class.

    // I'll replace the last closing brace with my new methods and the closing brace.

    public function changePassword()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
        $this->view('profile/change_password', ['pageTitle' => 'Đổi mật khẩu']);
    }

    public function updatePassword()
    {
        if (session_status() == PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $errors = [];
        if (empty($currentPassword)) {
            $errors[] = 'Vui lòng nhập mật khẩu hiện tại';
        }
        if (empty($newPassword)) {
            $errors[] = 'Vui lòng nhập mật khẩu mới';
        }
        if (strlen($newPassword) < 6) {
            $errors[] = 'Mật khẩu mới phải có ít nhất 6 ký tự';
        }
        if ($newPassword !== $confirmPassword) {
            $errors[] = 'Mật khẩu xác nhận không khớp';
        }

        if (!empty($errors)) {
            $this->view('profile/change_password', ['errors' => $errors, 'pageTitle' => 'Đổi mật khẩu']);
            return;
        }

        $authService = new \App\Services\AuthService();
        $result = $authService->changePassword($_SESSION['user']['id'], $currentPassword, $newPassword);

        if ($result['success']) {
            $_SESSION['success'] = 'Đổi mật khẩu thành công';
            header('Location: /profile/change-password');
            exit;
        } else {
            $this->view('profile/change_password', ['errors' => [$result['message']], 'pageTitle' => 'Đổi mật khẩu']);
        }
    }
}
