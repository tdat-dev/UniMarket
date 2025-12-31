<?php

namespace App\Controllers;

use App\Models\Product;

class CheckoutController extends BaseController
{
    public function process()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $allCart = $_SESSION['cart'] ?? [];
        $selectedIds = $_POST['selected_products'] ?? null; // Array of IDs
        $selectedIds = $_POST['selected_products'] ?? [];

        // If no products selected, redirect back to cart
        if (empty($selectedIds)) {
             header('Location: /cart');
             exit;
        }

        $productModel = new Product();
        $products = [];
        
        // Prepare products for review
        foreach ($selectedIds as $id) {
            if (isset($allCart[$id])) {
                 $p = $productModel->find($id);
                 if ($p) {
                     $cartItem = $allCart[$id];
                     $p['cart_quantity'] = is_array($cartItem) ? ($cartItem['quantity'] ?? 1) : $cartItem;
                     $products[] = $p;
                 }
            }
        }

        // Render checkout view
        $this->view('cart/checkout', [
            'products' => $products,
            'selected_ids' => $selectedIds
        ]);
    }

    // confirm method: Handles POST from Checkout View -> Deducts Stock
    public function confirm()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $allCart = $_SESSION['cart'] ?? [];
        $selectedIds = $_POST['selected_products'] ?? null; 

        $cartToProcess = [];
        if (!empty($selectedIds) && is_array($selectedIds)) {
            $postQuantities = $_POST['quantities'] ?? [];
            foreach ($selectedIds as $id) {
                // Prioritize quantity from POST, then Session
                if (isset($postQuantities[$id])) {
                    $qty = (int)$postQuantities[$id];
                    if ($qty >= 0) { // Allow 0 to potentially skip item
                         if ($qty > 0) $cartToProcess[$id] = $qty;
                         // If 0, we simply don't add it to cartToProcess, effectively removing it from order
                    }
                } elseif (isset($allCart[$id])) {
                    $cartItem = $allCart[$id];
                    $cartToProcess[$id] = is_array($cartItem) ? ($cartItem['quantity'] ?? 1) : $cartItem;
                }
            }
        } 
        // Fallback or validation
        if (empty($cartToProcess)) {
            header('Location: /cart');
            exit;
        }

        $productModel = new Product();
        $errors = [];

        // 1. Validate Stock
        foreach ($cartToProcess as $id => $qty) {
            $product = $productModel->find($id);
            if (!$product || $product['quantity'] < $qty) {
                 // Simple error handling
                $errors[] = "Sản phẩm " . ($product['name'] ?? 'Unknown') . " không đủ hàng.";
            }
        }

        if (!empty($errors)) {
            echo implode('<br>', $errors);
            echo '<br><a href="/cart">Quay lại giỏ hàng</a>';
            exit;
        }

        // 2. Process Order (Decrement Stock)
        foreach ($cartToProcess as $id => $qty) {
            $productModel->decreaseQuantity($id, $qty);
        }

        // 3. Clear Processed Items from Cart
        foreach ($cartToProcess as $id => $qty) {
            unset($_SESSION['cart'][$id]);
        }

        // 4. Success View
        $this->view('cart/success');
    }
}
