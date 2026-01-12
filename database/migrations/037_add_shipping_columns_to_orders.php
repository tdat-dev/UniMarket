<?php

/**
 * Migration: Add shipping columns to orders table
 * 
 * Thêm các cột để lưu thông tin giao hàng của đơn hàng:
 * - shipping_address: Địa chỉ giao hàng
 * - shipping_phone: Số điện thoại người nhận
 * - shipping_name: Tên người nhận
 * - note: Ghi chú đơn hàng
 * 
 * @version 037
 * @date 2026-01-12
 */

declare(strict_types=1);

use App\Core\Database;

return new class {
    private \App\Core\Database $db;

    public function __construct()
    {
        $this->db = \App\Core\Database::getInstance();
    }

    /**
     * Run the migration
     */
    public function up(): void
    {
        echo "Adding shipping columns to orders table...\n";

        // Thêm cột shipping_address nếu chưa tồn tại
        if (!$this->columnExists('orders', 'shipping_address')) {
            $this->db->query("
                ALTER TABLE orders 
                ADD COLUMN shipping_address TEXT NULL 
                COMMENT 'Địa chỉ giao hàng đầy đủ'
                AFTER payment_method
            ");
            echo "  ✓ Added column: shipping_address\n";
        } else {
            echo "  - Column shipping_address already exists, skipping\n";
        }

        // Thêm cột shipping_phone nếu chưa tồn tại
        if (!$this->columnExists('orders', 'shipping_phone')) {
            $this->db->query("
                ALTER TABLE orders 
                ADD COLUMN shipping_phone VARCHAR(20) NULL 
                COMMENT 'Số điện thoại người nhận'
                AFTER shipping_address
            ");
            echo "  ✓ Added column: shipping_phone\n";
        } else {
            echo "  - Column shipping_phone already exists, skipping\n";
        }

        // Thêm cột shipping_name nếu chưa tồn tại
        if (!$this->columnExists('orders', 'shipping_name')) {
            $this->db->query("
                ALTER TABLE orders 
                ADD COLUMN shipping_name VARCHAR(100) NULL 
                COMMENT 'Tên người nhận hàng'
                AFTER shipping_phone
            ");
            echo "  ✓ Added column: shipping_name\n";
        } else {
            echo "  - Column shipping_name already exists, skipping\n";
        }

        // Thêm cột note nếu chưa tồn tại
        if (!$this->columnExists('orders', 'note')) {
            $this->db->query("
                ALTER TABLE orders 
                ADD COLUMN note TEXT NULL 
                COMMENT 'Ghi chú của người mua'
                AFTER shipping_name
            ");
            echo "  ✓ Added column: note\n";
        } else {
            echo "  - Column note already exists, skipping\n";
        }

        echo "Migration completed successfully!\n";
    }

    /**
     * Rollback the migration
     */
    public function down(): void
    {
        echo "Removing shipping columns from orders table...\n";

        $columns = ['note', 'shipping_name', 'shipping_phone', 'shipping_address'];

        foreach ($columns as $column) {
            if ($this->columnExists('orders', $column)) {
                $this->db->query("ALTER TABLE orders DROP COLUMN {$column}");
                echo "  ✓ Dropped column: {$column}\n";
            }
        }

        echo "Rollback completed!\n";
    }

    /**
     * Check if a column exists in a table
     */
    private function columnExists(string $table, string $column): bool
    {
        $result = $this->db->fetchAll(
            "SELECT COUNT(*) as count 
             FROM INFORMATION_SCHEMA.COLUMNS 
             WHERE TABLE_SCHEMA = DATABASE() 
             AND TABLE_NAME = ? 
             AND COLUMN_NAME = ?",
            [$table, $column]
        );

        return (int) ($result[0]['count'] ?? 0) > 0;
    }
};
