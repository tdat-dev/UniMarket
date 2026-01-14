<?php
require_once __DIR__ . '/vendor/autoload.php';

// Simulate what config/database.php does but standalone so we can check priority
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo "Environment DB: " . ($_ENV['DB_DATABASE'] ?? 'null') . "\n";
echo "getenv DB: " . getenv('DB_DATABASE') . "\n";
// Default to checking for zoldify_test
if (!isset($_ENV['DB_DATABASE']))
    $_ENV['DB_DATABASE'] = 'zoldify_test';

$config = require __DIR__ . '/config/database.php';
echo "Final Config DB: " . $config['db_name'] . "\n";
