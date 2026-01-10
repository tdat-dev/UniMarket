<?php

namespace App\Models;

use App\Core\Database;

/**
 * Model UserAddress
 * 
 * Quản lý địa chỉ giao hàng của users với tích hợp HERE Maps.
 * Mỗi user có thể có nhiều địa chỉ và 1 địa chỉ mặc định.
 * 
 * @author UniMarket Team
 * @version 1.0.0
 */
class UserAddress extends BaseModel
{
    protected $table = 'user_addresses';

    /**
     * Các trường được phép mass assignment
     */
    protected array $fillable = [
        'user_id',
        'label',
        'recipient_name',
        'phone_number',
        'province',
        'district',
        'ward',
        'street_address',
        'full_address',
        'latitude',
        'longitude',
        'here_place_id',
        'is_default'
    ];

    /**
     * Lấy tất cả địa chỉ của user
     * 
     * @param int $userId User ID
     * @return array Danh sách địa chỉ, mặc định lên đầu
     */
    public function getByUserId(int $userId): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE user_id = :user_id 
                ORDER BY is_default DESC, created_at DESC";

        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }

    /**
     * Lấy địa chỉ mặc định của user
     * 
     * @param int $userId User ID
     * @return array|null Địa chỉ mặc định hoặc null
     */
    public function getDefaultAddress(int $userId): ?array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE user_id = :user_id AND is_default = 1 
                LIMIT 1";

        $result = $this->db->fetchOne($sql, ['user_id' => $userId]);
        return $result ?: null;
    }

    /**
     * Lấy địa chỉ theo ID (kèm kiểm tra ownership)
     * 
     * @param int $id Address ID
     * @param int|null $userId Optional: kiểm tra ownership
     * @return array|null
     */
    public function findById(int $id, ?int $userId = null): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $params = ['id' => $id];

        if ($userId !== null) {
            $sql .= " AND user_id = :user_id";
            $params['user_id'] = $userId;
        }

        $result = $this->db->fetchOne($sql, $params);
        return $result ?: null;
    }

    /**
     * Tạo địa chỉ mới
     * 
     * @param array $data Dữ liệu địa chỉ
     * @return int ID của địa chỉ mới
     */
    public function create(array $data): int
    {
        // Nếu đây là địa chỉ đầu tiên hoặc được đặt mặc định
        if (!empty($data['is_default'])) {
            $this->clearDefaultAddress($data['user_id']);
        }

        // Nếu chưa có địa chỉ nào, tự động đặt mặc định
        $existingCount = $this->countByUserId($data['user_id']);
        if ($existingCount === 0) {
            $data['is_default'] = 1;
        }

        // Tạo full_address nếu chưa có
        if (empty($data['full_address'])) {
            $data['full_address'] = $this->buildFullAddress($data);
        }

        $columns = [];
        $placeholders = [];
        $params = [];

        foreach ($this->fillable as $field) {
            if (array_key_exists($field, $data)) {
                $columns[] = $field;
                $placeholders[] = ":$field";
                $params[$field] = $data[$field];
            }
        }

        $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";

        return $this->db->insert($sql, $params);
    }

    /**
     * Cập nhật địa chỉ
     * 
     * @param int $id Address ID
     * @param array $data Dữ liệu cập nhật
     * @param int|null $userId Optional: kiểm tra ownership
     * @return bool
     */
    public function update(int $id, array $data, ?int $userId = null): bool
    {
        // Kiểm tra ownership nếu cần
        if ($userId !== null) {
            $address = $this->findById($id, $userId);
            if (!$address) {
                return false;
            }
        }

        // Xử lý is_default
        if (!empty($data['is_default']) && $userId) {
            $this->clearDefaultAddress($userId);
        }

        // Cập nhật full_address nếu có thay đổi địa chỉ
        if (
            isset($data['street_address']) || isset($data['ward']) ||
            isset($data['district']) || isset($data['province'])
        ) {

            $current = $this->findById($id);
            $merged = array_merge($current, $data);
            $data['full_address'] = $this->buildFullAddress($merged);
        }

        $sets = [];
        $params = ['id' => $id];

        foreach ($this->fillable as $field) {
            if (array_key_exists($field, $data)) {
                $sets[] = "$field = :$field";
                $params[$field] = $data[$field];
            }
        }

        if (empty($sets)) {
            return false;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE id = :id";

        if ($userId !== null) {
            $sql .= " AND user_id = :user_id";
            $params['user_id'] = $userId;
        }

        return $this->db->execute($sql, $params);
    }

    /**
     * Xóa địa chỉ
     * 
     * @param int $id Address ID
     * @param int|null $userId Optional: kiểm tra ownership
     * @return bool
     */
    public function delete(int $id, ?int $userId = null): bool
    {
        $address = $this->findById($id, $userId);
        if (!$address) {
            return false;
        }

        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $params = ['id' => $id];

        if ($userId !== null) {
            $sql .= " AND user_id = :user_id";
            $params['user_id'] = $userId;
        }

        $result = $this->db->execute($sql, $params);

        // Nếu xóa địa chỉ mặc định, đặt địa chỉ khác làm mặc định
        if ($result && $address['is_default'] && $userId) {
            $this->setFirstAsDefault($userId);
        }

        return $result;
    }

    /**
     * Đặt địa chỉ làm mặc định
     * 
     * @param int $id Address ID
     * @param int $userId User ID (bắt buộc để clear các default khác)
     * @return bool
     */
    public function setAsDefault(int $id, int $userId): bool
    {
        // Kiểm tra địa chỉ thuộc về user
        $address = $this->findById($id, $userId);
        if (!$address) {
            return false;
        }

        // Bỏ default của các địa chỉ khác
        $this->clearDefaultAddress($userId);

        // Đặt địa chỉ này làm default
        $sql = "UPDATE {$this->table} SET is_default = 1 WHERE id = :id AND user_id = :user_id";
        return $this->db->execute($sql, ['id' => $id, 'user_id' => $userId]);
    }

    /**
     * Đếm số địa chỉ của user
     */
    public function countByUserId(int $userId): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE user_id = :user_id";
        $result = $this->db->fetchOne($sql, ['user_id' => $userId]);
        return (int) ($result['total'] ?? 0);
    }

    /**
     * Bỏ flag default của tất cả địa chỉ user
     */
    private function clearDefaultAddress(int $userId): void
    {
        $sql = "UPDATE {$this->table} SET is_default = 0 WHERE user_id = :user_id";
        $this->db->execute($sql, ['user_id' => $userId]);
    }

    /**
     * Đặt địa chỉ đầu tiên làm mặc định
     */
    private function setFirstAsDefault(int $userId): void
    {
        $sql = "UPDATE {$this->table} 
                SET is_default = 1 
                WHERE user_id = :user_id 
                ORDER BY created_at ASC 
                LIMIT 1";
        $this->db->execute($sql, ['user_id' => $userId]);
    }

    /**
     * Tạo full_address từ các thành phần
     */
    private function buildFullAddress(array $data): string
    {
        $parts = array_filter([
            $data['street_address'] ?? '',
            $data['ward'] ?? '',
            $data['district'] ?? '',
            $data['province'] ?? ''
        ]);

        return implode(', ', $parts);
    }
}
