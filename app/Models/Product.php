<?php

namespace App\Models;

use App\Core\RedisCache;

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
        // ✅ FIXED: Validate ID trước khi query
        // Chỉ chấp nhận số nguyên dương
        if (!is_numeric($id) || $id <= 0) {
            return null;
        }

        // fetchOne = lấy 1 dòng
        // :id là placeholder (prepared statement) để tránh SQL injection
        return $this->db->fetchOne(
            "SELECT p.*, (SELECT COALESCE(SUM(od.quantity), 0) FROM order_details od JOIN orders o ON od.order_id = o.id WHERE od.product_id = p.id AND o.status != 'cancelled') as sold_count FROM products p WHERE p.id = :id",
            ['id' => (int) $id]  // Cast sang int để đảm bảo an toàn
        );
    }

    public function getLatest($limit = 12)
    {
        $cacheKey = "latest_products_{$limit}";
        $cacheTTL = 300; // 5 phút (products thay đổi thường xuyên hơn)

        // Thử dùng Redis cache
        $redis = RedisCache::getInstance();

        if ($redis->isAvailable()) {
            $products = $redis->get($cacheKey);

            if ($products === null) {
                // Cache miss → Query DB
                $products = $this->db->fetchAll(
                    "SELECT p.*, (SELECT COALESCE(SUM(od.quantity), 0) FROM order_details od JOIN orders o ON od.order_id = o.id WHERE od.product_id = p.id AND o.status != 'cancelled') as sold_count FROM products p ORDER BY id DESC LIMIT :limit",
                    ['limit' => (int) $limit]
                );

                // Lưu vào Redis
                $redis->set($cacheKey, $products, $cacheTTL);
            }

            return $products;
        }

        // Fallback: Redis không khả dụng
        return $this->db->fetchAll(
            "SELECT p.*, (SELECT COALESCE(SUM(od.quantity), 0) FROM order_details od JOIN orders o ON od.order_id = o.id WHERE od.product_id = p.id AND o.status != 'cancelled') as sold_count FROM products p ORDER BY id DESC LIMIT :limit",
            ['limit' => (int) $limit]
        );
    }

    public function getRandom($limit = 12)
    {
        // ✅ FIXED: Validate và dùng prepared statement
        $limit = (int) $limit; // Đảm bảo là số nguyên

        // Sử dụng ORDER BY RAND() của MySQL để lấy dữ liệu ngẫu nhiên
        // Chỉ lấy những sản phẩm đang ở trạng thái 'active'
        $sql = "SELECT * FROM products WHERE status = 'active' ORDER BY RAND() LIMIT :limit";
        return $this->db->fetchAll($sql, ['limit' => $limit]);
    }

    // Tạo sản phẩm mới
    public function create($data)
    {
        // Lưu ý: Cột trong DB nên đặt là 'image' hoặc 'thumbnail'
        $sql = "INSERT INTO products (name, price, description, user_id, category_id, image, quantity, status) 
                VALUES (:name, :price, :description, :user_id, :category_id, :image, :quantity, 'active')";

        return $this->db->insert($sql, [
            'name' => $data['name'],
            'price' => $data['price'],
            'description' => $data['description'],
            'user_id' => $data['user_id'] ?? 1,
            'category_id' => $data['category_id'] ?? 1,
            'image' => $data['image'],
            'quantity' => $data['quantity'] ?? 1
        ]);
    }

    // Lấy sản phẩm theo phân trang
    public function getPaginated($limit, $offset)
    {
        return $this->db->fetchAll("SELECT * FROM products ORDER BY id DESC LIMIT :limit OFFSET :offset", [
            'limit' => (int) $limit,
            'offset' => (int) $offset
        ]);
    }

    // Lấy tổng số sản phẩm
    public function countAll()
    {
        $result = $this->db->fetchOne("SELECT COUNT(*) as total FROM products");
        return $result['total'];
    }

    // ✅ FIXED: Lấy sản phẩm theo từ khóa tìm kiếm phổ biến nhất
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
                // ✅ FIXED: Sử dụng prepared statements thay vì string concatenation
                $conditions = [];
                $params = [];
                $index = 0;

                foreach ($allWords as $word) {
                    $paramName = "word_$index";
                    $conditions[] = "name LIKE :$paramName";
                    $params[$paramName] = "%$word%";
                    $index++;
                }

                $whereClause = implode(' OR ', $conditions);
                $params['limit'] = (int) $limit;

                $sql = "SELECT * FROM products 
                        WHERE status = 'active' 
                        AND ($whereClause) 
                        LIMIT :limit";

                $products = $this->db->fetchAll($sql, $params);
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

    // ✅ FIXED: Tìm kiếm sản phẩm theo keyword
    public function searchByKeyword($keyword, $limit, $offset)
    {
        $keyword = trim($keyword);
        if (empty($keyword)) {
            return $this->getPaginated($limit, $offset);
        }

        // ✅ FIXED: Dùng prepared statement cho LIMIT/OFFSET
        $sql = "SELECT * FROM products 
            WHERE (name LIKE :keyword) 
            ORDER BY id DESC 
            LIMIT :limit OFFSET :offset";

        return $this->db->fetchAll($sql, [
            'keyword' => "%$keyword%",
            'limit' => (int) $limit,
            'offset' => (int) $offset
        ]);
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


    // ✅ FIXED: Lấy sản phẩm theo danh mục (cho phần "Sản phẩm tương tự")
    public function getByCategory($categoryId, $limit = 4, $excludeId = null)
    {
        $sql = "SELECT p.*, (SELECT COALESCE(SUM(od.quantity), 0) FROM order_details od JOIN orders o ON od.order_id = o.id WHERE od.product_id = p.id AND o.status != 'cancelled') as sold_count FROM products p 
                WHERE category_id = :category_id 
                AND status = 'active'";

        $params = ['category_id' => (int) $categoryId];

        if ($excludeId) {
            $sql .= " AND id != :exclude_id";
            $params['exclude_id'] = (int) $excludeId;
        }

        // ✅ FIXED: Dùng prepared statement cho LIMIT
        $sql .= " ORDER BY RAND() LIMIT :limit";
        $params['limit'] = (int) $limit;

        return $this->db->fetchAll($sql, $params);
    }

    // Giảm số lượng tồn kho
    public function decreaseQuantity($id, $amount)
    {
        // Sửa lỗi HY093: Đặt tên tham số khác nhau cho mỗi lần xuất hiện
        $sql = "UPDATE products 
                SET quantity = quantity - :amount_dec 
                WHERE id = :id AND quantity >= :amount_check";

        return $this->db->execute($sql, [
            'id' => (int) $id,
            'amount_dec' => (int) $amount,   // Dùng cho phép trừ
            'amount_check' => (int) $amount  // Dùng cho điều kiện WHERE
        ]);
    }

    // Tăng số lượng tồn kho (khi hủy đơn hàng)
    public function increaseQuantity($id, $amount)
    {
        $sql = "UPDATE products 
                SET quantity = quantity + :amount 
                WHERE id = :id";

        return $this->db->execute($sql, [
            'id' => (int) $id,
            'amount' => (int) $amount
        ]);
    }

    // Đếm tổng số products
    public function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM products";
        $result = $this->db->fetchOne($sql);
        return $result['total'] ?? 0;
    }

    /**
     * Lấy tất cả sản phẩm của một user (seller)
     * Dùng cho trang Shop cá nhân
     */
    public function getByUserId($userId, $limit = 50, $offset = 0)
    {
        $sql = "SELECT p.*, (SELECT COALESCE(SUM(od.quantity), 0) FROM order_details od JOIN orders o ON od.order_id = o.id WHERE od.product_id = p.id AND o.status != 'cancelled') as sold_count FROM products p
                WHERE user_id = :user_id 
                ORDER BY created_at DESC 
                LIMIT :limit OFFSET :offset";

        return $this->db->fetchAll($sql, [
            'user_id' => (int) $userId,
            'limit' => (int) $limit,
            'offset' => (int) $offset
        ]);
    }

    /**
     * Đếm số sản phẩm của một user
     */
    public function countByUserId($userId): int
    {
        $sql = "SELECT COUNT(*) as total FROM products WHERE user_id = :user_id";
        $result = $this->db->fetchOne($sql, ['user_id' => (int) $userId]);
        return $result['total'] ?? 0;
    }

    /**
     * Đếm số sản phẩm ACTIVE của một user (để hiển thị trên shop profile)
     */
    public function countActiveByUserId($userId): int
    {
        $sql = "SELECT COUNT(*) as total FROM products WHERE user_id = :user_id AND status = 'active'";
        $result = $this->db->fetchOne($sql, ['user_id' => (int) $userId]);
        return $result['total'] ?? 0;
    }

    // ===================== ADMIN METHODS =====================

    /**
     * Lấy tất cả products cho admin (có phân trang)
     */
    public function getAllForAdmin($limit = 20, $offset = 0): array
    {
        $sql = "SELECT p.*, c.name as category_name, u.full_name as seller_name
            FROM products p
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN users u ON p.user_id = u.id
            ORDER BY p.created_at DESC
            LIMIT :limit OFFSET :offset";
        return $this->db->fetchAll($sql, ['limit' => $limit, 'offset' => $offset]);
    }

    /**
     * Cập nhật sản phẩm
     */
    public function update(int $id, array $data): bool
    {
        $sql = "UPDATE products SET 
            name = :name, 
            price = :price,
            quantity = :quantity,
            description = :description,
            category_id = :category_id,
            status = :status";

        $params = [
            'id' => $id,
            'name' => $data['name'],
            'price' => $data['price'],
            'quantity' => $data['quantity'] ?? 1,
            'description' => $data['description'] ?? '',
            'category_id' => $data['category_id'],
            'status' => $data['status'] ?? 'active'
        ];

        // Nếu có upload ảnh mới
        if (!empty($data['image'])) {
            $sql .= ", image = :image";
            $params['image'] = $data['image'];
        }

        $sql .= " WHERE id = :id";

        return $this->db->execute($sql, $params);
    }

    /**
     * Xóa sản phẩm
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM products WHERE id = :id";
        return $this->db->execute($sql, ['id' => $id]);
    }

    /**
     * Kiểm tra xem sản phẩm có đơn hàng nào không (bất kể trạng thái)
     */
    public function hasAnyOrder(int $id): bool
    {
        $sql = "SELECT COUNT(*) as total FROM order_details WHERE product_id = :id";
        $result = $this->db->fetchOne($sql, ['id' => $id]);
        return ($result['total'] ?? 0) > 0;
    }

    // ===================== SEARCH & FILTER METHODS =====================

    /**
     * Tìm sản phẩm theo keyword VÀ category
     */
    public function searchByKeywordAndCategory($keyword, $categoryId, $limit, $offset)
    {
        $keyword = trim($keyword);
        $sql = "SELECT * FROM products 
                WHERE name LIKE :keyword 
                AND category_id = :category_id
                AND status = 'active'
                ORDER BY id DESC 
                LIMIT :limit OFFSET :offset";

        return $this->db->fetchAll($sql, [
            'keyword' => "%$keyword%",
            'category_id' => (int) $categoryId,
            'limit' => (int) $limit,
            'offset' => (int) $offset
        ]);
    }

    /**
     * Đếm sản phẩm theo keyword VÀ category (cho phân trang)
     */
    public function countByKeywordAndCategory($keyword, $categoryId)
    {
        $keyword = trim($keyword);
        $sql = "SELECT COUNT(*) as total FROM products 
                WHERE name LIKE :keyword 
                AND category_id = :category_id
                AND status = 'active'";

        $result = $this->db->fetchOne($sql, [
            'keyword' => "%$keyword%",
            'category_id' => (int) $categoryId
        ]);
        return $result['total'];
    }

    /**
     * Lấy sản phẩm theo category có phân trang
     */
    public function getByCategoryPaginated($categoryId, $limit, $offset)
    {
        $sql = "SELECT * FROM products 
                WHERE category_id = :category_id 
                AND status = 'active'
                ORDER BY id DESC 
                LIMIT :limit OFFSET :offset";

        return $this->db->fetchAll($sql, [
            'category_id' => (int) $categoryId,
            'limit' => (int) $limit,
            'offset' => (int) $offset
        ]);
    }

    /**
     * Đếm sản phẩm theo category (cho phân trang)
     */
    public function countByCategory($categoryId)
    {
        $sql = "SELECT COUNT(*) as total FROM products 
                WHERE category_id = :category_id 
                AND status = 'active'";

        $result = $this->db->fetchOne($sql, [
            'category_id' => (int) $categoryId
        ]);
        return $result['total'];
    }

    // ===================== ADVANCED FILTER + SORT =====================

    /**
     * Lấy sản phẩm với đầy đủ filter (category, keyword, price) và sort
     */
    public function getFiltered(array $filters, int $limit, int $offset): array
    {
        $sql = "SELECT p.*, (SELECT COALESCE(SUM(od.quantity), 0) FROM order_details od JOIN orders o ON od.order_id = o.id WHERE od.product_id = p.id AND o.status != 'cancelled') as sold_count FROM products p WHERE status = 'active'";
        $params = [];

        // Filter theo category
        if (!empty($filters['category_id'])) {
            if (is_array($filters['category_id'])) {
                // Support filtering by multiple categories (parent + children)
                $placeholders = [];
                foreach ($filters['category_id'] as $k => $id) {
                    $key = "cat_id_{$k}";
                    $placeholders[] = ":$key";
                    $params[$key] = (int) $id;
                }
                if (!empty($placeholders)) {
                    $sql .= " AND category_id IN (" . implode(',', $placeholders) . ")";
                }
            } else {
                $sql .= " AND category_id = :category_id";
                $params['category_id'] = (int) $filters['category_id'];
            }
        }

        // Filter theo keyword
        if (!empty($filters['keyword'])) {
            $sql .= " AND name LIKE :keyword";
            $params['keyword'] = "%" . trim($filters['keyword']) . "%";
        }

        // Filter theo khoảng giá
        if (!empty($filters['price_min'])) {
            $sql .= " AND price >= :price_min";
            $params['price_min'] = (int) $filters['price_min'];
        }
        if (!empty($filters['price_max'])) {
            $sql .= " AND price <= :price_max";
            $params['price_max'] = (int) $filters['price_max'];
        }

        // Sort
        $sort = $filters['sort'] ?? 'newest';
        switch ($sort) {
            case 'price_asc':
                $sql .= " ORDER BY price ASC";
                break;
            case 'price_desc':
                $sql .= " ORDER BY price DESC";
                break;
            case 'bestseller':
                $sql .= " ORDER BY quantity DESC"; // Hoặc thêm cột sold_count
                break;
            case 'popular':
            case 'newest':
            default:
                $sql .= " ORDER BY id DESC";
                break;
        }

        $sql .= " LIMIT :limit OFFSET :offset";
        $params['limit'] = $limit;
        $params['offset'] = $offset;

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Đếm sản phẩm với đầy đủ filter (cho phân trang)
     */
    public function countFiltered(array $filters): int
    {
        $sql = "SELECT COUNT(*) as total FROM products WHERE status = 'active'";
        $params = [];

        // Filter theo category
        if (!empty($filters['category_id'])) {
            $sql .= " AND category_id = :category_id";
            $params['category_id'] = (int) $filters['category_id'];
        }

        // Filter theo keyword
        if (!empty($filters['keyword'])) {
            $sql .= " AND name LIKE :keyword";
            $params['keyword'] = "%" . trim($filters['keyword']) . "%";
        }

        // Filter theo khoảng giá
        if (!empty($filters['price_min'])) {
            $sql .= " AND price >= :price_min";
            $params['price_min'] = (int) $filters['price_min'];
        }
        if (!empty($filters['price_max'])) {
            $sql .= " AND price <= :price_max";
            $params['price_max'] = (int) $filters['price_max'];
        }

        $result = $this->db->fetchOne($sql, $params);
        return $result['total'] ?? 0;
    }

    protected $table = 'products';

    public function hideProduct($id)
    {
        $sql = "UPDATE products SET status = 'hidden' WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }


}
