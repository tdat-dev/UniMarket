<?php
/**
 * Migration: Create wallets table
 * 
 * Bảng ví tiền cho seller. Mỗi user có 1 ví duy nhất.
 * Tiền từ escrow sẽ được chuyển vào ví, seller rút khi cần.
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

    echo "🚀 Bắt đầu migration: Create wallets table...\n";

    // Kiểm tra bảng đã tồn tại chưa
    $stmt = $pdo->query("SHOW TABLES LIKE 'wallets'");
    if ($stmt->rowCount() > 0) {
        echo "⏭️ Bảng 'wallets' đã tồn tại\n";
    } else {
        $sql = "CREATE TABLE wallets (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            
            -- Chủ ví (1 user = 1 ví)
            user_id INT NOT NULL UNIQUE,
            
            -- Số dư hiện tại (có thể rút)
            balance DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Số dư khả dụng',
            
            -- Số tiền đang trong escrow (chưa thể rút)
            pending_balance DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Tiền đang trong escrow',
            
            -- Thống kê tổng
            total_earned DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Tổng tiền đã nhận từ bán hàng',
            total_withdrawn DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Tổng tiền đã rút',
            
            -- Thông tin ngân hàng để rút tiền
            bank_name VARCHAR(100) NULL COMMENT 'Tên ngân hàng',
            bank_account_number VARCHAR(50) NULL COMMENT 'Số tài khoản',
            bank_account_name VARCHAR(100) NULL COMMENT 'Tên chủ tài khoản',
            bank_bin VARCHAR(10) NULL COMMENT 'Mã BIN ngân hàng (VietQR)',
            
            -- Timestamps
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
            
            -- Indexes
            INDEX idx_user_id (user_id),
            INDEX idx_balance (balance),
            
            -- Foreign key
            CONSTRAINT fk_wallet_user 
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        COMMENT='Ví tiền của seller'";

        $pdo->exec($sql);
        echo "✅ Đã tạo bảng 'wallets'\n";
    }

    echo "\n✅ Migration hoàn tất: Create wallets table!\n";

} catch (PDOException $e) {
    echo "❌ Lỗi migration: " . $e->getMessage() . "\n";
    exit(1);
}
