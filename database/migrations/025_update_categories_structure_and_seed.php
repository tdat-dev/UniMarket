<?php
require_once __DIR__ . '/../../app/Core/Database.php';

use App\Core\Database;

try {
    $db = Database::getInstance();

    // 1. Add columns if not exist
    // parent_id
    try {
        $db->query("SELECT parent_id FROM categories LIMIT 1");
    } catch (PDOException $e) {
        $db->execute("ALTER TABLE categories ADD COLUMN parent_id INT NULL DEFAULT NULL AFTER id");
        $db->execute("ALTER TABLE categories ADD CONSTRAINT fk_category_parent FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE CASCADE");
    }

    // tag
    try {
        $db->query("SELECT tag FROM categories LIMIT 1");
    } catch (PDOException $e) {
        $db->execute("ALTER TABLE categories ADD COLUMN tag VARCHAR(50) NULL AFTER name");
    }

    // Modify icon to varchar(50) if it's too long, or leave it. 255 is safe. 
    // Just ensure it can hold 'fa-solid fa-book'

    // 2. Truncate and Seed
    $db->execute("SET FOREIGN_KEY_CHECKS = 0");
    $db->execute("TRUNCATE TABLE categories");
    $db->execute("SET FOREIGN_KEY_CHECKS = 1");

    $data = [
        [
            'name' => 'Sách & Giáo trình',
            'icon' => 'fa-book',
            'children' => [
                ['name' => 'Sách giáo khoa - giáo trình'],
                ['name' => 'Sách văn học'],
                ['name' => 'Sách kinh tế'],
                ['name' => 'Sách thiếu nhi'],
                ['name' => 'Sách kỹ năng sống'],
                ['name' => 'Sách học ngoại ngữ'],
                ['name' => 'Truyện tranh (Manga/Comic)']
            ]
        ],
        [
            'name' => 'Đồ điện tử',
            'icon' => 'fa-laptop',
            'tag' => 'Hot',
            'children' => [
                ['name' => 'Điện thoại & Phụ kiện'],
                ['name' => 'Máy tính bảng'],
                ['name' => 'Laptop & PC'],
                ['name' => 'Máy ảnh & Quay phim'],
                ['name' => 'Thiết bị âm thanh']
            ]
        ],
        [
            'name' => 'Đồ học tập',
            'icon' => 'fa-pen-ruler',
            'children' => [
                ['name' => 'Bút viết & Hộp bút'],
                ['name' => 'Vở & Sổ tay'],
                ['name' => 'Dụng cụ vẽ'],
                ['name' => 'Máy tính bỏ túi'],
                ['name' => 'Balo học sinh']
            ]
        ],
        [
            'name' => 'Thời trang',
            'icon' => 'fa-shirt',
            'tag' => 'Trend',
            'children' => [
                ['name' => 'Áo thun & Áo phông'],
                ['name' => 'Áo sơ mi'],
                ['name' => 'Quần Jeans/Kaki'],
                ['name' => 'Áo khoác & Hoodie'],
                ['name' => 'Váy & Đầm']
            ]
        ],
        [
            'name' => 'Phụ kiện',
            'icon' => 'fa-glasses',
            'children' => [
                ['name' => 'Đồng hồ'],
                ['name' => 'Kính mắt'],
                ['name' => 'Trang sức'],
                ['name' => 'Túi xách & Ví'],
                ['name' => 'Giày dép']
            ]
        ],
        [
            'name' => 'Khác',
            'icon' => 'fa-box-open',
            'children' => [
                ['name' => 'Đồ gia dụng'],
                ['name' => 'Nhà cửa & Đời sống'],
                ['name' => 'Thể thao & Du lịch'],
                ['name' => 'Sản phẩm khác']
            ]
        ],
    ];

    foreach ($data as $parent) {
        $parentId = $db->insert("INSERT INTO categories (name, icon, tag) VALUES (:name, :icon, :tag)", [
            'name' => $parent['name'],
            'icon' => $parent['icon'],
            'tag' => $parent['tag'] ?? null
        ]);

        if (isset($parent['children'])) {
            foreach ($parent['children'] as $child) {
                $db->insert("INSERT INTO categories (name, parent_id) VALUES (:name, :parent_id)", [
                    'name' => $child['name'],
                    'parent_id' => $parentId
                ]);
            }
        }
    }

    echo "Migration completed successfully.";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
