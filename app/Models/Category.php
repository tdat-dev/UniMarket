<?php
namespace App\Models;

class Category extends BaseModel
{
    protected $table = 'categories';

    public function getAll()
    {
        return $this->db->query("SELECT * FROM " . $this->table)->fetchAll();
    }
}
