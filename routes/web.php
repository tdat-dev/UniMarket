<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\SearchController;
use App\Controllers\GoogleAuthController;
use App\Controllers\VerificationController;



/** @var \App\Core\Router $router */

// Trang chủ (cần check login trong controller)
$router->get('/', [HomeController::class, 'index']);
$router->get('support', [HomeController::class, 'support']);

// Đăng nhập
$router->get('login', [AuthController::class, 'login']);
$router->post('login', [AuthController::class, 'processLogin']);

// Đăng ký
$router->get('register', [AuthController::class, 'register']);
$router->post('register', [AuthController::class, 'processRegister']);

// Google OAuth
$router->get('auth/google', [GoogleAuthController::class, 'redirectToGoogle']);
$router->get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

// Route show list and detail
$router->get('products', [ProductController::class, 'index']);
$router->get('product-detail', [ProductController::class, 'show']);

// Route search
$router->get('search', [SearchController::class, 'index']);
$router->get('api/search-suggest', [SearchController::class, 'suggest']);

//Route create product
$router->get('/products', [ProductController::class, 'index']);
$router->get('/products/create', [ProductController::class, 'create']);
$router->post('/products/store', [ProductController::class, 'store']);

// Cart & Checkout
$router->post('cart/add', [\App\Controllers\CartController::class, 'add']);
$router->get('cart', [\App\Controllers\CartController::class, 'index']);
$router->post('cart/update', [\App\Controllers\CartController::class, 'update']);
$router->post('checkout', [\App\Controllers\CheckoutController::class, 'process']); // Hiển thị trang checkout (review)
$router->post('checkout/confirm', [\App\Controllers\CheckoutController::class, 'confirm']); // Xử lý đặt hàng thực sự

// Shop & Chat
$router->get('shop', [\App\Controllers\ShopController::class, 'index']);
$router->post('shop/follow', [\App\Controllers\ShopController::class, 'toggleFollow']);
$router->get('shop/orders', [\App\Controllers\ShopController::class, 'orders']);
$router->get('chat', [\App\Controllers\ChatController::class, 'index']);
$router->post('chat/send', [\App\Controllers\ChatController::class, 'send']);

// Product Management
$router->get('products/create', [ProductController::class, 'create']);
$router->post('products/store', [ProductController::class, 'store']); // For form submission

// User Profile
$router->post('products/cancel-sale', [ProductController::class, 'cancelSale']);
$router->get('profile', [\App\Controllers\ProfileController::class, 'index']);
$router->post('profile/update', [\App\Controllers\ProfileController::class, 'update']);
$router->get('wallet', [\App\Controllers\ProfileController::class, 'wallet']);
$router->post('wallet/process', [\App\Controllers\ProfileController::class, 'processWallet']);
$router->get('reviews', [\App\Controllers\ProfileController::class, 'reviews']);
$router->post('reviews/store', [\App\Controllers\ProfileController::class, 'storeReview']);
$router->get('shop/orders', [\App\Controllers\ShopController::class, 'orders']);
$router->post('shop/orders/update', [\App\Controllers\ShopController::class, 'updateOrderStatus']);
$router->get('profile/orders', [\App\Controllers\ProfileController::class, 'orders']);
$router->post('profile/orders/cancel', [\App\Controllers\ProfileController::class, 'cancelOrder']);
$router->post('profile/orders/rebuy', [\App\Controllers\ProfileController::class, 'rebuyOrder']);
$router->get('profile/orders/detail', [\App\Controllers\ProfileController::class, 'orderDetail']);
$router->post('profile/avatar', [\App\Controllers\ProfileController::class, 'updateAvatar']);

// Route search (yêu cầu đăng nhập)
$router->get('search', [SearchController::class, 'search']);
$router->get('api/search-suggest', [SearchController::class, 'suggest']);

// Verify email
$router->get('verify-email', [VerificationController::class, 'showVerifyForm']);
$router->post('verify-email', [VerificationController::class, 'verifyByOtp']);
$router->get('verify-email/token', [VerificationController::class, 'verifyByToken']);
$router->post('verify-email/resend', [VerificationController::class, 'resendVerification']);

// Đăng xuất
$router->post('logout', [AuthController::class, 'logout']);


