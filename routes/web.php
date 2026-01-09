<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\SearchController;
use App\Controllers\GoogleAuthController;
use App\Controllers\VerificationController;
use App\Controllers\PaymentController;
use App\Controllers\PasswordResetController;
use App\Controllers\CartController;
use App\Controllers\CheckoutController;
use App\Controllers\ProfileController;
use App\Controllers\ShopController;
use App\Controllers\ChatController;

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

// Quên mật khẩu
$router->get('forgot-password', [PasswordResetController::class, 'showForgotForm']);
$router->post('forgot-password', [PasswordResetController::class, 'sendResetOtp']);
$router->post('verify-otp', [PasswordResetController::class, 'verifyOtp']);
$router->get('reset-password', [PasswordResetController::class, 'showResetForm']);
$router->post('reset-password', [PasswordResetController::class, 'resetPassword']);


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
$router->post('cart/add', [CartController::class, 'add']);
$router->get('cart', [CartController::class, 'index']);
$router->post('cart/update', [CartController::class, 'update']);
$router->post('checkout', [CheckoutController::class, 'process']); // Hiển thị trang checkout (review)
$router->post('checkout/confirm', [CheckoutController::class, 'confirm']); // Xử lý đặt hàng thực sự

// Payment (PayOS)
$router->post('payment/create', [PaymentController::class, 'create']);
$router->post('payment/webhook', [PaymentController::class, 'webhook']);
$router->get('payment/return', [PaymentController::class, 'returnUrl']);
$router->get('payment/cancel', [PaymentController::class, 'cancelUrl']);
$router->get('payment/qr', [PaymentController::class, 'showQR']);
$router->get('payment/check-status', [PaymentController::class, 'checkStatus']);

// Shop & Chat
$router->get('shop', [ShopController::class, 'index']);
$router->post('shop/follow', [ShopController::class, 'toggleFollow']);
$router->get('shop/orders', [ShopController::class, 'orders']);
$router->get('chat', [ChatController::class, 'index']);
$router->post('chat/send', [ChatController::class, 'send']);

// Product Management
$router->get('products/create', [ProductController::class, 'create']);
$router->post('products/store', [ProductController::class, 'store']); // For form submission

// User Profile
$router->post('products/cancel-sale', [ProductController::class, 'cancelSale']);
$router->get('profile', [ProfileController::class, 'index']);
$router->post('profile/update', [ProfileController::class, 'update']);
$router->get('profile/change-password', [ProfileController::class, 'changePassword']);
$router->post('profile/change-password', [ProfileController::class, 'updatePassword']);
$router->get('wallet', [ProfileController::class, 'wallet']);
$router->post('wallet/process', [ProfileController::class, 'processWallet']);
$router->get('reviews', [ProfileController::class, 'reviews']);
$router->post('reviews/store', [ProfileController::class, 'storeReview']);
$router->get('shop/orders', [ShopController::class, 'orders']);
$router->post('shop/orders/update', [ShopController::class, 'updateOrderStatus']);
$router->get('profile/orders', [ProfileController::class, 'orders']);
$router->post('profile/orders/cancel', [ProfileController::class, 'cancelOrder']);
$router->post('profile/orders/rebuy', [ProfileController::class, 'rebuyOrder']);
$router->get('profile/orders/detail', [ProfileController::class, 'orderDetail']);
$router->post('profile/avatar', [ProfileController::class, 'updateAvatar']);
$router->post('profile/orders/confirm-received', [ProfileController::class, 'confirmReceived']);

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
