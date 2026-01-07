<?php

namespace App\Models;

class Follow extends BaseModel
{
    protected $table = 'follows';

    public function follow($followerId, $followingId)
    {
        // Use execute() from Database wrapper
        return $this->db->execute(
            "INSERT IGNORE INTO {$this->table} (follower_id, following_id) VALUES (?, ?)", 
            [$followerId, $followingId]
        );
    }

    public function unfollow($followerId, $followingId)
    {
        return $this->db->execute(
            "DELETE FROM {$this->table} WHERE follower_id = ? AND following_id = ?", 
            [$followerId, $followingId]
        );
    }

    public function isFollowing($followerId, $followingId)
    {
        // Use query() to get PDOStatement, then fetchColumn()
        $stmt = $this->db->query(
            "SELECT 1 FROM {$this->table} WHERE follower_id = ? AND following_id = ? LIMIT 1", 
            [$followerId, $followingId]
        );
        return (bool) $stmt->fetchColumn();
    }

    public function getFollowers($userId)
    {
        return $this->db->fetchAll(
            "SELECT u.* 
            FROM users u
            JOIN {$this->table} f ON u.id = f.follower_id
            WHERE f.following_id = ?", 
            [$userId]
        );
    }

    public function getFollowing($userId)
    {
        return $this->db->fetchAll(
            "SELECT u.* 
            FROM users u
            JOIN {$this->table} f ON u.id = f.following_id
            WHERE f.follower_id = ?", 
            [$userId]
        );
    }
    
    public function getFollowerCount($userId) {
        $stmt = $this->db->query(
            "SELECT COUNT(*) FROM {$this->table} WHERE following_id = ?", 
            [$userId]
        );
        return $stmt->fetchColumn();
    }
}
