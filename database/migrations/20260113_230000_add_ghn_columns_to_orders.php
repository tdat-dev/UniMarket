<?php

/**
 * Migration: Thêm columns GHN vào bảng orders
 * 
 * Columns mới:
 * - ghn_order_code: Mã vận đơn từ GHN (e.g., FFFNL9HH)
 * - ghn_sort_code: Mã phân loại kho
 * - ghn_expected_delivery: Ngày giao dự kiến
 * - ghn_shipping_fee: Phí ship từ GHN
 * - ghn_status: Trạng thái vận chuyển GHN
 * 
 * @date 2026-01-13
 */

return new class {
    public function up(\PDO $pdo): void
    {
        // Kiểm tra nếu column đã tồn tại
        $stmt = $pdo->query("SHOW COLUMNS FROM orders LIKE 'ghn_order_code'");
        if ($stmt->rowCount() > 0) {
            echo "Columns GHN đã tồn tại, bỏ qua.\n";
            return;
        }

        $pdo->exec("
            ALTER TABLE orders
            ADD COLUMN ghn_order_code VARCHAR(20) NULL COMMENT 'Mã vận đơn GHN' AFTER note,
            ADD COLUMN ghn_sort_code VARCHAR(20) NULL COMMENT 'Mã phân loại kho GHN' AFTER ghn_order_code,
            ADD COLUMN ghn_expected_delivery DATETIME NULL COMMENT 'Ngày giao dự kiến' AFTER ghn_sort_code,
            ADD COLUMN ghn_shipping_fee INT UNSIGNED DEFAULT 0 COMMENT 'Phí ship GHN (VND)' AFTER ghn_expected_delivery,
            ADD COLUMN ghn_status VARCHAR(50) NULL COMMENT 'Trạng thái GHN (ready_to_pick, delivering, delivered...)' AFTER ghn_shipping_fee
        ");

        // Thêm index cho mã vận đơn (để tìm kiếm nhanh)
        $pdo->exec("CREATE INDEX idx_orders_ghn_order_code ON orders(ghn_order_code)");

        echo "Đã thêm columns GHN vào bảng orders.\n";
    }

    public function down(\PDO $pdo): void
    {
        $pdo->exec("
            ALTER TABLE orders
            DROP INDEX idx_orders_ghn_order_code,
            DROP COLUMN ghn_order_code,
            DROP COLUMN ghn_sort_code,
            DROP COLUMN ghn_expected_delivery,
            DROP COLUMN ghn_shipping_fee,
            DROP COLUMN ghn_status
        ");

        echo "Đã xóa columns GHN khỏi bảng orders.\n";
    }
};
