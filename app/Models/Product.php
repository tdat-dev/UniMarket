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
        // Lấy 5 keyword phổ biến nhất
        $keywordModel = new SearchKeyword();
        $topKeywords = $keywordModel->getTopKeywords(5);

        $products = [];

        // Nếu có keyword, thử tìm sản phẩm
        if (!empty($topKeywords)) {
            $allWords = [];
            // Tách từng keyword thành các từ riêng lẻ
            foreach ($topKeywords as $kw) {
                $keyword = $kw['keyword'];
                // Tách theo khoảng trắng
                $words = explode(' ', trim($keyword));
                foreach ($words as $word) {
                    $word = trim($word);
                    // Chỉ lấy từ có độ dài >= 3 ký tự (tránh từ như "c", "a"...)
                    if (mb_strlen($word) >= 3) {
                        $allWords[] = $word;
                    }
                }
            }

            // Loại bỏ từ trùng lặp
            $allWords = array_unique($allWords);

            if (!empty($allWords)) {
                // Tạo điều kiện LIKE cho mỗi từ
                $conditions = [];
                foreach ($allWords as $word) {
                    $conditions[] = "name LIKE '%$word%'";
                }
                $whereClause = implode(' OR ', $conditions);

                $sql = "SELECT * FROM products WHERE status = 'active' AND ($whereClause) LIMIT $limit";
                $products = $this->db->fetchAll($sql);
            }
        }

        // Nếu số lượng sản phẩm tìm được < limit -> Lấy thêm ngẫu nhiên bù vào
        // $count = count($products);
        // if ($count < $limit) {
        //     $needed = $limit - $count;

        //     // Lấy ID các sản phẩm đã có để loại trừ (tránh trùng)
        //     $existingIds = array_column($products, 'id');
        //     $excludeSql = "";
        //     if (!empty($existingIds)) {
        //         $ids = implode(',', $existingIds);
        //         $excludeSql = "AND id NOT IN ($ids)";
        //     }

        //     // Query lấy thêm
        //     $moreProducts = $this->db->fetchAll(
        //         "SELECT * FROM products WHERE status = 'active' $excludeSql ORDER BY RAND() LIMIT $needed"
        //     );

        //     // Gộp lại
        //     $products = array_merge($products, $moreProducts);
        // }

        return $products;
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