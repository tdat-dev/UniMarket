<?php

namespace App\Models;

/**
 * Wallet Model
 * 
 * Quản lý ví tiền của seller.
 * Mỗi user có 1 ví duy nhất.
 */
class Wallet extends BaseModel
{
    protected $table = 'wallets';

    /**
     * Lấy hoặc tạo ví cho user (lazy creation)
     */
    public function getOrCreate(int $userId): array
    {
        $wallet = $this->findByUserId($userId);

        if (!$wallet) {
            $id = $this->create(['user_id' => $userId]);
            $wallet = $this->find($id);
        }

        return $wallet;
    }

    /**
     * Tạo ví mới
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO {$this->table} (user_id, balance, pending_balance)
                VALUES (:user_id, :balance, :pending_balance)";

        return $this->db->insert($sql, [
            'user_id' => $data['user_id'],
            'balance' => $data['balance'] ?? 0,
            'pending_balance' => $data['pending_balance'] ?? 0,
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
     * Tìm theo user ID
     */
    public function findByUserId(int $userId): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id";
        return $this->db->fetchOne($sql, ['user_id' => $userId]) ?: null;
    }

    /**
     * Cập nhật ví
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
     * Cập nhật pending_balance
     */
    public function updatePendingBalance(int $walletId, float $amount, string $action = 'add'): bool
    {
        if ($action === 'add') {
            $sql = "UPDATE {$this->table} SET pending_balance = pending_balance + :amount WHERE id = :id";
        } else {
            $sql = "UPDATE {$this->table} SET pending_balance = GREATEST(0, pending_balance - :amount) WHERE id = :id";
        }
        return $this->db->execute($sql, ['amount' => $amount, 'id' => $walletId]);
    }

    /**
     * Lấy tất cả ví có số dư > 0 (cho admin)
     */
    public function getAllWithBalance(int $limit = 50, int $offset = 0): array
    {
        $sql = "SELECT w.*, u.full_name, u.email
                FROM {$this->table} w
                JOIN users u ON w.user_id = u.id
                WHERE w.balance > 0 OR w.pending_balance > 0
                ORDER BY w.balance DESC
                LIMIT :limit OFFSET :offset";
        return $this->db->fetchAll($sql, ['limit' => $limit, 'offset' => $offset]);
    }

    /**
     * Tổng tiền đang trong hệ thống
     */
    public function getTotalBalance(): array
    {
        $sql = "SELECT 
                    SUM(balance) as total_balance,
                    SUM(pending_balance) as total_pending,
                    SUM(total_earned) as total_earned,
                    SUM(total_withdrawn) as total_withdrawn
                FROM {$this->table}";
        return $this->db->fetchOne($sql) ?: [
            'total_balance' => 0,
            'total_pending' => 0,
            'total_earned' => 0,
            'total_withdrawn' => 0,
        ];
    }
}
