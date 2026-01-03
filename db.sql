-- =====================================================
-- Zoldify Database Dump (Compatible with MySQL 5.7+ / MariaDB)
-- Generated: 2026-01-03 08:07:40
-- =====================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS = 0;
START TRANSACTION;
SET time_zone = "+07:00";

-- -----------------------------------------------
-- Table: `users`
-- -----------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('buyer','seller','admin','moderator') COLLATE utf8mb4_unicode_ci DEFAULT 'buyer',
  `email_verified` tinyint(1) DEFAULT '0',
  `email_verification_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verification_expires_at` datetime DEFAULT NULL,
  `is_locked` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` VALUES 
('1', 'Nguyễn Văn Admin', 'admin@unizify.vn', '$2y$10$Xe1KqRb2J6kxshp32LkY9.mXPO6K7H5vv3v9wCEeI1hDbyK6bckuC', '0901234567', 'Hà Nội', '2025-12-30 21:18:26', 'admin', '1', NULL, NULL, '0'),
('2', 'Trần Thị Lan', 'lan.tran@student.edu.vn', '$2y$10$Xe1KqRb2J6kxshp32LkY9.mXPO6K7H5vv3v9wCEeI1hDbyK6bckuC', '0912345678', 'TP HCM', '2025-12-30 21:18:26', 'seller', '1', NULL, NULL, '0'),
('3', 'Lê Văn Hùng', 'hung.le@student.edu.vn', '$2y$10$Xe1KqRb2J6kxshp32LkY9.mXPO6K7H5vv3v9wCEeI1hDbyK6bckuC', '0923456789', 'Đà Nẵng', '2025-12-30 21:18:26', 'seller', '1', NULL, NULL, '0'),
('4', 'Phạm Thị Mai', 'mai.pham@student.edu.vn', '$2y$10$Xe1KqRb2J6kxshp32LkY9.mXPO6K7H5vv3v9wCEeI1hDbyK6bckuC', '0934567890', 'Hải Phòng', '2025-12-30 21:18:26', 'seller', '1', NULL, NULL, '0'),
('5', 'Hoàng Văn Nam', 'nam.hoang@student.edu.vn', '$2y$10$Xe1KqRb2J6kxshp32LkY9.mXPO6K7H5vv3v9wCEeI1hDbyK6bckuC', '0945678901', 'Cần Thơ', '2025-12-30 21:18:26', 'seller', '1', NULL, NULL, '0'),
('6', 'Super Admin', 'superadmin@unizify.vn', '$2y$10$ryaKIddnyDqn6qYXws/1fODdTCJ8/wYsqtGPYVd8bbRjJHyMLQTNi', NULL, NULL, '2025-12-30 21:36:04', 'admin', '1', NULL, NULL, '0'),
('8', 'Test User', 'admin\'--@test.com', '$2y$10$EULA2vPmgymbXSR9fM9J6.MW4uFOfmE7WsxNhBF.WOGrMmSjv0F0W', '0123456789', NULL, '2025-12-31 14:20:49', 'seller', '1', NULL, NULL, '0'),
('11', 'Super Admin', 'superadmin@zoldify.vn', '$2y$10$B1jBQA4/W//ZZYFFtcId8ew3xHEG7YAf3HoLGeZUOTqYzNBjbBroO', NULL, NULL, '2025-12-31 15:22:47', 'admin', '1', NULL, NULL, '0'),
('15', 'Admin Zoldify', 'admin@zoldify.vn', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0901234567', 'Hà Nội', '2025-12-31 15:43:03', 'admin', '1', NULL, NULL, '0'),
('16', 'Nguyễn Văn Kiểm', 'moderator@zoldify.vn', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0902345678', 'TP HCM', '2025-12-31 15:43:03', 'moderator', '1', NULL, NULL, '0'),
('17', 'Trần Thị Hoa', 'hoa.seller@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0912345678', 'Quận 1, TP HCM', '2025-12-31 15:43:03', 'seller', '1', NULL, NULL, '0'),
('18', 'Lê Văn Minh', 'minh.shop@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0923456789', 'Hải Châu, Đà Nẵng', '2025-12-31 15:43:03', 'seller', '1', NULL, NULL, '0'),
('19', 'Phạm Thị Mai', 'mai.vintage@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0934567890', 'Lê Chân, Hải Phòng', '2025-12-31 15:43:03', 'seller', '1', NULL, NULL, '0'),
('20', 'Hoàng Văn Nam', 'nam.secondhand@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0945678901', 'Ninh Kiều, Cần Thơ', '2025-12-31 15:43:03', 'seller', '1', NULL, NULL, '0'),
('21', 'Ngô Thị Lan', 'lan.buyer@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0956789012', 'Đống Đa, Hà Nội', '2025-12-31 15:43:03', 'buyer', '1', NULL, NULL, '0'),
('22', 'Đặng Văn Tùng', 'tung.customer@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0967890123', 'Tân Bình, TP HCM', '2025-12-31 15:43:03', 'buyer', '1', NULL, NULL, '0'),
('23', 'Vũ Thị Hương', 'huong.buyer@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0978901234', 'Thanh Khê, Đà Nẵng', '2025-12-31 15:43:03', 'buyer', '1', NULL, NULL, '0'),
('24', 'Đạt Đặng', 'tvmaroka1@gmail.com', '$2y$10$KKeEBgDba9Oba/rGp0ynf.6Wu5zE2HIfPzZ96ys1biBHGNUmNpYxq', NULL, NULL, '2025-12-31 22:09:31', 'buyer', '1', NULL, NULL, '0'),
('25', 'Tdat', 'tdatdev@gmail.com', '$2y$10$7QLt/VugPI3rTdl2NajpX.zmpeZhOaogtsS8ypM3F1j0P8iAC81S2', '0926531052', '', '2025-12-31 22:47:58', 'buyer', '1', NULL, NULL, '1'),
('27', 'Tdat', 'tdatdev11@gmail.com', '$2y$10$YeiWWqVXbA818yQXlUb92OBJFNS3PGE82GYVQkG9PKNxTLyyrSHGq', '0926531052', '', '2025-12-31 22:54:36', 'buyer', '1', NULL, NULL, '0'),
('28', 'Tdatdev', 'datdt.1140101240018@vtc.edu.vn', '$2y$10$9DKa4XXYLpB5CD565VVM3OLj16/QYBsrCdfwriGL/MaaEyhohH.mK', '0926531052', '', '2026-01-01 21:25:28', 'buyer', '0', NULL, NULL, '0');

-- -----------------------------------------------
-- Table: `categories`
-- -----------------------------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `categories` VALUES 
('1', 'Thời Trang Nam', '/images/categories/item.png'),
('2', 'Điện Thoại', '/images/categories/dienthoai.png'),
('3', 'Điện Tử', '/images/categories/manhinh.png'),
('4', 'Laptop', '/images/categories/laptop.png'),
('5', 'Máy Ảnh', '/images/categories/camera.png'),
('6', 'Đồng Hồ', '/images/categories/dongho.png'),
('7', 'Giày Dép', '/images/categories/giay.png'),
('8', 'Gia Dụng', '/images/categories/amsieutoc.png'),
('9', 'Thể Thao', '/images/categories/bongda.png'),
('10', 'Xe Cộ', '/images/categories/xemay.png'),
('11', 'Thời Trang Nữ', '/images/categories/aonu.png'),
('12', 'Mẹ & Bé', '/images/categories/banghetreem.png'),
('13', 'Nhà Cửa', '/images/categories/noicanh.png'),
('14', 'Sắc Đẹp', '/images/categories/sonphan.png'),
('15', 'Sức Khỏe', '/images/categories/thuockhautrang.png'),
('16', 'Giày Nữ', '/images/categories/guoc.png'),
('17', 'Túi Ví', '/images/categories/tuida.png'),
('18', 'Phụ Kiện', '/images/categories/thatlung.png'),
('19', 'Sách', '/images/categories/sach.png'),
('20', 'Khác', '/images/categories/item.png');

-- -----------------------------------------------
-- Table: `migrations`
-- -----------------------------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `filename` (`filename`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` VALUES 
('1', '001_create_base_tables.sql', '2025-12-30 00:53:50'),
('2', '002_create_products_table.sql', '2025-12-30 00:53:50'),
('3', '003_create_orders_tables.sql', '2025-12-30 00:53:50'),
('4', '004_create_social_tables.sql', '2025-12-30 00:53:50'),
('5', '005_create_system_tables.sql', '2025-12-30 00:53:50'),
('6', '006_create_search_keywords.sql', '2025-12-30 00:53:50'),
('7', '007_add_quantity_if_missing.sql', '2025-12-30 01:02:41'),
('8', '008_seed_categories_data.sql', '2025-12-30 18:01:46'),
('9', '009_correct_category_images.sql', '2025-12-30 18:27:51'),
('10', '010_update_renamed_category_images.sql', '2025-12-30 18:28:42'),
('11', '011_fix_password_hash.sql', '2025-12-30 21:10:00'),
('12', '012_reset_users_with_correct_hash.sql', '2025-12-30 21:18:26'),
('13', '013_fix_password_final.sql', '2025-12-30 21:23:23'),
('14', '014_seed_admin.php', '2025-12-30 21:45:52'),
('15', '015_create_carts_table.sql', '2025-12-30 21:45:52'),
('16', '016_remove_major_id_from_users.sql', '2025-12-31 09:58:58'),
('17', '018_seed_popular_keywords.php', '2025-12-31 10:07:15'),
('18', '017_update_user_roles.sql', '2025-12-31 15:43:03'),
('19', '018_seed_new_users.sql', '2025-12-31 15:43:03'),
('20', '019_add_email_verification_columns.sql', '2025-12-31 22:42:23'),
('21', '020_fix_token_column_length.sql', '2025-12-31 22:49:33'),
('22', '015_add_is_locked_to_users.php', '2026-01-01 23:56:05'),
('24', '016_create_settings_table.php', '2026-01-02 21:56:19');

-- -----------------------------------------------
-- Table: `settings`
-- -----------------------------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text COLLATE utf8mb4_unicode_ci,
  `setting_group` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key` (`setting_key`),
  KEY `idx_setting_key` (`setting_key`),
  KEY `idx_setting_group` (`setting_group`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `settings` VALUES 
('1', 'site_name', 'Zoldify', 'general', '2026-01-02 21:56:19', '2026-01-03 14:43:12'),
('2', 'site_description', 'Sàn thương mại điện tử đồ cũ', 'general', '2026-01-02 21:56:19', '2026-01-02 23:27:39'),
('3', 'site_logo', '', 'general', '2026-01-02 21:56:19', '2026-01-02 21:56:19'),
('4', 'site_favicon', '', 'general', '2026-01-02 21:56:19', '2026-01-02 21:56:19'),
('5', 'contact_email', 'admin@zoldify.com', 'contact', '2026-01-02 21:56:19', '2026-01-02 21:56:52'),
('6', 'contact_phone', '', 'contact', '2026-01-02 21:56:19', '2026-01-02 21:56:19'),
('7', 'contact_address', '', 'contact', '2026-01-02 21:56:19', '2026-01-02 21:56:19'),
('8', 'smtp_host', '', 'email', '2026-01-02 21:56:19', '2026-01-02 21:56:19'),
('9', 'smtp_port', '587', 'email', '2026-01-02 21:56:19', '2026-01-02 21:56:19'),
('10', 'smtp_username', '', 'email', '2026-01-02 21:56:19', '2026-01-02 21:56:19'),
('11', 'smtp_password', '', 'email', '2026-01-02 21:56:19', '2026-01-02 21:56:19'),
('12', 'smtp_encryption', 'tls', 'email', '2026-01-02 21:56:19', '2026-01-02 21:56:19'),
('13', 'mail_from_name', 'Zoldify', 'email', '2026-01-02 21:56:19', '2026-01-02 21:56:55'),
('14', 'mail_from_email', '', 'email', '2026-01-02 21:56:19', '2026-01-02 21:56:19'),
('15', 'payment_gateway', 'vnpay', 'payment', '2026-01-02 21:56:19', '2026-01-02 23:38:43'),
('16', 'payment_api_key', 'superadmin@zoldify.vn', 'payment', '2026-01-02 21:56:19', '2026-01-02 23:38:43'),
('17', 'payment_secret_key', 'admin123', 'payment', '2026-01-02 21:56:19', '2026-01-02 23:38:43'),
('18', 'social_facebook', 'https://www.facebook.com/Zoldify', 'social', '2026-01-02 21:56:19', '2026-01-03 00:27:49'),
('19', 'social_zalo', '', 'social', '2026-01-02 21:56:19', '2026-01-02 21:56:19'),
('20', 'social_instagram', '', 'social', '2026-01-02 21:56:19', '2026-01-02 21:56:19'),
('21', 'social_youtube', '', 'social', '2026-01-02 21:56:19', '2026-01-02 21:56:19'),
('22', 'maintenance_mode', '0', 'maintenance', '2026-01-02 21:56:19', '2026-01-03 14:43:33'),
('23', 'maintenance_message', 'Website đang bảo trì, vui lòng quay lại sau.', 'maintenance', '2026-01-02 21:56:19', '2026-01-02 21:56:19');

-- -----------------------------------------------
-- Table: `search_keywords`
-- -----------------------------------------------
DROP TABLE IF EXISTS `search_keywords`;
CREATE TABLE `search_keywords` (
  `id` int NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `search_count` int DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyword` (`keyword`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `search_keywords` VALUES 
('1', 'giáo trình c', '2', '2025-12-29 22:48:38', '2025-12-29 22:55:28'),
('2', 'alo', '3', '2025-12-29 22:51:19', '2025-12-29 23:12:26'),
('3', 'lập trình', '1', '2025-12-29 22:53:34', '2025-12-29 22:53:34'),
('4', 'thảm', '1', '2025-12-29 23:12:35', '2025-12-29 23:12:35'),
('5', 'thảm tập', '1', '2025-12-29 23:12:42', '2025-12-29 23:12:42'),
('6', 'yoga', '1', '2025-12-29 23:15:16', '2025-12-29 23:15:16'),
('7', 'ba lô', '2', '2025-12-29 23:15:53', '2025-12-29 23:59:39'),
('8', 'máy tính', '2', '2025-12-30 00:00:33', '2025-12-30 00:06:38'),
('9', 'bóng đá', '2', '2025-12-30 00:06:48', '2025-12-30 00:07:16'),
('10', 'das', '1', '2025-12-30 21:08:36', '2025-12-30 21:08:36'),
('11', 'sục crocs', '150', '2025-12-31 10:07:15', '2025-12-31 10:07:15'),
('12', 'áo khoác', '120', '2025-12-31 10:07:15', '2025-12-31 10:07:15'),
('13', 'giáo trình c++', '95', '2025-12-31 10:07:15', '2025-12-31 10:07:15'),
('14', 'bàn phím cơ', '80', '2025-12-31 10:07:15', '2025-12-31 10:07:15'),
('15', 'tai nghe', '65', '2025-12-31 10:07:15', '2025-12-31 10:07:15'),
('16', 'sách tiếng anh', '55', '2025-12-31 10:07:15', '2025-12-31 10:07:15'),
('17', 'dsa', '1', '2025-12-31 15:23:15', '2025-12-31 15:23:15');

-- -----------------------------------------------
-- Table: `products`
-- -----------------------------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `category_id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL DEFAULT '0',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','sold','hidden') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` VALUES 
('1', '2', '1', 'Giáo trình Lập trình C++', 'Sách mới 95%, không gạch chú. Phù hợp cho sinh viên năm 1-2 IT.', '85000.00', '10', 'products/product-1.png', 'active', '2025-12-26 14:57:03'),
('2', '3', '1', 'Kinh tế vi mô - N. Gregory Mankiw', 'Bản tiếng Việt, đã dùng 1 kỳ, còn mới.', '120000.00', '10', 'products/product-2.png', 'active', '2025-12-26 14:57:03'),
('3', '4', '1', 'Oxford Advanced Learner Dictionary', 'Từ điển Anh-Việt bìa cứng, không rách.', '150000.00', '10', 'product_1767284402_69569eb2407d4.jpeg', 'active', '2025-12-26 14:57:03'),
('4', '2', '2', 'Chuột Logitech G102', 'Dùng 6 tháng, còn nguyên hộp. Bảo hành 18 tháng.', '250000.00', '10', 'products/product-4.png', 'active', '2025-12-26 14:57:03'),
('5', '5', '2', 'Tai nghe Sony WH-1000XM4', 'Chống ồn cực tốt, pin 8/10. Không hộp.', '4500000.00', '10', NULL, 'active', '2025-12-26 14:57:03'),
('6', '3', '2', 'USB SanDisk 32GB', 'Mới 100%, chưa bóc seal.', '80000.00', '10', 'homepage3.png', 'sold', '2025-12-26 14:57:03'),
('7', '4', '3', 'Áo hoodie Uniqlo màu đen', 'Size M, giặt 2 lần. Form rộng unisex.', '180000.00', '10', NULL, 'active', '2025-12-26 14:57:03'),
('8', '5', '3', 'Giày Converse Chuck Taylor', 'Size 40, màu trắng. Mua tháng trước nhưng không vừa.', '550000.00', '10', NULL, 'active', '2025-12-26 14:57:03'),
('9', '2', '4', 'Combo 10 bút bi Thiên Long', 'Mực xanh, mới 100%.', '25000.00', '10', NULL, 'active', '2025-12-26 14:57:03'),
('10', '3', '4', 'Máy tính Casio FX-580VN X', 'Dùng 1 năm, còn tốt. Có hướng dẫn sử dụng.', '350000.00', '10', NULL, 'active', '2025-12-26 14:57:03'),
('11', '4', '5', 'Ba lô The North Face 20L', 'Màu xám, chống nước. Dùng 1 năm nhưng còn mới 90%.', '650000.00', '10', NULL, 'active', '2025-12-26 14:57:03'),
('12', '5', '5', 'Bình giữ nhiệt Lock&Lock 500ml', 'Màu hồng pastel, chưa sử dụng.', '120000.00', '10', NULL, 'active', '2025-12-26 14:57:03'),
('13', '2', '6', 'Bóng đá Mikasa size 5', 'Dùng tập luyện 3 tháng, còn bơm tốt.', '180000.00', '10', NULL, 'active', '2025-12-26 14:57:03'),
('14', '3', '6', 'Thảm tập Yoga Nike 6mm', 'Màu xanh dương, có túi đựng. Mua nhầm size.', '300000.00', '10', NULL, 'active', '2025-12-26 14:57:03');

-- -----------------------------------------------
-- Table: `orders`
-- -----------------------------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `buyer_id` int NOT NULL COMMENT 'Người mua',
  `seller_id` int NOT NULL COMMENT 'Người bán - Thêm cái này vào cho dễ code',
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','shipping','completed','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `buyer_id` (`buyer_id`),
  KEY `seller_id` (`seller_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`),
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `orders` VALUES 
('1', '3', '2', '85000.00', 'completed', '2025-12-26 14:57:03'),
('2', '4', '5', '4500000.00', 'shipping', '2025-12-26 14:57:03'),
('3', '5', '3', '80000.00', 'completed', '2025-12-26 14:57:03');

-- -----------------------------------------------
-- Table: `order_details`
-- -----------------------------------------------
DROP TABLE IF EXISTS `order_details`;
CREATE TABLE `order_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price_at_purchase` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `order_details` VALUES 
('1', '1', '1', '1', '85000.00'),
('2', '2', '5', '1', '4500000.00'),
('3', '3', '6', '1', '80000.00');

-- -----------------------------------------------
-- Table: `carts`
-- -----------------------------------------------
DROP TABLE IF EXISTS `carts`;
CREATE TABLE `carts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `carts` VALUES 
('1', '6', '1', '3', '2025-12-30 21:57:37', '2025-12-30 21:58:48'),
('4', '27', '5', '1', '2026-01-01 16:30:32', '2026-01-01 16:30:32'),
('5', '27', '1', '1', '2026-01-01 16:30:32', '2026-01-01 16:30:32'),
('6', '11', '12', '1', '2026-01-02 22:12:00', '2026-01-02 22:12:00');

-- -----------------------------------------------
-- Table: `favorites`
-- -----------------------------------------------
DROP TABLE IF EXISTS `favorites`;
CREATE TABLE `favorites` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `favorites` VALUES 
('1', '2', '5', '2025-12-26 14:57:03'),
('2', '3', '8', '2025-12-26 14:57:03'),
('3', '4', '11', '2025-12-26 14:57:03'),
('4', '5', '1', '2025-12-26 14:57:03');

-- -----------------------------------------------
-- Table: `interactions`
-- -----------------------------------------------
DROP TABLE IF EXISTS `interactions`;
CREATE TABLE `interactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `interaction_type` enum('view','click') COLLATE utf8mb4_unicode_ci NOT NULL,
  `score` int DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `interactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `interactions_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `interactions` VALUES 
('1', '2', '1', 'view', '3', '2025-12-26 14:57:04'),
('2', '2', '2', 'click', '5', '2025-12-26 14:57:04'),
('3', '3', '5', 'view', '2', '2025-12-26 14:57:04'),
('4', '3', '8', 'click', '7', '2025-12-26 14:57:04'),
('5', '4', '4', 'view', '1', '2025-12-26 14:57:04'),
('6', '4', '11', 'click', '10', '2025-12-26 14:57:04'),
('7', '5', '1', 'view', '4', '2025-12-26 14:57:04'),
('8', '5', '7', 'click', '6', '2025-12-26 14:57:04');

-- -----------------------------------------------
-- Table: `messages`
-- -----------------------------------------------
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `messages` VALUES 
('1', '3', '2', 'Chào bạn, sách C++ còn không?', '1', '2025-12-26 14:57:03'),
('2', '2', '3', 'Còn bạn nhé! Bạn lấy khi nào?', '1', '2025-12-26 14:57:03'),
('3', '3', '2', 'Chiều nay mình qua nhận được không?', '0', '2025-12-26 14:57:03'),
('4', '4', '5', 'Tai nghe còn bảo hành không bạn?', '1', '2025-12-26 14:57:03'),
('5', '5', '4', 'Còn 18 tháng nha, hộp mất rồi.', '0', '2025-12-26 14:57:03');

-- -----------------------------------------------
-- Table: `notifications`
-- -----------------------------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `content` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `notifications` VALUES 
('1', '2', 'Sản phẩm \"Giáo trình C++\" của bạn đã được mua!', '1', '2025-12-26 14:57:04'),
('2', '3', 'Bạn có tin nhắn mới từ Trần Thị Lan', '0', '2025-12-26 14:57:04'),
('3', '5', 'Đơn hàng #2 đang được giao', '0', '2025-12-26 14:57:04');

-- -----------------------------------------------
-- Table: `reports`
-- -----------------------------------------------
DROP TABLE IF EXISTS `reports`;
CREATE TABLE `reports` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reporter_id` int NOT NULL,
  `product_id` int NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','resolved') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `reporter_id` (`reporter_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`),
  CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `reports` VALUES 
('1', '4', '13', 'Sản phẩm không đúng mô tả, nghi ngờ hàng giả', 'pending', '2025-12-26 14:57:04');

-- -----------------------------------------------
-- Table: `reviews`
-- -----------------------------------------------
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reviewer_id` int NOT NULL,
  `product_id` int NOT NULL,
  `rating` int DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `reviewer_id` (`reviewer_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`),
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `reviews` VALUES 
('1', '3', '1', '5', 'Sách đẹp, giao hàng nhanh. Recommend!', '2025-12-26 14:57:03'),
('2', '5', '6', '4', 'USB chạy tốt, đóng gói cẩn thận.', '2025-12-26 14:57:03');

-- -----------------------------------------------
-- Table: `transactions`
-- -----------------------------------------------
DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `type` enum('deposit','withdraw','payment','refund') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` text,
  `status` enum('pending','completed','failed') DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `transactions` VALUES 
('1', '19', 'deposit', '10000000.00', 'Nạp tiền vào ví', 'completed', '2026-01-01 16:00:56'),
('2', '19', 'deposit', '10000.00', 'Nạp tiền vào ví', 'completed', '2026-01-01 16:01:18'),
('3', '19', 'deposit', '10000000.00', 'Nạp tiền vào ví', 'completed', '2026-01-02 07:29:24'),
('4', '19', 'deposit', '100000000.00', 'Nạp tiền vào ví', 'completed', '2026-01-02 07:42:23'),
('5', '19', 'deposit', '1000000.00', 'Nạp tiền vào ví', 'completed', '2026-01-03 07:06:10');

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
