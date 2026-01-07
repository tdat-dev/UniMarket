<?php

namespace App\Models;

class Review extends BaseModel
{
    // Get reviews by user (My Reviews)
    public function getByUserId($userId)
    {
        $sql = "SELECT r.*, p.name as product_name, p.image as product_image 
                FROM reviews r 
                JOIN products p ON r.product_id = p.id 
                WHERE r.reviewer_id = :uid 
                ORDER BY r.created_at DESC";
        return $this->db->fetchAll($sql, ['uid' => $userId]);
    }

    // Get reviews for a product
    public function getByProductId($productId)
    {
        $sql = "SELECT r.*, u.full_name as user_name, u.avatar 
                FROM reviews r 
                JOIN users u ON r.reviewer_id = u.id 
                WHERE r.product_id = :pid 
                ORDER BY r.created_at DESC";
        return $this->db->fetchAll($sql, ['pid' => $productId]);
    }

    public function create($data)
    {
        $sql = "INSERT INTO reviews (reviewer_id, product_id, rating, comment) VALUES (:uid, :pid, :rating, :comment)";
        return $this->db->insert($sql, [
            'uid' => $data['user_id'], // Data array usage stays same if controller passes 'user_id'
            'pid' => $data['product_id'],
            'rating' => $data['rating'],
            'comment' => $data['comment']
        ]);
    }

    public function getSellerStats($sellerId)
    {
        $sql = "SELECT 
                    COUNT(r.id) as review_count, 
                    COALESCE(AVG(r.rating), 0) as avg_rating 
                FROM reviews r 
                JOIN products p ON r.product_id = p.id 
                WHERE p.user_id = :seller_id";
        
        $result = $this->db->fetchOne($sql, ['seller_id' => $sellerId]);
        return $result ? $result : ['review_count' => 0, 'avg_rating' => 0];
    }
}
