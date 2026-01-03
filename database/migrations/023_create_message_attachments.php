<?php
/**
 * Migration: Tạo bảng message_attachments
 * Lưu file đính kèm trong tin nhắn
 */

require_once __DIR__ . '/../../app/Core/Database.php';

use App\Core\Database;

$db = Database::getInstance();

try {
    // Tạo bảng message_attachments
    $sql = "
        CREATE TABLE IF NOT EXISTS message_attachments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            message_id INT NOT NULL,
            file_name VARCHAR(255) NOT NULL,
            file_path VARCHAR(500) NOT NULL,
            file_type VARCHAR(50) NOT NULL,
            file_size INT NOT NULL DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            
            FOREIGN KEY (message_id) REFERENCES messages(id) ON DELETE CASCADE,
            INDEX idx_message_id (message_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ";

    $db->execute($sql);
    echo "✅ Tạo bảng message_attachments thành công!\n";

    // Kiểm tra xem cột has_attachment đã tồn tại chưa
    $pdo = $db->getConnection();
    $stmt = $pdo->query("
        SELECT COUNT(*) as cnt FROM information_schema.COLUMNS 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'messages' 
        AND COLUMN_NAME = 'has_attachment'
    ");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['cnt'] == 0) {
        // Thêm cột has_attachment vào bảng messages
        $sql2 = "ALTER TABLE messages ADD COLUMN has_attachment TINYINT(1) DEFAULT 0 AFTER is_read";
        $db->execute($sql2);
        echo "✅ Thêm cột has_attachment vào messages thành công!\n";
    } else {
        echo "ℹ️ Cột has_attachment đã tồn tại.\n";
    }

} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
