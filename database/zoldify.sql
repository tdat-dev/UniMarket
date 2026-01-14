-- phpMyAdmin SQL Dump
-- version 6.0.0-dev+20251208.9610139710
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 05, 2026 at 07:57 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `zoldify`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE IF NOT EXISTS `carts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_product` (`user_id`,`product_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `product_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 6, 1, 3, '2025-12-30 14:57:37', '2025-12-30 14:58:48'),
(4, 27, 5, 1, '2026-01-01 09:30:32', '2026-01-01 09:30:32'),
(5, 27, 1, 1, '2026-01-01 09:30:32', '2026-01-01 09:30:32');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int DEFAULT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `tag` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sort_order` int DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_category_parent` (`parent_id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `name`, `description`, `tag`, `icon`, `image`, `sort_order`) VALUES
(1, NULL, 'Sách & Giáo trình', '', NULL, '/images/categories/category_1767542023_695a8d070f34d.png', '/images/categories/cat_books_premium.png', 1),
(2, 1, 'Sách giáo khoa - giáo trình', NULL, NULL, NULL, '/images/categories/cat_books_premium.png', 0),
(3, 1, 'Sách văn học', NULL, NULL, NULL, '/images/categories/cat_books_premium.png', 0),
(4, 1, 'Sách kinh tế', NULL, NULL, NULL, '/images/categories/cat_books_premium.png', 0),
(5, 1, 'Sách thiếu nhi', NULL, NULL, NULL, '/images/categories/cat_books_premium.png', 0),
(6, 1, 'Sách kỹ năng sống', NULL, NULL, NULL, '/images/categories/cat_books_premium.png', 0),
(7, 1, 'Sách học ngoại ngữ', NULL, NULL, NULL, '/images/categories/cat_books_premium.png', 0),
(8, 1, 'Truyện tranh (Manga/Comic)', NULL, NULL, NULL, NULL, 0),
(9, NULL, 'Đồ điện tử', NULL, 'Hot', 'fa-laptop', '/images/categories/cat_electronics.png', 3),
(10, 9, 'Điện thoại & Phụ kiện', NULL, NULL, NULL, NULL, 0),
(11, 9, 'Máy tính bảng', NULL, NULL, NULL, NULL, 0),
(12, 9, 'Laptop & PC', NULL, NULL, NULL, NULL, 0),
(13, 9, 'Máy ảnh & Quay phim', NULL, NULL, NULL, NULL, 0),
(14, 9, 'Thiết bị âm thanh', NULL, NULL, NULL, NULL, 0),
(15, NULL, 'Đồ học tập', NULL, NULL, 'fa-pen-ruler', '/images/categories/cat_school.png', 2),
(16, 15, 'Bút viết & Hộp bút', NULL, NULL, NULL, NULL, 0),
(17, 15, 'Vở & Sổ tay', NULL, NULL, NULL, NULL, 0),
(18, 15, 'Dụng cụ vẽ', NULL, NULL, NULL, NULL, 0),
(19, 15, 'Máy tính bỏ túi', NULL, NULL, NULL, NULL, 0),
(20, 15, 'Balo học sinh', NULL, NULL, NULL, NULL, 0),
(21, NULL, 'Thời trang', NULL, 'Trend', 'fa-shirt', '/images/categories/cat_fashion.png', 9),
(22, 21, 'Áo thun & Áo phông', NULL, NULL, NULL, NULL, 0),
(23, 21, 'Áo sơ mi', NULL, NULL, NULL, NULL, 0),
(24, 21, 'Quần Jeans/Kaki', NULL, NULL, NULL, NULL, 0),
(25, 21, 'Áo khoác & Hoodie', NULL, NULL, NULL, NULL, 0),
(26, 21, 'Váy & Đầm', NULL, NULL, NULL, NULL, 0),
(27, NULL, 'Phụ kiện', NULL, NULL, 'fa-glasses', '/images/categories/cat_accessories.png', 12),
(28, 27, 'Đồng hồ', NULL, NULL, NULL, NULL, 0),
(29, 27, 'Kính mắt', NULL, NULL, NULL, NULL, 0),
(30, 27, 'Trang sức', NULL, NULL, NULL, NULL, 0),
(31, 27, 'Túi xách & Ví', NULL, NULL, NULL, NULL, 0),
(32, 27, 'Giày dép', NULL, NULL, NULL, NULL, 0),
(33, NULL, 'Khác', NULL, NULL, 'fa-box-open', '/images/categories/cat_other.png', 20),
(34, 33, 'Đồ gia dụng', NULL, NULL, NULL, NULL, 0),
(35, 33, 'Nhà cửa & Đời sống', NULL, NULL, NULL, NULL, 0),
(36, 33, 'Thể thao & Du lịch', NULL, NULL, NULL, NULL, 0),
(37, 33, 'Sản phẩm khác', NULL, NULL, NULL, NULL, 0),
(38, NULL, 'Điện thoại', NULL, NULL, 'fa-mobile-screen', '/images/categories/cat_phone.png', 4),
(39, NULL, 'Laptop', NULL, NULL, 'fa-laptop', '/images/categories/cat_laptop.png', 5),
(40, NULL, 'Máy ảnh', NULL, NULL, 'fa-camera', '/images/categories/cat_camera.png', 6),
(41, NULL, 'Đồng hồ', NULL, NULL, 'fa-clock', '/images/categories/cat_watch.png', 8),
(42, NULL, 'Giày dép', NULL, NULL, 'fa-shoe-prints', '/images/categories/cat_shoes.png', 10),
(43, NULL, 'Túi xách & Ví', NULL, NULL, 'fa-bag-shopping', '/images/categories/cat_bag.png', 11),
(44, NULL, 'Nhà cửa & Đời sống', NULL, NULL, 'fa-house', '/images/categories/cat_home.png', 16),
(45, NULL, 'Thể thao & Du lịch', NULL, NULL, 'fa-dumbbell', '/images/categories/cat_sport.png', 15),
(46, NULL, 'Sắc đẹp', NULL, NULL, 'fa-spa', '/images/categories/cat_beauty.png', 13),
(47, NULL, 'Sức khỏe', NULL, NULL, 'fa-heart-pulse', '/images/categories/cat_health.png', 14),
(48, NULL, 'Mẹ & Bé', NULL, NULL, 'fa-baby', '/images/categories/cat_mom_baby.png', 17),
(49, NULL, 'Xe cộ', NULL, NULL, 'fa-motorcycle', '/images/categories/cat_vehicle.png', 18),
(50, NULL, 'Thú cưng', NULL, NULL, 'fa-paw', '/images/categories/cat_pet.png', 19),
(51, NULL, 'Âm thanh', NULL, NULL, 'fa-headphones', '/images/categories/cat_audio.png', 7);

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE IF NOT EXISTS `favorites` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `product_id`, `created_at`) VALUES
(1, 2, 5, '2025-12-26 07:57:03'),
(2, 3, 8, '2025-12-26 07:57:03'),
(3, 4, 11, '2025-12-26 07:57:03'),
(4, 5, 1, '2025-12-26 07:57:03');

-- --------------------------------------------------------

--
-- Table structure for table `interactions`
--

CREATE TABLE IF NOT EXISTS `interactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `interaction_type` enum('view','click') COLLATE utf8mb4_unicode_ci NOT NULL,
  `score` int DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `interactions`
--

INSERT INTO `interactions` (`id`, `user_id`, `product_id`, `interaction_type`, `score`, `created_at`) VALUES
(1, 2, 1, 'view', 3, '2025-12-26 07:57:04'),
(2, 2, 2, 'click', 5, '2025-12-26 07:57:04'),
(3, 3, 5, 'view', 2, '2025-12-26 07:57:04'),
(4, 3, 8, 'click', 7, '2025-12-26 07:57:04'),
(5, 4, 4, 'view', 1, '2025-12-26 07:57:04'),
(6, 4, 11, 'click', 10, '2025-12-26 07:57:04'),
(7, 5, 1, 'view', 4, '2025-12-26 07:57:04'),
(8, 5, 7, 'click', 6, '2025-12-26 07:57:04');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `has_attachment` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `sender_id` (`sender_id`),
  KEY `receiver_id` (`receiver_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `content`, `is_read`, `has_attachment`, `created_at`) VALUES
(1, 3, 2, 'Chào bạn, sách C++ còn không?', 1, 0, '2025-12-26 07:57:03'),
(2, 2, 3, 'Còn bạn nhé! Bạn lấy khi nào?', 1, 0, '2025-12-26 07:57:03'),
(3, 3, 2, 'Chiều nay mình qua nhận được không?', 0, 0, '2025-12-26 07:57:03'),
(4, 4, 5, 'Tai nghe còn bảo hành không bạn?', 1, 0, '2025-12-26 07:57:03'),
(5, 5, 4, 'Còn 18 tháng nha, hộp mất rồi.', 0, 0, '2025-12-26 07:57:03'),
(6, 24, 5, 'alo', 0, 0, '2026-01-03 14:40:07'),
(7, 24, 2, 'alo', 0, 0, '2026-01-03 15:05:00'),
(8, 24, 2, '123', 0, 0, '2026-01-03 15:19:24'),
(9, 24, 2, 'alo', 0, 0, '2026-01-03 15:34:12'),
(10, 24, 2, '🤭', 0, 0, '2026-01-03 15:34:17'),
(11, 24, 2, '[File đính kèm]', 0, 1, '2026-01-03 15:34:22'),
(12, 24, 2, '[File đính kèm]', 0, 1, '2026-01-03 15:59:51'),
(13, 24, 2, '🤪', 0, 0, '2026-01-03 16:03:15'),
(14, 11, 5, 'sad', 0, 0, '2026-01-03 18:46:29'),
(15, 11, 5, '😀', 0, 0, '2026-01-03 18:49:19'),
(16, 11, 5, 'hello ae👌', 0, 0, '2026-01-03 18:49:24');

-- --------------------------------------------------------

--
-- Table structure for table `message_attachments`
--

CREATE TABLE IF NOT EXISTS `message_attachments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `message_id` int NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `file_type` varchar(50) NOT NULL,
  `file_size` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_message_id` (`message_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `message_attachments`
--

INSERT INTO `message_attachments` (`id`, `message_id`, `file_name`, `file_path`, `file_type`, `file_size`, `created_at`) VALUES
(1, 11, '2.jpg', '/uploads/chat/chat_24_1767454462_695936fe21619.jpg', 'image/jpeg', 225291, '2026-01-03 15:34:22'),
(2, 12, 'limit.png', '/uploads/chat/chat_24_1767455991_69593cf76b13f.png', 'image/png', 30205, '2026-01-03 15:59:51');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `filename` (`filename`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `filename`, `executed_at`) VALUES
(1, '001_create_base_tables.sql', '2025-12-29 17:53:50'),
(2, '002_create_products_table.sql', '2025-12-29 17:53:50'),
(3, '003_create_orders_tables.sql', '2025-12-29 17:53:50'),
(4, '004_create_social_tables.sql', '2025-12-29 17:53:50'),
(5, '005_create_system_tables.sql', '2025-12-29 17:53:50'),
(6, '006_create_search_keywords.sql', '2025-12-29 17:53:50'),
(7, '007_add_quantity_if_missing.sql', '2025-12-29 18:02:41'),
(8, '008_seed_categories_data.sql', '2025-12-30 11:01:46'),
(9, '009_correct_category_images.sql', '2025-12-30 11:27:51'),
(10, '010_update_renamed_category_images.sql', '2025-12-30 11:28:42'),
(11, '011_fix_password_hash.sql', '2025-12-30 14:10:00'),
(12, '012_reset_users_with_correct_hash.sql', '2025-12-30 14:18:26'),
(13, '013_fix_password_final.sql', '2025-12-30 14:23:23'),
(14, '014_seed_admin.php', '2025-12-30 14:45:52'),
(15, '015_create_carts_table.sql', '2025-12-30 14:45:52'),
(16, '016_remove_major_id_from_users.sql', '2025-12-31 02:58:58'),
(17, '018_seed_popular_keywords.php', '2025-12-31 03:07:15'),
(18, '017_update_user_roles.sql', '2025-12-31 08:43:03'),
(19, '018_seed_new_users.sql', '2025-12-31 08:43:03'),
(20, '019_add_email_verification_columns.sql', '2025-12-31 15:42:23'),
(21, '020_fix_token_column_length.sql', '2025-12-31 15:49:33'),
(22, '015_add_is_locked_to_users.php', '2026-01-01 16:56:05'),
(24, '016_create_settings_table.php', '2026-01-02 14:56:19'),
(25, '021_create_settings_table.sql', '2026-01-03 10:20:14'),
(26, '021_update_product_images.sql', '2026-01-03 10:20:15'),
(27, '022_add_balance_and_avatar_to_users.sql', '2026-01-03 10:25:24'),
(28, '023_create_message_attachments.php', '2026-01-03 15:29:42'),
(29, '024_add_last_seen_to_users.php', '2026-01-03 15:42:26');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `content` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `content`, `is_read`, `created_at`) VALUES
(1, 2, 'Sản phẩm \"Giáo trình C++\" của bạn đã được mua!', 1, '2025-12-26 07:57:04'),
(2, 3, 'Bạn có tin nhắn mới từ Trần Thị Lan', 0, '2025-12-26 07:57:04'),
(3, 5, 'Đơn hàng #2 đang được giao', 0, '2025-12-26 07:57:04');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `buyer_id` int NOT NULL COMMENT 'Người mua',
  `seller_id` int NOT NULL COMMENT 'Người bán - Thêm cái này vào cho dễ code',
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','shipping','completed','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `buyer_id` (`buyer_id`),
  KEY `seller_id` (`seller_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `buyer_id`, `seller_id`, `total_amount`, `status`, `created_at`) VALUES
(1, 3, 2, 85000.00, 'completed', '2025-12-26 07:57:03'),
(2, 4, 5, 4500000.00, 'shipping', '2025-12-26 07:57:03'),
(3, 5, 3, 80000.00, 'completed', '2025-12-26 07:57:03'),
(4, 11, 2, 85000.00, 'pending', '2026-01-04 16:03:48');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE IF NOT EXISTS `order_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price_at_purchase` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price_at_purchase`) VALUES
(1, 1, 1, 1, 85000.00),
(2, 2, 5, 1, 4500000.00),
(3, 3, 6, 1, 80000.00),
(4, 4, 1, 1, 85000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
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
  `condition` enum('new','like_new','good','fair') COLLATE utf8mb4_unicode_ci DEFAULT 'good',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `user_id`, `category_id`, `name`, `description`, `price`, `quantity`, `image`, `status`, `created_at`, `condition`) VALUES
(1, 2, 1, 'Giáo trình Lập trình C++', 'Sách mới 95%, không gạch chú. Phù hợp cho sinh viên năm 1-2 IT.', 85000.00, 9, '/images/products/1.png', 'active', '2025-12-26 07:57:03', 'good'),
(2, 3, 1, 'Kinh tế vi mô - N. Gregory Mankiw', 'Bản tiếng Việt, đã dùng 1 kỳ, còn mới.', 120000.00, 10, '/images/products/2.png', 'active', '2025-12-26 07:57:03', 'good'),
(3, 4, 1, 'Oxford Advanced Learner Dictionary', 'Từ điển Anh-Việt bìa cứng, không rách.', 150000.00, 10, '/images/products/3.png', 'active', '2025-12-26 07:57:03', 'good'),
(4, 2, 2, 'Chuột Logitech G102', 'Dùng 6 tháng, còn nguyên hộp. Bảo hành 18 tháng.', 250000.00, 10, '/images/products/4.png', 'active', '2025-12-26 07:57:03', 'good'),
(5, 5, 2, 'Tai nghe Sony WH-1000XM4', 'Chống ồn cực tốt, pin 8/10. Không hộp.', 4500000.00, 10, '/images/products/5.png', 'active', '2025-12-26 07:57:03', 'good'),
(6, 3, 2, 'USB SanDisk 32GB', 'Mới 100%, chưa bóc seal.', 80000.00, 10, '/images/products/6.png', 'sold', '2025-12-26 07:57:03', 'good'),
(7, 4, 3, 'Áo hoodie Uniqlo màu đen', 'Size M, giặt 2 lần. Form rộng unisex.', 180000.00, 10, '/images/products/7.png', 'active', '2025-12-26 07:57:03', 'good'),
(8, 5, 3, 'Giày Converse Chuck Taylor', 'Size 40, màu trắng. Mua tháng trước nhưng không vừa.', 550000.00, 10, '/images/products/8.png', 'active', '2025-12-26 07:57:03', 'good'),
(9, 2, 4, 'Combo 10 bút bi Thiên Long', 'Mực xanh, mới 100%.', 25000.00, 10, '/images/products/9.png', 'active', '2025-12-26 07:57:03', 'good'),
(10, 3, 4, 'Máy tính Casio FX-580VN X', 'Dùng 1 năm, còn tốt. Có hướng dẫn sử dụng.', 350000.00, 10, '/images/products/10.png', 'active', '2025-12-26 07:57:03', 'good'),
(11, 4, 5, 'Ba lô The North Face 20L', 'Màu xám, chống nước. Dùng 1 năm nhưng còn mới 90%.', 650000.00, 10, '/images/products/11.png', 'active', '2025-12-26 07:57:03', 'good'),
(12, 5, 5, 'Bình giữ nhiệt Lock&Lock 500ml', 'Màu hồng pastel, chưa sử dụng.', 120000.00, 10, '/images/products/12.png', 'active', '2025-12-26 07:57:03', 'good'),
(13, 2, 6, 'Bóng đá Mikasa size 5', 'Dùng tập luyện 3 tháng, còn bơm tốt.', 180000.00, 10, '/images/products/13.png', 'active', '2025-12-26 07:57:03', 'good'),
(14, 3, 6, 'Thảm tập Yoga Nike 6mm', 'Màu xanh dương, có túi đựng. Mua nhầm size.', 300000.00, 10, '/images/products/14.png', 'active', '2025-12-26 07:57:03', 'good'),
(15, 11, 2, 'Lập trình hướng đối tượng C++', '\n\nTình trạng: Như mới', 50000.00, 1, 'products/1767542583_image.png', 'active', '2026-01-04 16:03:03', 'good');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reporter_id` int NOT NULL,
  `product_id` int NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','resolved') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `reporter_id` (`reporter_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `reporter_id`, `product_id`, `reason`, `status`, `created_at`) VALUES
(1, 4, 13, 'Sản phẩm không đúng mô tả, nghi ngờ hàng giả', 'pending', '2025-12-26 07:57:04');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reviewer_id` int NOT NULL,
  `product_id` int NOT NULL,
  `rating` int DEFAULT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `reviewer_id` (`reviewer_id`),
  KEY `product_id` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `reviewer_id`, `product_id`, `rating`, `comment`, `created_at`) VALUES
(1, 3, 1, 5, 'Sách đẹp, giao hàng nhanh. Recommend!', '2025-12-26 07:57:03'),
(2, 5, 6, 4, 'USB chạy tốt, đóng gói cẩn thận.', '2025-12-26 07:57:03');

-- --------------------------------------------------------

--
-- Table structure for table `search_keywords`
--

CREATE TABLE IF NOT EXISTS `search_keywords` (
  `id` int NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `search_count` int DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `keyword` (`keyword`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `search_keywords`
--

INSERT INTO `search_keywords` (`id`, `keyword`, `search_count`, `created_at`, `updated_at`) VALUES
(1, 'giáo trình c', 2, '2025-12-29 15:48:38', '2025-12-29 15:55:28'),
(2, 'alo', 3, '2025-12-29 15:51:19', '2025-12-29 16:12:26'),
(3, 'lập trình', 1, '2025-12-29 15:53:34', '2025-12-29 15:53:34'),
(4, 'thảm', 1, '2025-12-29 16:12:35', '2025-12-29 16:12:35'),
(5, 'thảm tập', 1, '2025-12-29 16:12:42', '2025-12-29 16:12:42'),
(6, 'yoga', 1, '2025-12-29 16:15:16', '2025-12-29 16:15:16'),
(7, 'ba lô', 2, '2025-12-29 16:15:53', '2025-12-29 16:59:39'),
(8, 'máy tính', 2, '2025-12-29 17:00:33', '2025-12-29 17:06:38'),
(9, 'bóng đá', 2, '2025-12-29 17:06:48', '2025-12-29 17:07:16'),
(10, 'das', 1, '2025-12-30 14:08:36', '2025-12-30 14:08:36'),
(11, 'sục crocs', 150, '2025-12-31 03:07:15', '2025-12-31 03:07:15'),
(12, 'áo khoác', 120, '2025-12-31 03:07:15', '2025-12-31 03:07:15'),
(13, 'giáo trình c++', 96, '2025-12-31 03:07:15', '2026-01-03 10:26:03'),
(14, 'bàn phím cơ', 80, '2025-12-31 03:07:15', '2025-12-31 03:07:15'),
(15, 'tai nghe', 65, '2025-12-31 03:07:15', '2025-12-31 03:07:15'),
(16, 'sách tiếng anh', 55, '2025-12-31 03:07:15', '2025-12-31 03:07:15'),
(17, 'dsa', 1, '2025-12-31 08:23:15', '2025-12-31 08:23:15');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
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
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `setting_group`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'Zoldify', 'general', '2026-01-02 14:56:19', '2026-01-03 07:43:12'),
(2, 'site_description', 'Sàn thương mại điện tử đồ cũ', 'general', '2026-01-02 14:56:19', '2026-01-02 16:27:39'),
(3, 'site_logo', '', 'general', '2026-01-02 14:56:19', '2026-01-02 14:56:19'),
(4, 'site_favicon', '', 'general', '2026-01-02 14:56:19', '2026-01-02 14:56:19'),
(5, 'contact_email', 'admin@zoldify.com', 'contact', '2026-01-02 14:56:19', '2026-01-02 14:56:52'),
(6, 'contact_phone', '', 'contact', '2026-01-02 14:56:19', '2026-01-02 14:56:19'),
(7, 'contact_address', '', 'contact', '2026-01-02 14:56:19', '2026-01-02 14:56:19'),
(8, 'smtp_host', '', 'email', '2026-01-02 14:56:19', '2026-01-02 14:56:19'),
(9, 'smtp_port', '587', 'email', '2026-01-02 14:56:19', '2026-01-02 14:56:19'),
(10, 'smtp_username', '', 'email', '2026-01-02 14:56:19', '2026-01-02 14:56:19'),
(11, 'smtp_password', '', 'email', '2026-01-02 14:56:19', '2026-01-02 14:56:19'),
(12, 'smtp_encryption', 'tls', 'email', '2026-01-02 14:56:19', '2026-01-02 14:56:19'),
(13, 'mail_from_name', 'Zoldify', 'email', '2026-01-02 14:56:19', '2026-01-02 14:56:55'),
(14, 'mail_from_email', '', 'email', '2026-01-02 14:56:19', '2026-01-02 14:56:19'),
(15, 'payment_gateway', 'vnpay', 'payment', '2026-01-02 14:56:19', '2026-01-02 16:38:43'),
(16, 'payment_api_key', 'superadmin@zoldify.vn', 'payment', '2026-01-02 14:56:19', '2026-01-02 16:38:43'),
(17, 'payment_secret_key', 'admin123', 'payment', '2026-01-02 14:56:19', '2026-01-02 16:38:43'),
(18, 'social_facebook', 'https://www.facebook.com/Zoldify', 'social', '2026-01-02 14:56:19', '2026-01-02 17:27:49'),
(19, 'social_zalo', '', 'social', '2026-01-02 14:56:19', '2026-01-02 14:56:19'),
(20, 'social_instagram', '', 'social', '2026-01-02 14:56:19', '2026-01-02 14:56:19'),
(21, 'social_youtube', '', 'social', '2026-01-02 14:56:19', '2026-01-02 14:56:19'),
(22, 'maintenance_mode', '0', 'maintenance', '2026-01-02 14:56:19', '2026-01-03 18:57:53'),
(23, 'maintenance_message', 'Website đang bảo trì, vui lòng quay lại sau.', 'maintenance', '2026-01-02 14:56:19', '2026-01-02 14:56:19');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `type` enum('deposit','withdraw','payment','refund') NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` text,
  `status` enum('pending','completed','failed') DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `type`, `amount`, `description`, `status`, `created_at`) VALUES
(1, 19, 'deposit', 10000000.00, 'Nạp tiền vào ví', 'completed', '2026-01-01 09:00:56'),
(2, 19, 'deposit', 10000.00, 'Nạp tiền vào ví', 'completed', '2026-01-01 09:01:18'),
(3, 19, 'deposit', 10000000.00, 'Nạp tiền vào ví', 'completed', '2026-01-02 00:29:24'),
(4, 19, 'deposit', 100000000.00, 'Nạp tiền vào ví', 'completed', '2026-01-02 00:42:23'),
(5, 19, 'deposit', 1000000.00, 'Nạp tiền vào ví', 'completed', '2026-01-03 00:06:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('buyer','seller','admin','moderator') COLLATE utf8mb4_unicode_ci DEFAULT 'buyer',
  `balance` decimal(15,2) DEFAULT '0.00',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT '0',
  `email_verification_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verification_expires_at` datetime DEFAULT NULL,
  `is_locked` tinyint(1) DEFAULT '0',
  `last_seen` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `phone_number`, `address`, `created_at`, `role`, `balance`, `avatar`, `email_verified`, `email_verification_token`, `email_verification_expires_at`, `is_locked`, `last_seen`) VALUES
(1, 'Nguyễn Văn Admin', 'admin@unizify.vn', '$2y$10$Xe1KqRb2J6kxshp32LkY9.mXPO6K7H5vv3v9wCEeI1hDbyK6bckuC', '0901234567', 'Hà Nội', '2025-12-30 14:18:26', 'admin', 0.00, NULL, 1, NULL, NULL, 0, NULL),
(2, 'Trần Thị Lan', 'lan.tran@student.edu.vn', '$2y$10$Xe1KqRb2J6kxshp32LkY9.mXPO6K7H5vv3v9wCEeI1hDbyK6bckuC', '0912345678', 'TP HCM', '2025-12-30 14:18:26', 'seller', 0.00, NULL, 1, NULL, NULL, 0, NULL),
(3, 'Lê Văn Hùng', 'hung.le@student.edu.vn', '$2y$10$Xe1KqRb2J6kxshp32LkY9.mXPO6K7H5vv3v9wCEeI1hDbyK6bckuC', '0923456789', 'Đà Nẵng', '2025-12-30 14:18:26', 'seller', 0.00, NULL, 1, NULL, NULL, 0, NULL),
(4, 'Phạm Thị Mai', 'mai.pham@student.edu.vn', '$2y$10$Xe1KqRb2J6kxshp32LkY9.mXPO6K7H5vv3v9wCEeI1hDbyK6bckuC', '0934567890', 'Hải Phòng', '2025-12-30 14:18:26', 'seller', 0.00, NULL, 1, NULL, NULL, 0, NULL),
(5, 'Hoàng Văn Nam', 'nam.hoang@student.edu.vn', '$2y$10$Xe1KqRb2J6kxshp32LkY9.mXPO6K7H5vv3v9wCEeI1hDbyK6bckuC', '0945678901', 'Cần Thơ', '2025-12-30 14:18:26', 'seller', 0.00, NULL, 1, NULL, NULL, 0, NULL),
(6, 'Super Admin', 'superadmin@unizify.vn', '$2y$10$ryaKIddnyDqn6qYXws/1fODdTCJ8/wYsqtGPYVd8bbRjJHyMLQTNi', NULL, NULL, '2025-12-30 14:36:04', 'admin', 0.00, NULL, 1, NULL, NULL, 0, NULL),
(8, 'Test User', 'admin\'--@test.com', '$2y$10$EULA2vPmgymbXSR9fM9J6.MW4uFOfmE7WsxNhBF.WOGrMmSjv0F0W', '0123456789', NULL, '2025-12-31 07:20:49', 'seller', 0.00, NULL, 1, NULL, NULL, 0, NULL),
(11, 'Super Admin', 'superadmin@zoldify.vn', '$2y$10$B1jBQA4/W//ZZYFFtcId8ew3xHEG7YAf3HoLGeZUOTqYzNBjbBroO', NULL, NULL, '2025-12-31 08:22:47', 'admin', 0.00, NULL, 1, NULL, NULL, 0, '2026-01-04 02:00:42'),
(15, 'Admin Zoldify', 'admin@zoldify.vn', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0901234567', 'Hà Nội', '2025-12-31 08:43:03', 'admin', 0.00, NULL, 1, NULL, NULL, 0, NULL),
(16, 'Nguyễn Văn Kiểm', 'moderator@zoldify.vn', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0902345678', 'TP HCM', '2025-12-31 08:43:03', 'moderator', 0.00, NULL, 1, NULL, NULL, 0, NULL),
(17, 'Trần Thị Hoa', 'hoa.seller@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0912345678', 'Quận 1, TP HCM', '2025-12-31 08:43:03', 'seller', 0.00, NULL, 1, NULL, NULL, 0, NULL),
(18, 'Lê Văn Minh', 'minh.shop@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0923456789', 'Hải Châu, Đà Nẵng', '2025-12-31 08:43:03', 'seller', 0.00, NULL, 1, NULL, NULL, 0, NULL),
(19, 'Phạm Thị Mai', 'mai.vintage@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0934567890', 'Lê Chân, Hải Phòng', '2025-12-31 08:43:03', 'seller', 0.00, NULL, 1, NULL, NULL, 0, NULL),
(20, 'Hoàng Văn Nam', 'nam.secondhand@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0945678901', 'Ninh Kiều, Cần Thơ', '2025-12-31 08:43:03', 'seller', 0.00, NULL, 1, NULL, NULL, 0, NULL),
(21, 'Ngô Thị Lan', 'lan.buyer@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0956789012', 'Đống Đa, Hà Nội', '2025-12-31 08:43:03', 'buyer', 0.00, NULL, 1, NULL, NULL, 0, NULL),
(22, 'Đặng Văn Tùng', 'tung.customer@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0967890123', 'Tân Bình, TP HCM', '2025-12-31 08:43:03', 'buyer', 0.00, NULL, 1, NULL, NULL, 0, NULL),
(23, 'Vũ Thị Hương', 'huong.buyer@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0978901234', 'Thanh Khê, Đà Nẵng', '2025-12-31 08:43:03', 'buyer', 0.00, NULL, 1, NULL, NULL, 0, NULL),
(24, 'Đạt Đặng', 'tvmaroka1@gmail.com', '$2y$10$KKeEBgDba9Oba/rGp0ynf.6Wu5zE2HIfPzZ96ys1biBHGNUmNpYxq', NULL, NULL, '2025-12-31 15:09:31', 'buyer', 0.00, NULL, 1, NULL, NULL, 0, '2026-01-04 01:07:48'),
(25, 'Tdat', 'tdatdev@gmail.com', '$2y$10$7QLt/VugPI3rTdl2NajpX.zmpeZhOaogtsS8ypM3F1j0P8iAC81S2', '0926531052', '', '2025-12-31 15:47:58', 'buyer', 0.00, NULL, 1, NULL, NULL, 1, NULL),
(27, 'Tdat', 'tdatdev11@gmail.com', '$2y$10$YeiWWqVXbA818yQXlUb92OBJFNS3PGE82GYVQkG9PKNxTLyyrSHGq', '0926531052', '', '2025-12-31 15:54:36', 'buyer', 0.00, NULL, 1, NULL, NULL, 0, NULL),
(28, 'Tdatdev', 'datdt.1140101240018@vtc.edu.vn', '$2y$10$9DKa4XXYLpB5CD565VVM3OLj16/QYBsrCdfwriGL/MaaEyhohH.mK', '0926531052', '', '2026-01-01 14:25:28', 'buyer', 0.00, NULL, 0, NULL, NULL, 0, NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `fk_category_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `interactions`
--
ALTER TABLE `interactions`
  ADD CONSTRAINT `interactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `interactions_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `message_attachments`
--
ALTER TABLE `message_attachments`
  ADD CONSTRAINT `message_attachments_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
