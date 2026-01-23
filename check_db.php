<?php
$pdo = new PDO('mysql:host=localhost;dbname=zoldify', 'root', '');

// Check column info
$stmt = $pdo->query("SHOW COLUMNS FROM products WHERE Field = 'condition'");
$col = $stmt->fetch(PDO::FETCH_ASSOC);
echo "=== Column Info ===\n";
print_r($col);

// Check latest products
$stmt = $pdo->query('SELECT id, name, `condition` FROM products ORDER BY id DESC LIMIT 3');
echo "\n=== Latest Products ===\n";
foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $p) {
    echo "ID: {$p['id']} | Name: {$p['name']} | Condition: '{$p['condition']}'\n";
}
