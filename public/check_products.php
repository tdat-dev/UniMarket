<?php
require_once __DIR__ . '/../app/Core/Database.php';

$db = App\Core\Database::getInstance();

$products = $db->fetchAll("SELECT id, name, category_id, description FROM products");

file_put_contents(__DIR__ . '/products_dump.json', json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
