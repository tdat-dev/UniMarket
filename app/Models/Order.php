<?php
namespace App\Models;

class Order extends BaseModel
{
    protected $table = 'orders';

    /**
     * Lấy tất cả đơn hàng cho admin (có thông tin buyer, seller)
     */
    public function getAllForAdmin($limit = 20, $offset = 0): array
    {
        $sql = "SELECT o.*, 
                       b.full_name as buyer_name, b.email as buyer_email,
                       s.full_name as seller_name
                FROM orders o
                LEFT JOIN users b ON o.buyer_id = b.id
                LEFT JOIN users s ON o.seller_id = s.id
                ORDER BY o.created_at DESC
                LIMIT :limit OFFSET :offset";
        return $this->db->fetchAll($sql, ['limit' => $limit, 'offset' => $offset]);
    }

    /**
     * Lấy 1 đơn hàng theo ID (có chi tiết)
     */
    public function find(int $id): ?array
    {
        $sql = "SELECT o.*, 
                       b.full_name as buyer_name, b.email as buyer_email, b.phone_number as buyer_phone,
                       s.full_name as seller_name, s.email as seller_email
                FROM orders o
                LEFT JOIN users b ON o.buyer_id = b.id
                LEFT JOIN users s ON o.seller_id = s.id
                WHERE o.id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]) ?: null;
    }

    /**
     * Lấy chi tiết sản phẩm trong đơn hàng
     */
    public function getOrderDetails(int $orderId): array
    {
        $sql = "SELECT od.*, p.name as product_name, p.image as product_image
                FROM order_details od
                LEFT JOIN products p ON od.product_id = p.id
                WHERE od.order_id = :order_id";
        return $this->db->fetchAll($sql, ['order_id' => $orderId]);
    }

    /**
     * Đếm tổng số đơn hàng
     */
    public function count(): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->db->fetchOne($sql);
        return $result['total'] ?? 0;
    }

    /**
     * Tính tổng doanh thu
     */
    public function getTotalRevenue(): float
    {
        $sql = "SELECT COALESCE(SUM(total_amount), 0) as total FROM {$this->table} WHERE status = 'completed'";
        $result = $this->db->fetchOne($sql);
        return (float) ($result['total'] ?? 0);
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateStatus(int $id, string $status): bool
    {
        $validStatuses = ['pending', 'shipping', 'completed', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            return false;
        }

        $sql = "UPDATE {$this->table} SET status = :status WHERE id = :id";
        return $this->db->execute($sql, ['id' => $id, 'status' => $status]);
    }

    /**
     * Đếm theo trạng thái
     */
    public function countByStatus(): array
    {
        $sql = "SELECT status, COUNT(*) as count FROM {$this->table} GROUP BY status";
        $results = $this->db->fetchAll($sql);

        $counts = [
            'pending' => 0,
            'shipping' => 0,
            'completed' => 0,
            'cancelled' => 0
        ];

        foreach ($results as $row) {
            $counts[$row['status']] = (int) $row['count'];
        }

        return $counts;
    }
}