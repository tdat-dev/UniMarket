-- Bước 1: Kiểm tra tên foreign key constraint
-- Chạy lệnh này trước để xem tên constraint:
-- SHOW CREATE TABLE users;

-- Bước 2: Xóa foreign key (thay 'users_ibfk_1' bằng tên thật nếu khác)
ALTER TABLE users DROP FOREIGN KEY users_ibfk_1;

-- Bước 3: Xóa cột major_id
ALTER TABLE users DROP COLUMN major_id;