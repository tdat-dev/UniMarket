<?php
/**
 * Migration: Create payment_transactions table
 * 
 * Bảng lưu lịch sử tất cả giao dịch thanh toán từ PayOS.
 * Mục đích: Audit trail, đối soát, debug.
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

    echo "🚀 Bắt đầu migration: Create payment_transactions table...\n";

    // Kiểm tra bảng đã tồn tại chưa
    $stmt = $pdo->query("SHOW TABLES LIKE 'payment_transactions'");
    if ($stmt->rowCount() > 0) {
        echo "⏭️ Bảng 'payment_transactions' đã tồn tại\n";
    } else {
        $sql = "CREATE TABLE payment_transactions (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            
            -- Liên kết với order
            order_id INT NOT NULL,
            
            -- Loại giao dịch
            transaction_type ENUM(
                'payment',           -- Thanh toán từ buyer
                'escrow_hold',       -- Giữ tiền vào escrow
                'escrow_release',    -- Giải ngân cho seller
                'refund'             -- Hoàn tiền cho buyer
            ) NOT NULL,
            
            -- Số tiền giao dịch
            amount DECIMAL(15,2) NOT NULL,
            
            -- Thông tin từ PayOS
            payment_link_id VARCHAR(100) NULL COMMENT 'ID link thanh toán từ PayOS',
            payos_transaction_id VARCHAR(100) NULL COMMENT 'Mã giao dịch từ PayOS',
            payos_reference VARCHAR(100) NULL COMMENT 'Reference number từ ngân hàng',
            payos_order_code BIGINT UNSIGNED NULL COMMENT 'Mã đơn hàng gửi cho PayOS',
            
            -- Trạng thái
            status ENUM('pending', 'processing', 'success', 'failed') DEFAULT 'pending',
            
            -- Thông tin bổ sung (JSON)
            metadata JSON NULL COMMENT 'Dữ liệu raw từ PayOS webhook',
            
            -- Timestamps
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
            
            -- Indexes
            INDEX idx_order_id (order_id),
            INDEX idx_payment_link_id (payment_link_id),
            INDEX idx_payos_order_code (payos_order_code),
            INDEX idx_status (status),
            INDEX idx_transaction_type (transaction_type),
            INDEX idx_created_at (created_at),
            
            -- Foreign key
            CONSTRAINT fk_payment_trans_order 
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
                
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        COMMENT='Lịch sử giao dịch thanh toán PayOS'";

        $pdo->exec($sql);
        echo "✅ Đã tạo bảng 'payment_transactions'\n";
    }

    echo "\n✅ Migration hoàn tất: Create payment_transactions table!\n";

} catch (PDOException $e) {
    echo "❌ Lỗi migration: " . $e->getMessage() . "\n";
    exit(1);
}
