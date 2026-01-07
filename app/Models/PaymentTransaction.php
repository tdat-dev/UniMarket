<?php

namespace App\Models;

/**
 * PaymentTransaction Model
 * 
 * Lưu lịch sử tất cả giao dịch thanh toán từ PayOS.
 */
class PaymentTransaction extends BaseModel
{
    protected $table = 'payment_transactions';

    /**
     * Tạo transaction mới
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO {$this->table} 
                (order_id, transaction_type, amount, payment_link_id, payos_transaction_id, 
                 payos_reference, payos_order_code, status, metadata)
                VALUES 
                (:order_id, :transaction_type, :amount, :payment_link_id, :payos_transaction_id,
                 :payos_reference, :payos_order_code, :status, :metadata)";

        return $this->db->insert($sql, [
            'order_id' => $data['order_id'],
            'transaction_type' => $data['transaction_type'],
            'amount' => $data['amount'],
            'payment_link_id' => $data['payment_link_id'] ?? null,
            'payos_transaction_id' => $data['payos_transaction_id'] ?? null,
            'payos_reference' => $data['payos_reference'] ?? null,
            'payos_order_code' => $data['payos_order_code'] ?? null,
            'status' => $data['status'] ?? 'pending',
            'metadata' => $data['metadata'] ?? null,
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
     * Lấy transactions theo order ID
     */
    public function getByOrderId(int $orderId): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE order_id = :order_id ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, ['order_id' => $orderId]);
    }

    /**
     * Tìm theo payment_link_id
     */
    public function findByPaymentLinkId(string $paymentLinkId): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE payment_link_id = :payment_link_id ORDER BY created_at DESC LIMIT 1";
        return $this->db->fetchOne($sql, ['payment_link_id' => $paymentLinkId]) ?: null;
    }

    /**
     * Tìm theo payos_order_code
     */
    public function findByPayosOrderCode(int $orderCode): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE payos_order_code = :order_code ORDER BY created_at DESC LIMIT 1";
        return $this->db->fetchOne($sql, ['order_code' => $orderCode]) ?: null;
    }

    /**
     * Cập nhật status
     */
    public function updateStatus(int $id, string $status, ?string $metadata = null): bool
    {
        $sql = "UPDATE {$this->table} SET status = :status";
        $params = ['status' => $status, 'id' => $id];

        if ($metadata !== null) {
            $sql .= ", metadata = :metadata";
            $params['metadata'] = $metadata;
        }

        $sql .= " WHERE id = :id";
        return $this->db->execute($sql, $params);
    }

    /**
     * Lấy tất cả transactions (cho admin)
     */
    public function getAll(int $limit = 50, int $offset = 0): array
    {
        $sql = "SELECT pt.*, o.total_amount as order_total
                FROM {$this->table} pt
                LEFT JOIN orders o ON pt.order_id = o.id
                ORDER BY pt.created_at DESC
                LIMIT :limit OFFSET :offset";
        return $this->db->fetchAll($sql, ['limit' => $limit, 'offset' => $offset]);
    }

    /**
     * Thống kê theo transaction_type
     */
    public function getStatsByType(): array
    {
        $sql = "SELECT transaction_type, 
                       COUNT(*) as count,
                       SUM(amount) as total_amount
                FROM {$this->table}
                WHERE status = 'success'
                GROUP BY transaction_type";
        return $this->db->fetchAll($sql);
    }
}
