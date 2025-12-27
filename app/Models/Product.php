<?php

namespace App\Models;

class Product extends BaseModel  // Kế thừa BaseModel → tự động có $this->db
{
    // Lấy tất cả sản phẩm
    public function all()
    {
        // fetchAll = helper method trong Database.php
        // SELECT * FROM products, sắp xếp theo id giảm dần
        return $this->db->fetchAll("SELECT * FROM products ORDER BY id DESC");
    }

    // Lấy 1 sản phẩm theo ID
    public function find($id)
    {
        // fetchOne = lấy 1 dòng
        // :id là placeholder (prepared statement) để tránh SQL injection
        return $this->db->fetchOne(
            "SELECT * FROM products WHERE id = :id", 
            ['id' => $id]  // Gán giá trị $id vào :id
        );
    }

    // Tạo sản phẩm mới
    public function create($data)
    {
        $sql = "INSERT INTO products (name, price, description) 
                VALUES (:name, :price, :description)";
        
        // insert() trả về ID của dòng vừa tạo
        return $this->db->insert($sql, [
            'name' => $data['name'],
            'price' => $data['price'],
            'description' => $data['description']
        ]);
    }
}