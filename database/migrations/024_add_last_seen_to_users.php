<?php

use App\Core\Database;

/**
 * Migration: Thêm cột last_seen cho tính năng hiển thị online status
 * 
 * Cột này lưu thời điểm cuối cùng user hoạt động (disconnect khỏi Socket.IO)
 * Dùng để hiển thị "X phút trước" khi user offline
 */
return new class {
    public function up(): void
    {
        $db = Database::getInstance()->getConnection();

        // Kiểm tra cột đã tồn tại chưa
        $stmt = $db->prepare("SHOW COLUMNS FROM users LIKE 'last_seen'");
        $stmt->execute();

        if ($stmt->rowCount() === 0) {
            $db->exec("ALTER TABLE users ADD COLUMN last_seen DATETIME NULL DEFAULT NULL");
            echo "✅ Đã thêm cột 'last_seen' vào bảng 'users'\n";
        } else {
            echo "⏭️ Cột 'last_seen' đã tồn tại\n";
        }
    }

    public function down(): void
    {
        $db = Database::getInstance()->getConnection();
        $db->exec("ALTER TABLE users DROP COLUMN last_seen");
        echo "✅ Đã xóa cột 'last_seen'\n";
    }
};