<?php

namespace App\Models;

use PDO;

class Report extends BaseModel
{
    protected $table = 'reports';

    public function getAll()
    {
        $sql = "SELECT reports.*, products.name AS product_name
                FROM reports
                JOIN products ON reports.product_id = products.id
                ORDER BY reports.created_at DESC";
        return $this->db->fetchAll($sql);
    }

    public function findById($id)
    {
        $sql = "SELECT reports.*, 
                products.name, 
                products.description,
                products.price,
                products.image,
                products.status,
                products.condition,
                products.quantity,
                users.full_name AS seller_name,
                categories.name AS category_name
                FROM reports
                JOIN products ON reports.product_id = products.id
                LEFT JOIN users ON products.user_id = users.id
                LEFT JOIN categories ON products.category_id = categories.id
                WHERE reports.id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }

    public function markResolved($id)
    {
        $sql = "UPDATE reports SET status = 'resolved' WHERE id = :id";
        return $this->db->execute($sql, ['id' => $id]);
    }
}
