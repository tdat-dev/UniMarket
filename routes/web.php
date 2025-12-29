<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\ProductController;

/** @var \App\Core\Router $router */

// Route Index
$router->get('/', [HomeController::class, 'index']);
$router->get('login', [AuthController::class, 'login']);
$router->get('register', [AuthController::class, 'register']);

// Route show list and detail
$router->get('products', [ProductController::class, 'index']);
$router->get('product-detail', [ProductController::class, 'show']);