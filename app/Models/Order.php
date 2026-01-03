<?php

namespace App\Models;

class Order extends BaseModel
{
    protected $table = 'orders';

    /**
     * Tạo đơn hàng mới
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (buyer_id, seller_id, total_amount, status) 
                VALUES (:buyer_id, :seller_id, :total_amount, :status)";

        return $this->db->insert($sql, [
            'buyer_id' => $data['buyer_id'],
            'seller_id' => $data['seller_id'],
            'total_amount' => $data['total_amount'],
            'status' => $data['status']
        ]);
    }

    /**
     * Lấy 1 đơn hàng theo ID (có chi tiết buyer, seller)
     */
    public function find($id)
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
     * Lấy đơn hàng theo buyer ID
     */
    public function getByBuyerId($buyerId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE buyer_id = :buyer_id ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, ['buyer_id' => $buyerId]);
    }

    /**
     * Lấy đơn hàng theo seller ID
     */
    public function getBySellerId($sellerId)
    {
        $sql = "SELECT o.*, u.full_name as buyer_name, u.address as buyer_address 
                FROM {$this->table} o
                JOIN users u ON o.buyer_id = u.id
                WHERE o.seller_id = :seller_id 
                ORDER BY o.created_at DESC";
        return $this->db->fetchAll($sql, ['seller_id' => $sellerId]);
    }

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
     * Cập nhật trạng thái đơn hàng
     */
    public function updateStatus($orderId, $status, $reason = null)
    {
        $validStatuses = ['pending', 'shipping', 'completed', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            return false;
        }

        if ($reason) {
            $sql = "UPDATE {$this->table} SET status = :status, cancel_reason = :reason WHERE id = :id";
            return $this->db->execute($sql, ['status' => $status, 'reason' => $reason, 'id' => $orderId]);
        } else {
            $sql = "UPDATE {$this->table} SET status = :status WHERE id = :id";
            return $this->db->execute($sql, ['status' => $status, 'id' => $orderId]);
        }
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