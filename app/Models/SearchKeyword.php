<?php

declare(strict_types=1);

namespace App\Models;

/**
 * SearchKeyword Model
 * 
 * Theo dõi và thống kê từ khóa tìm kiếm phổ biến.
 * Dùng để gợi ý sản phẩm trending.
 * 
 * @package App\Models
 */
class SearchKeyword extends BaseModel
{
    /** @var string */
    protected $table = 'search_keywords';

    /** @var array<string> */
    protected array $fillable = [
        'keyword',
        'search_count',
    ];

    // =========================================================================
    // TRACKING
    // =========================================================================

    /**
     * Ghi nhận từ khóa tìm kiếm (tăng count hoặc tạo mới)
     * 
     * Sử dụng UPSERT pattern để tối ưu performance.
     * 
     * @param string $keyword
     * @return void
     */
    public function trackKeyword(string $keyword): void
    {
        $keyword = $this->normalizeKeyword($keyword);

        if (empty($keyword)) {
            return;
        }

        // UPSERT: Insert hoặc Update nếu đã tồn tại
        $sql = "INSERT INTO {$this->table} (keyword, search_count) 
                VALUES (?, 1)
                ON DUPLICATE KEY UPDATE search_count = search_count + 1";

        $this->db->execute($sql, [$keyword]);
    }

    // =========================================================================
    // QUERY METHODS
    // =========================================================================

    /**
     * Lấy top từ khóa phổ biến
     * 
     * @param int $limit
     * @return array<int, array<string, mixed>>
     */
    public function getTopKeywords(int $limit = 10): array
    {
        $sql = "SELECT keyword, search_count 
                FROM {$this->table} 
                ORDER BY search_count DESC 
                LIMIT ?";

        return $this->db->fetchAll($sql, [$limit]);
    }

    /**
     * Tìm keywords tương tự (cho autocomplete)
     * 
     * @param string $prefix
     * @param int $limit
     * @return array<int, string>
     */
    public function getSuggestions(string $prefix, int $limit = 5): array
    {
        $prefix = $this->normalizeKeyword($prefix);

        if (empty($prefix)) {
            return [];
        }

        $sql = "SELECT keyword 
                FROM {$this->table} 
                WHERE keyword LIKE ? 
                ORDER BY search_count DESC 
                LIMIT ?";

        $results = $this->db->fetchAll($sql, [$prefix . '%', $limit]);

        return array_column($results, 'keyword');
    }

    /**
     * Lấy trending keywords (tăng mạnh gần đây)
     * 
     * @param int $days Số ngày gần đây
     * @param int $limit
     * @return array<int, array<string, mixed>>
     */
    public function getTrending(int $days = 7, int $limit = 5): array
    {
        $sql = "SELECT keyword, search_count 
                FROM {$this->table} 
                WHERE updated_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
                ORDER BY search_count DESC 
                LIMIT ?";

        return $this->db->fetchAll($sql, [$days, $limit]);
    }

    // =========================================================================
    // MAINTENANCE
    // =========================================================================

    /**
     * Xóa keywords ít phổ biến (cleanup job)
     * 
     * @param int $minCount Xóa keywords có count < minCount
     * @return int Số records đã xóa
     */
    public function cleanup(int $minCount = 2): int
    {
        $sql = "DELETE FROM {$this->table} WHERE search_count < ?";
        return $this->db->execute($sql, [$minCount]);
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Chuẩn hóa keyword
     * 
     * @param string $keyword
     * @return string
     */
    private function normalizeKeyword(string $keyword): string
    {
        return trim(mb_strtolower($keyword));
    }
}