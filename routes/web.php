<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\SearchController;

/** @var \App\Core\Router $router */

// Trang chủ (cần check login trong controller)
$router->get('/', [HomeController::class, 'index']);

// Đăng nhập
$router->get('login', [AuthController::class, 'login']);
$router->post('login', [AuthController::class, 'processLogin']);

// Đăng ký
$router->get('register', [AuthController::class, 'register']);

// Route show list and detail
$router->get('products', [ProductController::class, 'index']);
$router->get('product-detail', [ProductController::class, 'show']);

// Cart & Checkout
$router->post('cart/add', [\App\Controllers\CartController::class, 'add']);
$router->get('cart', [\App\Controllers\CartController::class, 'index']);
$router->post('cart/update', [\App\Controllers\CartController::class, 'update']);
$router->post('checkout', [\App\Controllers\CheckoutController::class, 'process']); // Hiển thị trang checkout (review)
$router->post('checkout/confirm', [\App\Controllers\CheckoutController::class, 'confirm']); // Xử lý đặt hàng thực sự

// Shop & Chat
$router->get('shop', [\App\Controllers\ShopController::class, 'index']);
$router->get('shop/orders', [\App\Controllers\ShopController::class, 'orders']);
$router->get('chat', [\App\Controllers\ChatController::class, 'index']);
$router->post('chat/send', [\App\Controllers\ChatController::class, 'send']);

// Product Management
$router->get('products/create', [ProductController::class, 'create']);
$router->post('products/store', [ProductController::class, 'store']); // For form submission

// User Profile
$router->get('profile', [\App\Controllers\ProfileController::class, 'index']);
$router->post('profile/update', [\App\Controllers\ProfileController::class, 'update']);
$router->get('wallet', [\App\Controllers\ProfileController::class, 'wallet']);
$router->get('reviews', [\App\Controllers\ProfileController::class, 'reviews']);

// Route search (yêu cầu đăng nhập)
$router->get('search', [SearchController::class, 'search']);
$router->get('api/search-suggest', [SearchController::class, 'suggest']);
$router->post('register', [AuthController::class, 'processRegister']);

// Đăng xuất
$router->post('logout', [AuthController::class, 'logout']);