<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? 'localhost';
$user = $_ENV['DB_USERNAME'] ?? 'root';
$pass = $_ENV['DB_PASSWORD'] ?? '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Reset Database
    $pdo->exec("DROP DATABASE IF EXISTS zoldify_test");
    $pdo->exec("CREATE DATABASE zoldify_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "✅ Database 'zoldify_test' reset.\n";

    // 2. Select Database
    $pdo->exec("USE zoldify_test");

    // 3. Create 'users' table (required for Database class initialization)
    $pdo->exec("
        CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            full_name VARCHAR(100) NOT NULL,
            email VARCHAR(150) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            phone_number VARCHAR(20) DEFAULT NULL,
            address VARCHAR(255) DEFAULT NULL,
            role ENUM('buyer', 'seller', 'admin', 'moderator') DEFAULT 'buyer',
            email_verified TINYINT(1) DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_email (email),
            INDEX idx_role (role)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✅ Table 'users' created/checked.\n";

    // 4. Create 'categories' table (required for products)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            icon VARCHAR(50) DEFAULT NULL,
            image VARCHAR(255) DEFAULT NULL,
            INDEX idx_name (name)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✅ Table 'categories' created/checked.\n";

    // 5. Create 'products' table (required for Database class initialization)
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            category_id INT NOT NULL,
            name VARCHAR(255) NOT NULL,
            description TEXT DEFAULT NULL,
            price DECIMAL(10,2) NOT NULL,
            image VARCHAR(255) DEFAULT NULL,
            status ENUM('active', 'sold', 'hidden') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `condition` ENUM('new', 'like_new', 'good', 'fair') DEFAULT 'good',
            INDEX idx_user_id (user_id),
            INDEX idx_category_id (category_id),
            INDEX idx_status (status),
            INDEX idx_created_at (created_at),
            CONSTRAINT fk_products_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "✅ Table 'products' created/checked.\n";

    echo "🚀 Environment ready for migrations!\n";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
