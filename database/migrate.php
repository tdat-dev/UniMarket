<?php
require_once __DIR__ . '/../app/Core/Database.php';

$db = \App\Core\Database::getInstance();

// Tạo bảng tracking migrations nếu chưa có
$db->query("
    CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        filename VARCHAR(255) NOT NULL UNIQUE,
        executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;
");

// Lấy các file đã chạy
$executed = $db->fetchAll("SELECT filename FROM migrations");
$executedFiles = array_column($executed, 'filename');

// Lấy tất cả file migration
$migrationPath = __DIR__ . '/migrations/';
$files = glob($migrationPath . '*.sql');
sort($files);

$count = 0;
foreach ($files as $file) {
    $filename = basename($file);

    if (in_array($filename, $executedFiles)) {
        continue;
    }

    $sql = file_get_contents($file);

    try {
        $db->getConnection()->exec($sql);
        $db->insert("INSERT INTO migrations (filename) VALUES (:filename)", [
            'filename' => $filename
        ]);
        echo "Migrated: $filename\n";
        $count++;
    } catch (Exception $e) {
        echo "Failed: $filename - " . $e->getMessage() . "\n";
        exit(1);
    }
}

echo "\nDone! $count migration(s) executed.\n";