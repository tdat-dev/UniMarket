<?php
/**
 * Migration: Add payment columns to orders table
 * 
 * Thêm các cột liên quan đến thanh toán PayOS và escrow vào bảng orders.
 * 
 * @author UniMarket
 * @date 2026-01-07
 */

// Load database config
$config = require __DIR__ . '/../../config/database.php';

try {
    $pdo = new PDO(
        "mysql:host=" . $config['host'] . ";dbname=" . $config['db_name'] . ";charset=utf8mb4",
        $config['username'],
        $config['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "🚀 Bắt đầu migration: Add payment columns to orders...\n";

    // 1. Thêm cột payment_method (phương thức thanh toán)
    $stmt = $pdo->query("SHOW COLUMNS FROM orders LIKE 'payment_method'");
    if ($stmt->rowCount() === 0) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN payment_method ENUM('cod', 'payos') DEFAULT 'cod' AFTER status");
        echo "✅ Đã thêm cột 'payment_method'\n";
    } else {
        echo "⏭️ Cột 'payment_method' đã tồn tại\n";
    }

    // 2. Thêm cột payment_status (trạng thái thanh toán)
    $stmt = $pdo->query("SHOW COLUMNS FROM orders LIKE 'payment_status'");
    if ($stmt->rowCount() === 0) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN payment_status ENUM('pending', 'paid', 'refunded') DEFAULT 'pending' AFTER payment_method");
        echo "✅ Đã thêm cột 'payment_status'\n";
    } else {
        echo "⏭️ Cột 'payment_status' đã tồn tại\n";
    }

    // 3. Thêm cột payment_link_id (ID từ PayOS)
    $stmt = $pdo->query("SHOW COLUMNS FROM orders LIKE 'payment_link_id'");
    if ($stmt->rowCount() === 0) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN payment_link_id VARCHAR(100) NULL AFTER payment_status");
        echo "✅ Đã thêm cột 'payment_link_id'\n";
    } else {
        echo "⏭️ Cột 'payment_link_id' đã tồn tại\n";
    }

    // 4. Thêm cột payos_order_code (Mã đơn hàng unique cho PayOS - kiểu INT)
    $stmt = $pdo->query("SHOW COLUMNS FROM orders LIKE 'payos_order_code'");
    if ($stmt->rowCount() === 0) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN payos_order_code BIGINT UNSIGNED NULL AFTER payment_link_id");
        echo "✅ Đã thêm cột 'payos_order_code'\n";
    } else {
        echo "⏭️ Cột 'payos_order_code' đã tồn tại\n";
    }

    // 5. Thêm cột paid_at (thời điểm thanh toán thành công)
    $stmt = $pdo->query("SHOW COLUMNS FROM orders LIKE 'paid_at'");
    if ($stmt->rowCount() === 0) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN paid_at TIMESTAMP NULL AFTER payos_order_code");
        echo "✅ Đã thêm cột 'paid_at'\n";
    } else {
        echo "⏭️ Cột 'paid_at' đã tồn tại\n";
    }

    // 6. Thêm cột received_at (thời điểm buyer bấm "Đã nhận hàng")
    $stmt = $pdo->query("SHOW COLUMNS FROM orders LIKE 'received_at'");
    if ($stmt->rowCount() === 0) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN received_at TIMESTAMP NULL AFTER paid_at");
        echo "✅ Đã thêm cột 'received_at'\n";
    } else {
        echo "⏭️ Cột 'received_at' đã tồn tại\n";
    }

    // 7. Thêm cột escrow_release_at (thời điểm dự kiến giải ngân)
    $stmt = $pdo->query("SHOW COLUMNS FROM orders LIKE 'escrow_release_at'");
    if ($stmt->rowCount() === 0) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN escrow_release_at TIMESTAMP NULL AFTER received_at");
        echo "✅ Đã thêm cột 'escrow_release_at'\n";
    } else {
        echo "⏭️ Cột 'escrow_release_at' đã tồn tại\n";
    }

    // 8. Thêm cột trial_days (số ngày thử hàng, phụ thuộc vào condition sản phẩm)
    $stmt = $pdo->query("SHOW COLUMNS FROM orders LIKE 'trial_days'");
    if ($stmt->rowCount() === 0) {
        $pdo->exec("ALTER TABLE orders ADD COLUMN trial_days TINYINT UNSIGNED DEFAULT 7 AFTER escrow_release_at");
        echo "✅ Đã thêm cột 'trial_days'\n";
    } else {
        echo "⏭️ Cột 'trial_days' đã tồn tại\n";
    }

    // 9. Cập nhật ENUM status để thêm các trạng thái mới
    // Lấy các giá trị ENUM hiện tại
    $stmt = $pdo->query("SHOW COLUMNS FROM orders LIKE 'status'");
    $column = $stmt->fetch(PDO::FETCH_ASSOC);
    $currentType = $column['Type'] ?? '';

    // Kiểm tra xem đã có trạng thái mới chưa
    if (strpos($currentType, 'received') === false) {
        $pdo->exec("ALTER TABLE orders MODIFY COLUMN status ENUM(
            'pending',
            'paid',
            'shipping',
            'received',
            'trial_period',
            'completed',
            'cancelled',
            'refunded'
        ) DEFAULT 'pending'");
        echo "✅ Đã cập nhật ENUM status với các trạng thái mới\n";
    } else {
        echo "⏭️ ENUM status đã được cập nhật trước đó\n";
    }

    // 10. Thêm index cho các cột quan trọng
    $stmt = $pdo->query("SHOW INDEX FROM orders WHERE Key_name = 'idx_payment_status'");
    if ($stmt->rowCount() === 0) {
        $pdo->exec("ALTER TABLE orders ADD INDEX idx_payment_status (payment_status)");
        echo "✅ Đã thêm index 'idx_payment_status'\n";
    }

    $stmt = $pdo->query("SHOW INDEX FROM orders WHERE Key_name = 'idx_payment_link_id'");
    if ($stmt->rowCount() === 0) {
        $pdo->exec("ALTER TABLE orders ADD INDEX idx_payment_link_id (payment_link_id)");
        echo "✅ Đã thêm index 'idx_payment_link_id'\n";
    }

    $stmt = $pdo->query("SHOW INDEX FROM orders WHERE Key_name = 'idx_escrow_release'");
    if ($stmt->rowCount() === 0) {
        $pdo->exec("ALTER TABLE orders ADD INDEX idx_escrow_release (escrow_release_at)");
        echo "✅ Đã thêm index 'idx_escrow_release'\n";
    }

    echo "\n✅ Migration hoàn tất: Add payment columns to orders!\n";

} catch (PDOException $e) {
    echo "❌ Lỗi migration: " . $e->getMessage() . "\n";
    exit(1);
}
