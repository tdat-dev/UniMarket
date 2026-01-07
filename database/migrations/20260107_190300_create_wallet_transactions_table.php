<?php
/**
 * Migration: Create wallet_transactions table
 * 
 * Bảng lưu lịch sử giao dịch ví. Mỗi thay đổi số dư = 1 record.
 * Dùng để audit, đối soát, hiển thị lịch sử cho seller.
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

    echo "🚀 Bắt đầu migration: Create wallet_transactions table...\n";

    // Kiểm tra bảng đã tồn tại chưa
    $stmt = $pdo->query("SHOW TABLES LIKE 'wallet_transactions'");
    if ($stmt->rowCount() > 0) {
        echo "⏭️ Bảng 'wallet_transactions' đã tồn tại\n";
    } else {
        $sql = "CREATE TABLE wallet_transactions (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            
            -- Liên kết
            wallet_id BIGINT UNSIGNED NOT NULL,
            order_id INT NULL COMMENT 'Đơn hàng liên quan (nếu có)',
            
            -- Loại giao dịch
            transaction_type ENUM(
                'credit',           -- Cộng tiền (nhận từ escrow)
                'debit',            -- Trừ tiền (phí, điều chỉnh)
                'withdrawal',       -- Rút tiền về ngân hàng
                'refund_debit'      -- Trừ tiền hoàn cho buyer
            ) NOT NULL,
            
            -- Số tiền và số dư
            amount DECIMAL(15,2) NOT NULL COMMENT 'Số tiền giao dịch',
            balance_before DECIMAL(15,2) NOT NULL COMMENT 'Số dư trước giao dịch',
            balance_after DECIMAL(15,2) NOT NULL COMMENT 'Số dư sau giao dịch',
            
            -- Mô tả
            description VARCHAR(255) NULL COMMENT 'Mô tả giao dịch',
            reference_id VARCHAR(100) NULL COMMENT 'Mã tham chiếu (VD: payout_id)',
            
            -- Trạng thái
            status ENUM('pending', 'completed', 'failed') DEFAULT 'completed',
            
            -- Timestamps
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            
            -- Indexes
            INDEX idx_wallet_id (wallet_id),
            INDEX idx_order_id (order_id),
            INDEX idx_transaction_type (transaction_type),
            INDEX idx_status (status),
            INDEX idx_created_at (created_at),
            
            -- Foreign keys
            CONSTRAINT fk_wallet_trans_wallet 
                FOREIGN KEY (wallet_id) REFERENCES wallets(id) ON DELETE CASCADE,
            CONSTRAINT fk_wallet_trans_order 
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE SET NULL
                
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        COMMENT='Lịch sử giao dịch ví'";

        $pdo->exec($sql);
        echo "✅ Đã tạo bảng 'wallet_transactions'\n";
    }

    echo "\n✅ Migration hoàn tất: Create wallet_transactions table!\n";

} catch (PDOException $e) {
    echo "❌ Lỗi migration: " . $e->getMessage() . "\n";
    exit(1);
}
