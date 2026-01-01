<?php

namespace App\Models;

class Transaction extends BaseModel
{
    // Get transactions by user
    public function getByUserId($userId)
    {
        return $this->db->fetchAll("SELECT * FROM transactions WHERE user_id = :uid ORDER BY created_at DESC", ['uid' => $userId]);
    }

    // Create transaction (and update balance)
    public function create($data)
    {
        try {
            $this->db->beginTransaction();

            // 1. Insert transaction
            $sql = "INSERT INTO transactions (user_id, type, amount, description, status) 
                    VALUES (:uid, :type, :amount, :desc, :status)";
            
            $this->db->insert($sql, [
                'uid' => $data['user_id'],
                'type' => $data['type'], // 'deposit', 'withdraw'
                'amount' => $data['amount'],
                'desc' => $data['description'] ?? '',
                'status' => $data['status'] ?? 'completed'
            ]);

            // 2. Update User Balance
            if ($data['status'] == 'completed') {
                $adjust = $data['amount'];
                if ($data['type'] == 'withdraw' || $data['type'] == 'payment') {
                    $adjust = -$data['amount'];
                }
                
                $this->db->execute("UPDATE users SET balance = balance + :adjust WHERE id = :uid", [
                    'adjust' => $adjust, 
                    'uid' => $data['user_id']
                ]);
            }

            $this->db->commit();
            return true;

        } catch (\Exception $e) {
            $this->db->rollback();
            return false;
        }
    }
}
