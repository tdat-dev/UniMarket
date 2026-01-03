<?php
<<<<<<< HEAD
function run_015_add_is_locked($pdo)
{
    // Kiểm tra xem cột is_locked đã tồn tại chưa
    $checkSql = "SHOW COLUMNS FROM users LIKE 'is_locked'";
    $result = $pdo->query($checkSql)->fetch();
=======
return new class {
    public function run($pdo)
    {
        // Kiểm tra xem cột is_locked đã tồn tại chưa
        $checkSql = "SHOW COLUMNS FROM users LIKE 'is_locked'";
        $result = $pdo->query($checkSql)->fetch();
>>>>>>> 98c3c855e9323bd8051f18b796c1fede8aabae8a

        if ($result) {
            echo "Column is_locked already exists, skipping...\n";
            return;
        }

        // Thêm cột is_locked vào bảng users
        $sql = "ALTER TABLE users ADD COLUMN is_locked TINYINT(1) DEFAULT 0";
        $pdo->exec($sql);
        echo "Added is_locked column to users table!\n";
    }
};