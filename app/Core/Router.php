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

        // Normalize URL
        $url = trim($url, '/');
        if ($url === '') {
            $url = '/';
        }

        $method = $_SERVER['REQUEST_METHOD'];

        // 1. Check EXACT match trước (nhanh nhất) - Code ưu tiên hiệu năng
        if (isset($this->routes[$method][$url])) {
            $this->executeRoute($this->routes[$method][$url]);
            return;
        }

        // 2. Nếu không khớp chính xác -> Check DYNAMIC route (có tham số {id})
        foreach ($this->routes[$method] as $routePath => $callback) {
            // Biến đổi routePath thành Regex
            // Ví dụ: products/{id} -> products/([a-zA-Z0-9_-]+)
            // Dấu ~ là delimiter của Regex trong PHP
            if (strpos($routePath, '{') !== false) {
                // Convert {param} to Regex capturing group
                $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_-]+)', $routePath);
                $pattern = "~^" . $pattern . "$~";
                if (preg_match($pattern, $url, $matches)) {
                    // $matches[0] là toàn bộ url, $matches[1] là id...
                    array_shift($matches); // Bỏ phần tử đầu đi

                    $this->executeRoute($callback, $matches);
                    return;
                }
            }
        }

        // 3. Nếu không khớp -> 404 Not Found
        http_response_code(404);
        echo "404 Not Found";
    }

    // Tách hàm xử lý callback ra cho gọn (Helper function)
    private function executeRoute($callback, $params = [])
    {
        if (is_array($callback)) {
            $controllerName = $callback[0];
            $action = $callback[1];

            $controller = new $controllerName();
            // Gọi hàm controller và truyền tham số (ví dụ $id) vào
            call_user_func_array([$controller, $action], $params);
        } else {
            call_user_func_array($callback, $params);
        }
    }

}