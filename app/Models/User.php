<?php

namespace App\Models;

use App\Core\Database;

class User extends BaseModel
{
	protected $table = 'users';

	// Đăng ký tài khoản mới
	public function register($data)
	{
		$sql = "INSERT INTO users (full_name, email, password, phone_number, address, email_verified) VALUES (:full_name, :email, :password, :phone_number, :address, :email_verified)";
		return $this->db->insert($sql, [
			'full_name' => $data['full_name'],
			'email' => $data['email'],
			'password' => password_hash($data['password'], PASSWORD_DEFAULT),
			'phone_number' => $data['phone_number'] ?? null,
			'address' => $data['address'] ?? null,
			'email_verified' => $data['email_verified'] ?? 0, // Mặc định là 0 (chưa verify)
		]);
	}

	// Kiểm tra email đã tồn tại chưa
	public function checkEmailExists($email)
	{
		$sql = "SELECT id FROM users WHERE email = :email";
		$user = $this->db->fetchOne($sql, ['email' => $email]);
		return $user ? true : false;
	}

	// Đăng nhập: kiểm tra email và password
	public function login($email, $password)
	{
		$sql = "SELECT * FROM users WHERE email = :email";  // Đã lấy tất cả cột rồi, OK!
		$user = $this->db->fetchOne($sql, ['email' => $email]);
		if ($user && password_verify($password, $user['password'])) {
			return $user;
		}
		return false;
	}
	// Lấy thông tin user theo ID
	public function find($id)
	{
		$sql = "SELECT id, full_name, email, phone_number, address, role, created_at, balance, avatar FROM users WHERE id = :id";
		return $this->db->fetchOne($sql, ['id' => $id]);
	}

	// Lấy thông tin user theo email (cho Google OAuth)
	public function findByEmail($email)
	{
		$sql = "SELECT id, full_name, email, phone_number, address, role, created_at, balance, avatar FROM users WHERE email = :email";
		return $this->db->fetchOne($sql, ['email' => $email]);
	}

	// Lưu token xác minh
	public function saveVerificationToken(int $userId, string $token, string $expiresAt): bool
	{
		$sql = "UPDATE users SET 
            email_verification_token = :token, 
            email_verification_expires_at = :expires_at 
            WHERE id = :id";
		return $this->db->execute($sql, [
			'token' => $token,
			'expires_at' => $expiresAt,
			'id' => $userId
		]);
	}

	// Tìm user theo token (cho verify bằng link)
	public function findByVerificationToken(string $token): ?array
	{
		$sql = "SELECT * FROM users WHERE email_verification_token LIKE :token";
		return $this->db->fetchOne($sql, ['token' => $token . '%']) ?: null;
	}

	// Tìm user theo email để verify (lấy cả token)
	public function findByEmailForVerification(string $email): ?array
	{
		$sql = "SELECT id, full_name, email, email_verified, email_verification_token, email_verification_expires_at 
            FROM users WHERE email = :email";
		return $this->db->fetchOne($sql, ['email' => $email]) ?: null;
	}

	// Đánh dấu đã xác minh
	public function markAsVerified(int $userId): bool
	{
		$sql = "UPDATE users SET 
            email_verified = 1, 
            email_verification_token = NULL, 
            email_verification_expires_at = NULL 
            WHERE id = :id";
		return $this->db->execute($sql, ['id' => $userId]);
	}

	// Đếm tổng số users
	public function count(): int
	{
		$sql = "SELECT COUNT(*) as total FROM users";
		$result = $this->db->fetchOne($sql);
		return $result['total'] ?? 0;
	}

	/**
	 * Lấy tất cả users (thường dùng cho Admin)
	 */
	public function getAll()
	{
		// Lấy tất cả thông tin quan trọng (trừ password)
		// Sắp xếp người mới nhất lên đầu
		$sql = "SELECT id, full_name, email, phone_number, address, role, created_at, email_verified, is_locked, avatar 
                FROM users 
                ORDER BY created_at DESC";
		return $this->db->fetchAll($sql);
	}

	/**
	 * Cập nhật thông tin user (Admin)
	 */
	public function update($id, $data)
	{
		$sql = "UPDATE users SET 
                full_name = :full_name,
                email = :email,
                phone_number = :phone_number,
                role = :role,
                email_verified = :email_verified
                WHERE id = :id";

		return $this->db->execute($sql, [
			'id' => $id,
			'full_name' => $data['full_name'],
			'email' => $data['email'],
			'phone_number' => $data['phone_number'],
			'role' => $data['role'],
			'email_verified' => $data['email_verified']
		]);
	}

	/**
	 * Khóa/Mở khóa tài khoản
	 */
	public function toggleLock($id)
	{
		// Kiểm tra trạng thái hiện tại
		$user = $this->find($id);
		if (!$user)
			return false;

		// Đảo ngược trạng thái: nếu đang locked (1) -> mở (0), và ngược lại
		// Lưu ý: database có thể đang lưu is_locked là NULL hoặc 0
		$currentStatus = !empty($user['is_locked']) ? 1 : 0;
		$newStatus = $currentStatus == 1 ? 0 : 1;

		$sql = "UPDATE users SET is_locked = :new_status WHERE id = :id";
		return $this->db->execute($sql, ['new_status' => $newStatus, 'id' => $id]);
	}

	/**
	 * Đổi trạng thái xác minh email
	 */
	public function toggleVerified($id)
	{
		$sql = "UPDATE users SET email_verified = NOT email_verified WHERE id = :id";
		return $this->db->execute($sql, ['id' => $id]);
	}
}
