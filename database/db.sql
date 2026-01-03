-- ===========================================
-- ZOLDIFY DATABASE SCHEMA
-- Version: 1.0
-- Ngày tạo: 2026-01-03
-- ===========================================

SET FOREIGN_KEY_CHECKS = 0;

-- ==========================================
-- 1. BẢNG NGƯỜI DÙNG (USERS)
-- ==========================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20),
    address VARCHAR(255),
    role ENUM('buyer', 'seller', 'admin', 'moderator') DEFAULT 'buyer',
    balance DECIMAL(15, 2) DEFAULT 0.00,
    avatar VARCHAR(255) DEFAULT NULL,
    email_verified TINYINT(1) DEFAULT 0,
    email_verification_token VARCHAR(64) NULL,
    email_verification_expires_at DATETIME NULL,
    is_locked TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- 2. BẢNG DANH MỤC (CATEGORIES)
-- ==========================================
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    icon VARCHAR(255),
    image VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- 3. BẢNG SẢN PHẨM (PRODUCTS)
-- ==========================================
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    quantity INT NOT NULL DEFAULT 1,
    status ENUM('active', 'sold', 'hidden') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- 4. BẢNG ĐƠN HÀNG (ORDERS)
-- ==========================================
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT NOT NULL,
    seller_id INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending', 'shipping', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- 5. CHI TIẾT ĐƠN HÀNG (ORDER_DETAILS)
-- ==========================================
CREATE TABLE IF NOT EXISTS order_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    price_at_purchase DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- 6. BẢNG TIN NHẮN (MESSAGES)
-- ==========================================
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    content TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- 7. BẢNG ĐÁNH GIÁ (REVIEWS)
-- ==========================================
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reviewer_id INT NOT NULL,
    product_id INT NOT NULL,
    rating INT,
    comment TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- 8. BẢNG YÊU THÍCH (FAVORITES)
-- ==========================================
CREATE TABLE IF NOT EXISTS favorites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- 9. BẢNG TƯƠNG TÁC (INTERACTIONS)
-- ==========================================
CREATE TABLE IF NOT EXISTS interactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    interaction_type ENUM('view', 'click') NOT NULL,
    score INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- 10. BẢNG THÔNG BÁO (NOTIFICATIONS)
-- ==========================================
CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    content VARCHAR(255) NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- 11. BẢNG BÁO CÁO VI PHẠM (REPORTS)
-- ==========================================
CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reporter_id INT NOT NULL,
    product_id INT NOT NULL,
    reason TEXT NOT NULL,
    status ENUM('pending', 'resolved') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reporter_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- 12. BẢNG TỪ KHÓA TÌM KIẾM (SEARCH_KEYWORDS)
-- ==========================================
CREATE TABLE IF NOT EXISTS search_keywords (
    id INT AUTO_INCREMENT PRIMARY KEY,
    keyword VARCHAR(255) NOT NULL UNIQUE,
    search_count INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- 13. BẢNG GIỎ HÀNG (CARTS)
-- ==========================================
CREATE TABLE IF NOT EXISTS carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_product (user_id, product_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ==========================================
-- 14. BẢNG CÀI ĐẶT (SETTINGS)
-- ==========================================
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    setting_group VARCHAR(50) DEFAULT 'general',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET FOREIGN_KEY_CHECKS = 1;

-- ==========================================
-- KẾT THÚC DATABASE SCHEMA
-- ==========================================

-- ==========================================
-- DỮ LIỆU MẪU (SEED DATA)
-- ==========================================

-- Xóa dữ liệu cũ trước khi insert (tránh lỗi duplicate)
-- Dùng DELETE thay vì TRUNCATE để tránh lỗi FK trên MariaDB
SET FOREIGN_KEY_CHECKS = 0;
DELETE FROM carts;
DELETE FROM order_details;
DELETE FROM orders;
DELETE FROM messages;
DELETE FROM reviews;
DELETE FROM favorites;
DELETE FROM interactions;
DELETE FROM notifications;
DELETE FROM reports;
DELETE FROM products;
DELETE FROM categories;
DELETE FROM search_keywords;
DELETE FROM settings;
DELETE FROM users;
-- Reset AUTO_INCREMENT
ALTER TABLE users AUTO_INCREMENT = 1;
ALTER TABLE categories AUTO_INCREMENT = 1;
ALTER TABLE products AUTO_INCREMENT = 1;
ALTER TABLE orders AUTO_INCREMENT = 1;
ALTER TABLE order_details AUTO_INCREMENT = 1;
ALTER TABLE messages AUTO_INCREMENT = 1;
ALTER TABLE reviews AUTO_INCREMENT = 1;
ALTER TABLE favorites AUTO_INCREMENT = 1;
ALTER TABLE interactions AUTO_INCREMENT = 1;
ALTER TABLE notifications AUTO_INCREMENT = 1;
ALTER TABLE reports AUTO_INCREMENT = 1;
ALTER TABLE carts AUTO_INCREMENT = 1;
ALTER TABLE search_keywords AUTO_INCREMENT = 1;
ALTER TABLE settings AUTO_INCREMENT = 1;
SET FOREIGN_KEY_CHECKS = 1;

-- ==========================================
-- 15. SEED DANH MỤC (CATEGORIES)
-- ==========================================
INSERT INTO categories (name, icon) VALUES
('Thời Trang Nam', '/images/categories/item.png'),
('Điện Thoại', '/images/categories/dienthoai.png'),
('Điện Tử', '/images/categories/manhinh.png'),
('Laptop', '/images/categories/laptop.png'),
('Máy Ảnh', '/images/categories/camera.png'),
('Đồng Hồ', '/images/categories/dongho.png'),
('Giày Dép', '/images/categories/giay.png'),
('Gia Dụng', '/images/categories/noicanh.png'),
('Thể Thao', '/images/categories/bongda.png'),
('Xe Cộ', '/images/categories/xemay.png'),
('Thời Trang Nữ', '/images/categories/aonu.png'),
('Mẹ & Bé', '/images/categories/banghetreem.png'),
('Nhà Cửa', '/images/categories/noicanh.png'),
('Sắc Đẹp', '/images/categories/sonphan.png'),
('Sức Khỏe', '/images/categories/thuockhautrang.png'),
('Giày Nữ', '/images/categories/guoc.png'),
('Túi Ví', '/images/categories/tuida.png'),
('Phụ Kiện', '/images/categories/thatlung.png'),
('Sách', '/images/categories/sach.png'),
('Khác', '/images/categories/item.png');

-- ==========================================
-- 16. SEED USERS (Password: 123456)
-- ==========================================
INSERT INTO users (full_name, email, password, phone_number, address, role, email_verified) VALUES
-- Admin
('Admin Zoldify', 'admin@zoldify.vn', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0901234567', 'Hà Nội', 'admin', 1),
-- Moderator
('Nguyễn Văn Kiểm', 'moderator@zoldify.vn', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0902345678', 'TP HCM', 'moderator', 1),
-- Sellers
('Trần Thị Hoa', 'hoa.seller@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0912345678', 'Quận 1, TP HCM', 'seller', 1),
('Lê Văn Minh', 'minh.shop@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0923456789', 'Hải Châu, Đà Nẵng', 'seller', 1),
('Phạm Thị Mai', 'mai.vintage@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0934567890', 'Lê Chân, Hải Phòng', 'seller', 1),
('Hoàng Văn Nam', 'nam.secondhand@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0945678901', 'Ninh Kiều, Cần Thơ', 'seller', 1),
-- Buyers
('Ngô Thị Lan', 'lan.buyer@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0956789012', 'Đống Đa, Hà Nội', 'buyer', 1),
('Đặng Văn Tùng', 'tung.customer@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0967890123', 'Tân Bình, TP HCM', 'buyer', 1),
('Vũ Thị Hương', 'huong.buyer@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0978901234', 'Thanh Khê, Đà Nẵng', 'buyer', 1);

-- ==========================================
-- 17. SEED SETTINGS
-- ==========================================
INSERT INTO settings (setting_key, setting_value, setting_group) VALUES
('site_name', 'Zoldify', 'general'),
('site_description', 'Mua bán trực tuyến dễ dàng', 'general'),
('site_email', 'contact@zoldify.vn', 'contact'),
('site_phone', '1900-xxxx', 'contact'),
('maintenance_mode', '0', 'general');

-- ==========================================
-- 18. SEED PRODUCTS (Sản phẩm mẫu)
-- ==========================================
INSERT INTO products (user_id, category_id, name, description, price, image, quantity, status) VALUES
-- Seller 1: Trần Thị Hoa (id=3) - Thời trang
(3, 1, 'Áo Thun Nam Basic Cotton', 'Áo thun cotton 100%, form regular fit, thoáng mát, nhiều màu sắc', 150000, '/images/products/1.png', 15, 'active'),
(3, 1, 'Quần Jean Nam Slim Fit', 'Quần jean co giãn nhẹ, màu xanh đậm, size 28-34', 350000, '/images/products/2.png', 10, 'active'),
(3, 7, 'Giày Sneaker Trắng Classic', 'Giày thể thao unisex, đế cao su chống trượt, form ôm chân', 450000, '/images/products/3.png', 8, 'active'),
(3, 11, 'Áo Sơ Mi Nữ Công Sở', 'Áo sơ mi trắng form rộng, chất liệu lụa mềm mại', 280000, '/images/products/1.png', 12, 'active'),
(3, 18, 'Kính Mát Thời Trang', 'Kính râm chống UV, gọng kim loại cao cấp', 320000, '/images/products/2.png', 20, 'active'),

-- Seller 2: Lê Văn Minh (id=4) - Công nghệ
(4, 2, 'iPhone 12 Pro 128GB', 'Máy cũ 95%, pin 89%, fullbox, bảo hành 3 tháng', 12500000, '/images/products/4.png', 2, 'active'),
(4, 4, 'Laptop Dell Inspiron 15', 'Core i5 Gen 11, RAM 8GB, SSD 256GB, màn 15.6 FHD', 8900000, '/images/products/5.png', 1, 'active'),
(4, 3, 'Tai Nghe Sony WH-1000XM4', 'Chống ồn chủ động, pin 30h, fullbox, còn bảo hành', 4200000, '/images/products/6.png', 3, 'active'),
(4, 2, 'Samsung Galaxy S21 Ultra', 'Máy mới 99%, pin 95%, fullbox, camera 108MP', 15000000, '/images/products/4.png', 1, 'active'),
(4, 4, 'MacBook Air M1 2020', 'RAM 8GB, SSD 256GB, màn Retina, pin trâu', 18500000, '/images/products/5.png', 1, 'active'),
(4, 3, 'Loa Bluetooth JBL Flip 5', 'Chống nước IPX7, pin 12h, âm bass mạnh', 1800000, '/images/products/6.png', 5, 'active'),

-- Seller 3: Phạm Thị Mai (id=5) - Thời trang nữ & Làm đẹp
(5, 11, 'Váy Hoa Vintage', 'Váy xòe họa tiết hoa nhí, chất vải mềm mịn, freesize', 280000, '/images/products/7.png', 6, 'active'),
(5, 17, 'Túi Xách Nữ Da PU', 'Túi đeo chéo thời trang, nhiều ngăn tiện dụng, màu đen', 320000, '/images/products/8.png', 8, 'active'),
(5, 14, 'Son MAC Ruby Woo', 'Son lì màu đỏ ruby classic, chính hãng còn seal', 450000, '/images/products/9.png', 15, 'active'),
(5, 11, 'Đầm Maxi Đi Biển', 'Đầm dài hoa nhí thoáng mát, phù hợp đi biển, du lịch', 350000, '/images/products/7.png', 5, 'active'),
(5, 14, 'Phấn Nước Cushion Laneige', 'Phấn nước kiềm dầu, che phủ tốt, tone 21', 680000, '/images/products/9.png', 10, 'active'),
(5, 16, 'Giày Cao Gót Mũi Nhọn', 'Giày cao 7cm, màu nude, size 35-39', 420000, '/images/products/8.png', 4, 'active'),

-- Seller 4: Hoàng Văn Nam (id=6) - Sách & Đồ dùng học tập
(6, 19, 'Giáo Trình Lập Trình C++', 'Sách mới 90%, có ghi chú bài tập, bìa còn đẹp', 85000, '/images/products/10.png', 5, 'active'),
(6, 19, 'Sách IELTS Cambridge 15-18', 'Trọn bộ 4 cuốn kèm audio, đáp án chi tiết', 280000, '/images/products/11.png', 3, 'active'),
(6, 3, 'Bàn Phím Cơ Gaming RGB', 'Switch Blue, LED RGB 16.8 triệu màu, kết nối USB', 650000, '/images/products/12.png', 6, 'active'),
(6, 9, 'Vợt Cầu Lông Yonex Astrox', 'Vợt chính hãng, kèm túi đựng và grip mới', 850000, '/images/products/13.png', 3, 'active'),
(6, 6, 'Đồng Hồ Casio F-91W', 'Đồng hồ classic, chống nước 30m, pin 7 năm', 350000, '/images/products/14.png', 8, 'active'),
(6, 19, 'Sách Đắc Nhân Tâm', 'Sách mới 100%, bản dịch mới nhất, bìa cứng', 120000, '/images/products/10.png', 10, 'active'),
(6, 9, 'Giày Chạy Bộ Nike', 'Giày thể thao êm chân, đế cao su bền, size 40-44', 890000, '/images/products/13.png', 4, 'active'),
(6, 5, 'Máy Ảnh Canon EOS M50', 'Body mới 95%, kèm lens kit 15-45mm, túi đựng', 9500000, '/images/products/14.png', 1, 'active');

-- ==========================================
-- 19. SEED SEARCH KEYWORDS (Từ khóa tìm kiếm)
-- ==========================================
INSERT INTO search_keywords (keyword, search_count) VALUES
('iphone', 350),
('laptop', 280),
('macbook', 220),
('samsung', 200),
('áo khoác', 180),
('giày sneaker', 165),
('tai nghe bluetooth', 150),
('sục crocs', 140),
('váy đầm', 130),
('túi xách nữ', 120),
('bàn phím cơ', 115),
('son môi', 110),
('đồng hồ casio', 105),
('quần jean nam', 100),
('sách tiếng anh', 95),
('giáo trình c++', 90),
('vợt cầu lông', 85),
('áo thun nam', 80),
('giày cao gót', 75),
('phấn nước', 70),
('máy ảnh canon', 65),
('loa bluetooth', 60),
('đầm maxi', 55),
('kính mát', 50),
('sách đắc nhân tâm', 45);

-- ==========================================
-- 20. SEED THÊM SETTINGS ĐẦY ĐỦ
-- ==========================================
INSERT INTO settings (setting_key, setting_value, setting_group) VALUES
('site_logo', '/images/logo.png', 'general'),
('site_favicon', '/images/favicon.ico', 'general'),
('site_address', '123 Đường ABC, Quận 1, TP.HCM', 'contact'),
('facebook_url', 'https://facebook.com/zoldify', 'social'),
('instagram_url', 'https://instagram.com/zoldify', 'social'),
('youtube_url', 'https://youtube.com/zoldify', 'social'),
('tiktok_url', 'https://tiktok.com/@zoldify', 'social'),
('smtp_host', 'smtp.gmail.com', 'email'),
('smtp_port', '587', 'email'),
('smtp_username', '', 'email'),
('smtp_password', '', 'email'),
('smtp_from_email', 'noreply@zoldify.vn', 'email'),
('smtp_from_name', 'Zoldify', 'email'),
('payment_vnpay_enabled', '0', 'payment'),
('payment_momo_enabled', '0', 'payment'),
('payment_cod_enabled', '1', 'payment'),
('commission_rate', '5', 'payment'),
('min_withdrawal', '100000', 'payment'),
('terms_of_service', '', 'legal'),
('privacy_policy', '', 'legal'),
('about_us', 'Zoldify - Nền tảng mua bán trực tuyến hàng đầu Việt Nam', 'general');

-- ==========================================
-- 21. SEED ORDERS (Đơn hàng mẫu)
-- ==========================================
INSERT INTO orders (buyer_id, seller_id, total_amount, status, created_at) VALUES
-- Buyer Lan (id=7) mua từ Seller Hoa (id=3)
(7, 3, 500000, 'completed', '2025-12-20 10:30:00'),
(7, 3, 350000, 'shipping', '2025-12-28 14:15:00'),
-- Buyer Tùng (id=8) mua từ Seller Minh (id=4)
(8, 4, 12500000, 'completed', '2025-12-15 09:00:00'),
(8, 4, 4200000, 'shipping', '2025-12-30 16:45:00'),
-- Buyer Hương (id=9) mua từ Seller Mai (id=5)
(9, 5, 730000, 'completed', '2025-12-22 11:20:00'),
(9, 5, 450000, 'pending', '2026-01-02 08:30:00'),
-- Buyer Lan (id=7) mua từ Seller Nam (id=6)
(7, 6, 365000, 'completed', '2025-12-25 15:00:00'),
-- Buyer Tùng (id=8) mua từ Seller Mai (id=5)
(8, 5, 320000, 'cancelled', '2025-12-18 10:00:00');

-- ==========================================
-- 22. SEED ORDER_DETAILS (Chi tiết đơn hàng)
-- ==========================================
INSERT INTO order_details (order_id, product_id, quantity, price_at_purchase) VALUES
-- Order 1: Buyer Lan mua áo + quần
(1, 1, 2, 150000),
(1, 2, 1, 350000),
-- Order 2: Buyer Lan mua quần jean
(2, 2, 1, 350000),
-- Order 3: Buyer Tùng mua iPhone
(3, 6, 1, 12500000),
-- Order 4: Buyer Tùng mua tai nghe Sony
(4, 8, 1, 4200000),
-- Order 5: Buyer Hương mua váy + son
(5, 13, 1, 280000),
(5, 15, 1, 450000),
-- Order 6: Buyer Hương mua son
(6, 15, 1, 450000),
-- Order 7: Buyer Lan mua sách
(7, 22, 1, 85000),
(7, 23, 1, 280000),
-- Order 8: Buyer Tùng mua túi (đã hủy)
(8, 14, 1, 320000);

-- ==========================================
-- 23. SEED MESSAGES (Tin nhắn mẫu)
-- ==========================================
INSERT INTO messages (sender_id, receiver_id, content, is_read, created_at) VALUES
-- Lan hỏi Hoa về sản phẩm
(7, 3, 'Chào shop, áo thun còn màu đen size L không ạ?', 1, '2025-12-19 09:00:00'),
(3, 7, 'Chào bạn, còn ạ. Bạn đặt hàng nhé!', 1, '2025-12-19 09:15:00'),
(7, 3, 'Vâng, em đặt 2 cái ạ', 1, '2025-12-19 09:20:00'),
-- Tùng hỏi Minh về iPhone
(8, 4, 'iPhone này còn bảo hành không shop?', 1, '2025-12-14 14:00:00'),
(4, 8, 'Dạ còn bảo hành FPT 3 tháng ạ', 1, '2025-12-14 14:30:00'),
(8, 4, 'OK em lấy nha', 1, '2025-12-14 14:35:00'),
-- Hương hỏi Mai về váy
(9, 5, 'Váy này có size S không chị?', 1, '2025-12-21 10:00:00'),
(5, 9, 'Freesize em ơi, ai mặc cũng đẹp', 1, '2025-12-21 10:10:00'),
-- Tin nhắn mới chưa đọc
(7, 6, 'Sách C++ còn không anh?', 0, '2026-01-03 08:00:00'),
(9, 3, 'Shop ơi có giày size 36 không?', 0, '2026-01-03 09:30:00');

-- ==========================================
-- 24. SEED REVIEWS (Đánh giá sản phẩm)
-- ==========================================
INSERT INTO reviews (reviewer_id, product_id, rating, comment, created_at) VALUES
-- Đánh giá từ Lan
(7, 1, 5, 'Áo đẹp lắm, chất vải mát, form chuẩn. Sẽ mua thêm!', '2025-12-22 10:00:00'),
(7, 2, 4, 'Quần đẹp, hơi chật size một chút', '2025-12-22 10:05:00'),
(7, 22, 5, 'Sách còn mới, giao hàng nhanh', '2025-12-27 14:00:00'),
-- Đánh giá từ Tùng
(8, 6, 5, 'Máy đẹp như mới, pin tốt, rất hài lòng!', '2025-12-18 09:00:00'),
(8, 8, 5, 'Tai nghe chống ồn cực tốt, âm thanh hay', '2026-01-02 11:00:00'),
-- Đánh giá từ Hương
(9, 13, 5, 'Váy xinh lắm, đúng hình, giao hàng cẩn thận', '2025-12-24 16:00:00'),
(9, 15, 4, 'Son đẹp, màu chuẩn, hơi khô môi một chút', '2025-12-24 16:10:00');

-- ==========================================
-- 25. SEED FAVORITES (Yêu thích)
-- ==========================================
INSERT INTO favorites (user_id, product_id, created_at) VALUES
-- Lan yêu thích
(7, 6, '2025-12-10 08:00:00'),
(7, 8, '2025-12-12 09:30:00'),
(7, 13, '2025-12-15 14:00:00'),
(7, 15, '2025-12-15 14:05:00'),
-- Tùng yêu thích
(8, 1, '2025-12-11 10:00:00'),
(8, 3, '2025-12-11 10:05:00'),
(8, 13, '2025-12-20 16:00:00'),
-- Hương yêu thích
(9, 1, '2025-12-18 11:00:00'),
(9, 6, '2025-12-19 09:00:00'),
(9, 24, '2025-12-22 15:00:00'),
(9, 25, '2025-12-25 10:00:00');

-- ==========================================
-- 26. SEED INTERACTIONS (Lịch sử xem sản phẩm)
-- ==========================================
INSERT INTO interactions (user_id, product_id, interaction_type, score, created_at) VALUES
-- Lan xem sản phẩm
(7, 1, 'view', 1, '2025-12-19 08:55:00'),
(7, 1, 'click', 2, '2025-12-19 08:56:00'),
(7, 2, 'view', 1, '2025-12-19 09:00:00'),
(7, 6, 'view', 1, '2025-12-10 08:00:00'),
(7, 6, 'click', 2, '2025-12-10 08:01:00'),
-- Tùng xem sản phẩm
(8, 6, 'view', 1, '2025-12-14 13:50:00'),
(8, 6, 'click', 2, '2025-12-14 13:55:00'),
(8, 8, 'view', 1, '2025-12-29 16:00:00'),
(8, 8, 'click', 2, '2025-12-29 16:05:00'),
-- Hương xem sản phẩm
(9, 13, 'view', 1, '2025-12-21 09:50:00'),
(9, 13, 'click', 2, '2025-12-21 09:55:00'),
(9, 15, 'view', 1, '2025-12-21 10:00:00');

-- ==========================================
-- 27. SEED NOTIFICATIONS (Thông báo)
-- ==========================================
INSERT INTO notifications (user_id, content, is_read, created_at) VALUES
-- Thông báo cho Seller
(3, 'Bạn có đơn hàng mới #1 từ Ngô Thị Lan', 1, '2025-12-20 10:30:00'),
(3, 'Bạn có đơn hàng mới #2 từ Ngô Thị Lan', 1, '2025-12-28 14:15:00'),
(4, 'Bạn có đơn hàng mới #3 từ Đặng Văn Tùng', 1, '2025-12-15 09:00:00'),
(4, 'Bạn có đơn hàng mới #4 từ Đặng Văn Tùng', 0, '2025-12-30 16:45:00'),
(5, 'Bạn có đơn hàng mới #5 từ Vũ Thị Hương', 1, '2025-12-22 11:20:00'),
(5, 'Bạn có đơn hàng mới #6 từ Vũ Thị Hương', 0, '2026-01-02 08:30:00'),
(6, 'Bạn có đơn hàng mới #7 từ Ngô Thị Lan', 1, '2025-12-25 15:00:00'),
-- Thông báo cho Buyer
(7, 'Đơn hàng #1 đã được giao thành công', 1, '2025-12-22 10:00:00'),
(7, 'Đơn hàng #2 đang được vận chuyển', 0, '2025-12-29 08:00:00'),
(8, 'Đơn hàng #3 đã được giao thành công', 1, '2025-12-18 09:00:00'),
(9, 'Đơn hàng #5 đã được giao thành công', 1, '2025-12-24 16:00:00'),
-- Thông báo hệ thống
(1, 'Có 3 sản phẩm mới cần duyệt', 0, '2026-01-03 07:00:00'),
(2, 'Có 1 báo cáo vi phạm mới', 0, '2026-01-03 08:00:00');

-- ==========================================
-- 28. SEED REPORTS (Báo cáo vi phạm)
-- ==========================================
INSERT INTO reports (reporter_id, product_id, reason, status, created_at) VALUES
(7, 10, 'Hình ảnh không đúng với mô tả sản phẩm', 'resolved', '2025-12-15 10:00:00'),
(8, 17, 'Nghi ngờ hàng giả, hàng nhái', 'pending', '2026-01-02 14:00:00'),
(9, 11, 'Thông tin sản phẩm không chính xác', 'resolved', '2025-12-20 09:30:00');

-- ==========================================
-- 29. SEED CARTS (Giỏ hàng hiện tại)
-- ==========================================
INSERT INTO carts (user_id, product_id, quantity, created_at) VALUES
-- Giỏ hàng của Lan
(7, 3, 1, '2026-01-03 08:00:00'),
(7, 13, 2, '2026-01-03 08:05:00'),
(7, 17, 1, '2026-01-03 08:10:00'),
-- Giỏ hàng của Tùng
(8, 9, 1, '2026-01-02 20:00:00'),
(8, 10, 1, '2026-01-02 20:05:00'),
-- Giỏ hàng của Hương
(9, 4, 1, '2026-01-03 07:30:00'),
(9, 16, 1, '2026-01-03 07:35:00');

-- ==========================================
-- KẾT THÚC SEED DATA
-- ==========================================