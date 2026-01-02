<?php
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\UserController;
use App\Controllers\Admin\ProductController;
use App\Controllers\Admin\CategoryController;
use App\Controllers\Admin\OrderController;
use App\Controllers\Admin\SettingController;

/** @var \App\Core\Router $router */

// Dashboard
$router->get('admin', [DashboardController::class, 'index']);
$router->get('admin/dashboard', [DashboardController::class, 'index']);

// Users Management
$router->get('admin/users', [UserController::class, 'index']);
$router->get('admin/users/edit', [UserController::class, 'edit']);
$router->post('admin/users/update', [UserController::class, 'update']);
$router->post('admin/users/toggle-lock', [UserController::class, 'toggleLock']);
$router->post('admin/users/toggle-status', [UserController::class, 'toggleStatus']);

// Products Management
$router->get('admin/products', [ProductController::class, 'index']);
$router->get('admin/products/create', [ProductController::class, 'create']);
$router->post('admin/products/store', [ProductController::class, 'store']);
$router->get('admin/products/edit', [ProductController::class, 'edit']);
$router->post('admin/products/update', [ProductController::class, 'update']);
$router->post('admin/products/delete', [ProductController::class, 'delete']);

// Categories Management
$router->get('admin/categories', [CategoryController::class, 'index']);
$router->post('admin/categories/store', [CategoryController::class, 'store']);
$router->post('admin/categories/update', [CategoryController::class, 'update']);
$router->post('admin/categories/delete', [CategoryController::class, 'delete']);

// Orders Management
$router->get('admin/orders', [OrderController::class, 'index']);
$router->get('admin/orders/show', [OrderController::class, 'show']);
$router->post('admin/orders/update-status', [OrderController::class, 'updateStatus']);

// Settings Management
$router->get('admin/settings', [SettingController::class, 'index']);
$router->post('admin/settings/update', [SettingController::class, 'update']);
$router->post('admin/settings/upload-image', [SettingController::class, 'uploadImage']);
$router->post('admin/settings/toggle-maintenance', [SettingController::class, 'toggleMaintenance']);