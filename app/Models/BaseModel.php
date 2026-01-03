<?php

namespace App\Models;

use App\Core\Database;

abstract class BaseModel  // abstract = không tạo object trực tiếp, chỉ để kế thừa
{
    protected $db;
    protected $table;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Đếm tổng số bản ghi
    public function count()
    {
        if (!$this->table) {
            // Nếu model con chưa define table -> báo lỗi hoặc return 0
            // Ở đây throw exception để dễ debug
            throw new \Exception("Table property not defined in " . get_class($this));
        }

        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->db->fetchOne($sql);
        return $result['total'] ?? 0;
    }
}