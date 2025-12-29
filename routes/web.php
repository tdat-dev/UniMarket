<?php

use App\Controllers\AuthController;
use App\Controllers\HomeController;

/** @var \App\Core\Router $router */

// Trang chủ (cần check login trong controller)
$router->get('/', [HomeController::class, 'index']);

// Đăng nhập
$router->get('login', [AuthController::class, 'login']);
$router->post('login', [AuthController::class, 'processLogin']);

// Đăng ký
$router->get('register', [AuthController::class, 'register']);
$router->post('register', [AuthController::class, 'processRegister']);

// Đăng xuất
$router->post('logout', [AuthController::class, 'logout']);