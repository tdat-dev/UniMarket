<?php

namespace App\Core;

class Router
{
    private $routes = [];

    public function get($path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function dispatch()
    {
        // Lấy URL từ query string (do .htaccess rewrite)
        $url = $_GET['url'] ?? '/';
        $url = rtrim($url, '/');
        if ($url === '') $url = '/';
        
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset($this->routes[$method][$url])) {
            $callback = $this->routes[$method][$url];

            if (is_array($callback)) {
                $controller = new $callback[0]();
                $action = $callback[1];
                call_user_func([$controller, $action]);
            } else {
                call_user_func($callback);
            }
        } else {
            echo "404 Not Found";
        }
    }
}