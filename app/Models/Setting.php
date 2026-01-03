<?php

namespace App\Models;

/**
 * Model Setting
 * Quản lý cài đặt website dạng key-value
 */
class Setting extends BaseModel
{
    /**
     * Lấy giá trị setting theo key
     * @param string $key
     * @param mixed $default Giá trị mặc định nếu không tìm thấy
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $sql = "SELECT setting_value FROM settings WHERE setting_key = :key";
        $result = $this->db->fetchOne($sql, ['key' => $key]);

        return $result ? $result['setting_value'] : $default;
    }

    /**
     * Cập nhật hoặc tạo setting
     * @param string $key
     * @param string $value
     * @param string $group
     * @return bool
     */
    public function set(string $key, string $value, string $group = 'general'): bool
    {
        $sql = "INSERT INTO settings (setting_key, setting_value, setting_group) 
            VALUES (:key, :value, :group)
            ON DUPLICATE KEY UPDATE setting_value = :value2";

        return $this->db->execute($sql, [
            'key' => $key,
            'value' => $value,
            'group' => $group,
            'value2' => $value
        ]) !== false;
    }

    /**
     * Lấy tất cả settings theo group
     * @param string $group
     * @return array
     */
    public function getByGroup(string $group): array
    {
        $sql = "SELECT setting_key, setting_value FROM settings WHERE setting_group = :group";
        $results = $this->db->fetchAll($sql, ['group' => $group]);

        // Chuyển thành mảng key => value cho dễ dùng
        $settings = [];
        foreach ($results as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        return $settings;
    }

    /**
     * Lấy tất cả settings
     * @return array
     */
    public function getAll(): array
    {
        $sql = "SELECT setting_key, setting_value, setting_group FROM settings ORDER BY setting_group, id";
        $results = $this->db->fetchAll($sql);

        // Nhóm theo group
        $grouped = [];
        foreach ($results as $row) {
            $grouped[$row['setting_group']][$row['setting_key']] = $row['setting_value'];
        }

        return $grouped;
    }

    /**
     * Cập nhật nhiều settings cùng lúc
     * @param array $settings Mảng ['key' => 'value', ...]
     * @param string $group
     * @return bool
     */
    public function updateMultiple(array $settings, string $group = ''): bool
    {
        foreach ($settings as $key => $value) {
            $this->set($key, $value, $group ?: 'general');
        }
        return true;
    }
}