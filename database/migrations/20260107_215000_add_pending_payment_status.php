<?php

/**
 * Migration: Add pending_payment and paid to orders status ENUM
 * 
 * This migration adds new status values:
 * - pending_payment: Order created with PayOS, waiting for payment
 * - paid: Payment received, waiting for shipping
 * 
 * Run: php database/migrations/20260107_215000_add_pending_payment_status.php
 */

require_once __DIR__ . '/../../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? 'localhost';
$dbname = $_ENV['DB_DATABASE'] ?? 'zoldify';
$username = $_ENV['DB_USERNAME'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    echo "Connected to database.\n";

    // Modify status column to include new values
    // New ENUM: pending, pending_payment, paid, confirmed, shipping, received, completed, cancelled, refunded
    $sql = "ALTER TABLE orders 
            MODIFY COLUMN status ENUM(
                'pending',         -- COD: Chờ xác nhận từ seller
                'pending_payment', -- PayOS: Chờ thanh toán
                'paid',            -- Đã thanh toán (PayOS thành công)
                'confirmed',       -- Seller đã xác nhận
                'shipping',        -- Đang giao hàng
                'received',        -- Buyer đã nhận hàng
                'completed',       -- Hoàn thành (tiền đã về seller)
                'cancelled',       -- Đã hủy
                'refunded'         -- Đã hoàn tiền
            ) NOT NULL DEFAULT 'pending'";

    $pdo->exec($sql);
    echo "✅ Successfully updated orders.status ENUM.\n";

    echo "\nNew status values:\n";
    echo "  - pending: Chờ xác nhận (COD)\n";
    echo "  - pending_payment: Chờ thanh toán (PayOS)\n";
    echo "  - paid: Đã thanh toán\n";
    echo "  - confirmed: Seller đã xác nhận\n";
    echo "  - shipping: Đang giao hàng\n";
    echo "  - received: Đã nhận hàng\n";
    echo "  - completed: Hoàn thành\n";
    echo "  - cancelled: Đã hủy\n";
    echo "  - refunded: Đã hoàn tiền\n";

} catch (PDOException $e) {
    die("❌ Migration failed: " . $e->getMessage() . "\n");
}
