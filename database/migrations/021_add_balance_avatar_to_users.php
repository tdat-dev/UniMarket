<?php
function run_021_add_balance_avatar_to_users($pdo)
{
    // Kiểm tra cột balance
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'balance'");
    if (!$stmt->fetch()) {
        try {
            $pdo->exec("ALTER TABLE users ADD COLUMN balance DECIMAL(15,2) DEFAULT 0.00 AFTER role");
            echo "Added column 'balance' to 'users'\n";
        } catch (PDOException $e) {
            echo "Error adding 'balance': " . $e->getMessage() . "\n";
        }
    } else {
        echo "Column 'balance' already exists, skipping.\n";
    }

    // Kiểm tra cột avatar
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'avatar'");
    if (!$stmt->fetch()) {
        try {
            $pdo->exec("ALTER TABLE users ADD COLUMN avatar VARCHAR(255) DEFAULT NULL AFTER balance");
            echo "Added column 'avatar' to 'users'\n";
        } catch (PDOException $e) {
            echo "Error adding 'avatar': " . $e->getMessage() . "\n";
        }
    } else {
        echo "Column 'avatar' already exists, skipping.\n";
    }
}
