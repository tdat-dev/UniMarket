<?php

namespace App\Models;

/**
 * EscrowHold Model
 * 
 * Quản lý tiền đang được giữ (escrow).
 * 1 order = 1 escrow hold.
 */
class EscrowHold extends BaseModel
{
    protected $table = 'escrow_holds';

    /**
     * Tạo escrow hold mới
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO {$this->table} 
                (order_id, seller_id, amount, platform_fee, seller_amount, status)
                VALUES 
                (:order_id, :seller_id, :amount, :platform_fee, :seller_amount, :status)";

        return $this->db->insert($sql, [
            'order_id' => $data['order_id'],
            'seller_id' => $data['seller_id'],
            'amount' => $data['amount'],
            'platform_fee' => $data['platform_fee'] ?? 0,
            'seller_amount' => $data['seller_amount'],
            'status' => $data['status'] ?? 'holding',
        ]);
    }

    /**
     * Tìm theo ID
     */
    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]) ?: null;
    }

    /**
     * Tìm theo order ID
     */
    public function findByOrderId(int $orderId): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE order_id = :order_id";
        return $this->db->fetchOne($sql, ['order_id' => $orderId]) ?: null;
    }

    /**
     * Cập nhật escrow
     */
    public function update(int $id, array $data): bool
    {
        $setClauses = [];
        $params = ['id' => $id];

        foreach ($data as $key => $value) {
            $setClauses[] = "{$key} = :{$key}";
            $params[$key] = $value;
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $setClauses) . " WHERE id = :id";
        return $this->db->execute($sql, $params);
    }

    /**
     * Lấy danh sách escrow đã đến hạn giải ngân
     * 
     * Điều kiện:
     * - status = 'holding'
     * - release_scheduled_at <= NOW()
     */
    public function getReleasable(): array
    {
        $sql = "SELECT eh.*, o.total_amount as order_total, u.full_name as seller_name
                FROM {$this->table} eh
                JOIN orders o ON eh.order_id = o.id
                JOIN users u ON eh.seller_id = u.id
                WHERE eh.status = 'holding' 
                AND eh.release_scheduled_at IS NOT NULL
                AND eh.release_scheduled_at <= NOW()
                ORDER BY eh.release_scheduled_at ASC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Lấy escrow theo seller ID
     */
    public function getBySellerId(int $sellerId, int $limit = 20, int $offset = 0): array
    {
        $sql = "SELECT eh.*, o.total_amount as order_total
                FROM {$this->table} eh
                JOIN orders o ON eh.order_id = o.id
                WHERE eh.seller_id = :seller_id
                ORDER BY eh.held_at DESC
                LIMIT :limit OFFSET :offset";
        return $this->db->fetchAll($sql, [
            'seller_id' => $sellerId,
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    /**
     * Lấy tất cả escrow (cho admin)
     */
    public function getAll(int $limit = 50, int $offset = 0, ?string $status = null): array
    {
        $sql = "SELECT eh.*, o.total_amount as order_total, 
                       u.full_name as seller_name, u.email as seller_email
                FROM {$this->table} eh
                JOIN orders o ON eh.order_id = o.id
                JOIN users u ON eh.seller_id = u.id";

        $params = ['limit' => $limit, 'offset' => $offset];

        if ($status) {
            $sql .= " WHERE eh.status = :status";
            $params['status'] = $status;
        }

        $sql .= " ORDER BY eh.held_at DESC LIMIT :limit OFFSET :offset";
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Thống kê escrow
     */
    public function getStats(): array
    {
        $sql = "SELECT 
                    status,
                    COUNT(*) as count,
                    SUM(amount) as total_amount,
                    SUM(seller_amount) as total_seller_amount,
                    SUM(platform_fee) as total_platform_fee
                FROM {$this->table}
                GROUP BY status";
        return $this->db->fetchAll($sql);
    }

    /**
     * Tổng tiền đang holding
     */
    public function getTotalHolding(): float
    {
        $sql = "SELECT COALESCE(SUM(amount), 0) as total FROM {$this->table} WHERE status = 'holding'";
        $result = $this->db->fetchOne($sql);
        return (float) ($result['total'] ?? 0);
    }

    /**
     * Lấy escrow đang tranh chấp
     */
    public function getDisputed(): array
    {
        $sql = "SELECT eh.*, o.total_amount as order_total,
                       u.full_name as seller_name,
                       b.full_name as buyer_name
                FROM {$this->table} eh
                JOIN orders o ON eh.order_id = o.id
                JOIN users u ON eh.seller_id = u.id
                JOIN users b ON o.buyer_id = b.id
                WHERE eh.status = 'disputed'
                ORDER BY eh.held_at ASC";
        return $this->db->fetchAll($sql);
    }
}
