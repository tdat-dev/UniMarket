<?php
<<<<<<< HEAD
function run_014_seed_admin($pdo)
{
    // Hash password bằng PHP
    $hash = password_hash('admin123', PASSWORD_BCRYPT);
=======
return new class {
    public function run($pdo)
    {
        // Hash password bằng PHP
        $hash = password_hash('admin123', PASSWORD_BCRYPT);
>>>>>>> 98c3c855e9323bd8051f18b796c1fede8aabae8a

        $stmt = $pdo->prepare("
            INSERT INTO users (email, password, role, full_name) 
            VALUES (?, ?, 'admin', 'Super Admin')
            ON DUPLICATE KEY UPDATE password = ?
        ");
        $stmt->execute(['superadmin@zoldify.vn', $hash, $hash]);

        echo "Created super admin!\n";
    }
};