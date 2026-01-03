<?php
/**
 * MIGRATION RUNNER
 * Hỗ trợ cả file .sql và .php
 * 
 * - File .sql: Chạy trực tiếp bằng PDO::exec()
 * - File .php: Include file và gọi hàm run($pdo)
 */

require_once __DIR__ . '/../app/Core/Database.php';

$db = \App\Core\Database::getInstance();
$pdo = $db->getConnection();

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

// Lấy tất cả file migration (.sql và .php)
$migrationPath = __DIR__ . '/migrations/';
$sqlFiles = glob($migrationPath . '*.sql');
$phpFiles = glob($migrationPath . '*.php');
$files = array_merge($sqlFiles, $phpFiles);
sort($files);

$count = 0;
foreach ($files as $file) {
    $filename = basename($file);
    $extension = pathinfo($file, PATHINFO_EXTENSION);

    if (in_array($filename, $executedFiles)) {
        continue;
    }

    try {
        if ($extension === 'sql') {
            // Chạy file SQL
            $sql = file_get_contents($file);
            $pdo->exec($sql);
        } elseif ($extension === 'php') {
            // Chạy file PHP
            // Cách mới: File trả về một anonymous object có phương thức run()
            // return new class { public function run($pdo) { ... } };
            $migration = require_once $file;

            if (is_object($migration) && method_exists($migration, 'run')) {
                $migration->run($pdo);
            } elseif (function_exists('run')) {
                // Cách cũ: File định nghĩa hàm run() global
                // Warning: Dễ gây lỗi "Cannot redeclare run()" nếu có nhiều file PHP
                run($pdo);
            }
        }

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
