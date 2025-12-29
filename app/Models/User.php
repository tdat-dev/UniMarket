<?php

namespace App\Models;

use App\Core\Database;

class User extends BaseModel
{
	// Đăng ký tài khoản mới
	public function register($data)
	{
		$sql = "INSERT INTO users (full_name, email, password, phone_number, address, major_id) VALUES (:full_name, :email, :password, :phone_number, :address, :major_id)";
		return $this->db->insert($sql, [
			'full_name' => $data['full_name'],
			'email' => $data['email'],
			'password' => password_hash($data['password'], PASSWORD_DEFAULT),
			'phone_number' => $data['phone_number'] ?? null,
			'address' => $data['address'] ?? null,
			'major_id' => $data['major_id'] ?? null
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
}
