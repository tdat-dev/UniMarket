<?php

use App\Controllers\HomeController;
use App\Controllers\AuthController;

/** @var \App\Core\Router $router */

$router->get('/', [HomeController::class, 'index']);
$router->get('login', [AuthController::class, 'login']);
$router->get('register', [AuthController::class, 'register']);