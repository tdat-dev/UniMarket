<?php
require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


$pdo = new PDO(
    'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASS']
);

echo "=== Checking products.condition column ===\n\n";

// Check ENUM values
$result = $pdo->query("
    SELECT COLUMN_TYPE, COLUMN_DEFAULT
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'products' 
    AND COLUMN_NAME = 'condition'
")->fetch();

echo "COLUMN_TYPE: " . $result['COLUMN_TYPE'] . "\n";
echo "DEFAULT: " . $result['COLUMN_DEFAULT'] . "\n\n";

// Check recent products
echo "=== Recent products ===\n";
$products = $pdo->query("SELECT id, name, `condition` FROM products ORDER BY id DESC LIMIT 5")->fetchAll();
foreach ($products as $p) {
    echo "ID: {$p['id']}, Name: {$p['name']}, Condition: {$p['condition']}\n";
}
