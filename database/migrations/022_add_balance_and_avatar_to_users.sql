-- Thêm cột balance và avatar cho bảng users
ALTER TABLE users 
ADD COLUMN balance DECIMAL(15, 2) DEFAULT 0.00 AFTER role,
ADD COLUMN avatar VARCHAR(255) DEFAULT NULL AFTER balance;
