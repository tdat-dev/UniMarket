<?php

namespace App\Controllers;

use App\Models\Product;

class CartController extends BaseController
{
    public function add()
    {
        // Start session if not started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $productId = $_POST['product_id'] ?? null;
        $quantity = (int)($_POST['quantity'] ?? 1);
        $action = $_POST['action'] ?? 'add'; // 'add' or 'buy'

        if (!$productId) {
            header('Location: /products');
            exit;
        }

        // Initialize cart
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // Check if product exists in cart
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }

        // Redirect based on action
        if ($action === 'buy') {
            header('Location: /checkout'); // Redirect to Checkout page
        } else {
            // Redirect back to product page with success message (tạm thời redirect back)
            // Trong thực tế có thể dùng flash message
            header("Location: /product-detail?id=$productId");
        }
        exit;
    }
    
    // index method to view cart (basic placeholder)
    public function index() {
         if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $cart = $_SESSION['cart'] ?? [];
        
        // Fetch product details for items in cart
        $products = [];
        if (!empty($cart)) {
             $productModel = new Product();
             foreach ($cart as $id => $qty) {
                 $p = $productModel->find($id);
                 if ($p) {
                     $p['cart_quantity'] = $qty;
                     $products[] = $p;
                 }
             }
        }

        // Render View
        $this->view('cart/index', [
            'products' => $products
        ]);
    }

    public function update()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Get raw POST data for JSON
        $input = json_decode(file_get_contents('php://input'), true);
        
        $productId = $input['product_id'] ?? $_POST['product_id'] ?? null;
        $quantity = isset($input['quantity']) ? (int)$input['quantity'] : (isset($_POST['quantity']) ? (int)$_POST['quantity'] : null);

        if (!$productId || $quantity === null) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid data']);
            exit;
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        if ($quantity <= 0) {
            // Remove item
            unset($_SESSION['cart'][$productId]);
        } else {
            // Update quantity
            $_SESSION['cart'][$productId] = $quantity;
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit;
    }
}
