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
                     $p['cart_quantity'] = $allCart[$id];
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
            foreach ($selectedIds as $id) {
                if (isset($allCart[$id])) {
                    $cartToProcess[$id] = $allCart[$id];
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
