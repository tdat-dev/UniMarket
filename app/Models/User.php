<?php

namespace App\Models;

use App\Core\Database;

class User extends BaseModel
{
	// Đăng ký tài khoản mới
	public function register($data)
	{
		$sql = "INSERT INTO users (full_name, email, password, phone_number, address) VALUES (:full_name, :email, :password, :phone_number, :address)";
		return $this->db->insert($sql, [
			'full_name' => $data['full_name'],
			'email' => $data['email'],
			'password' => password_hash($data['password'], PASSWORD_DEFAULT),
			'phone_number' => $data['phone_number'] ?? null,
			'address' => $data['address'] ?? null,
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
		$sql = "SELECT * FROM users WHERE email = :email";
		$user = $this->db->fetchOne($sql, ['email' => $email]);
		if ($user && password_verify($password, $user['password'])) {
			return $user;
		}
		return false;
	}
	// Lấy thông tin user theo ID
	public function find($id)
	{
		$sql = "SELECT id, full_name, email, phone_number, address, created_at FROM users WHERE id = :id";
		return $this->db->fetchOne($sql, ['id' => $id]);
	}
	public function update($id, $data)
	{
		$fields = [];
		$params = ['id' => $id];

		foreach ($data as $key => $value) {
			$fields[] = "$key = :$key";
			$params[$key] = $value;
		}

		if (empty($fields)) {
			return true;
		}

		$sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
		return $this->db->execute($sql, $params);
	}
}
