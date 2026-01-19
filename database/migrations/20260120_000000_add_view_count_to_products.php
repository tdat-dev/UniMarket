<?php

use App\Core\Database;

class AddViewCountToProducts
{
    public function up(PDO $pdo): void
    {
        $sql = "ALTER TABLE products ADD COLUMN view_count INT DEFAULT 0 AFTER quantity";
        $pdo->exec($sql);
    }

    public function down(PDO $pdo): void
    {
        $sql = "ALTER TABLE products DROP COLUMN view_count";
        $pdo->exec($sql);
    }
}
