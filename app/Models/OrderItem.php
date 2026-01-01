<?php

namespace App\Models;

class OrderItem extends BaseModel
{
    protected $table = 'order_details';

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (order_id, product_id, quantity, price_at_purchase) 
                VALUES (:order_id, :product_id, :quantity, :price)";
        
        return $this->db->insert($sql, [
            'order_id' => $data['order_id'],
            'product_id' => $data['product_id'],
            'quantity' => $data['quantity'],
            'price' => $data['price']
        ]);
    }

    public function getByOrderId($orderId)
    {
        $sql = "SELECT od.*, p.name as product_name, p.image as product_image 
                FROM {$this->table} od
                JOIN products p ON od.product_id = p.id
                WHERE od.order_id = :order_id";
        return $this->db->fetchAll($sql, ['order_id' => $orderId]);
    }
    
    // Get items purchased by user that haven't been reviewed
    public function getUnreviewedItems($userId)
    {
        // Logic: 
        // 1. Get completed orders by user
        // 2. Get items in those orders
        // 3. Exclude items where (user_id, product_id) exists in reviews table
        // Note: This matches simple review logic (1 review per product per user). 
        // If we want 1 review per purchase, we need to key by order_id too, but db.sql review table doesn't have order_id.
        // So we assume 1 review per product.
        
        $sql = "SELECT od.*, o.created_at as order_date, p.name as product_name, p.image as product_image, p.id as product_id
                FROM order_details od
                JOIN orders o ON od.order_id = o.id
                JOIN products p ON od.product_id = p.id
                LEFT JOIN reviews r ON (r.product_id = od.product_id AND r.reviewer_id = :uid)
                WHERE o.buyer_id = :uid2 
                AND o.status = 'completed'
                AND r.id IS NULL
                ORDER BY o.created_at DESC";
                
        return $this->db->fetchAll($sql, ['uid' => $userId, 'uid2' => $userId]);
    }
}
