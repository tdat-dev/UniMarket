-- 2. Tạo Database mới (Hỗ trợ tiếng Việt)
CREATE DATABASE IF NOT EXISTS Zoldify CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE Zoldify;


-- 4. Bảng Người dùng
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20),
    address VARCHAR(255),
    role ENUM('buyer', 'seller', 'admin', 'moderator') DEFAULT 'buyer',
    email_verified TINYINT(1) DEFAULT 0,           -- 0 = chưa xác minh, 1 = đã xác minh
    email_verification_token VARCHAR(64),           -- Token ngẫu nhiên
    email_verification_expires_at DATETIME,         -- Thời gian hết hạn token
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 5. Bảng Danh mục
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    icon VARCHAR(50)
) ENGINE=InnoDB;

-- 6. Bảng Sản phẩm
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    quantity INT NOT NULL ,
    status ENUM('active', 'sold', 'hidden') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB;

-- 7. Bảng Đơn hàng
-- 5. Bảng Đơn hàng (Cập nhật thêm seller_id)
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL COMMENT 'Người mua',
    seller_id INT NOT NULL COMMENT 'Người bán - Thêm cái này vào cho dễ code',
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'shipping', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES users(id),
    FOREIGN KEY (seller_id) REFERENCES users(id) -- Nối thêm dây này
) ENGINE=InnoDB;

-- 8. Chi tiết Đơn hàng
CREATE TABLE IF NOT EXISTS order_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price_at_purchase DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB;

-- 9. Tin nhắn
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    content TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id),
    FOREIGN KEY (receiver_id) REFERENCES users(id)
) ENGINE=InnoDB;

-- 10. Đánh giá
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reviewer_id INT NOT NULL,
    product_id INT NOT NULL,
    rating INT, -- Đã bỏ CHECK constraint để tránh lỗi trên MySQL cũ
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reviewer_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB;

-- 11. Yêu thích
CREATE TABLE IF NOT EXISTS favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB;

-- 12. Tương tác (Gợi ý)
CREATE TABLE IF NOT EXISTS interactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    interaction_type ENUM('view', 'click') NOT NULL,
    score INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB;

-- 13. Thông báo
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    content VARCHAR(255) NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

-- 14. Báo cáo vi phạm
CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reporter_id INT NOT NULL,
    product_id INT NOT NULL,
    reason TEXT NOT NULL,
    status ENUM('pending', 'resolved') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reporter_id) REFERENCES users(id),
    FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB;

-- 15. Từ khóa tìm kiếm (Tracking search keywords)
CREATE TABLE IF NOT EXISTS search_keywords (
    id INT AUTO_INCREMENT PRIMARY KEY,
    keyword VARCHAR(255) NOT NULL UNIQUE,
    search_count INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ===================================
-- DATA MẪU (INSERT)
-- ===================================

-- 2. Người dùng (password: 123456 đã hash bằng bcrypt)
INSERT IGNORE INTO users (full_name, email, password, phone_number, address, role) VALUES
-- Admin & Moderator
('Admin Zoldify', 'admin@zoldify.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901234567', 'Hà Nội', 'admin'),
('Nguyễn Văn Kiểm', 'moderator@zoldify.vn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0902345678', 'TP HCM', 'moderator'),

-- Sellers (Người bán đồ cũ)
('Trần Thị Hoa', 'hoa.seller@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0912345678', 'Quận 1, TP HCM', 'seller'),
('Lê Văn Minh', 'minh.shop@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0923456789', 'Hải Châu, Đà Nẵng', 'seller'),
('Phạm Thị Mai', 'mai.vintage@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0934567890', 'Lê Chân, Hải Phòng', 'seller'),

-- Buyers (Người mua)
('Ngô Thị Lan', 'lan.buyer@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0956789012', 'Đống Đa, Hà Nội', 'buyer'),
('Đặng Văn Tùng', 'tung.customer@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0967890123', 'Tân Bình, TP HCM', 'buyer');

-- 3. Danh mục
INSERT IGNORE INTO categories (name, icon) VALUES
('Sách - Giáo trình', '📚'),
('Điện tử', '💻'),
('Thời trang', '👕'),
('Văn phòng phẩm', '✏️'),
('Đồ dùng cá nhân', '🎒'),
('Thể thao', '⚽'),
('Khác', '📦');

-- 4. Sản phẩm
INSERT IGNORE INTO products (user_id, category_id, name, description, price, quantity, image, status) VALUES
-- Sách
(2, 1, 'Giáo trình Lập trình C++', 'Sách mới 95%, không gạch chú. Phù hợp cho sinh viên năm 1-2 IT.', 85000, 1, 'book_cpp.jpg', 'active'),
(3, 1, 'Kinh tế vi mô - N. Gregory Mankiw', 'Bản tiếng Việt, đã dùng 1 kỳ, còn mới.', 120000, 1, 'book_eco.jpg', 'active'),
(4, 1, 'Oxford Advanced Learner Dictionary', 'Từ điển Anh-Việt bìa cứng, không rách.', 150000, 1, 'oxford_dict.jpg', 'active'),

-- Điện tử
(2, 2, 'Chuột Logitech G102', 'Dùng 6 tháng, còn nguyên hộp. Bảo hành 18 tháng.', 250000, 1, 'mouse_logitech.jpg', 'active'),
(5, 2, 'Tai nghe Sony WH-1000XM4', 'Chống ồn cực tốt, pin 8/10. Không hộp.', 4500000, 1, 'headphone_sony.jpg', 'active'),
(3, 2, 'USB SanDisk 32GB', 'Mới 100%, chưa bóc seal.', 80000, 1, 'usb_sandisk.jpg', 'sold'),

-- Thời trang
(4, 3, 'Áo hoodie Uniqlo màu đen', 'Size M, giặt 2 lần. Form rộng unisex.', 180000, 1, 'hoodie_uniqlo.jpg', 'active'),
(5, 3, 'Giày Converse Chuck Taylor', 'Size 40, màu trắng. Mua tháng trước nhưng không vừa.', 550000, 1, 'shoes_converse.jpg', 'active'),

-- Văn phòng phẩm
(2, 4, 'Combo 10 bút bi Thiên Long', 'Mực xanh, mới 100%.', 25000, 1, 'pen_combo.jpg', 'active'),
(3, 4, 'Máy tính Casio FX-580VN X', 'Dùng 1 năm, còn tốt. Có hướng dẫn sử dụng.', 350000, 1, 'calculator_casio.jpg', 'active'),

-- Đồ dùng cá nhân
(4, 5, 'Ba lô The North Face 20L', 'Màu xám, chống nước. Dùng 1 năm nhưng còn mới 90%.', 650000, 1, 'backpack_tnf.jpg', 'active'),
(5, 5, 'Bình giữ nhiệt Lock&Lock 500ml', 'Màu hồng pastel, chưa sử dụng.', 120000, 1, 'bottle_locknlock.jpg', 'active'),

-- Thể thao
(2, 6, 'Bóng đá Mikasa size 5', 'Dùng tập luyện 3 tháng, còn bơm tốt.', 180000, 1, 'ball_mikasa.jpg', 'active'),
(3, 6, 'Thảm tập Yoga Nike 6mm', 'Màu xanh dương, có túi đựng. Mua nhầm size.', 300000, 1, 'yoga_mat.jpg', 'active');

-- 5. Đơn hàng (Đã có giao dịch)
INSERT IGNORE INTO orders (buyer_id, seller_id, total_amount, status) VALUES
(3, 2, 85000, 'completed'),   -- Hùng mua sách C++ từ Lan
(4, 5, 4500000, 'shipping'),   -- Mai mua tai nghe từ Nam
(5, 3, 80000, 'completed');    -- Nam mua USB từ Hùng (đã sold)

-- 6. Chi tiết đơn hàng
INSERT IGNORE INTO order_details (order_id, product_id, quantity, price_at_purchase) VALUES
(1, 1, 1, 85000),    -- Sách C++
(2, 5, 1, 4500000),  -- Tai nghe Sony
(3, 6, 1, 80000);    -- USB SanDisk

-- 7. Tin nhắn
INSERT IGNORE INTO messages (sender_id, receiver_id, content, is_read) VALUES
(3, 2, 'Chào bạn, sách C++ còn không?', TRUE),
(2, 3, 'Còn bạn nhé! Bạn lấy khi nào?', TRUE),
(3, 2, 'Chiều nay mình qua nhận được không?', FALSE),
(4, 5, 'Tai nghe còn bảo hành không bạn?', TRUE),
(5, 4, 'Còn 18 tháng nha, hộp mất rồi.', FALSE);

-- 8. Đánh giá
INSERT IGNORE INTO reviews (reviewer_id, product_id, rating, comment) VALUES
(3, 1, 5, 'Sách đẹp, giao hàng nhanh. Recommend!'),
(5, 6, 4, 'USB chạy tốt, đóng gói cẩn thận.');

-- 9. Yêu thích
INSERT IGNORE INTO favorites (user_id, product_id) VALUES
(2, 5),  -- Lan thích tai nghe Sony
(3, 8),  -- Hùng thích giày Converse
(4, 11), -- Mai thích ba lô TNF
(5, 1);  -- Nam thích sách C++

-- 10. Tương tác (Cho hệ thống gợi ý)
INSERT IGNORE INTO interactions (user_id, product_id, interaction_type, score) VALUES
(2, 1, 'view', 3),
(2, 2, 'click', 5),
(3, 5, 'view', 2),
(3, 8, 'click', 7),
(4, 4, 'view', 1),
(4, 11, 'click', 10),
(5, 1, 'view', 4),
(5, 7, 'click', 6);

-- 11. Thông báo
INSERT IGNORE INTO notifications (user_id, content, is_read) VALUES
(2, 'Sản phẩm "Giáo trình C++" của bạn đã được mua!', TRUE),
(3, 'Bạn có tin nhắn mới từ Trần Thị Lan', FALSE),
(5, 'Đơn hàng #2 đang được giao', FALSE);

-- 12. Báo cáo vi phạm
INSERT IGNORE INTO reports (reporter_id, product_id, reason, status) VALUES
(4, 13, 'Sản phẩm không đúng mô tả, nghi ngờ hàng giả', 'pending');