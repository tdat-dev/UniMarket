<?php

namespace App\Models;

class ProductImage extends BaseModel
{
    protected $table = 'product_images';

    /**
     * Lấy tất cả ảnh của sản phẩm
     */
    public function getByProductId($productId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE product_id = ? ORDER BY is_primary DESC, sort_order ASC";
        return $this->db->query($sql, [$productId])->fetchAll();
    }

    /**
     * Lấy ảnh chính của sản phẩm
     */
    public function getPrimary($productId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE product_id = ? AND is_primary = 1 LIMIT 1";
        return $this->db->query($sql, [$productId])->fetch();
    }

    /**
     * Thêm ảnh cho sản phẩm
     */
    public function addImage($productId, $imagePath, $isPrimary = 0, $sortOrder = 0)
    {
        $sql = "INSERT INTO {$this->table} (product_id, image_path, is_primary, sort_order) VALUES (?, ?, ?, ?)";
        return $this->db->query($sql, [$productId, $imagePath, $isPrimary, $sortOrder]);
    }

    /**
     * Thêm nhiều ảnh cùng lúc
     */
    public function addMultiple($productId, $imagePaths)
    {
        if (empty($imagePaths))
            return;

        foreach ($imagePaths as $index => $path) {
            $isPrimary = ($index === 0) ? 1 : 0; // Ảnh đầu tiên là ảnh chính
            $this->addImage($productId, $path, $isPrimary, $index);
        }
    }

    /**
     * Xóa tất cả ảnh của sản phẩm
     */
    public function deleteByProductId($productId)
    {
        $sql = "DELETE FROM {$this->table} WHERE product_id = ?";
        return $this->db->query($sql, [$productId]);
    }

    /**
     * Đếm số ảnh của sản phẩm
     */
    public function countByProductId($productId)
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE product_id = ?";
        $result = $this->db->query($sql, [$productId])->fetch();
        return $result['total'] ?? 0;
    }
}
