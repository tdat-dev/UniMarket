<?php

namespace App\Controllers;

use App\Middleware\VerificationMiddleware;
use App\Models\Product;

class CheckoutController extends BaseController
{
    private function getCartItems($userId)
    {
        if ($userId) {
            $cartModel = new \App\Models\Cart();
            $dbItems = $cartModel->getByUserId($userId);
            $cart = [];
            foreach ($dbItems as $item) {
                $item['cart_quantity'] = $item['quantity']; // Normalize
                $cart[$item['product_id']] = $item; // Key by Product ID
            }
            return $cart;
        }
        return $_SESSION['cart'] ?? [];
    }

    public function process()
    {
        VerificationMiddleware::requireVerified();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['user']['id'] ?? null;
        $allCart = $this->getCartItems($userId);

        $selectedIds = $_POST['selected_products'] ?? [];

        // If no products selected, redirect back to cart
        if (empty($selectedIds)) {
            header('Location: /cart');
            exit;
        }

        $productModel = new Product();
        $products = [];

        // Prepare products for review
        // Prepare products for review
        foreach ($selectedIds as $id) {
            $qty = 0;

            // 1. Check in Database/Session Cart
            if (isset($allCart[$id])) {
                $cartItem = $allCart[$id];
                $qty = is_array($cartItem) ? ($cartItem['quantity'] ?? $cartItem['cart_quantity'] ?? 1) : $cartItem;
            }
            // 2. Check in POST data (Buy Now flow)
            elseif (isset($_POST['quantities'][$id])) {
                $qty = (int) $_POST['quantities'][$id];
            }

            // If we have a valid quantity, fetch product details
            if ($qty > 0) {
                $p = $productModel->find($id);
                if ($p) {
                    $p['cart_quantity'] = (int) $qty;
                    $products[] = $p;
                }
            }
        }

        // Fetch User Info for Address
        $userModel = new \App\Models\User();
        $user = $userModel->find($userId);

        // Render checkout view
        $this->view('cart/checkout', [
            'products' => $products,
            'selected_ids' => $selectedIds,
            'user' => $user
        ]);

    }

    // confirm method: Handles POST from Checkout View -> Creates Order -> Deducts Stock
    public function confirm()
    {
        VerificationMiddleware::requireVerified();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $userId = $_SESSION['user']['id'] ?? null;
        $allCart = $this->getCartItems($userId);
        $selectedIds = $_POST['selected_products'] ?? null;

        $cartToProcess = [];
        if (!empty($selectedIds) && is_array($selectedIds)) {
            $postQuantities = $_POST['quantities'] ?? [];
            foreach ($selectedIds as $id) {
                if (isset($postQuantities[$id])) {
                    $qty = (int) $postQuantities[$id];
                    if ($qty > 0)
                        $cartToProcess[$id] = $qty;
                } elseif (isset($allCart[$id])) {
                    $cartItem = $allCart[$id];
                    $cartToProcess[$id] = is_array($cartItem) ? ($cartItem['quantity'] ?? $cartItem['cart_quantity'] ?? 1) : $cartItem;
                }
            }
        }

        if (empty($cartToProcess)) {
            header('Location: /cart');
            exit;
        }

        $productModel = new Product();
        $productsToOrder = [];
        $errors = [];

        // 1. Validate Stock & Load Product Details
        foreach ($cartToProcess as $id => $qty) {
            $product = $productModel->find($id);
            if (!$product || $product['quantity'] < $qty) {
                $errors[] = "Sản phẩm " . ($product['name'] ?? 'Unknown') . " không đủ hàng.";
            } else {
                $productsToOrder[] = [
                    'product' => $product,
                    'quantity' => $qty
                ];
            }
        }

        if (!empty($errors)) {
            // In a real app, flashing session error is better.
            echo implode('<br>', $errors);
            echo '<br><a href="/cart">Quay lại giỏ hàng</a>';
            exit;
        }

        // 2. Group by Seller
        $ordersBySeller = [];
        foreach ($productsToOrder as $item) {
            $sellerId = $item['product']['user_id'];
            if (!isset($ordersBySeller[$sellerId])) {
                $ordersBySeller[$sellerId] = [
                    'seller_id' => $sellerId,
                    'total' => 0,
                    'items' => []
                ];
            }
            $itemTotal = $item['product']['price'] * $item['quantity'];
            $ordersBySeller[$sellerId]['total'] += $itemTotal;
            $ordersBySeller[$sellerId]['items'][] = $item;
        }

        // 3. Create Orders in DB
        $orderModel = new \App\Models\Order();
        $orderItemModel = new \App\Models\OrderItem();
        $cartModel = new \App\Models\Cart();

        // Check if user is logged in
        if (!isset($_SESSION['user']['id'])) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để thanh toán.';
            header('Location: /login');
            exit;
        }
        $buyerId = $_SESSION['user']['id'];

        foreach ($ordersBySeller as $sellerId => $orderData) {
            // Create Order
            $orderId = $orderModel->create([
                'buyer_id' => $buyerId,
                'seller_id' => $sellerId,
                'total_amount' => $orderData['total'],
                'status' => 'pending'
            ]);

            // Create Order Items & Update Stock
            foreach ($orderData['items'] as $item) {
                $product = $item['product'];
                $qty = $item['quantity'];

                $orderItemModel->create([
                    'order_id' => $orderId,
                    'product_id' => $product['id'],
                    'quantity' => $qty,
                    'price' => $product['price']
                ]);

                // Decrease Stock
                $productModel->decreaseQuantity($product['id'], $qty);

                // Remove from Cart (DB or Session)
                if ($userId) {
                    $cartModel->removeItem($userId, $product['id']);
                } else {
                    unset($_SESSION['cart'][$product['id']]);
                }
            }
        }

        // 4. Success View
        $this->view('cart/success');
    }
}
