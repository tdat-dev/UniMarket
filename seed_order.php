<?php
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $pdo = new PDO("mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_DATABASE']};charset=utf8mb4", $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check users
    $stmt = $pdo->query("SELECT id, full_name, role FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $buyers = array_filter($users, fn($u) => $u['role'] == 'buyer');
    $sellers = array_filter($users, fn($u) => $u['role'] == 'seller');
    
    // Fallback if roles not strict
    if (empty($buyers)) $buyers = $users; 
    if (empty($sellers)) $sellers = $users;

    $buyer = reset($buyers);
    $seller = reset($sellers); // Just pick first one
    
    if (!$buyer || !$seller) {
        die("Need at least 2 users to seed orders.");
    }

    echo "Seeding Order: Buyer [{$buyer['id']}] -> Seller [{$seller['id']}]\n";

    // Create Order
    $stmt = $pdo->prepare("INSERT INTO orders (buyer_id, seller_id, total_amount, status) VALUES (?, ?, ?, ?)");
    $stmt->execute([$buyer['id'], $seller['id'], 500000, 'pending']);
    $orderId = $pdo->lastInsertId();

    // Create Order Details (Random Product)
    $stmt = $pdo->prepare("SELECT id, price FROM products WHERE user_id = ? LIMIT 1");
    $stmt->execute([$seller['id']]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $stmt = $pdo->prepare("INSERT INTO order_details (order_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)");
        $stmt->execute([$orderId, $product['id'], 2, $product['price']]);
        echo "Created Order #$orderId with Product #{$product['id']}\n";
    } else {
        echo "Created Order #$orderId (No products found for seller)\n";
    }
    
    echo "Done.";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
