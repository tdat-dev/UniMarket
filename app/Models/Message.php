<?php

namespace App\Models;

class Message extends BaseModel
{
    // Send a message
    public function create($data)
    {
        $sql = "INSERT INTO messages (sender_id, receiver_id, content) VALUES (:sender_id, :receiver_id, :content)";
        return $this->db->insert($sql, [
            'sender_id' => $data['sender_id'],
            'receiver_id' => $data['receiver_id'],
            'content' => $data['content']
        ]);
    }

    // Get conversation between two users
    public function getConversation($user1Id, $user2Id)
    {
        $sql = "SELECT * FROM messages 
                WHERE (sender_id = :u1a AND receiver_id = :u2a) 
                   OR (sender_id = :u2b AND receiver_id = :u1b) 
                ORDER BY created_at ASC";
        return $this->db->fetchAll($sql, [
            'u1a' => $user1Id, 
            'u2a' => $user2Id,
            'u2b' => $user2Id,
            'u1b' => $user1Id
        ]);
    }

    // Get list of recent conversations for a user
    public function getRecentConversations($userId)
    {
        // Step 1: Get all unique partners
        $sql = "SELECT DISTINCT 
                    CASE 
                        WHEN sender_id = :uid1 THEN receiver_id 
                        ELSE sender_id 
                    END as partner_id
                FROM messages 
                WHERE sender_id = :uid2 OR receiver_id = :uid3";
        
        $partners = $this->db->fetchAll($sql, [
            'uid1' => $userId, 
            'uid2' => $userId, 
            'uid3' => $userId
        ]);
        
        $results = [];

        foreach ($partners as $p) {
            $partnerId = $p['partner_id'];
            
            // Get partner details
            $userModel = new User();
            $partner = $userModel->find($partnerId);

            if ($partner) {
                // Get last message
                $lastMsg = $this->db->fetchOne("
                    SELECT * FROM messages 
                    WHERE (sender_id = :uid1 AND receiver_id = :pid1) 
                       OR (sender_id = :pid2 AND receiver_id = :uid2)
                    ORDER BY created_at DESC LIMIT 1
                ", [
                    'uid1' => $userId, 
                    'pid1' => $partnerId, 
                    'pid2' => $partnerId, 
                    'uid2' => $userId
                ]);

                $results[] = [
                    'partner' => $partner,
                    'last_message' => $lastMsg
                ];
            }
        }
        
        // Sort by last message time
        usort($results, function($a, $b) {
            return strtotime($b['last_message']['created_at']) - strtotime($a['last_message']['created_at']);
        });

        return $results;
    }
}
