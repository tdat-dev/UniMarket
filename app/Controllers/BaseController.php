<?php

namespace App\Controllers;

class BaseController
{
    protected function view($viewPath, $data = [])
    {
        extract($data);
        
        // Tìm file view .php
        $viewFile = __DIR__ . '/../../resources/views/' . $viewPath . '.php';
        
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else { 
                echo "View not found: $viewPath";
        }
    }
}