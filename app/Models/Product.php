<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\RedisCache;

/**
 * Product Model
 * 
 * Quản lý sản phẩm trong marketplace.
 * Hỗ trợ: CRUD, search, filter, pagination, caching.
 * 
 * @package App\Models
 */
class Product extends BaseModel
{
    /** @var string */
    protected $table = 'products';

    /** @var array<string> */
    protected array $fillable = [
        'name',
        'price',
        'description',
        'user_id',
        'category_id',
        'image',
        'quantity',
        'status',
        'condition',
    ];

    /** @var array<string> Product statuses */
    public const STATUS_ACTIVE = 'active';
    public const STATUS_HIDDEN = 'hidden';
    public const STATUS_SOLD = 'sold';
    public const STATUS_DELETED = 'deleted';

    /** @var array<string> Product conditions */
    public const CONDITION_NEW = 'new';
    public const CONDITION_LIKE_NEW = 'like_new';
    public const CONDITION_GOOD = 'good';
    public const CONDITION_FAIR = 'fair';

    /** @var string Cache key prefix */
    private const CACHE_PREFIX = 'products_';

    /** @var int Cache TTL in seconds */
    private const CACHE_TTL = 300;

    // =========================================================================
    // QUERY METHODS - SINGLE
    // =========================================================================

    /**
     * Lấy 1 sản phẩm theo ID với sold_count
     * 
     * @param int $id
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }

        $sql = "SELECT p.*, 
                    c.name AS category_name,
                    u.full_name AS seller_name,
                    u.avatar AS seller_avatar,
                    {$this->getSoldCountSubquery()} AS sold_count
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN users u ON p.user_id = u.id
                WHERE p.id = ?";

        return $this->db->fetchOne($sql, [$id]) ?: null;
    }

    /**
     * Lấy sản phẩm cơ bản (không JOIN)
     * 
     * @param int $id
     * @return array<string, mixed>|null
     */
    public function findBasic(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        return $this->db->fetchOne($sql, [$id]) ?: null;
    }

    // =========================================================================
    // QUERY METHODS - LISTING
    // =========================================================================

    /**
     * Lấy sản phẩm mới nhất (có cache)
     * 
     * @param int $limit
     * @return array<int, array<string, mixed>>
     */
    public function getLatest(int $limit = 12): array
    {
        $cacheKey = self::CACHE_PREFIX . "latest_{$limit}";

        $redis = RedisCache::getInstance();
        if ($redis->isAvailable()) {
            $cached = $redis->get($cacheKey);
            if ($cached !== null) {
                return $cached;
            }
        }

        $sql = "SELECT p.*, {$this->getSoldCountSubquery()} AS sold_count
                FROM {$this->table} p
                WHERE p.status = ?
                ORDER BY p.id DESC 
                LIMIT ?";

        $products = $this->db->fetchAll($sql, [self::STATUS_ACTIVE, $limit]);

        if ($redis->isAvailable()) {
            $redis->set($cacheKey, $products, self::CACHE_TTL);
        }

        return $products;
    }

    /**
     * Lấy sản phẩm ngẫu nhiên
     * 
     * @param int $limit
     * @return array<int, array<string, mixed>>
     */
    public function getRandom(int $limit = 12): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE status = ? 
                ORDER BY RAND() 
                LIMIT ?";

        return $this->db->fetchAll($sql, [self::STATUS_ACTIVE, $limit]);
    }

    /**
     * Lấy sản phẩm theo top keywords phổ biến
     * 
     * Tìm các sản phẩm match với keywords được tìm nhiều nhất.
     * 
     * @param int $limit
     * @return array<int, array<string, mixed>>
     */
    public function getByTopKeywords(int $limit = 6): array
    {
        // Lấy top keywords từ search_keywords table
        $keywordsSql = "SELECT keyword FROM search_keywords ORDER BY search_count DESC LIMIT 5";
        $topKeywords = $this->db->fetchAll($keywordsSql);

        if (empty($topKeywords)) {
            // Fallback: return random products if no keywords
            return $this->getRandom($limit);
        }

        // Build LIKE conditions cho mỗi keyword
        $conditions = [];
        $params = [self::STATUS_ACTIVE];

        foreach ($topKeywords as $row) {
            $conditions[] = "p.name LIKE ?";
            $params[] = '%' . $row['keyword'] . '%';
        }

        $whereClause = '(' . implode(' OR ', $conditions) . ')';
        $params[] = $limit;

        $sql = "SELECT p.*, {$this->getSoldCountSubquery()} AS sold_count
                FROM {$this->table} p
                WHERE p.status = ? AND {$whereClause}
                ORDER BY RAND()
                LIMIT ?";

        $products = $this->db->fetchAll($sql, $params);

        // Nếu không đủ sản phẩm, bổ sung bằng random
        if (count($products) < $limit) {
            $existingIds = array_column($products, 'id');
            $remaining = $limit - count($products);

            if (!empty($existingIds)) {
                $excludePlaceholders = implode(',', array_fill(0, count($existingIds), '?'));
                $sql = "SELECT * FROM {$this->table} 
                        WHERE status = ? AND id NOT IN ({$excludePlaceholders})
                        ORDER BY RAND() LIMIT ?";
                $moreProducts = $this->db->fetchAll($sql, array_merge([self::STATUS_ACTIVE], $existingIds, [$remaining]));
            } else {
                $moreProducts = $this->getRandom($remaining);
            }

            $products = array_merge($products, $moreProducts);
        }

        return $products;
    }

    /**
     * Lấy sản phẩm theo user (seller's shop)
     * 
     * @param int $userId
     * @param int $limit
     * @param int $offset
     * @return array<int, array<string, mixed>>
     */
    public function getByUserId(int $userId, int $limit = 50, int $offset = 0): array
    {
        $sql = "SELECT p.*, {$this->getSoldCountSubquery()} AS sold_count
                FROM {$this->table} p
                WHERE p.user_id = ? 
                ORDER BY p.created_at DESC 
                LIMIT ? OFFSET ?";

        return $this->db->fetchAll($sql, [$userId, $limit, $offset]);
    }

    /**
     * Lấy sản phẩm theo category
     * 
     * @param int $categoryId
     * @param int $limit
     * @param int|null $excludeId Exclude product ID
     * @return array<int, array<string, mixed>>
     */
    public function getByCategory(int $categoryId, int $limit = 4, ?int $excludeId = null): array
    {
        $sql = "SELECT p.*, {$this->getSoldCountSubquery()} AS sold_count
                FROM {$this->table} p
                WHERE p.category_id = ? AND p.status = ?";

        $params = [$categoryId, self::STATUS_ACTIVE];

        if ($excludeId !== null) {
            $sql .= " AND p.id != ?";
            $params[] = $excludeId;
        }

        $sql .= " ORDER BY RAND() LIMIT ?";
        $params[] = $limit;

        return $this->db->fetchAll($sql, $params);
    }

    // =========================================================================
    // SEARCH & FILTER (Shopee/Lazada Style)
    // =========================================================================

    /**
     * Tìm kiếm sản phẩm với relevance scoring (giống Shopee/Lazada)
     * 
     * Features:
     * - Tìm trong cả name VÀ description
     * - Relevance scoring: exact match > word start > contains
     * - Tokenize keyword để tìm từng từ
     * - Sort by relevance mặc định
     * 
     * @param string $keyword
     * @param int $limit
     * @param int $offset
     * @return array<int, array<string, mixed>>
     */
    public function searchByKeyword(string $keyword, int $limit = 20, int $offset = 0): array
    {
        $keyword = trim($keyword);
        if (empty($keyword)) {
            return $this->getPaginated($limit, $offset);
        }

        // Tokenize: tách từ khóa thành các từ riêng
        $tokens = $this->tokenizeKeyword($keyword);

        // Build relevance score
        $relevanceScore = $this->buildRelevanceScore($keyword, $tokens);

        // Build search conditions (OR cho mỗi token)
        $searchConditions = $this->buildSearchConditions($tokens);

        $sql = "SELECT p.*, 
                    {$this->getSoldCountSubquery()} AS sold_count,
                    ({$relevanceScore}) AS relevance_score
                FROM {$this->table} p
                WHERE p.status = ? AND ({$searchConditions['sql']})
                ORDER BY relevance_score DESC, sold_count DESC
                LIMIT ? OFFSET ?";

        $params = array_merge(
            [self::STATUS_ACTIVE],
            $searchConditions['params'],
            [$limit, $offset]
        );

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Đếm sản phẩm theo keyword (search trong name + description)
     */
    public function countByKeyword(string $keyword): int
    {
        $keyword = trim($keyword);
        if (empty($keyword)) {
            return $this->countActive();
        }

        $tokens = $this->tokenizeKeyword($keyword);
        $searchConditions = $this->buildSearchConditions($tokens);

        $sql = "SELECT COUNT(*) AS total FROM {$this->table} 
                WHERE status = ? AND ({$searchConditions['sql']})";

        $params = array_merge([self::STATUS_ACTIVE], $searchConditions['params']);
        $result = $this->db->fetchOne($sql, $params);
        return (int) ($result['total'] ?? 0);
    }

    /**
     * Lấy sản phẩm với filter nâng cao (Shopee/Lazada style)
     */
    public function getFiltered(array $filters, int $limit = 20, int $offset = 0): array
    {
        $keyword = trim($filters['keyword'] ?? '');
        $hasKeyword = !empty($keyword);

        // Tokenize keyword
        $tokens = $hasKeyword ? $this->tokenizeKeyword($keyword) : [];
        $relevanceScore = $hasKeyword ? $this->buildRelevanceScore($keyword, $tokens) : '0';
        $searchConditions = $hasKeyword ? $this->buildSearchConditions($tokens) : null;

        $sql = "SELECT p.*, 
                    {$this->getSoldCountSubquery()} AS sold_count
                    " . ($hasKeyword ? ", ({$relevanceScore}) AS relevance_score" : "") . "
                FROM {$this->table} p
                WHERE p.status = ?";
        $params = [self::STATUS_ACTIVE];

        // Filter by keyword (search in name + description)
        if ($searchConditions !== null) {
            $sql .= " AND ({$searchConditions['sql']})";
            $params = array_merge($params, $searchConditions['params']);
        }

        // Filter by category (include children)
        if (!empty($filters['category_id'])) {
            if (is_array($filters['category_id'])) {
                $placeholders = implode(',', array_fill(0, count($filters['category_id']), '?'));
                $sql .= " AND p.category_id IN ({$placeholders})";
                $params = array_merge($params, array_map('intval', $filters['category_id']));
            } else {
                $sql .= " AND p.category_id = ?";
                $params[] = (int) $filters['category_id'];
            }
        }

        // Filter by condition (new, like_new, good, fair)
        if (!empty($filters['condition'])) {
            $sql .= " AND p.condition = ?";
            $params[] = $filters['condition'];
        }

        // Filter by price range
        if (!empty($filters['price_min'])) {
            $sql .= " AND p.price >= ?";
            $params[] = (int) $filters['price_min'];
        }
        if (!empty($filters['price_max'])) {
            $sql .= " AND p.price <= ?";
            $params[] = (int) $filters['price_max'];
        }

        // Filter by minimum rating (3, 4, or 5 stars)
        if (!empty($filters['rating'])) {
            $minRating = (int) $filters['rating'];
            if ($minRating >= 1 && $minRating <= 5) {
                $sql .= " AND (SELECT AVG(r.rating) FROM reviews r WHERE r.product_id = p.id) >= ?";
                $params[] = $minRating;
            }
        }

        // Smart Sort (giống Shopee)
        $sort = $filters['sort'] ?? ($hasKeyword ? 'relevance' : 'newest');
        $sql .= $this->buildSmartSortClause($sort, $hasKeyword);

        // Pagination
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Đếm sản phẩm với filter
     */
    public function countFiltered(array $filters): int
    {
        $keyword = trim($filters['keyword'] ?? '');
        $hasKeyword = !empty($keyword);
        $tokens = $hasKeyword ? $this->tokenizeKeyword($keyword) : [];
        $searchConditions = $hasKeyword ? $this->buildSearchConditions($tokens) : null;

        $sql = "SELECT COUNT(*) AS total FROM {$this->table} p WHERE p.status = ?";
        $params = [self::STATUS_ACTIVE];

        if ($searchConditions !== null) {
            $sql .= " AND ({$searchConditions['sql']})";
            $params = array_merge($params, $searchConditions['params']);
        }

        if (!empty($filters['category_id'])) {
            if (is_array($filters['category_id'])) {
                $placeholders = implode(',', array_fill(0, count($filters['category_id']), '?'));
                $sql .= " AND p.category_id IN ({$placeholders})";
                $params = array_merge($params, array_map('intval', $filters['category_id']));
            } else {
                $sql .= " AND p.category_id = ?";
                $params[] = (int) $filters['category_id'];
            }
        }

        if (!empty($filters['condition'])) {
            $sql .= " AND p.condition = ?";
            $params[] = $filters['condition'];
        }

        if (!empty($filters['price_min'])) {
            $sql .= " AND p.price >= ?";
            $params[] = (int) $filters['price_min'];
        }
        if (!empty($filters['price_max'])) {
            $sql .= " AND p.price <= ?";
            $params[] = (int) $filters['price_max'];
        }

        $result = $this->db->fetchOne($sql, $params);
        return (int) ($result['total'] ?? 0);
    }

    // =========================================================================
    // SEARCH HELPERS (Private)
    // =========================================================================

    /**
     * Tách keyword thành các token (từ)
     * 
     * @return array<string>
     */
    private function tokenizeKeyword(string $keyword): array
    {
        // Chuẩn hóa: lowercase, remove extra spaces
        $keyword = mb_strtolower(trim($keyword));
        $keyword = preg_replace('/\s+/', ' ', $keyword);

        // Tách thành các từ
        $tokens = explode(' ', $keyword);

        // Lọc từ rỗng và stopwords tiếng Việt
        $stopwords = ['và', 'hoặc', 'của', 'cho', 'với', 'trong', 'là', 'có', 'được', 'này'];
        $tokens = array_filter($tokens, fn($t) => strlen($t) >= 2 && !in_array($t, $stopwords));

        return array_values(array_unique($tokens));
    }

    /**
     * Build relevance score SQL
     * 
     * Scoring:
     * - Exact match in name: 100 points
     * - Word starts with keyword in name: 50 points
     * - Contains keyword in name: 20 points
     * - Contains keyword in description: 5 points
     */
    private function buildRelevanceScore(string $keyword, array $tokens): string
    {
        $scores = [];

        // Exact match (full keyword)
        $scores[] = "CASE WHEN LOWER(p.name) = LOWER('{$this->escapeString($keyword)}') THEN 100 ELSE 0 END";

        // Each token contributes to score
        foreach ($tokens as $token) {
            $escaped = $this->escapeString($token);
            // Starts with token in name (50 points)
            $scores[] = "CASE WHEN LOWER(p.name) LIKE '{$escaped}%' THEN 50 ELSE 0 END";
            // Contains token in name (20 points)
            $scores[] = "CASE WHEN LOWER(p.name) LIKE '%{$escaped}%' THEN 20 ELSE 0 END";
            // Contains in description (5 points)
            $scores[] = "CASE WHEN LOWER(p.description) LIKE '%{$escaped}%' THEN 5 ELSE 0 END";
        }

        return implode(' + ', $scores);
    }

    /**
     * Build search conditions (WHERE clause)
     * 
     * Tìm trong name HOẶC description, match BẤT KỲ token nào
     * 
     * @return array{sql: string, params: array}
     */
    private function buildSearchConditions(array $tokens): array
    {
        if (empty($tokens)) {
            return ['sql' => '1=1', 'params' => []];
        }

        $conditions = [];
        $params = [];

        foreach ($tokens as $token) {
            // Search in name OR description
            $conditions[] = "(LOWER(p.name) LIKE ? OR LOWER(p.description) LIKE ?)";
            $params[] = '%' . mb_strtolower($token) . '%';
            $params[] = '%' . mb_strtolower($token) . '%';
        }

        // Dùng OR để match bất kỳ token nào
        return [
            'sql' => implode(' OR ', $conditions),
            'params' => $params,
        ];
    }

    /**
     * Escape string for SQL LIKE
     */
    private function escapeString(string $str): string
    {
        return addslashes(mb_strtolower($str));
    }

    /**
     * Build smart sort clause (giống Shopee)
     */
    private function buildSmartSortClause(string $sort, bool $hasKeyword = false): string
    {
        return match ($sort) {
            'relevance' => $hasKeyword ? " ORDER BY relevance_score DESC, sold_count DESC" : " ORDER BY sold_count DESC",
            'popular', 'best_selling' => " ORDER BY sold_count DESC",
            'price_asc' => " ORDER BY p.price ASC",
            'price_desc' => " ORDER BY p.price DESC",
            'newest' => " ORDER BY p.created_at DESC",
            default => $hasKeyword ? " ORDER BY relevance_score DESC" : " ORDER BY p.id DESC",
        };
    }

    // =========================================================================
    // CRUD METHODS
    // =========================================================================

    /**
     * Tạo sản phẩm mới
     * 
     * @param array{
     *     name: string,
     *     price: float,
     *     description: string,
     *     user_id: int,
     *     category_id: int,
     *     image: string,
     *     quantity?: int
     * } $data
     * @return int Product ID
     */
    public function createProduct(array $data): int
    {
        $sql = "INSERT INTO {$this->table} 
                (name, price, description, user_id, category_id, image, quantity, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $id = $this->db->insert($sql, [
            $data['name'],
            $data['price'],
            $data['description'],
            $data['user_id'],
            $data['category_id'],
            $data['image'],
            $data['quantity'] ?? 1,
            self::STATUS_ACTIVE,
        ]);

        $this->clearCache();
        return $id;
    }

    /**
     * Cập nhật sản phẩm
     * 
     * @param int $id
     * @param array<string, mixed> $data
     * @return bool
     */
    public function updateProduct(int $id, array $data): bool
    {
        $allowedFields = ['name', 'price', 'quantity', 'description', 'category_id', 'status', 'image', 'condition'];

        $sets = [];
        $params = [];

        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $data)) {
                $sets[] = "{$field} = ?";
                $params[] = $data[$field];
            }
        }

        if (empty($sets)) {
            return false;
        }

        $params[] = $id;
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE id = ?";

        $result = $this->db->execute($sql, $params) !== false;

        if ($result) {
            $this->clearCache();
        }

        return $result;
    }

    /**
     * Ẩn sản phẩm (soft delete)
     * 
     * @param int $id
     * @return bool
     */
    public function hideProduct(int $id): bool
    {
        return $this->updateProduct($id, ['status' => self::STATUS_HIDDEN]);
    }

    /**
     * Xóa sản phẩm (hard delete)
     * 
     * @param int $id
     * @return bool
     */
    public function deleteProduct(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $result = $this->db->execute($sql, [$id]) !== false;

        if ($result) {
            $this->clearCache();
        }

        return $result;
    }

    // =========================================================================
    // INVENTORY MANAGEMENT
    // =========================================================================

    /**
     * Giảm số lượng tồn kho
     * 
     * @param int $id
     * @param int $amount
     * @return bool
     */
    public function decreaseQuantity(int $id, int $amount): bool
    {
        $sql = "UPDATE {$this->table} 
                SET quantity = quantity - ? 
                WHERE id = ? AND quantity >= ?";

        return $this->db->execute($sql, [$amount, $id, $amount]) > 0;
    }

    /**
     * Tăng số lượng tồn kho
     * 
     * @param int $id
     * @param int $amount
     * @return bool
     */
    public function increaseQuantity(int $id, int $amount): bool
    {
        $sql = "UPDATE {$this->table} SET quantity = quantity + ? WHERE id = ?";
        return $this->db->execute($sql, [$amount, $id]) !== false;
    }

    /**
     * Kiểm tra còn hàng không
     * 
     * @param int $id
     * @param int $quantity
     * @return bool
     */
    public function hasStock(int $id, int $quantity = 1): bool
    {
        $sql = "SELECT 1 FROM {$this->table} WHERE id = ? AND quantity >= ? AND status = ?";
        return $this->db->fetchOne($sql, [$id, $quantity, self::STATUS_ACTIVE]) !== null;
    }

    // =========================================================================
    // ADMIN METHODS
    // =========================================================================

    /**
     * Lấy tất cả products cho admin (có phân trang)
     * 
     * @param int $limit
     * @param int $offset
     * @return array<int, array<string, mixed>>
     */
    public function getAllForAdmin(int $limit = 20, int $offset = 0): array
    {
        $sql = "SELECT p.*, 
                    c.name AS category_name, 
                    u.full_name AS seller_name,
                    u.email AS seller_email
                FROM {$this->table} p
                LEFT JOIN categories c ON p.category_id = c.id
                LEFT JOIN users u ON p.user_id = u.id
                ORDER BY p.created_at DESC
                LIMIT ? OFFSET ?";

        return $this->db->fetchAll($sql, [$limit, $offset]);
    }

    /**
     * Kiểm tra sản phẩm có đơn hàng không
     * 
     * @param int $id
     * @return bool
     */
    public function hasAnyOrder(int $id): bool
    {
        $sql = "SELECT 1 FROM order_details WHERE product_id = ? LIMIT 1";
        return $this->db->fetchOne($sql, [$id]) !== null;
    }

    // =========================================================================
    // STATISTICS
    // =========================================================================

    /**
     * Đếm sản phẩm active
     * 
     * @return int
     */
    public function countActive(): int
    {
        $sql = "SELECT COUNT(*) AS total FROM {$this->table} WHERE status = ?";
        $result = $this->db->fetchOne($sql, [self::STATUS_ACTIVE]);
        return (int) ($result['total'] ?? 0);
    }

    /**
     * Đếm sản phẩm của user
     * 
     * @param int $userId
     * @return int
     */
    public function countByUserId(int $userId): int
    {
        $sql = "SELECT COUNT(*) AS total FROM {$this->table} WHERE user_id = ?";
        $result = $this->db->fetchOne($sql, [$userId]);
        return (int) ($result['total'] ?? 0);
    }

    /**
     * Đếm sản phẩm theo category
     * 
     * @param int $categoryId
     * @return int
     */
    public function countByCategory(int $categoryId): int
    {
        $sql = "SELECT COUNT(*) AS total FROM {$this->table} 
                WHERE category_id = ? AND status = ?";
        $result = $this->db->fetchOne($sql, [$categoryId, self::STATUS_ACTIVE]);
        return (int) ($result['total'] ?? 0);
    }

    // =========================================================================
    // PAGINATION HELPERS
    // =========================================================================

    /**
     * Lấy sản phẩm theo phân trang
     * 
     * @param int $limit
     * @param int $offset
     * @return array<int, array<string, mixed>>
     */
    public function getPaginated(int $limit = 20, int $offset = 0): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE status = ?
                ORDER BY id DESC 
                LIMIT ? OFFSET ?";

        return $this->db->fetchAll($sql, [self::STATUS_ACTIVE, $limit, $offset]);
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Subquery để tính sold_count
     */
    private function getSoldCountSubquery(): string
    {
        return "(SELECT COALESCE(SUM(od.quantity), 0) 
                 FROM order_details od 
                 JOIN orders o ON od.order_id = o.id 
                 WHERE od.product_id = p.id AND o.status != 'cancelled')";
    }

    /**
     * Build sort clause
     */
    private function buildSortClause(string $sort): string
    {
        return match ($sort) {
            'price_asc' => " ORDER BY p.price ASC",
            'price_desc' => " ORDER BY p.price DESC",
            'bestseller' => " ORDER BY sold_count DESC",
            'popular', 'newest' => " ORDER BY p.id DESC",
            default => " ORDER BY p.id DESC",
        };
    }

    /**
     * Clear product cache
     */
    private function clearCache(): void
    {
        $redis = RedisCache::getInstance();
        if ($redis->isAvailable()) {
            // Clear các cache keys phổ biến
            $redis->delete(self::CACHE_PREFIX . 'latest_12');
            $redis->delete(self::CACHE_PREFIX . 'latest_20');
        }
    }

    // =========================================================================
    // LEGACY COMPATIBILITY
    // =========================================================================

    /**
     * @deprecated Use createProduct() instead
     */
    public function create(array $data): int
    {
        return $this->createProduct($data);
    }

    /**
     * @deprecated Use updateProduct() instead
     */
    public function update(int $id, array $data): bool
    {
        return $this->updateProduct($id, $data);
    }

    /**
     * @deprecated Use deleteProduct() instead
     */
    public function delete(int $id): bool
    {
        return $this->deleteProduct($id);
    }

    /**
     * @deprecated Use countActive() instead
     */
    public function countAll(): int
    {
        return $this->countActive();
    }
}
