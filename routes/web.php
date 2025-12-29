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

// Route search
$router->get('search', [SearchController::class, 'index']);
$router->get('api/search-suggest', [SearchController::class, 'suggest']);
$router->post('register', [AuthController::class, 'processRegister']);

// Đăng xuất
$router->post('logout', [AuthController::class, 'logout']);