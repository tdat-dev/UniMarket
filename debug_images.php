<?php
require_once __DIR__ . '/app/Core/Database.php';

use App\Core\Database;

$db = Database::getInstance();

// Kiểm tra 5 sản phẩm mới nhất
$sql = "SELECT id, name, image FROM products ORDER BY id DESC LIMIT 5";
$products = $db->query($sql)->fetchAll();

echo "=== SAN PHAM ===\n";
foreach ($products as $p) {
    echo "ID: {$p['id']} | Image: {$p['image']}\n";
}

// Kiểm tra file tồn tại
echo "\n=== KIEM TRA FILE ===\n";
$testPath = __DIR__ . '/public/uploads/' . ($products[0]['image'] ?? 'none');
echo "Path: $testPath\n";
echo "Exists: " . (file_exists($testPath) ? 'YES' : 'NO') . "\n";
