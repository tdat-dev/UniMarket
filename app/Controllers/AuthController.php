<?php

namespace App\Controllers;

class AuthController extends BaseController
{
    public function login()
    {
        
        // Gọi view resources/views/auth/index.php
        $this->view('auth/login');
    }

    public function register()
    {
        // Gọi view resources/views/auth/register.php
        $this->view('auth/register');
    }
}