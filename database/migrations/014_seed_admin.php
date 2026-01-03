<?php
function run($pdo)
{
    // Hash password bằng PHP
    $hash = password_hash('admin123', PASSWORD_BCRYPT);

    $stmt = $pdo->prepare("
        INSERT INTO users (email, password, role, full_name) 
        VALUES (?, ?, 'admin', 'Super Admin')
        ON DUPLICATE KEY UPDATE password = ?
    ");
    $stmt->execute(['superadmin@zoldify.vn', $hash, $hash]);

    echo "Created super admin!\n";
}