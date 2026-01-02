<?php
function run($pdo)
{
    // Kiểm tra xem cột is_locked đã tồn tại chưa
    $checkSql = "SHOW COLUMNS FROM users LIKE 'is_locked'";
    $result = $pdo->query($checkSql)->fetch();

    if ($result) {
        echo "Column is_locked already exists, skipping...\n";
        return;
    }

    // Thêm cột is_locked vào bảng users
    $sql = "ALTER TABLE users ADD COLUMN is_locked TINYINT(1) DEFAULT 0";
    $pdo->exec($sql);
    echo "Added is_locked column to users table!\n";
}