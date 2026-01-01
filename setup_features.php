<?php
// Script to setup database tables for 4 components: 
// 1. Profile (already done)
// 2. Chat (messages)
// 3. Wallet (transactions, balance)
// 4. Reviews (reviews)

require_once __DIR__ . '/app/Core/Database.php';

use App\Core\Database;

$db = Database::getInstance();

// 1. Transactions Table (For Wallet)
echo "Creating transactions table...\n";
$sql = "CREATE TABLE IF NOT EXISTS transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('deposit', 'withdraw', 'payment', 'refund') NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    description TEXT,
    status ENUM('pending', 'completed', 'failed') DEFAULT 'completed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
$db->execute($sql);

// Adding balance column to users if not exists (Assume users table exists)
echo "Adding balance to users...\n";
try {
    $db->execute("ALTER TABLE users ADD COLUMN balance DECIMAL(15, 2) DEFAULT 0.00");
} catch (Exception $e) {
    echo "Column balance might already exist.\n";
}

// 2. Reviews Table
echo "Creating reviews table...\n";
$sql = "CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL, -- Reviewer
    product_id INT NOT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
)";
$db->execute($sql);

// 3. Messages Table (Simple chat)
echo "Creating messages table...\n";
$sql = "CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    content TEXT,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
)";
$db->execute($sql);

echo "Database setup completed!\n";
