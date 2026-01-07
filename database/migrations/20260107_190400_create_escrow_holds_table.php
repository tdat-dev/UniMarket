<?php
/**
 * Migration: Create escrow_holds table
 * 
 * Bảng quản lý tiền đang được giữ (escrow).
 * Khi buyer thanh toán thành công → tạo escrow_hold
 * Khi hết thời gian thử hàng → release escrow → cộng vào ví seller
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

    echo "🚀 Bắt đầu migration: Create escrow_holds table...\n";

    // Kiểm tra bảng đã tồn tại chưa
    $stmt = $pdo->query("SHOW TABLES LIKE 'escrow_holds'");
    if ($stmt->rowCount() > 0) {
        echo "⏭️ Bảng 'escrow_holds' đã tồn tại\n";
    } else {
        $sql = "CREATE TABLE escrow_holds (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            
            -- Liên kết (1 order = 1 escrow hold)
            order_id INT NOT NULL UNIQUE,
            seller_id INT NOT NULL,
            
            -- Số tiền
            amount DECIMAL(15,2) NOT NULL COMMENT 'Tổng số tiền giữ',
            platform_fee DECIMAL(15,2) DEFAULT 0.00 COMMENT 'Phí sàn (nếu có)',
            seller_amount DECIMAL(15,2) NOT NULL COMMENT 'Số tiền seller nhận (amount - fee)',
            
            -- Trạng thái
            status ENUM(
                'holding',     -- Đang giữ
                'released',    -- Đã giải ngân
                'refunded',    -- Đã hoàn tiền cho buyer
                'disputed'     -- Đang tranh chấp
            ) DEFAULT 'holding',
            
            -- Thời gian
            held_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm bắt đầu giữ',
            release_scheduled_at TIMESTAMP NULL COMMENT 'Ngày dự kiến giải ngân',
            released_at TIMESTAMP NULL COMMENT 'Ngày thực tế giải ngân',
            
            -- Ghi chú
            release_notes TEXT NULL COMMENT 'Ghi chú khi giải ngân/hoàn tiền',
            
            -- Indexes
            INDEX idx_seller_id (seller_id),
            INDEX idx_status (status),
            INDEX idx_release_scheduled (release_scheduled_at),
            INDEX idx_held_at (held_at),
            
            -- Foreign keys
            CONSTRAINT fk_escrow_order 
                FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
            CONSTRAINT fk_escrow_seller 
                FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE
                
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        COMMENT='Quản lý tiền escrow (giữ lại)'";

        $pdo->exec($sql);
        echo "✅ Đã tạo bảng 'escrow_holds'\n";
    }

    echo "\n✅ Migration hoàn tất: Create escrow_holds table!\n";

} catch (PDOException $e) {
    echo "❌ Lỗi migration: " . $e->getMessage() . "\n";
    exit(1);
}
