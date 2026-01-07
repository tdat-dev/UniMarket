<?php
namespace App\Models;

use App\Core\RedisCache;

class Category extends BaseModel
{
    protected $table = 'categories';

    public function getAll()
    {
        $cacheKey = 'categories_all_v2'; // Changed key to force refresh schema
        $cacheTTL = 300;

        $redis = RedisCache::getInstance();
        if ($redis->isAvailable()) {
            $categories = $redis->get($cacheKey);
            if ($categories === null) {
                $categories = $this->db->query("SELECT * FROM " . $this->table)->fetchAll();
                $redis->set($cacheKey, $categories, $cacheTTL);
            }
            return $categories;
        }

        return $this->db->query("SELECT * FROM " . $this->table)->fetchAll();
    }

    public function getTree()
    {
        $all = $this->getAll();
        $parents = [];
        $children = [];

        foreach ($all as $cat) {
            $cat['children'] = [];
            if (empty($cat['parent_id'])) {
                $parents[$cat['id']] = $cat;
            } else {
                $children[] = $cat;
            }
        }

        foreach ($children as $child) {
            if (isset($parents[$child['parent_id']])) {
                $parents[$child['parent_id']]['children'][] = $child;
            }
        }

        return array_values($parents);
    }

    /**
     * Lấy chỉ danh mục cha (không có parent_id), sắp xếp theo id
     */
    public function getParents(int $limit = 20): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE parent_id IS NULL ORDER BY id ASC LIMIT :limit";
        return $this->db->fetchAll($sql, ['limit' => $limit]);
    }

    // ===================== ADMIN METHODS =====================

    /**
     * Lấy 1 category theo ID
     */
    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]) ?: null;
    }

    /**
     * Đếm tổng số categories
     */
    public function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->db->fetchOne($sql);
        return $result['total'] ?? 0;
    }

    /**
     * Thêm category mới
     */
    public function create(array $data): int|false
    {
        $sql = "INSERT INTO {$this->table} (name, icon, description) 
            VALUES (:name, :icon, :description)";

        // Clear cache khi thêm mới
        $redis = RedisCache::getInstance();
        if ($redis->isAvailable()) {
            $redis->delete('categories_all');
        }

        return $this->db->insert($sql, [
            'name' => $data['name'],
            'icon' => $data['icon'] ?? '',
            'description' => $data['description'] ?? ''
        ]);
    }

    /**
     * Xóa category
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";

        // Clear cache
        $redis = RedisCache::getInstance();
        if ($redis->isAvailable()) {
            $redis->delete('categories_all');
        }

        return $this->db->execute($sql, ['id' => $id]);
    }

    /**
     * Đếm số sản phẩm trong category
     */
    public function countProducts(int $categoryId): int
    {
        $sql = "SELECT COUNT(*) as total FROM products WHERE category_id = :id";
        $result = $this->db->fetchOne($sql, ['id' => $categoryId]);
        return $result['total'] ?? 0;
    }

    /**
     * Cập nhật category
     */
    public function update(int $id, array $data): bool
    {
        // Build SQL động - chỉ update những field có giá trị
        $fields = ['name = :name', 'description = :description'];
        $params = [
            'id' => $id,
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
        ];

        // Chỉ update icon nếu có upload mới
        if (!empty($data['icon'])) {
            $fields[] = 'icon = :icon';
            $params['icon'] = $data['icon'];
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :id";

        // Clear cache
        $redis = RedisCache::getInstance();
        if ($redis->isAvailable()) {
            $redis->delete('categories_all');
        }

        return $this->db->execute($sql, $params);
    }
}
