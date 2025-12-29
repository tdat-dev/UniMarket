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

    public function getRandom($limit = 12)
    {
        // Sử dụng ORDER BY RAND() của MySQL để lấy dữ liệu ngẫu nhiên
        // Chỉ lấy những sản phẩm đang ở trạng thái 'active'
        $sql = "SELECT * FROM products WHERE status = 'active' ORDER BY RAND() LIMIT $limit";
        return $this->db->fetchAll($sql);
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

    // Lấy sản phẩm theo phân trang
    public function getPaginated($limit, $offset)
    {
        return $this->db->fetchAll("SELECT * FROM products ORDER BY id DESC LIMIT :limit OFFSET :offset", [
            'limit' => $limit,
            'offset' => $offset
        ]);
    }

    // Lấy tổng số sản phẩm
    public function countAll()
    {
        $result = $this->db->fetchOne("SELECT COUNT(*) as total FROM products");
        return $result['total'];
    }

    // Lấy sản phẩm theo từ khóa tìm kiếm phổ biến nhất
    public function getByTopKeywords($limit = 6)
    {
        // Lấy top keyword
        $keywordModel = new SearchKeyword();
        $topKeywords = $keywordModel->getTopKeywords(3); // Lấy 3 keyword phổ biến nhất

        if (empty($topKeywords)) {
            return $this->getRandom($limit); // Fallback nếu chưa có data
        }

        // Tạo điều kiện LIKE cho mỗi keyword
        $conditions = [];
        foreach ($topKeywords as $kw) {
            $keyword = $kw['keyword'];
            $conditions[] = "name LIKE '%$keyword%'";
        }
        $whereClause = implode(' OR ', $conditions);

        $sql = "SELECT * FROM products WHERE status = 'active' AND ($whereClause) LIMIT $limit";
        return $this->db->fetchAll($sql);
    }

    // Tìm kiếm sản phẩm theo keyword
    public function searchByKeyword($keyword, $limit, $offset)
    {
        $keyword = trim($keyword);
        if (empty($keyword)) {
            return $this->getPaginated($limit, $offset);
        }

        $sql = "SELECT * FROM products 
            WHERE (name LIKE :keyword) 
            ORDER BY id DESC 
            LIMIT $limit OFFSET $offset";

        return $this->db->fetchAll($sql, ['keyword' => "%$keyword%"]);
    }

    // Đếm số sản phẩm theo keyword
    public function countByKeyword($keyword)
    {
        $keyword = trim($keyword);
        if (empty($keyword)) {
            return $this->countAll();
        }

        $sql = "SELECT COUNT(*) as total FROM products WHERE (name LIKE :keyword)";
        $result = $this->db->fetchOne($sql, ['keyword' => "%$keyword%"]);
        return $result['total'];
    }
}