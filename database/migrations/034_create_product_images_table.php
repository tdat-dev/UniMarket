<?php
/**
 * Migration: 034_create_product_images_table
 * Tạo bảng lưu nhiều ảnh cho mỗi sản phẩm
 */

require_once __DIR__ . '/../../app/Core/Database.php';

use App\Core\Database;

$db = Database::getInstance();

// Tạo bảng product_images
$sql = "
CREATE TABLE IF NOT EXISTS product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL COMMENT 'Đường dẫn ảnh relative to uploads',
    is_primary TINYINT(1) DEFAULT 0 COMMENT 'Ảnh chính của sản phẩm',
    sort_order INT DEFAULT 0 COMMENT 'Thứ tự hiển thị',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
";

try {
    $db->query($sql);
    echo "✅ Đã tạo bảng product_images thành công!\n";

    // Migrate ảnh từ cột image trong products sang product_images
    $migrateSQL = "
        INSERT INTO product_images (product_id, image_path, is_primary, sort_order)
        SELECT id, image, 1, 0 FROM products 
        WHERE image IS NOT NULL AND image != '' AND image != 'default_product.png'
        ON DUPLICATE KEY UPDATE image_path = VALUES(image_path)
    ";
    $db->query($migrateSQL);
    echo "✅ Đã migrate ảnh từ products sang product_images!\n";

} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
