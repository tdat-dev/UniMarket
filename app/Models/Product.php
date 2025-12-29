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
    $sql = "INSERT INTO products (user_id, category_id, name, description, price, quantity, image_base64, status)
            VALUES (:user_id, :category_id, :name, :description, :price, :quantity, :image_base64, 'active')";

    return $this->db->insert($sql, [
        'user_id' => $data['user_id'] ?? 1,
        'category_id' => $data['category_id'] ?? 1,
        'name' => $data['name'] ?? ($data['title'] ?? null),
        'description' => $data['description'] ?? null,
        'price' => $data['price'] ?? 0,
        'quantity' => $data['quantity'] ?? 0,
        'image_base64' => $data['image_base64'] ?? null,
    ]);
    }
}