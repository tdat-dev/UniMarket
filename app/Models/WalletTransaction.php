<?php

namespace App\Models;

/**
 * WalletTransaction Model
 * 
 * Lưu lịch sử giao dịch ví.
 * Mỗi thay đổi số dư = 1 record.
 */
class WalletTransaction extends BaseModel
{
    protected $table = 'wallet_transactions';

    /**
     * Tạo transaction mới
     */
    public function create(array $data): int
    {
        $sql = "INSERT INTO {$this->table} 
                (wallet_id, order_id, transaction_type, amount, balance_before, 
                 balance_after, description, reference_id, status)
                VALUES 
                (:wallet_id, :order_id, :transaction_type, :amount, :balance_before,
                 :balance_after, :description, :reference_id, :status)";

        return $this->db->insert($sql, [
            'wallet_id' => $data['wallet_id'],
            'order_id' => $data['order_id'] ?? null,
            'transaction_type' => $data['transaction_type'],
            'amount' => $data['amount'],
            'balance_before' => $data['balance_before'],
            'balance_after' => $data['balance_after'],
            'description' => $data['description'] ?? null,
            'reference_id' => $data['reference_id'] ?? null,
            'status' => $data['status'] ?? 'completed',
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
     * Lấy transactions theo wallet ID
     */
    public function getByWalletId(int $walletId, int $limit = 20, int $offset = 0): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE wallet_id = :wallet_id 
                ORDER BY created_at DESC
                LIMIT :limit OFFSET :offset";
        return $this->db->fetchAll($sql, [
            'wallet_id' => $walletId,
            'limit' => $limit,
            'offset' => $offset,
        ]);
    }

    /**
     * Lấy pending withdrawals (chờ admin approve)
     */
    public function getPendingWithdrawals(): array
    {
        $sql = "SELECT wt.*, w.user_id, w.bank_name, w.bank_account_number, w.bank_account_name,
                       u.full_name, u.email
                FROM {$this->table} wt
                JOIN wallets w ON wt.wallet_id = w.id
                JOIN users u ON w.user_id = u.id
                WHERE wt.transaction_type = 'withdrawal' AND wt.status = 'pending'
                ORDER BY wt.created_at ASC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Cập nhật status (cho withdrawal approval)
     */
    public function updateStatus(int $id, string $status, ?string $referenceId = null): bool
    {
        $sql = "UPDATE {$this->table} SET status = :status";
        $params = ['status' => $status, 'id' => $id];

        if ($referenceId !== null) {
            $sql .= ", reference_id = :reference_id";
            $params['reference_id'] = $referenceId;
        }

        $sql .= " WHERE id = :id";
        return $this->db->execute($sql, $params);
    }

    /**
     * Hoàn lại tiền khi withdrawal bị reject
     * 
     * @param int $transactionId ID của withdrawal transaction
     * @return bool
     */
    public function revertWithdrawal(int $transactionId): bool
    {
        $trans = $this->find($transactionId);
        if (!$trans || $trans['transaction_type'] !== 'withdrawal') {
            return false;
        }

        // Cập nhật status thành failed
        $this->updateStatus($transactionId, 'failed');

        // Cộng lại tiền vào ví
        $walletModel = new Wallet();
        $wallet = $walletModel->find($trans['wallet_id']);
        if ($wallet) {
            $walletModel->update($wallet['id'], [
                'balance' => $wallet['balance'] + $trans['amount'],
            ]);
        }

        return true;
    }

    /**
     * Thống kê theo transaction_type
     */
    public function getStatsByType(int $walletId): array
    {
        $sql = "SELECT transaction_type, 
                       COUNT(*) as count,
                       SUM(amount) as total_amount
                FROM {$this->table}
                WHERE wallet_id = :wallet_id AND status = 'completed'
                GROUP BY transaction_type";
        return $this->db->fetchAll($sql, ['wallet_id' => $walletId]);
    }
}
