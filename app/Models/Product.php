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

    public function getLatest($limit = 12)
{
    // Lấy sản phẩm mới nhất, giới hạn 12 cái để trang chủ không bị quá dài
    return $this->db->fetchAll("SELECT * FROM products ORDER BY id DESC LIMIT :limit", ['limit' => $limit]);
}

    // Tạo sản phẩm mới
    public function create($data)
    {
        // Lưu ý: Cột trong DB nên đặt là 'image' hoặc 'thumbnail'
        $sql = "INSERT INTO products (title, price, description, user_id, category_id, image, status) 
                VALUES (:title, :price, :description, :user_id, :category_id, :image, 'active')";
        
        return $this->db->insert($sql, [
            'title' => $data['title'],
            'price' => $data['price'],
            'description' => $data['description'],
            'user_id' => $data['user_id'] ?? 1, // Tạm thời set cứng nếu chưa login
            'category_id' => $data['category_id'] ?? 1,
            'image' => $data['image'] // <-- Chỉ lưu tên file
        ]);
    }
}