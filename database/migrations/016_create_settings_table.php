<?php

use App\Core\Database;

/**
 * Migration: Tạo bảng settings
 * Lưu trữ cấu hình website dạng key-value
 */
class CreateSettingsTable
{
    public function up()
    {
        $db = Database::getInstance();

        $sql = "CREATE TABLE IF NOT EXISTS settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(100) NOT NULL UNIQUE,
            setting_value TEXT NULL,
            setting_group VARCHAR(50) NOT NULL DEFAULT 'general',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            
            INDEX idx_setting_key (setting_key),
            INDEX idx_setting_group (setting_group)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

        $db->execute($sql);

        // Insert dữ liệu mặc định
        $this->seedDefaultSettings($db);

        return true;
    }

    /**
     * Thêm các settings mặc định
     */
    private function seedDefaultSettings($db)
    {
        $defaults = [
            // General - Thông tin website
            ['site_name', 'UniMarket', 'general'],
            ['site_description', 'Sàn thương mại điện tử cho sinh viên', 'general'],
            ['site_logo', '', 'general'],
            ['site_favicon', '', 'general'],

            // Contact - Liên hệ
            ['contact_email', 'admin@unimarket.com', 'contact'],
            ['contact_phone', '', 'contact'],
            ['contact_address', '', 'contact'],

            // Email - SMTP
            ['smtp_host', '', 'email'],
            ['smtp_port', '587', 'email'],
            ['smtp_username', '', 'email'],
            ['smtp_password', '', 'email'],
            ['smtp_encryption', 'tls', 'email'],
            ['mail_from_name', 'UniMarket', 'email'],
            ['mail_from_email', '', 'email'],

            // Payment - Thanh toán
            ['payment_gateway', '', 'payment'],
            ['payment_api_key', '', 'payment'],
            ['payment_secret_key', '', 'payment'],

            // Social - Mạng xã hội
            ['social_facebook', '', 'social'],
            ['social_zalo', '', 'social'],
            ['social_instagram', '', 'social'],
            ['social_youtube', '', 'social'],

            // Maintenance - Bảo trì
            ['maintenance_mode', '0', 'maintenance'],
            ['maintenance_message', 'Website đang bảo trì, vui lòng quay lại sau.', 'maintenance'],
        ];

        $sql = "INSERT IGNORE INTO settings (setting_key, setting_value, setting_group) VALUES (:key, :value, :group)";

        foreach ($defaults as $setting) {
            $db->execute($sql, [
                'key' => $setting[0],
                'value' => $setting[1],
                'group' => $setting[2]
            ]);
        }
    }

    public function down()
    {
        $db = Database::getInstance();
        $sql = "DROP TABLE IF EXISTS settings";
        return $db->execute($sql);
    }
}
/**
 * Hàm run() để tương thích với migrate.php
 */
function run_016_create_settings_table($pdo)
{
    $migration = new CreateSettingsTable();
    $migration->up();
}
