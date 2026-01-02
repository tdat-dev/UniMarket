<?php

namespace App\Models;

class Order extends BaseModel
{
    protected $table = 'orders';

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (buyer_id, seller_id, total_amount, status) 
                VALUES (:buyer_id, :seller_id, :total_amount, :status)";
        
        return $this->db->insert($sql, [
            'buyer_id' => $data['buyer_id'],
            'seller_id' => $data['seller_id'],
            'total_amount' => $data['total_amount'],
            'status' => $data['status']
        ]);
    }

    public function find($id)
    {
        return $this->db->fetchOne("SELECT * FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }

    public function getByBuyerId($buyerId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE buyer_id = :buyer_id ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, ['buyer_id' => $buyerId]);
    }

    public function getBySellerId($sellerId)
    {
        $sql = "SELECT o.*, u.full_name as buyer_name, u.address as buyer_address 
                FROM {$this->table} o
                JOIN users u ON o.buyer_id = u.id
                WHERE o.seller_id = :seller_id 
                ORDER BY o.created_at DESC";
        return $this->db->fetchAll($sql, ['seller_id' => $sellerId]);
    }
    
    public function updateStatus($orderId, $status, $reason = null)
    {
        if ($reason) {
            $sql = "UPDATE {$this->table} SET status = :status, cancel_reason = :reason WHERE id = :id";
            return $this->db->execute($sql, ['status' => $status, 'reason' => $reason, 'id' => $orderId]);
        } else {
            $sql = "UPDATE {$this->table} SET status = :status WHERE id = :id";
            return $this->db->execute($sql, ['status' => $status, 'id' => $orderId]);
        }
    }
}
