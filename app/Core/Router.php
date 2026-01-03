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
        // Lấy URL từ query string (Apache/Laragon) hoặc parse từ REQUEST_URI (PHP Built-in Server)
        if (isset($_GET['url'])) {
            $url = $_GET['url'];
        } else {
            // Parse từ REQUEST_URI và loại bỏ base path
            $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            
            // Lấy base path từ SCRIPT_NAME (vd: /Zodify/public/index.php -> /Zodify/public)
            $scriptName = dirname($_SERVER['SCRIPT_NAME']);
            
            // Loại bỏ base path khỏi REQUEST_URI
            if ($scriptName !== '/' && strpos($requestUri, $scriptName) === 0) {
                $url = substr($requestUri, strlen($scriptName));
            } else {
                $url = $requestUri;
            }
        }

        // Normalize URL: bỏ dấu / ở đầu và cuối
        $url = trim($url, '/');

        // Nếu rỗng thì là trang chủ
        if ($url === '') {
            $url = '/';
        }

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
            http_response_code(404);
            echo "404 Not Found";
        }
    }
}