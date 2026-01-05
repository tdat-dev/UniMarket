<?php

namespace App\Models;

class Notification extends BaseModel
{
    protected $table = 'notifications';

    public function create($userId, $content)
    {
        return $this->db->execute(
            "INSERT INTO {$this->table} (user_id, content) VALUES (?, ?)", 
            [$userId, $content]
        );
    }

    public function getUnread($userId)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = ? AND is_read = 0 ORDER BY created_at DESC", 
            [$userId]
        );
    }

    public function getAll($userId, $limit = 20)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = ? ORDER BY created_at DESC LIMIT " . (int)$limit, 
            [$userId]
        );
    }

    public function markAsRead($id, $userId)
    {
        return $this->db->execute(
            "UPDATE {$this->table} SET is_read = 1 WHERE id = ? AND user_id = ?", 
            [$id, $userId]
        );
    }

    public function markAllAsRead($userId)
    {
        return $this->db->execute(
            "UPDATE {$this->table} SET is_read = 1 WHERE user_id = ?", 
            [$userId]
        );
    }
}
