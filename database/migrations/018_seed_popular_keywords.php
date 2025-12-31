<?php

/**
 * Migration: Seed Popular Keywords
 * Mục đích: Thêm dữ liệu mẫu cho bảng search_keywords
 * Giúp hiển thị gợi ý tìm kiếm ngay từ đầu
 */

require_once __DIR__ . '/../../app/Core/Database.php';

use App\Core\Database;

$db = Database::getInstance();

// Danh sách keywords phổ biến với số lượt tìm kiếm giả lập
$popularKeywords = [
    ['keyword' => 'sục crocs', 'search_count' => 150],
    ['keyword' => 'áo khoác', 'search_count' => 120],
    ['keyword' => 'giáo trình c++', 'search_count' => 95],
    ['keyword' => 'bàn phím cơ', 'search_count' => 80],
    ['keyword' => 'tai nghe', 'search_count' => 65],
    ['keyword' => 'sách tiếng anh', 'search_count' => 55],
];

echo "Seeding popular keywords...\n";

foreach ($popularKeywords as $kw) {
    try {
        // Kiểm tra keyword đã tồn tại chưa
        $existing = $db->fetchOne(
            "SELECT id FROM search_keywords WHERE keyword = :keyword",
            ['keyword' => $kw['keyword']]
        );

        if ($existing) {
            // Đã tồn tại → Cập nhật search_count
            $db->execute(
                "UPDATE search_keywords SET search_count = :count WHERE keyword = :keyword",
                [
                    'count' => $kw['search_count'],
                    'keyword' => $kw['keyword']
                ]
            );
            echo "  ✓ Updated: {$kw['keyword']} (count: {$kw['search_count']})\n";
        } else {
            // Chưa tồn tại → Thêm mới
            $db->insert(
                "INSERT INTO search_keywords (keyword, search_count) VALUES (:keyword, :count)",
                [
                    'keyword' => $kw['keyword'],
                    'count' => $kw['search_count']
                ]
            );
            echo "  ✓ Inserted: {$kw['keyword']} (count: {$kw['search_count']})\n";
        }
    } catch (Exception $e) {
        echo "  ✗ Error with '{$kw['keyword']}': " . $e->getMessage() . "\n";
    }
}

echo "\nDone! Popular keywords seeded successfully.\n";
