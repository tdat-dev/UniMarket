/*
 Navicat Premium Dump SQL

 Source Server         : local
 Source Server Type    : MySQL
 Source Server Version : 80403 (8.4.3)
 Source Host           : localhost:3306
 Source Schema         : zoldify

 Target Server Type    : MySQL
 Target Server Version : 80403 (8.4.3)
 File Encoding         : 65001

 Date: 23/01/2026 16:12:56
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for carts
-- ----------------------------
DROP TABLE IF EXISTS `carts`;
CREATE TABLE `carts`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `unique_user_product`(`user_id` ASC, `product_id` ASC) USING BTREE,
  INDEX `product_id`(`product_id` ASC) USING BTREE,
  CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of carts
-- ----------------------------
INSERT INTO `carts` VALUES (1, 6, 1, 3, '2025-12-30 21:57:37', '2025-12-30 21:58:48');
INSERT INTO `carts` VALUES (4, 27, 5, 1, '2026-01-01 16:30:32', '2026-01-01 16:30:32');
INSERT INTO `carts` VALUES (5, 27, 1, 1, '2026-01-01 16:30:32', '2026-01-01 16:30:32');

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `parent_id` int NULL DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `tag` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `sort_order` int NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `fk_category_parent`(`parent_id` ASC) USING BTREE,
  CONSTRAINT `fk_category_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 52 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO `categories` VALUES (1, NULL, 'Sách & Giáo trình', NULL, NULL, 'fa-book', '/images/categories/cat_books_premium.png', 1);
INSERT INTO `categories` VALUES (2, 1, 'Sách giáo khoa - giáo trình', NULL, NULL, NULL, '/images/categories/cat_books_premium.png', 0);
INSERT INTO `categories` VALUES (3, 1, 'Sách văn học', NULL, NULL, NULL, '/images/categories/cat_books_premium.png', 0);
INSERT INTO `categories` VALUES (4, 1, 'Sách kinh tế', NULL, NULL, NULL, '/images/categories/cat_books_premium.png', 0);
INSERT INTO `categories` VALUES (5, 1, 'Sách thiếu nhi', NULL, NULL, NULL, '/images/categories/cat_books_premium.png', 0);
INSERT INTO `categories` VALUES (6, 1, 'Sách kỹ năng sống', NULL, NULL, NULL, '/images/categories/cat_books_premium.png', 0);
INSERT INTO `categories` VALUES (7, 1, 'Sách học ngoại ngữ', NULL, NULL, NULL, '/images/categories/cat_books_premium.png', 0);
INSERT INTO `categories` VALUES (8, 1, 'Truyện tranh (Manga/Comic)', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (9, NULL, 'Đồ điện tử', NULL, 'Hot', 'fa-laptop', '/images/categories/cat_electronics.png', 3);
INSERT INTO `categories` VALUES (10, 9, 'Điện thoại & Phụ kiện', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (11, 9, 'Máy tính bảng', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (12, 9, 'Laptop & PC', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (13, 9, 'Máy ảnh & Quay phim', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (14, 9, 'Thiết bị âm thanh', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (15, NULL, 'Đồ học tập', NULL, NULL, 'fa-pen-ruler', '/images/categories/cat_school.png', 2);
INSERT INTO `categories` VALUES (16, 15, 'Bút viết & Hộp bút', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (17, 15, 'Vở & Sổ tay', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (18, 15, 'Dụng cụ vẽ', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (19, 15, 'Máy tính bỏ túi', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (20, 15, 'Balo học sinh', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (21, NULL, 'Thời trang', NULL, 'Trend', 'fa-shirt', '/images/categories/cat_fashion.png', 9);
INSERT INTO `categories` VALUES (22, 21, 'Áo thun & Áo phông', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (23, 21, 'Áo sơ mi', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (24, 21, 'Quần Jeans/Kaki', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (25, 21, 'Áo khoác & Hoodie', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (26, 21, 'Váy & Đầm', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (27, NULL, 'Phụ kiện', NULL, NULL, 'fa-glasses', '/images/categories/cat_accessories.png', 12);
INSERT INTO `categories` VALUES (28, 27, 'Đồng hồ', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (29, 27, 'Kính mắt', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (30, 27, 'Trang sức', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (31, 27, 'Túi xách & Ví', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (32, 27, 'Giày dép', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (33, NULL, 'Khác', NULL, NULL, 'fa-box-open', '/images/categories/cat_other.png', 20);
INSERT INTO `categories` VALUES (34, 33, 'Đồ gia dụng', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (35, 33, 'Nhà cửa & Đời sống', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (36, 33, 'Thể thao & Du lịch', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (37, 33, 'Sản phẩm khác', NULL, NULL, NULL, NULL, 0);
INSERT INTO `categories` VALUES (38, NULL, 'Điện thoại', NULL, NULL, 'fa-mobile-screen', '/images/categories/cat_phone.png', 4);
INSERT INTO `categories` VALUES (39, NULL, 'Laptop', NULL, NULL, 'fa-laptop', '/images/categories/cat_laptop.png', 5);
INSERT INTO `categories` VALUES (40, NULL, 'Máy ảnh', NULL, NULL, 'fa-camera', '/images/categories/cat_camera.png', 6);
INSERT INTO `categories` VALUES (41, NULL, 'Đồng hồ', NULL, NULL, 'fa-clock', '/images/categories/cat_watch.png', 8);
INSERT INTO `categories` VALUES (42, NULL, 'Giày dép', NULL, NULL, 'fa-shoe-prints', '/images/categories/cat_shoes.png', 10);
INSERT INTO `categories` VALUES (43, NULL, 'Túi xách & Ví', NULL, NULL, 'fa-bag-shopping', '/images/categories/cat_bag.png', 11);
INSERT INTO `categories` VALUES (44, NULL, 'Nhà cửa & Đời sống', NULL, NULL, 'fa-house', '/images/categories/cat_home.png', 16);
INSERT INTO `categories` VALUES (45, NULL, 'Thể thao & Du lịch', NULL, NULL, 'fa-dumbbell', '/images/categories/cat_sport.png', 15);
INSERT INTO `categories` VALUES (46, NULL, 'Sắc đẹp', NULL, NULL, 'fa-spa', '/images/categories/cat_beauty.png', 13);
INSERT INTO `categories` VALUES (47, NULL, 'Sức khỏe', NULL, NULL, 'fa-heart-pulse', '/images/categories/cat_health.png', 14);
INSERT INTO `categories` VALUES (48, NULL, 'Mẹ & Bé', NULL, NULL, 'fa-baby', '/images/categories/cat_mom_baby.png', 17);
INSERT INTO `categories` VALUES (49, NULL, 'Xe cộ', NULL, NULL, 'fa-motorcycle', '/images/categories/cat_vehicle.png', 18);
INSERT INTO `categories` VALUES (50, NULL, 'Thú cưng', NULL, NULL, 'fa-paw', '/images/categories/cat_pet.png', 19);
INSERT INTO `categories` VALUES (51, NULL, 'Âm thanh', NULL, NULL, 'fa-headphones', '/images/categories/cat_audio.png', 7);

-- ----------------------------
-- Table structure for escrow_holds
-- ----------------------------
DROP TABLE IF EXISTS `escrow_holds`;
CREATE TABLE `escrow_holds`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `seller_id` int NOT NULL,
  `amount` decimal(15, 2) NOT NULL COMMENT 'Tổng số tiền giữ',
  `platform_fee` decimal(15, 2) NULL DEFAULT 0.00 COMMENT 'Phí sàn (nếu có)',
  `seller_amount` decimal(15, 2) NOT NULL COMMENT 'Số tiền seller nhận (amount - fee)',
  `status` enum('holding','released','refunded','disputed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'holding',
  `held_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm bắt đầu giữ',
  `release_scheduled_at` timestamp NULL DEFAULT NULL COMMENT 'Ngày dự kiến giải ngân',
  `released_at` timestamp NULL DEFAULT NULL COMMENT 'Ngày thực tế giải ngân',
  `release_notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'Ghi chú khi giải ngân/hoàn tiền',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `order_id`(`order_id` ASC) USING BTREE,
  INDEX `idx_seller_id`(`seller_id` ASC) USING BTREE,
  INDEX `idx_status`(`status` ASC) USING BTREE,
  INDEX `idx_release_scheduled`(`release_scheduled_at` ASC) USING BTREE,
  INDEX `idx_held_at`(`held_at` ASC) USING BTREE,
  CONSTRAINT `fk_escrow_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `fk_escrow_seller` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'Quản lý tiền escrow (giữ lại)' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of escrow_holds
-- ----------------------------

-- ----------------------------
-- Table structure for favorites
-- ----------------------------
DROP TABLE IF EXISTS `favorites`;
CREATE TABLE `favorites`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  INDEX `product_id`(`product_id` ASC) USING BTREE,
  CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of favorites
-- ----------------------------
INSERT INTO `favorites` VALUES (1, 2, 5, '2025-12-26 14:57:03');
INSERT INTO `favorites` VALUES (2, 3, 8, '2025-12-26 14:57:03');
INSERT INTO `favorites` VALUES (3, 4, 11, '2025-12-26 14:57:03');
INSERT INTO `favorites` VALUES (4, 5, 1, '2025-12-26 14:57:03');

-- ----------------------------
-- Table structure for follows
-- ----------------------------
DROP TABLE IF EXISTS `follows`;
CREATE TABLE `follows`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `follower_id` int NOT NULL COMMENT 'User đang theo dõi',
  `following_id` int NOT NULL COMMENT 'User được theo dõi',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `unique_follow`(`follower_id` ASC, `following_id` ASC) USING BTREE,
  INDEX `idx_follower`(`follower_id` ASC) USING BTREE,
  INDEX `idx_following`(`following_id` ASC) USING BTREE,
  CONSTRAINT `follows_ibfk_1` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT `follows_ibfk_2` FOREIGN KEY (`following_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of follows
-- ----------------------------

-- ----------------------------
-- Table structure for interactions
-- ----------------------------
DROP TABLE IF EXISTS `interactions`;
CREATE TABLE `interactions`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `product_id` int NOT NULL,
  `interaction_type` enum('view','click') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `score` int NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  INDEX `product_id`(`product_id` ASC) USING BTREE,
  CONSTRAINT `interactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `interactions_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of interactions
-- ----------------------------
INSERT INTO `interactions` VALUES (1, 2, 1, 'view', 3, '2025-12-26 14:57:04');
INSERT INTO `interactions` VALUES (2, 2, 2, 'click', 5, '2025-12-26 14:57:04');
INSERT INTO `interactions` VALUES (3, 3, 5, 'view', 2, '2025-12-26 14:57:04');
INSERT INTO `interactions` VALUES (4, 3, 8, 'click', 7, '2025-12-26 14:57:04');
INSERT INTO `interactions` VALUES (5, 4, 4, 'view', 1, '2025-12-26 14:57:04');
INSERT INTO `interactions` VALUES (6, 4, 11, 'click', 10, '2025-12-26 14:57:04');
INSERT INTO `interactions` VALUES (7, 5, 1, 'view', 4, '2025-12-26 14:57:04');
INSERT INTO `interactions` VALUES (8, 5, 7, 'click', 6, '2025-12-26 14:57:04');

-- ----------------------------
-- Table structure for message_attachments
-- ----------------------------
DROP TABLE IF EXISTS `message_attachments`;
CREATE TABLE `message_attachments`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `message_id` int NOT NULL,
  `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` int NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_message_id`(`message_id` ASC) USING BTREE,
  CONSTRAINT `message_attachments_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of message_attachments
-- ----------------------------
INSERT INTO `message_attachments` VALUES (1, 11, '2.jpg', '/uploads/chat/chat_24_1767454462_695936fe21619.jpg', 'image/jpeg', 225291, '2026-01-03 22:34:22');
INSERT INTO `message_attachments` VALUES (2, 12, 'limit.png', '/uploads/chat/chat_24_1767455991_69593cf76b13f.png', 'image/png', 30205, '2026-01-03 22:59:51');

-- ----------------------------
-- Table structure for messages
-- ----------------------------
DROP TABLE IF EXISTS `messages`;
CREATE TABLE `messages`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NULL DEFAULT 0,
  `has_attachment` tinyint(1) NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sender_id`(`sender_id` ASC) USING BTREE,
  INDEX `receiver_id`(`receiver_id` ASC) USING BTREE,
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of messages
-- ----------------------------
INSERT INTO `messages` VALUES (1, 3, 2, 'Chào bạn, sách C++ còn không?', 1, 0, '2025-12-26 14:57:03');
INSERT INTO `messages` VALUES (2, 2, 3, 'Còn bạn nhé! Bạn lấy khi nào?', 1, 0, '2025-12-26 14:57:03');
INSERT INTO `messages` VALUES (3, 3, 2, 'Chiều nay mình qua nhận được không?', 0, 0, '2025-12-26 14:57:03');
INSERT INTO `messages` VALUES (4, 4, 5, 'Tai nghe còn bảo hành không bạn?', 1, 0, '2025-12-26 14:57:03');
INSERT INTO `messages` VALUES (5, 5, 4, 'Còn 18 tháng nha, hộp mất rồi.', 0, 0, '2025-12-26 14:57:03');
INSERT INTO `messages` VALUES (6, 24, 5, 'alo', 0, 0, '2026-01-03 21:40:07');
INSERT INTO `messages` VALUES (7, 24, 2, 'alo', 0, 0, '2026-01-03 22:05:00');
INSERT INTO `messages` VALUES (8, 24, 2, '123', 0, 0, '2026-01-03 22:19:24');
INSERT INTO `messages` VALUES (9, 24, 2, 'alo', 0, 0, '2026-01-03 22:34:12');
INSERT INTO `messages` VALUES (10, 24, 2, '🤭', 0, 0, '2026-01-03 22:34:17');
INSERT INTO `messages` VALUES (11, 24, 2, '[File đính kèm]', 0, 1, '2026-01-03 22:34:22');
INSERT INTO `messages` VALUES (12, 24, 2, '[File đính kèm]', 0, 1, '2026-01-03 22:59:51');
INSERT INTO `messages` VALUES (13, 24, 2, '🤪', 0, 0, '2026-01-03 23:03:15');
INSERT INTO `messages` VALUES (14, 11, 5, 'sad', 0, 0, '2026-01-04 01:46:29');
INSERT INTO `messages` VALUES (15, 11, 5, '😀', 0, 0, '2026-01-04 01:49:19');
INSERT INTO `messages` VALUES (16, 11, 5, 'hello ae👌', 0, 0, '2026-01-04 01:49:24');
INSERT INTO `messages` VALUES (18, 11, 5, 'chao', 0, 0, '2026-01-09 01:52:35');
INSERT INTO `messages` VALUES (19, 11, 5, '13', 0, 0, '2026-01-09 14:26:44');
INSERT INTO `messages` VALUES (20, 11, 5, '11', 0, 0, '2026-01-16 14:49:18');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL DEFAULT 1,
  `executed_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `filename`(`filename` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 91 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '001_create_base_tables.sql', 1, '2025-12-30 00:53:50');
INSERT INTO `migrations` VALUES (2, '002_create_products_table.sql', 1, '2025-12-30 00:53:50');
INSERT INTO `migrations` VALUES (3, '003_create_orders_tables.sql', 1, '2025-12-30 00:53:50');
INSERT INTO `migrations` VALUES (4, '004_create_social_tables.sql', 1, '2025-12-30 00:53:50');
INSERT INTO `migrations` VALUES (5, '005_create_system_tables.sql', 1, '2025-12-30 00:53:50');
INSERT INTO `migrations` VALUES (6, '006_create_search_keywords.sql', 1, '2025-12-30 00:53:50');
INSERT INTO `migrations` VALUES (7, '007_add_quantity_if_missing.sql', 1, '2025-12-30 01:02:41');
INSERT INTO `migrations` VALUES (8, '008_seed_categories_data.sql', 1, '2025-12-30 18:01:46');
INSERT INTO `migrations` VALUES (9, '009_correct_category_images.sql', 1, '2025-12-30 18:27:51');
INSERT INTO `migrations` VALUES (10, '010_update_renamed_category_images.sql', 1, '2025-12-30 18:28:42');
INSERT INTO `migrations` VALUES (11, '011_fix_password_hash.sql', 1, '2025-12-30 21:10:00');
INSERT INTO `migrations` VALUES (12, '012_reset_users_with_correct_hash.sql', 1, '2025-12-30 21:18:26');
INSERT INTO `migrations` VALUES (13, '013_fix_password_final.sql', 1, '2025-12-30 21:23:23');
INSERT INTO `migrations` VALUES (14, '014_seed_admin.php', 1, '2025-12-30 21:45:52');
INSERT INTO `migrations` VALUES (15, '015_create_carts_table.sql', 1, '2025-12-30 21:45:52');
INSERT INTO `migrations` VALUES (16, '016_remove_major_id_from_users.sql', 1, '2025-12-31 09:58:58');
INSERT INTO `migrations` VALUES (17, '018_seed_popular_keywords.php', 1, '2025-12-31 10:07:15');
INSERT INTO `migrations` VALUES (18, '017_update_user_roles.sql', 1, '2025-12-31 15:43:03');
INSERT INTO `migrations` VALUES (19, '018_seed_new_users.sql', 1, '2025-12-31 15:43:03');
INSERT INTO `migrations` VALUES (20, '019_add_email_verification_columns.sql', 1, '2025-12-31 22:42:23');
INSERT INTO `migrations` VALUES (21, '020_fix_token_column_length.sql', 1, '2025-12-31 22:49:33');
INSERT INTO `migrations` VALUES (22, '015_add_is_locked_to_users.php', 1, '2026-01-01 23:56:05');
INSERT INTO `migrations` VALUES (24, '016_create_settings_table.php', 1, '2026-01-02 21:56:19');
INSERT INTO `migrations` VALUES (25, '021_create_settings_table.sql', 1, '2026-01-03 17:20:14');
INSERT INTO `migrations` VALUES (26, '021_update_product_images.sql', 1, '2026-01-03 17:20:15');
INSERT INTO `migrations` VALUES (27, '022_add_balance_and_avatar_to_users.sql', 1, '2026-01-03 17:25:24');
INSERT INTO `migrations` VALUES (28, '023_create_message_attachments.php', 1, '2026-01-03 22:29:42');
INSERT INTO `migrations` VALUES (29, '024_add_last_seen_to_users.php', 1, '2026-01-03 22:42:26');
INSERT INTO `migrations` VALUES (30, '021_add_balance_avatar_to_users.php', 1, '2026-01-12 14:31:55');
INSERT INTO `migrations` VALUES (31, '025_create_follows_table.sql', 1, '2026-01-12 14:31:55');
INSERT INTO `migrations` VALUES (32, '025_update_categories_structure_and_seed.php', 1, '2026-01-12 14:31:55');
INSERT INTO `migrations` VALUES (33, '026_add_gender_to_users.sql', 1, '2026-01-12 14:31:55');
INSERT INTO `migrations` VALUES (34, '026_add_image_to_categories.php', 1, '2026-01-12 14:31:55');
INSERT INTO `migrations` VALUES (35, '026_update_book_category_image.php', 1, '2026-01-12 14:31:55');
INSERT INTO `migrations` VALUES (36, '027_add_description_to_categories.php', 1, '2026-01-12 14:31:55');
INSERT INTO `migrations` VALUES (37, '028_update_all_category_images.php', 1, '2026-01-12 14:31:55');
INSERT INTO `migrations` VALUES (38, '029_add_more_parent_categories.php', 1, '2026-01-12 14:31:55');
INSERT INTO `migrations` VALUES (39, '20260113_230000_add_ghn_columns_to_orders.php', 1, '2026-01-14 00:39:38');
INSERT INTO `migrations` VALUES (40, '001_create_base_tables.php', 2, '2026-01-14 14:40:12');
INSERT INTO `migrations` VALUES (41, '002_create_products_table.php', 2, '2026-01-14 14:40:12');
INSERT INTO `migrations` VALUES (42, '003_create_orders_tables.php', 2, '2026-01-14 14:40:12');
INSERT INTO `migrations` VALUES (43, '004_create_social_tables.php', 2, '2026-01-14 14:40:12');
INSERT INTO `migrations` VALUES (44, '005_create_system_tables.php', 2, '2026-01-14 14:40:12');
INSERT INTO `migrations` VALUES (45, '006_create_search_keywords.php', 2, '2026-01-14 14:40:12');
INSERT INTO `migrations` VALUES (46, '007_add_quantity_if_missing.php', 2, '2026-01-14 14:40:12');
INSERT INTO `migrations` VALUES (47, '008_seed_categories_data.php', 2, '2026-01-14 14:40:12');
INSERT INTO `migrations` VALUES (48, '009_correct_category_images.php', 2, '2026-01-14 14:40:12');
INSERT INTO `migrations` VALUES (49, '010_update_renamed_category_images.php', 2, '2026-01-14 14:40:12');
INSERT INTO `migrations` VALUES (50, '011_fix_password_hash.php', 2, '2026-01-14 14:40:12');
INSERT INTO `migrations` VALUES (51, '012_reset_users_with_correct_hash.php', 2, '2026-01-14 14:40:12');
INSERT INTO `migrations` VALUES (52, '013_fix_password_final.php', 2, '2026-01-14 14:40:12');
INSERT INTO `migrations` VALUES (53, '015_create_carts_table.php', 2, '2026-01-14 14:40:12');
INSERT INTO `migrations` VALUES (54, '017_update_user_roles.php', 2, '2026-01-14 14:40:12');
INSERT INTO `migrations` VALUES (55, '018_seed_new_users.php', 2, '2026-01-14 14:40:13');
INSERT INTO `migrations` VALUES (56, '019_add_email_verification_columns.php', 2, '2026-01-14 14:40:13');
INSERT INTO `migrations` VALUES (57, '020_fix_token_column_length.php', 2, '2026-01-14 14:40:13');
INSERT INTO `migrations` VALUES (58, '025_create_follows_table.php', 2, '2026-01-14 14:40:13');
INSERT INTO `migrations` VALUES (59, '026_add_gender_to_users.php', 2, '2026-01-14 14:40:13');
INSERT INTO `migrations` VALUES (60, '030_reorder_categories.php', 2, '2026-01-14 14:40:13');
INSERT INTO `migrations` VALUES (61, '031_fix_duplicate_category_images.php', 2, '2026-01-14 14:40:13');
INSERT INTO `migrations` VALUES (62, '032_update_audio_image.php', 2, '2026-01-14 14:40:13');
INSERT INTO `migrations` VALUES (63, '033_sync_db_schema.php', 2, '2026-01-14 14:40:13');
INSERT INTO `migrations` VALUES (64, '034_create_product_images_table.php', 2, '2026-01-14 14:40:13');
INSERT INTO `migrations` VALUES (65, '035_create_follows_table.php', 2, '2026-01-14 14:40:13');
INSERT INTO `migrations` VALUES (66, '036_create_user_addresses_table.php', 2, '2026-01-14 14:40:13');
INSERT INTO `migrations` VALUES (67, '037_add_shipping_columns_to_orders.php', 2, '2026-01-14 14:40:14');
INSERT INTO `migrations` VALUES (68, '20260107_190000_add_payment_columns_to_orders.php', 2, '2026-01-14 14:40:14');
INSERT INTO `migrations` VALUES (69, '20260107_190100_create_payment_transactions_table.php', 2, '2026-01-14 14:40:14');
INSERT INTO `migrations` VALUES (70, '20260107_190200_create_wallets_table.php', 2, '2026-01-14 14:40:14');
INSERT INTO `migrations` VALUES (71, '20260107_190300_create_wallet_transactions_table.php', 2, '2026-01-14 14:40:14');
INSERT INTO `migrations` VALUES (72, '20260107_190400_create_escrow_holds_table.php', 2, '2026-01-14 14:40:14');
INSERT INTO `migrations` VALUES (73, '20260107_215000_add_pending_payment_status.php', 2, '2026-01-14 14:40:14');
INSERT INTO `migrations` VALUES (74, '20260110_000000_add_cancel_reason_to_orders.php', 2, '2026-01-14 14:40:14');
INSERT INTO `migrations` VALUES (75, '20260114_175000_fix_product_image_paths.php', 3, '2026-01-14 21:47:45');
INSERT INTO `migrations` VALUES (76, '20260114_210000_add_ghn_columns_to_user_addresses.php', 3, '2026-01-14 21:47:45');
INSERT INTO `migrations` VALUES (77, '038_add_product_indexes.php', 4, '2026-01-15 14:34:35');
INSERT INTO `migrations` VALUES (82, '20260114_111500_increase_product_price_length.php', 5, '2026-01-20 11:59:16');
INSERT INTO `migrations` VALUES (83, '20260114_145500_add_missing_order_columns.php', 5, '2026-01-20 11:59:17');
INSERT INTO `migrations` VALUES (84, '20260118_090000_add_resolved_at_to_reports.php', 5, '2026-01-20 11:59:17');
INSERT INTO `migrations` VALUES (85, '20260120_000000_add_view_count_to_products.php', 5, '2026-01-20 11:59:18');
INSERT INTO `migrations` VALUES (86, '20260120_000001_add_platform_fee_to_orders.php', 6, '2026-01-20 15:22:16');
INSERT INTO `migrations` VALUES (87, '041_add_phone_verified_to_users.php', 7, '2026-01-21 16:20:42');
INSERT INTO `migrations` VALUES (88, '20260120_000002_add_resolved_at_to_reports.php', 7, '2026-01-21 16:20:42');
INSERT INTO `migrations` VALUES (89, '20260123_124200_add_poor_condition_to_products.php', 8, '2026-01-23 12:42:46');
INSERT INTO `migrations` VALUES (90, '20260123_143000_rename_condition_to_product_condition.php', 9, '2026-01-23 14:36:01');

-- ----------------------------
-- Table structure for notifications
-- ----------------------------
DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id` ASC) USING BTREE,
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of notifications
-- ----------------------------
INSERT INTO `notifications` VALUES (1, 2, 'Sản phẩm \"Giáo trình C++\" của bạn đã được mua!', 1, '2025-12-26 14:57:04');
INSERT INTO `notifications` VALUES (2, 3, 'Bạn có tin nhắn mới từ Trần Thị Lan', 0, '2025-12-26 14:57:04');
INSERT INTO `notifications` VALUES (3, 5, 'Đơn hàng #2 đang được giao', 0, '2025-12-26 14:57:04');

-- ----------------------------
-- Table structure for order_details
-- ----------------------------
DROP TABLE IF EXISTS `order_details`;
CREATE TABLE `order_details`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price_at_purchase` decimal(10, 2) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `order_id`(`order_id` ASC) USING BTREE,
  INDEX `product_id`(`product_id` ASC) USING BTREE,
  CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 31 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of order_details
-- ----------------------------
INSERT INTO `order_details` VALUES (1, 1, 1, 1, 85000.00);
INSERT INTO `order_details` VALUES (2, 2, 5, 1, 4500000.00);
INSERT INTO `order_details` VALUES (3, 3, 6, 1, 80000.00);
INSERT INTO `order_details` VALUES (4, 4, 1, 1, 85000.00);
INSERT INTO `order_details` VALUES (5, 5, 1, 1, 85000.00);
INSERT INTO `order_details` VALUES (6, 6, 5, 1, 4500000.00);
INSERT INTO `order_details` VALUES (7, 7, 5, 1, 4500000.00);
INSERT INTO `order_details` VALUES (8, 8, 1, 1, 85000.00);
INSERT INTO `order_details` VALUES (9, 9, 1, 1, 85000.00);
INSERT INTO `order_details` VALUES (10, 10, 1, 1, 85000.00);
INSERT INTO `order_details` VALUES (11, 11, 5, 1, 4500000.00);
INSERT INTO `order_details` VALUES (12, 12, 5, 1, 4500000.00);
INSERT INTO `order_details` VALUES (13, 13, 5, 1, 4500000.00);
INSERT INTO `order_details` VALUES (14, 14, 1, 1, 85000.00);
INSERT INTO `order_details` VALUES (15, 15, 2, 1, 120000.00);
INSERT INTO `order_details` VALUES (16, 16, 3, 1, 150000.00);
INSERT INTO `order_details` VALUES (17, 17, 4, 1, 250000.00);
INSERT INTO `order_details` VALUES (18, 18, 5, 1, 4500000.00);
INSERT INTO `order_details` VALUES (19, 19, 1, 1, 85000.00);
INSERT INTO `order_details` VALUES (20, 20, 1, 1, 85000.00);
INSERT INTO `order_details` VALUES (21, 21, 5, 1, 4500000.00);
INSERT INTO `order_details` VALUES (22, 22, 14, 1, 300000.00);
INSERT INTO `order_details` VALUES (23, 23, 5, 1, 4500000.00);
INSERT INTO `order_details` VALUES (24, 25, 5, 1, 4500000.00);
INSERT INTO `order_details` VALUES (25, 26, 5, 1, 4500000.00);
INSERT INTO `order_details` VALUES (26, 27, 2, 1, 120000.00);
INSERT INTO `order_details` VALUES (27, 28, 5, 1, 4500000.00);
INSERT INTO `order_details` VALUES (28, 29, 14, 1, 300000.00);
INSERT INTO `order_details` VALUES (29, 30, 16, 1, 2000.00);
INSERT INTO `order_details` VALUES (30, 31, 14, 1, 300000.00);

-- ----------------------------
-- Table structure for orders
-- ----------------------------
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `buyer_id` int NOT NULL COMMENT 'Người mua',
  `seller_id` int NOT NULL COMMENT 'Người bán - Thêm cái này vào cho dễ code',
  `total_amount` decimal(10, 2) NOT NULL,
  `platform_fee` decimal(15, 2) NOT NULL DEFAULT 0.00 COMMENT 'Phí sàn (VND)',
  `seller_amount` decimal(15, 2) NOT NULL DEFAULT 0.00 COMMENT 'Tiền seller nhận sau phí (VND)',
  `shipping_address_id` int NULL DEFAULT NULL,
  `shipping_address_snapshot` json NULL,
  `shipping_fee` decimal(10, 2) NULL DEFAULT 0.00,
  `shipping_note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('pending','pending_payment','paid','confirmed','shipping','received','completed','cancelled','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_method` enum('cod','bank_transfer','payos') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'cod',
  `payment_status` enum('pending','paid','failed','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'pending',
  `payos_order_code` bigint UNSIGNED NULL DEFAULT NULL,
  `shipping_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'Địa chỉ giao hàng đầy đủ',
  `shipping_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Số điện thoại người nhận',
  `shipping_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Tên người nhận hàng',
  `note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'Ghi chú của người mua',
  `payment_link_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `received_at` timestamp NULL DEFAULT NULL,
  `escrow_release_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `trial_days` tinyint UNSIGNED NULL DEFAULT 7,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `cancel_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ghn_order_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Mã vận đơn GHN',
  `ghn_sort_code` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Mã phân loại kho GHN',
  `ghn_expected_delivery` datetime NULL DEFAULT NULL COMMENT 'Ngày giao dự kiến',
  `ghn_shipping_fee` int UNSIGNED NULL DEFAULT 0 COMMENT 'Phí ship GHN',
  `ghn_status` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Trạng thái GHN',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `buyer_id`(`buyer_id` ASC) USING BTREE,
  INDEX `seller_id`(`seller_id` ASC) USING BTREE,
  INDEX `idx_payment_link_id`(`payment_link_id` ASC) USING BTREE,
  INDEX `idx_escrow_release`(`escrow_release_at` ASC) USING BTREE,
  INDEX `idx_orders_ghn_order_code`(`ghn_order_code` ASC) USING BTREE,
  INDEX `idx_shipping_address`(`shipping_address_id` ASC) USING BTREE,
  INDEX `idx_payos_order_code`(`payos_order_code` ASC) USING BTREE,
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 32 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of orders
-- ----------------------------
INSERT INTO `orders` VALUES (1, 3, 2, 85000.00, 4250.00, 80750.00, NULL, NULL, 0.00, NULL, 'completed', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, '2025-12-26 14:57:03', NULL, NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (2, 4, 5, 4500000.00, 225000.00, 4275000.00, NULL, NULL, 0.00, NULL, 'shipping', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, '2025-12-26 14:57:03', NULL, NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (3, 5, 3, 80000.00, 4000.00, 76000.00, NULL, NULL, 0.00, NULL, 'completed', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, '2025-12-26 14:57:03', NULL, NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (4, 11, 2, 85000.00, 4250.00, 80750.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, '2026-01-04 23:03:48', 'not_needed', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (5, 11, 2, 85000.00, 4250.00, 80750.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2026-01-07 20:59:08', 'not_needed', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (6, 11, 5, 4500000.00, 225000.00, 4275000.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2026-01-07 21:08:32', 'not_needed', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (7, 11, 5, 4500000.00, 225000.00, 4275000.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2026-01-07 21:13:05', 'change_mind', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (8, 11, 2, 85000.00, 4250.00, 80750.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2026-01-07 21:17:53', 'change_product', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (9, 11, 2, 85000.00, 4250.00, 80750.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2026-01-07 21:21:16', 'not_needed', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (10, 11, 2, 85000.00, 4250.00, 80750.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2026-01-07 21:24:27', 'too_long', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (11, 11, 5, 4500000.00, 225000.00, 4275000.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2026-01-07 21:31:53', 'not_needed', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (12, 11, 5, 4500000.00, 225000.00, 4275000.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2026-01-07 21:33:29', 'not_needed', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (13, 11, 5, 4500000.00, 225000.00, 4275000.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2026-01-07 21:40:50', 'too_long', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (14, 11, 2, 85000.00, 4250.00, 80750.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2026-01-07 21:42:55', 'not_needed', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (15, 11, 3, 120000.00, 6000.00, 114000.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 3, '2026-01-07 21:46:03', 'too_long', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (16, 11, 4, 150000.00, 7500.00, 142500.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, '2507e0ab84a84ab581104affb61e5926', NULL, NULL, NULL, NULL, 3, '2026-01-07 21:48:52', 'not_needed', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (17, 11, 2, 250000.00, 12500.00, 237500.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, '01d88d6da8584037b0aab472720a1ea6', NULL, NULL, NULL, NULL, 3, '2026-01-07 21:55:41', 'not_needed', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (18, 11, 5, 4500000.00, 225000.00, 4275000.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, '3b797b10b5654edb9027f379b7bda6d9', NULL, NULL, NULL, NULL, 3, '2026-01-07 22:13:05', 'not_needed', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (19, 11, 2, 85000.00, 4250.00, 80750.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, '975d9b7d358a40b593a6ec5a662b1f0f', NULL, NULL, NULL, NULL, 3, '2026-01-07 23:30:25', 'other', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (20, 11, 2, 85000.00, 4250.00, 80750.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, 'e6996419c631438c86a7a37d5b380a9d', NULL, NULL, NULL, NULL, 3, '2026-01-08 00:26:26', 'change_mind', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (21, 11, 5, 4500000.00, 225000.00, 4275000.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, '23d98d4c40714ab9b951e9e1372115e1', NULL, NULL, NULL, NULL, 3, '2026-01-08 18:35:13', 'change_product', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (22, 11, 3, 300000.00, 15000.00, 285000.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, '1f422cbbb94f432592b7c046679266eb', NULL, NULL, NULL, NULL, 3, '2026-01-09 14:19:26', 'not_needed', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (23, 11, 5, 4500000.00, 225000.00, 4275000.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, 'b7394ee1cd6b45b28ea7a41df4b4713d', NULL, NULL, NULL, NULL, 3, '2026-01-09 16:28:01', 'not_needed', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (24, 11, 5, 4500000.00, 225000.00, 4275000.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, '2026-01-12 14:34:34', 'too_long', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (25, 11, 5, 4500000.00, 225000.00, 4275000.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 7, '2026-01-12 14:36:12', 'too_long', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (26, 11, 5, 4500000.00, 225000.00, 4275000.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, '51f54cb54cf94847ad9934e0270b1d65', NULL, NULL, NULL, NULL, 7, '2026-01-12 14:37:38', 'not_needed', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (27, 11, 3, 120000.00, 6000.00, 114000.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, '3abb9e93f56c416eab7529cc6bf71ee9', NULL, NULL, NULL, NULL, 7, '2026-01-12 14:42:37', 'change_product', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (28, 11, 5, 4500000.00, 225000.00, 4275000.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, '6353a7e0fbad47d696583eb73e538439', NULL, NULL, NULL, NULL, 7, '2026-01-12 14:45:05', 'too_long', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (29, 11, 3, 300000.00, 15000.00, 285000.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, 'f21b3ba36ba8413a9c0c3748c6c5af70', NULL, NULL, NULL, NULL, 7, '2026-01-12 14:45:45', 'not_needed', NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (30, 27, 11, 2000.00, 100.00, 1900.00, NULL, NULL, 0.00, NULL, 'completed', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, '9aac8677b4a949f9aa920b3c0626bdd5', '2026-01-12 14:50:32', NULL, NULL, NULL, 7, '2026-01-12 14:49:10', NULL, NULL, NULL, NULL, 0, NULL);
INSERT INTO `orders` VALUES (31, 11, 3, 300000.00, 15000.00, 285000.00, NULL, NULL, 0.00, NULL, 'cancelled', 'cod', 'pending', NULL, NULL, NULL, NULL, NULL, '27eb95f9ca1b4a379141a1712340fd47', NULL, NULL, NULL, NULL, 7, '2026-01-13 21:22:11', 'too_long', NULL, NULL, NULL, 0, NULL);

-- ----------------------------
-- Table structure for payment_transactions
-- ----------------------------
DROP TABLE IF EXISTS `payment_transactions`;
CREATE TABLE `payment_transactions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `transaction_type` enum('payment','escrow_hold','escrow_release','refund') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15, 2) NOT NULL,
  `payment_link_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'ID link thanh toán từ PayOS',
  `payos_transaction_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Mã giao dịch từ PayOS',
  `payos_reference` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Reference number từ ngân hàng',
  `payos_order_code` bigint UNSIGNED NULL DEFAULT NULL COMMENT 'Mã đơn hàng gửi cho PayOS',
  `status` enum('pending','processing','success','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'pending',
  `metadata` json NULL COMMENT 'Dữ liệu raw từ PayOS webhook',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_order_id`(`order_id` ASC) USING BTREE,
  INDEX `idx_payment_link_id`(`payment_link_id` ASC) USING BTREE,
  INDEX `idx_payos_order_code`(`payos_order_code` ASC) USING BTREE,
  INDEX `idx_status`(`status` ASC) USING BTREE,
  INDEX `idx_transaction_type`(`transaction_type` ASC) USING BTREE,
  INDEX `idx_created_at`(`created_at` ASC) USING BTREE,
  CONSTRAINT `fk_payment_trans_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'Lịch sử giao dịch thanh toán PayOS' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of payment_transactions
-- ----------------------------
INSERT INTO `payment_transactions` VALUES (1, 16, 'payment', 150000.00, '2507e0ab84a84ab581104affb61e5926', NULL, NULL, 7797332000016, 'pending', '{\"bin\": \"970422\", \"qr_code\": \"00020101021238570010A000000727012700069704220113VQRQAGIOZ53960208QRIBFTTA530370454061500005802VN62080804DH1663047BF5\", \"account_name\": \"DANG TIEN DAT\", \"checkout_url\": \"https://pay.payos.vn/web/2507e0ab84a84ab581104affb61e5926\", \"account_number\": \"VQRQAGIOZ5396\"}', '2026-01-07 21:48:53', NULL);
INSERT INTO `payment_transactions` VALUES (2, 17, 'payment', 250000.00, '01d88d6da8584037b0aab472720a1ea6', NULL, NULL, 7797741000017, 'pending', '{\"bin\": \"970422\", \"qr_code\": \"00020101021238570010A000000727012700069704220113VQRQAGIPA48960208QRIBFTTA530370454062500005802VN62080804DH17630401D3\", \"account_name\": \"DANG TIEN DAT\", \"checkout_url\": \"https://pay.payos.vn/web/01d88d6da8584037b0aab472720a1ea6\", \"account_number\": \"VQRQAGIPA4896\"}', '2026-01-07 21:55:42', NULL);
INSERT INTO `payment_transactions` VALUES (3, 18, 'payment', 4500000.00, '3b797b10b5654edb9027f379b7bda6d9', NULL, NULL, 7798786000018, 'pending', '{\"bin\": \"970422\", \"qr_code\": \"00020101021238570010A000000727012700069704220113VQRQAGIPD97260208QRIBFTTA5303704540745000005802VN62080804DH1863047270\", \"account_name\": \"DANG TIEN DAT\", \"checkout_url\": \"https://pay.payos.vn/web/3b797b10b5654edb9027f379b7bda6d9\", \"account_number\": \"VQRQAGIPD9726\"}', '2026-01-07 22:13:07', NULL);
INSERT INTO `payment_transactions` VALUES (4, 19, 'payment', 85000.00, '975d9b7d358a40b593a6ec5a662b1f0f', NULL, NULL, 7803426000019, 'pending', '{\"bin\": \"970422\", \"qr_code\": \"00020101021238570010A000000727012700069704220113VQRQAGIPI72160208QRIBFTTA53037045405850005802VN62080804DH19630462F6\", \"account_name\": \"DANG TIEN DAT\", \"checkout_url\": \"https://pay.payos.vn/web/975d9b7d358a40b593a6ec5a662b1f0f\", \"account_number\": \"VQRQAGIPI7216\"}', '2026-01-07 23:30:26', NULL);
INSERT INTO `payment_transactions` VALUES (5, 20, 'payment', 85000.00, 'e6996419c631438c86a7a37d5b380a9d', NULL, NULL, 7806786000020, 'pending', '{\"bin\": \"970422\", \"qr_code\": \"00020101021238570010A000000727012700069704220113VQRQAGIPL14240208QRIBFTTA53037045405850005802VN62080804DH206304D9F7\", \"account_name\": \"DANG TIEN DAT\", \"checkout_url\": \"https://pay.payos.vn/web/e6996419c631438c86a7a37d5b380a9d\", \"account_number\": \"VQRQAGIPL1424\"}', '2026-01-08 00:26:27', NULL);
INSERT INTO `payment_transactions` VALUES (6, 21, 'payment', 4500000.00, '732768b21c5145d18da4cd7ee658dbce', NULL, NULL, 7872113000021, 'pending', '{\"bin\": \"970422\", \"qr_code\": \"00020101021238570010A000000727012700069704220113VQRQAGIXP91210208QRIBFTTA5303704540745000005802VN62080804DH2163049F5E\", \"account_name\": \"DANG TIEN DAT\", \"checkout_url\": \"https://pay.payos.vn/web/732768b21c5145d18da4cd7ee658dbce\", \"account_number\": \"VQRQAGIXP9121\"}', '2026-01-08 18:35:14', NULL);
INSERT INTO `payment_transactions` VALUES (7, 21, 'payment', 4500000.00, 'eb25d1c1970d498d9715c5a03689f519', NULL, NULL, 7872928000021, 'pending', '{\"bin\": \"970422\", \"qr_code\": \"00020101021238570010A000000727012700069704220113VQRQAGIXV43040208QRIBFTTA5303704540745000005802VN62080804DH2163046528\", \"account_name\": \"DANG TIEN DAT\", \"checkout_url\": \"https://pay.payos.vn/web/eb25d1c1970d498d9715c5a03689f519\", \"account_number\": \"VQRQAGIXV4304\"}', '2026-01-08 18:48:49', NULL);
INSERT INTO `payment_transactions` VALUES (8, 21, 'payment', 4500000.00, '23d98d4c40714ab9b951e9e1372115e1', NULL, NULL, 7872942000021, 'pending', '{\"bin\": \"970422\", \"qr_code\": \"00020101021238570010A000000727012700069704220113VQRQAGIXT39460208QRIBFTTA5303704540745000005802VN62080804DH216304B253\", \"account_name\": \"DANG TIEN DAT\", \"checkout_url\": \"https://pay.payos.vn/web/23d98d4c40714ab9b951e9e1372115e1\", \"account_number\": \"VQRQAGIXT3946\"}', '2026-01-08 18:49:03', NULL);
INSERT INTO `payment_transactions` VALUES (9, 22, 'payment', 300000.00, '1f422cbbb94f432592b7c046679266eb', NULL, NULL, 7943166000022, 'pending', '{\"bin\": \"970422\", \"qr_code\": \"00020101021238570010A000000727012700069704220113VQRQAGJFH36960208QRIBFTTA530370454063000005802VN62080804DH226304702A\", \"account_name\": \"DANG TIEN DAT\", \"checkout_url\": \"https://pay.payos.vn/web/1f422cbbb94f432592b7c046679266eb\", \"account_number\": \"VQRQAGJFH3696\"}', '2026-01-09 14:19:27', NULL);
INSERT INTO `payment_transactions` VALUES (10, 23, 'payment', 4500000.00, 'b7394ee1cd6b45b28ea7a41df4b4713d', NULL, NULL, 7950881000023, 'pending', '{\"bin\": \"970422\", \"qr_code\": \"00020101021238570010A000000727012700069704220113VQRQAGJGU67540208QRIBFTTA5303704540745000005802VN62080804DH2363047E39\", \"account_name\": \"DANG TIEN DAT\", \"checkout_url\": \"https://pay.payos.vn/web/b7394ee1cd6b45b28ea7a41df4b4713d\", \"account_number\": \"VQRQAGJGU6754\"}', '2026-01-09 16:28:02', NULL);
INSERT INTO `payment_transactions` VALUES (11, 26, 'payment', 4500000.00, '51f54cb54cf94847ad9934e0270b1d65', NULL, NULL, 8203458000026, 'pending', '{\"bin\": \"970422\", \"qr_code\": \"00020101021238570010A000000727012700069704220113VQRQAGKRH34060208QRIBFTTA5303704540745000005802VN62080804DH2663041D16\", \"account_name\": \"DANG TIEN DAT\", \"checkout_url\": \"https://pay.payos.vn/web/51f54cb54cf94847ad9934e0270b1d65\", \"account_number\": \"VQRQAGKRH3406\"}', '2026-01-12 14:37:40', NULL);
INSERT INTO `payment_transactions` VALUES (12, 27, 'payment', 120000.00, '3abb9e93f56c416eab7529cc6bf71ee9', NULL, NULL, 8203757000027, 'pending', '{\"bin\": \"970422\", \"qr_code\": \"00020101021238570010A000000727012700069704220113VQRQAGKRK36600208QRIBFTTA530370454061200005802VN62080804DH276304FE7B\", \"account_name\": \"DANG TIEN DAT\", \"checkout_url\": \"https://pay.payos.vn/web/3abb9e93f56c416eab7529cc6bf71ee9\", \"account_number\": \"VQRQAGKRK3660\"}', '2026-01-12 14:42:38', NULL);
INSERT INTO `payment_transactions` VALUES (13, 28, 'payment', 4500000.00, '6353a7e0fbad47d696583eb73e538439', NULL, NULL, 8203905000028, 'pending', '{\"bin\": \"970422\", \"qr_code\": \"00020101021238570010A000000727012700069704220113VQRQAGKRK80840208QRIBFTTA5303704540745000005802VN62080804DH286304B632\", \"account_name\": \"DANG TIEN DAT\", \"checkout_url\": \"https://pay.payos.vn/web/6353a7e0fbad47d696583eb73e538439\", \"account_number\": \"VQRQAGKRK8084\"}', '2026-01-12 14:45:06', NULL);
INSERT INTO `payment_transactions` VALUES (14, 29, 'payment', 300000.00, 'f21b3ba36ba8413a9c0c3748c6c5af70', NULL, NULL, 8203945000029, 'pending', '{\"bin\": \"970422\", \"qr_code\": \"00020101021238570010A000000727012700069704220113VQRQAGKRK93620208QRIBFTTA530370454063000005802VN62080804DH2963041AED\", \"account_name\": \"DANG TIEN DAT\", \"checkout_url\": \"https://pay.payos.vn/web/f21b3ba36ba8413a9c0c3748c6c5af70\", \"account_number\": \"VQRQAGKRK9362\"}', '2026-01-12 14:45:46', NULL);
INSERT INTO `payment_transactions` VALUES (15, 30, 'payment', 2000.00, '9aac8677b4a949f9aa920b3c0626bdd5', NULL, NULL, 8204150000030, 'pending', '{\"bin\": \"970422\", \"qr_code\": \"00020101021238570010A000000727012700069704220113VQRQAGKRL53480208QRIBFTTA5303704540420005802VN62080804DH306304339F\", \"account_name\": \"DANG TIEN DAT\", \"checkout_url\": \"https://pay.payos.vn/web/9aac8677b4a949f9aa920b3c0626bdd5\", \"account_number\": \"VQRQAGKRL5348\"}', '2026-01-12 14:49:11', NULL);
INSERT INTO `payment_transactions` VALUES (16, 31, 'payment', 300000.00, '27eb95f9ca1b4a379141a1712340fd47', NULL, NULL, 8314131000031, 'pending', '{\"bin\": \"970422\", \"qr_code\": \"00020101021238570010A000000727012700069704220113VQRQAGLLI64700208QRIBFTTA530370454063000005802VN62080804DH316304F4EF\", \"account_name\": \"DANG TIEN DAT\", \"checkout_url\": \"https://pay.payos.vn/web/27eb95f9ca1b4a379141a1712340fd47\", \"account_number\": \"VQRQAGLLI6470\"}', '2026-01-13 21:22:12', NULL);

-- ----------------------------
-- Table structure for product_images
-- ----------------------------
DROP TABLE IF EXISTS `product_images`;
CREATE TABLE `product_images`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Đường dẫn ảnh relative to uploads',
  `is_primary` tinyint(1) NULL DEFAULT 0 COMMENT 'Ảnh chính của sản phẩm',
  `sort_order` int NULL DEFAULT 0 COMMENT 'Thứ tự hiển thị',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product_id`(`product_id` ASC) USING BTREE,
  CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 68 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of product_images
-- ----------------------------
INSERT INTO `product_images` VALUES (1, 1, '/images/products/1.png', 1, 0, '2026-01-05 15:41:26');
INSERT INTO `product_images` VALUES (2, 2, '/images/products/2.png', 1, 0, '2026-01-05 15:41:26');
INSERT INTO `product_images` VALUES (3, 3, '/images/products/3.png', 1, 0, '2026-01-05 15:41:26');
INSERT INTO `product_images` VALUES (4, 4, '/images/products/4.png', 1, 0, '2026-01-05 15:41:26');
INSERT INTO `product_images` VALUES (5, 5, '/images/products/5.png', 1, 0, '2026-01-05 15:41:26');
INSERT INTO `product_images` VALUES (6, 6, '/images/products/6.png', 1, 0, '2026-01-05 15:41:26');
INSERT INTO `product_images` VALUES (7, 7, '/images/products/7.png', 1, 0, '2026-01-05 15:41:26');
INSERT INTO `product_images` VALUES (8, 8, '/images/products/8.png', 1, 0, '2026-01-05 15:41:26');
INSERT INTO `product_images` VALUES (9, 9, '/images/products/9.png', 1, 0, '2026-01-05 15:41:26');
INSERT INTO `product_images` VALUES (10, 10, '/images/products/10.png', 1, 0, '2026-01-05 15:41:26');
INSERT INTO `product_images` VALUES (11, 11, '/images/products/11.png', 1, 0, '2026-01-05 15:41:26');
INSERT INTO `product_images` VALUES (12, 12, '/images/products/12.png', 1, 0, '2026-01-05 15:41:26');
INSERT INTO `product_images` VALUES (13, 13, '/images/products/13.png', 1, 0, '2026-01-05 15:41:26');
INSERT INTO `product_images` VALUES (14, 14, '/images/products/14.png', 1, 0, '2026-01-05 15:41:26');
INSERT INTO `product_images` VALUES (15, 15, 'products/1767542583_image.png', 1, 0, '2026-01-05 15:41:26');
INSERT INTO `product_images` VALUES (16, 1, '/images/products/1.png', 1, 0, '2026-01-05 16:15:30');
INSERT INTO `product_images` VALUES (17, 2, '/images/products/2.png', 1, 0, '2026-01-05 16:15:30');
INSERT INTO `product_images` VALUES (18, 3, '/images/products/3.png', 1, 0, '2026-01-05 16:15:30');
INSERT INTO `product_images` VALUES (19, 4, '/images/products/4.png', 1, 0, '2026-01-05 16:15:30');
INSERT INTO `product_images` VALUES (20, 5, '/images/products/5.png', 1, 0, '2026-01-05 16:15:30');
INSERT INTO `product_images` VALUES (21, 6, '/images/products/6.png', 1, 0, '2026-01-05 16:15:30');
INSERT INTO `product_images` VALUES (22, 7, '/images/products/7.png', 1, 0, '2026-01-05 16:15:30');
INSERT INTO `product_images` VALUES (23, 8, '/images/products/8.png', 1, 0, '2026-01-05 16:15:30');
INSERT INTO `product_images` VALUES (24, 9, '/images/products/9.png', 1, 0, '2026-01-05 16:15:30');
INSERT INTO `product_images` VALUES (25, 10, '/images/products/10.png', 1, 0, '2026-01-05 16:15:30');
INSERT INTO `product_images` VALUES (26, 11, '/images/products/11.png', 1, 0, '2026-01-05 16:15:30');
INSERT INTO `product_images` VALUES (27, 12, '/images/products/12.png', 1, 0, '2026-01-05 16:15:30');
INSERT INTO `product_images` VALUES (28, 13, '/images/products/13.png', 1, 0, '2026-01-05 16:15:30');
INSERT INTO `product_images` VALUES (29, 14, '/images/products/14.png', 1, 0, '2026-01-05 16:15:30');
INSERT INTO `product_images` VALUES (30, 15, 'products/1767542583_image.png', 1, 0, '2026-01-05 16:15:30');
INSERT INTO `product_images` VALUES (31, 1, '/images/products/1.png', 1, 0, '2026-01-05 16:16:00');
INSERT INTO `product_images` VALUES (32, 2, '/images/products/2.png', 1, 0, '2026-01-05 16:16:00');
INSERT INTO `product_images` VALUES (33, 3, '/images/products/3.png', 1, 0, '2026-01-05 16:16:00');
INSERT INTO `product_images` VALUES (34, 4, '/images/products/4.png', 1, 0, '2026-01-05 16:16:00');
INSERT INTO `product_images` VALUES (35, 5, '/images/products/5.png', 1, 0, '2026-01-05 16:16:00');
INSERT INTO `product_images` VALUES (36, 6, '/images/products/6.png', 1, 0, '2026-01-05 16:16:00');
INSERT INTO `product_images` VALUES (37, 7, '/images/products/7.png', 1, 0, '2026-01-05 16:16:00');
INSERT INTO `product_images` VALUES (38, 8, '/images/products/8.png', 1, 0, '2026-01-05 16:16:00');
INSERT INTO `product_images` VALUES (39, 9, '/images/products/9.png', 1, 0, '2026-01-05 16:16:00');
INSERT INTO `product_images` VALUES (40, 10, '/images/products/10.png', 1, 0, '2026-01-05 16:16:00');
INSERT INTO `product_images` VALUES (41, 11, '/images/products/11.png', 1, 0, '2026-01-05 16:16:00');
INSERT INTO `product_images` VALUES (42, 12, '/images/products/12.png', 1, 0, '2026-01-05 16:16:00');
INSERT INTO `product_images` VALUES (43, 13, '/images/products/13.png', 1, 0, '2026-01-05 16:16:00');
INSERT INTO `product_images` VALUES (44, 14, '/images/products/14.png', 1, 0, '2026-01-05 16:16:00');
INSERT INTO `product_images` VALUES (45, 15, 'products/1767542583_image.png', 1, 0, '2026-01-05 16:16:00');
INSERT INTO `product_images` VALUES (46, 16, 'products/1768204062_0_ChatGPT Image 02_51_36 18 thg 6, 2025.png', 1, 0, '2026-01-12 14:47:42');

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `category_id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `price` decimal(15, 2) NOT NULL DEFAULT 0.00,
  `quantity` int NOT NULL DEFAULT 0,
  `view_count` int UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Số lượt xem',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status` enum('active','sold','hidden') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `product_condition` enum('new','like_new','good','fair','poor') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'good',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `category_id`(`category_id` ASC) USING BTREE,
  INDEX `idx_status_created`(`status` ASC, `created_at` DESC) USING BTREE,
  INDEX `idx_status_category`(`status` ASC, `category_id` ASC) USING BTREE,
  INDEX `idx_status_price`(`status` ASC, `price` ASC) USING BTREE,
  INDEX `idx_quantity`(`quantity` ASC) USING BTREE,
  INDEX `idx_user_status`(`user_id` ASC, `status` ASC) USING BTREE,
  INDEX `idx_view_count`(`view_count` ASC) USING BTREE,
  FULLTEXT INDEX `ft_search`(`name`, `description`),
  CONSTRAINT `products_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 30 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES (1, 2, 2, 'Giáo trình Lập trình C++', 'Sách mới 95%, không gạch chú. Phù hợp cho sinh viên năm 1-2 IT.', 85000.00, 9, 3, 'products/product_1.png', 'active', '2025-12-26 14:57:03', 'good');
INSERT INTO `products` VALUES (2, 3, 4, 'Kinh tế vi mô - N. Gregory Mankiw', 'Bản tiếng Việt, đã dùng 1 kỳ, còn mới.', 120000.00, 10, 2, 'products/product_2.png', 'active', '2025-12-26 14:57:03', 'good');
INSERT INTO `products` VALUES (3, 4, 7, 'Oxford Advanced Learner Dictionary', 'Từ điển Anh-Việt bìa cứng, không rách.', 150000.00, 10, 0, 'products/product_3.png', 'active', '2025-12-26 14:57:03', 'good');
INSERT INTO `products` VALUES (4, 2, 12, 'Chuột Logitech G102', 'Dùng 6 tháng, còn nguyên hộp. Bảo hành 18 tháng.', 250000.00, 10, 0, 'products/product_4.png', 'active', '2025-12-26 14:57:03', 'good');
INSERT INTO `products` VALUES (5, 5, 14, 'Tai nghe Sony WH-1000XM4', 'Chống ồn cực tốt, pin 8/10. Không hộp.', 4500000.00, 10, 2, 'products/product_5.png', 'active', '2025-12-26 14:57:03', 'good');
INSERT INTO `products` VALUES (6, 3, 2, 'USB SanDisk 32GB', 'Mới 100%, chưa bóc seal.', 80000.00, 10, 0, 'products/product_6.png', 'sold', '2025-12-26 14:57:03', 'good');
INSERT INTO `products` VALUES (7, 4, 25, 'Áo hoodie Uniqlo màu đen', 'Size M, giặt 2 lần. Form rộng unisex.', 180000.00, 10, 0, 'products/product_7.png', 'active', '2025-12-26 14:57:03', 'good');
INSERT INTO `products` VALUES (8, 5, 32, 'Giày Converse Chuck Taylor', 'Size 40, màu trắng. Mua tháng trước nhưng không vừa.', 550000.00, 10, 1, 'products/product_8.png', 'active', '2025-12-26 14:57:03', 'good');
INSERT INTO `products` VALUES (9, 2, 16, 'Combo 10 bút bi Thiên Long', 'Mực xanh, mới 100%.', 25000.00, 10, 1, 'products/product_9.png', 'active', '2025-12-26 14:57:03', 'good');
INSERT INTO `products` VALUES (10, 3, 19, 'Máy tính Casio FX-580VN X', 'Dùng 1 năm, còn tốt. Có hướng dẫn sử dụng.', 350000.00, 10, 0, 'products/product_10.png', 'active', '2025-12-26 14:57:03', 'good');
INSERT INTO `products` VALUES (11, 4, 20, 'Ba lô The North Face 20L', 'Màu xám, chống nước. Dùng 1 năm nhưng còn mới 90%.', 650000.00, 10, 0, 'products/product_11.png', 'active', '2025-12-26 14:57:03', 'good');
INSERT INTO `products` VALUES (12, 5, 34, 'Bình giữ nhiệt Lock&Lock 500ml', 'Màu hồng pastel, chưa sử dụng.', 120000.00, 10, 0, 'products/product_12.png', 'active', '2025-12-26 14:57:03', 'good');
INSERT INTO `products` VALUES (13, 2, 36, 'Bóng đá Mikasa size 5', 'Dùng tập luyện 3 tháng, còn bơm tốt.', 180000.00, 10, 0, 'products/product_13.png', 'hidden', '2025-12-26 14:57:03', 'good');
INSERT INTO `products` VALUES (14, 3, 36, 'Thảm tập Yoga Nike 6mm', 'Màu xanh dương, có túi đựng. Mua nhầm size.', 300000.00, 10, 0, 'products/product_14.png', 'active', '2025-12-26 14:57:03', 'good');
INSERT INTO `products` VALUES (15, 11, 2, 'Lập trình hướng đối tượng C++', '\n\nTình trạng: Như mới', 50000.00, 1, 5, 'products/product_15.png', 'active', '2026-01-04 23:03:03', 'good');
INSERT INTO `products` VALUES (16, 11, 2, 'Huy Ngu', '\n\nTình trạng: Như mới', 2000.00, 0, 1, 'products/1768204062_0_ChatGPT Image 02_51_36 18 thg 6, 2025.png', 'active', '2026-01-12 14:47:42', 'good');

-- ----------------------------
-- Table structure for reports
-- ----------------------------
DROP TABLE IF EXISTS `reports`;
CREATE TABLE `reports`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `reporter_id` int NOT NULL,
  `product_id` int NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','resolved') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'pending',
  `resolved_at` datetime NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `reporter_id`(`reporter_id` ASC) USING BTREE,
  INDEX `product_id`(`product_id` ASC) USING BTREE,
  CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of reports
-- ----------------------------
INSERT INTO `reports` VALUES (1, 4, 13, 'Sản phẩm không đúng mô tả, nghi ngờ hàng giả', 'resolved', '2026-01-22 22:33:52', '2025-12-26 14:57:04');

-- ----------------------------
-- Table structure for reviews
-- ----------------------------
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `reviewer_id` int NOT NULL,
  `product_id` int NOT NULL,
  `rating` int NULL DEFAULT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `reviewer_id`(`reviewer_id` ASC) USING BTREE,
  INDEX `product_id`(`product_id` ASC) USING BTREE,
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of reviews
-- ----------------------------
INSERT INTO `reviews` VALUES (1, 3, 1, 5, 'Sách đẹp, giao hàng nhanh. Recommend!', '2025-12-26 14:57:03');
INSERT INTO `reviews` VALUES (2, 5, 6, 4, 'USB chạy tốt, đóng gói cẩn thận.', '2025-12-26 14:57:03');

-- ----------------------------
-- Table structure for search_keywords
-- ----------------------------
DROP TABLE IF EXISTS `search_keywords`;
CREATE TABLE `search_keywords`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `search_count` int NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `keyword`(`keyword` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 40 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of search_keywords
-- ----------------------------
INSERT INTO `search_keywords` VALUES (1, 'giáo trình c', 2, '2025-12-29 22:48:38', '2025-12-29 22:55:28');
INSERT INTO `search_keywords` VALUES (2, 'alo', 3, '2025-12-29 22:51:19', '2025-12-29 23:12:26');
INSERT INTO `search_keywords` VALUES (3, 'lập trình', 7, '2025-12-29 22:53:34', '2026-01-15 14:22:15');
INSERT INTO `search_keywords` VALUES (4, 'thảm', 1, '2025-12-29 23:12:35', '2025-12-29 23:12:35');
INSERT INTO `search_keywords` VALUES (5, 'thảm tập', 1, '2025-12-29 23:12:42', '2025-12-29 23:12:42');
INSERT INTO `search_keywords` VALUES (6, 'yoga', 1, '2025-12-29 23:15:16', '2025-12-29 23:15:16');
INSERT INTO `search_keywords` VALUES (7, 'ba lô', 4, '2025-12-29 23:15:53', '2026-01-15 14:35:21');
INSERT INTO `search_keywords` VALUES (8, 'máy tính', 2, '2025-12-30 00:00:33', '2025-12-30 00:06:38');
INSERT INTO `search_keywords` VALUES (9, 'bóng đá', 2, '2025-12-30 00:06:48', '2025-12-30 00:07:16');
INSERT INTO `search_keywords` VALUES (10, 'das', 1, '2025-12-30 21:08:36', '2025-12-30 21:08:36');
INSERT INTO `search_keywords` VALUES (11, 'sục crocs', 151, '2025-12-31 10:07:15', '2026-01-12 14:29:50');
INSERT INTO `search_keywords` VALUES (12, 'áo khoác', 122, '2025-12-31 10:07:15', '2026-01-12 14:29:52');
INSERT INTO `search_keywords` VALUES (13, 'giáo trình c++', 97, '2025-12-31 10:07:15', '2026-01-12 14:29:53');
INSERT INTO `search_keywords` VALUES (14, 'bàn phím cơ', 82, '2025-12-31 10:07:15', '2026-01-12 14:30:10');
INSERT INTO `search_keywords` VALUES (15, 'tai nghe', 66, '2025-12-31 10:07:15', '2026-01-15 14:22:11');
INSERT INTO `search_keywords` VALUES (16, 'sách tiếng anh', 55, '2025-12-31 10:07:15', '2025-12-31 10:07:15');
INSERT INTO `search_keywords` VALUES (17, 'dsa', 1, '2025-12-31 15:23:15', '2025-12-31 15:23:15');
INSERT INTO `search_keywords` VALUES (18, 'converse', 1, '2026-01-11 21:15:02', '2026-01-11 21:15:02');
INSERT INTO `search_keywords` VALUES (19, 'sd', 1, '2026-01-12 14:11:38', '2026-01-12 14:11:38');
INSERT INTO `search_keywords` VALUES (31, 'ihpone', 1, '2026-01-15 14:21:54', '2026-01-15 14:21:54');
INSERT INTO `search_keywords` VALUES (32, 'iphone', 1, '2026-01-15 14:21:58', '2026-01-15 14:21:58');
INSERT INTO `search_keywords` VALUES (35, 'balo', 2, '2026-01-15 14:22:30', '2026-01-15 14:35:14');
INSERT INTO `search_keywords` VALUES (38, 'ba loo', 1, '2026-01-15 14:35:17', '2026-01-15 14:35:17');

-- ----------------------------
-- Table structure for settings
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `setting_key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `setting_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `setting_group` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `setting_key`(`setting_key` ASC) USING BTREE,
  INDEX `idx_setting_key`(`setting_key` ASC) USING BTREE,
  INDEX `idx_setting_group`(`setting_group` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 51 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of settings
-- ----------------------------
INSERT INTO `settings` VALUES (1, 'site_name', 'Zoldify', 'general', '2026-01-02 21:56:19', '2026-01-03 14:43:12');
INSERT INTO `settings` VALUES (2, 'site_description', 'Sàn thương mại điện tử đồ cũ', 'general', '2026-01-02 21:56:19', '2026-01-02 23:27:39');
INSERT INTO `settings` VALUES (3, 'site_logo', '', 'general', '2026-01-02 21:56:19', '2026-01-02 21:56:19');
INSERT INTO `settings` VALUES (4, 'site_favicon', '', 'general', '2026-01-02 21:56:19', '2026-01-02 21:56:19');
INSERT INTO `settings` VALUES (5, 'contact_email', 'admin@zoldify.com', 'contact', '2026-01-02 21:56:19', '2026-01-02 21:56:52');
INSERT INTO `settings` VALUES (6, 'contact_phone', '', 'contact', '2026-01-02 21:56:19', '2026-01-02 21:56:19');
INSERT INTO `settings` VALUES (7, 'contact_address', '', 'contact', '2026-01-02 21:56:19', '2026-01-02 21:56:19');
INSERT INTO `settings` VALUES (8, 'smtp_host', '', 'email', '2026-01-02 21:56:19', '2026-01-02 21:56:19');
INSERT INTO `settings` VALUES (9, 'smtp_port', '587', 'email', '2026-01-02 21:56:19', '2026-01-02 21:56:19');
INSERT INTO `settings` VALUES (10, 'smtp_username', '', 'email', '2026-01-02 21:56:19', '2026-01-02 21:56:19');
INSERT INTO `settings` VALUES (11, 'smtp_password', '', 'email', '2026-01-02 21:56:19', '2026-01-02 21:56:19');
INSERT INTO `settings` VALUES (12, 'smtp_encryption', 'tls', 'email', '2026-01-02 21:56:19', '2026-01-02 21:56:19');
INSERT INTO `settings` VALUES (13, 'mail_from_name', 'Zoldify', 'email', '2026-01-02 21:56:19', '2026-01-02 21:56:55');
INSERT INTO `settings` VALUES (14, 'mail_from_email', '', 'email', '2026-01-02 21:56:19', '2026-01-02 21:56:19');
INSERT INTO `settings` VALUES (15, 'payment_gateway', 'vnpay', 'payment', '2026-01-02 21:56:19', '2026-01-02 23:38:43');
INSERT INTO `settings` VALUES (16, 'payment_api_key', 'superadmin@zoldify.vn', 'payment', '2026-01-02 21:56:19', '2026-01-02 23:38:43');
INSERT INTO `settings` VALUES (17, 'payment_secret_key', 'admin123', 'payment', '2026-01-02 21:56:19', '2026-01-02 23:38:43');
INSERT INTO `settings` VALUES (18, 'social_facebook', 'https://www.facebook.com/Zoldify', 'social', '2026-01-02 21:56:19', '2026-01-03 00:27:49');
INSERT INTO `settings` VALUES (19, 'social_zalo', '', 'social', '2026-01-02 21:56:19', '2026-01-02 21:56:19');
INSERT INTO `settings` VALUES (20, 'social_instagram', '', 'social', '2026-01-02 21:56:19', '2026-01-02 21:56:19');
INSERT INTO `settings` VALUES (21, 'social_youtube', '', 'social', '2026-01-02 21:56:19', '2026-01-02 21:56:19');
INSERT INTO `settings` VALUES (22, 'maintenance_mode', '0', 'maintenance', '2026-01-02 21:56:19', '2026-01-04 01:57:53');
INSERT INTO `settings` VALUES (23, 'maintenance_message', 'Website đang bảo trì, vui lòng quay lại sau.', 'maintenance', '2026-01-02 21:56:19', '2026-01-02 21:56:19');

-- ----------------------------
-- Table structure for transactions
-- ----------------------------
DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions`  (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `type` enum('deposit','withdraw','payment','refund') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15, 2) NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `status` enum('pending','completed','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of transactions
-- ----------------------------
INSERT INTO `transactions` VALUES (1, 19, 'deposit', 10000000.00, 'Nạp tiền vào ví', 'completed', '2026-01-01 16:00:56');
INSERT INTO `transactions` VALUES (2, 19, 'deposit', 10000.00, 'Nạp tiền vào ví', 'completed', '2026-01-01 16:01:18');
INSERT INTO `transactions` VALUES (3, 19, 'deposit', 10000000.00, 'Nạp tiền vào ví', 'completed', '2026-01-02 07:29:24');
INSERT INTO `transactions` VALUES (4, 19, 'deposit', 100000000.00, 'Nạp tiền vào ví', 'completed', '2026-01-02 07:42:23');
INSERT INTO `transactions` VALUES (5, 19, 'deposit', 1000000.00, 'Nạp tiền vào ví', 'completed', '2026-01-03 07:06:10');

-- ----------------------------
-- Table structure for user_addresses
-- ----------------------------
DROP TABLE IF EXISTS `user_addresses`;
CREATE TABLE `user_addresses`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `label` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên gợi nhớ: Nhà riêng, Công ty...',
  `recipient_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên người nhận hàng',
  `phone_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'SĐT người nhận',
  `province` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tỉnh/Thành phố',
  `district` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Quận/Huyện',
  `ward` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Phường/Xã (optional vì một số địa chỉ đặc biệt)',
  `street_address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Số nhà, tên đường, tòa nhà...',
  `full_address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Địa chỉ đầy đủ đã được chuẩn hóa',
  `latitude` decimal(10, 8) NULL DEFAULT NULL COMMENT 'Vĩ độ từ HERE Geocoding',
  `longitude` decimal(11, 8) NULL DEFAULT NULL COMMENT 'Kinh độ từ HERE Geocoding',
  `here_place_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'HERE Place ID để lookup sau',
  `ghn_province_id` int NULL DEFAULT NULL COMMENT 'Mã tỉnh GHN',
  `ghn_district_id` int NULL DEFAULT NULL COMMENT 'Mã quận GHN',
  `ghn_ward_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Mã phường GHN',
  `is_default` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = địa chỉ mặc định',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_user_id`(`user_id` ASC) USING BTREE,
  INDEX `idx_user_default`(`user_id` ASC, `is_default` ASC) USING BTREE,
  INDEX `idx_here_place_id`(`here_place_id` ASC) USING BTREE,
  INDEX `idx_ghn_district`(`ghn_district_id` ASC) USING BTREE,
  CONSTRAINT `fk_user_addresses_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'Bảng lưu địa chỉ giao hàng của users' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_addresses
-- ----------------------------
INSERT INTO `user_addresses` VALUES (1, 1, 'Địa chỉ cũ', 'Nguyễn Văn Admin', '0901234567', 'Chưa cập nhật', 'Chưa cập nhật', NULL, 'Hà Nội', 'Hà Nội', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-01-09 15:44:07', '2026-01-09 15:44:07');
INSERT INTO `user_addresses` VALUES (2, 2, 'Địa chỉ cũ', 'Trần Thị Lan', '0912345678', 'Chưa cập nhật', 'Chưa cập nhật', NULL, 'TP HCM', 'TP HCM', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-01-09 15:44:07', '2026-01-09 15:44:07');
INSERT INTO `user_addresses` VALUES (3, 3, 'Địa chỉ cũ', 'Lê Văn Hùng', '0923456789', 'Chưa cập nhật', 'Chưa cập nhật', NULL, 'Đà Nẵng', 'Đà Nẵng', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-01-09 15:44:07', '2026-01-09 15:44:07');
INSERT INTO `user_addresses` VALUES (4, 4, 'Địa chỉ cũ', 'Phạm Thị Mai', '0934567890', 'Chưa cập nhật', 'Chưa cập nhật', NULL, 'Hải Phòng', 'Hải Phòng', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-01-09 15:44:07', '2026-01-09 15:44:07');
INSERT INTO `user_addresses` VALUES (5, 5, 'Địa chỉ cũ', 'Hoàng Văn Nam', '0945678901', 'Chưa cập nhật', 'Chưa cập nhật', NULL, 'Cần Thơ', 'Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-01-09 15:44:07', '2026-01-09 15:44:07');
INSERT INTO `user_addresses` VALUES (6, 15, 'Địa chỉ cũ', 'Admin Zoldify', '0901234567', 'Chưa cập nhật', 'Chưa cập nhật', NULL, 'Hà Nội', 'Hà Nội', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-01-09 15:44:07', '2026-01-09 15:44:07');
INSERT INTO `user_addresses` VALUES (7, 16, 'Địa chỉ cũ', 'Nguyễn Văn Kiểm', '0902345678', 'Chưa cập nhật', 'Chưa cập nhật', NULL, 'TP HCM', 'TP HCM', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-01-09 15:44:07', '2026-01-09 15:44:07');
INSERT INTO `user_addresses` VALUES (8, 17, 'Địa chỉ cũ', 'Trần Thị Hoa', '0912345678', 'Chưa cập nhật', 'Chưa cập nhật', NULL, 'Quận 1, TP HCM', 'Quận 1, TP HCM', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-01-09 15:44:07', '2026-01-09 15:44:07');
INSERT INTO `user_addresses` VALUES (9, 18, 'Địa chỉ cũ', 'Lê Văn Minh', '0923456789', 'Chưa cập nhật', 'Chưa cập nhật', NULL, 'Hải Châu, Đà Nẵng', 'Hải Châu, Đà Nẵng', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-01-09 15:44:07', '2026-01-09 15:44:07');
INSERT INTO `user_addresses` VALUES (10, 19, 'Địa chỉ cũ', 'Phạm Thị Mai', '0934567890', 'Chưa cập nhật', 'Chưa cập nhật', NULL, 'Lê Chân, Hải Phòng', 'Lê Chân, Hải Phòng', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-01-09 15:44:07', '2026-01-09 15:44:07');
INSERT INTO `user_addresses` VALUES (11, 20, 'Địa chỉ cũ', 'Hoàng Văn Nam', '0945678901', 'Chưa cập nhật', 'Chưa cập nhật', NULL, 'Ninh Kiều, Cần Thơ', 'Ninh Kiều, Cần Thơ', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-01-09 15:44:07', '2026-01-09 15:44:07');
INSERT INTO `user_addresses` VALUES (12, 21, 'Địa chỉ cũ', 'Ngô Thị Lan', '0956789012', 'Chưa cập nhật', 'Chưa cập nhật', NULL, 'Đống Đa, Hà Nội', 'Đống Đa, Hà Nội', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-01-09 15:44:07', '2026-01-09 15:44:07');
INSERT INTO `user_addresses` VALUES (13, 22, 'Địa chỉ cũ', 'Đặng Văn Tùng', '0967890123', 'Chưa cập nhật', 'Chưa cập nhật', NULL, 'Tân Bình, TP HCM', 'Tân Bình, TP HCM', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-01-09 15:44:07', '2026-01-09 15:44:07');
INSERT INTO `user_addresses` VALUES (14, 23, 'Địa chỉ cũ', 'Vũ Thị Hương', '0978901234', 'Chưa cập nhật', 'Chưa cập nhật', NULL, 'Thanh Khê, Đà Nẵng', 'Thanh Khê, Đà Nẵng', NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-01-09 15:44:07', '2026-01-09 15:44:07');
INSERT INTO `user_addresses` VALUES (15, 11, 'Nhà riêng', 'Super Admin', '0926531052', 'Hà Nội', 'Quận Hai Bà Trưng', 'Phường Vĩnh Tuy', '57 ngõ 454 Minh Khai', '57 ngõ 454 Minh Khai, Phường Vĩnh Tuy, Quận Hai Bà Trưng, Hà Nội', 20.99635000, 105.86521000, 'here:af:street:9Gq-QpAJry2Y2SmoamcQoB', 201, 1488, '1A0320', 1, '2026-01-09 16:18:48', '2026-01-20 14:35:30');
INSERT INTO `user_addresses` VALUES (17, 25, 'Nhà riêng', 'Tdat', '0926531052', 'Tỉnh Cao Bằng', 'Huyện Bảo Lâm', 'Xã Đức Hạnh', '30/13/74 cô đông bình hàn tp hải dương', '30/13/74 cô đông bình hàn tp hải dương, Xã Đức Hạnh, Huyện Bảo Lâm, Tỉnh Cao Bằng', NULL, NULL, '', NULL, NULL, NULL, 1, '2026-01-09 16:40:01', '2026-01-09 16:40:01');
INSERT INTO `user_addresses` VALUES (18, 27, 'Nhà riêng', 'Tdat', '0926531052', 'Tỉnh Bắc Kạn', 'Huyện Ba Bể', 'Xã Phúc Lộc', '123213', '123213 , Xã Phúc Lộc, Huyện Ba Bể, Tỉnh Bắc Kạn', NULL, NULL, '', NULL, NULL, NULL, 1, '2026-01-12 14:48:54', '2026-01-12 14:48:54');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `phone_verified` tinyint(1) NULL DEFAULT 0,
  `gender` enum('male','female','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `role` enum('buyer','seller','admin','moderator') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'buyer',
  `balance` decimal(15, 2) NULL DEFAULT 0.00,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email_verified` tinyint(1) NULL DEFAULT 0,
  `email_verification_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email_verification_expires_at` datetime NULL DEFAULT NULL,
  `is_locked` tinyint(1) NULL DEFAULT 0,
  `last_seen` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `email`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 32 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Nguyễn Văn Admin', 'admin@unizify.vn', '$2y$10$Xe1KqRb2J6kxshp32LkY9.mXPO6K7H5vv3v9wCEeI1hDbyK6bckuC', '0901234567', 0, NULL, 'Hà Nội', '2025-12-30 21:18:26', 'admin', 0.00, NULL, 1, NULL, NULL, 0, NULL);
INSERT INTO `users` VALUES (2, 'Trần Thị Lan', 'lan.tran@student.edu.vn', '$2y$10$Xe1KqRb2J6kxshp32LkY9.mXPO6K7H5vv3v9wCEeI1hDbyK6bckuC', '0912345678', 0, NULL, 'TP HCM', '2025-12-30 21:18:26', 'seller', 0.00, NULL, 1, NULL, NULL, 0, NULL);
INSERT INTO `users` VALUES (3, 'Lê Văn Hùng', 'hung.le@student.edu.vn', '$2y$10$Xe1KqRb2J6kxshp32LkY9.mXPO6K7H5vv3v9wCEeI1hDbyK6bckuC', '0923456789', 0, NULL, 'Đà Nẵng', '2025-12-30 21:18:26', 'seller', 0.00, NULL, 1, NULL, NULL, 0, NULL);
INSERT INTO `users` VALUES (4, 'Phạm Thị Mai', 'mai.pham@student.edu.vn', '$2y$10$Xe1KqRb2J6kxshp32LkY9.mXPO6K7H5vv3v9wCEeI1hDbyK6bckuC', '0934567890', 0, NULL, 'Hải Phòng', '2025-12-30 21:18:26', 'seller', 0.00, NULL, 1, NULL, NULL, 0, NULL);
INSERT INTO `users` VALUES (5, 'Hoàng Văn Nam', 'nam.hoang@student.edu.vn', '$2y$10$Xe1KqRb2J6kxshp32LkY9.mXPO6K7H5vv3v9wCEeI1hDbyK6bckuC', '0945678901', 0, NULL, 'Cần Thơ', '2025-12-30 21:18:26', 'seller', 0.00, NULL, 1, NULL, NULL, 0, NULL);
INSERT INTO `users` VALUES (6, 'Super Admin', 'superadmin@unizify.vn', '$2y$10$ryaKIddnyDqn6qYXws/1fODdTCJ8/wYsqtGPYVd8bbRjJHyMLQTNi', NULL, 0, NULL, NULL, '2025-12-30 21:36:04', 'admin', 0.00, NULL, 1, NULL, NULL, 0, NULL);
INSERT INTO `users` VALUES (8, 'Test User', 'admin\'--@test.com', '$2y$10$EULA2vPmgymbXSR9fM9J6.MW4uFOfmE7WsxNhBF.WOGrMmSjv0F0W', '0123456789', 0, NULL, NULL, '2025-12-31 14:20:49', 'seller', 0.00, NULL, 1, NULL, NULL, 0, NULL);
INSERT INTO `users` VALUES (11, 'Super Admin', 'superadmin@zoldify.vn', '$2y$10$B1jBQA4/W//ZZYFFtcId8ew3xHEG7YAf3HoLGeZUOTqYzNBjbBroO', '0926531052', 1, NULL, '', '2025-12-31 15:22:47', 'admin', 0.00, 'avatar_11_1768045434.png', 1, NULL, NULL, 0, '2026-01-08 18:52:18');
INSERT INTO `users` VALUES (15, 'Admin Zoldify', 'admin@zoldify.vn', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0901234567', 0, NULL, 'Hà Nội', '2025-12-31 15:43:03', 'admin', 0.00, NULL, 1, NULL, NULL, 0, NULL);
INSERT INTO `users` VALUES (16, 'Nguyễn Văn Kiểm', 'moderator@zoldify.vn', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0902345678', 0, NULL, 'TP HCM', '2025-12-31 15:43:03', 'moderator', 0.00, NULL, 1, NULL, NULL, 0, NULL);
INSERT INTO `users` VALUES (17, 'Trần Thị Hoa', 'hoa.seller@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0912345678', 0, NULL, 'Quận 1, TP HCM', '2025-12-31 15:43:03', 'seller', 0.00, NULL, 1, NULL, NULL, 0, NULL);
INSERT INTO `users` VALUES (18, 'Lê Văn Minh', 'minh.shop@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0923456789', 0, NULL, 'Hải Châu, Đà Nẵng', '2025-12-31 15:43:03', 'seller', 0.00, NULL, 1, NULL, NULL, 0, NULL);
INSERT INTO `users` VALUES (19, 'Phạm Thị Mai', 'mai.vintage@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0934567890', 0, NULL, 'Lê Chân, Hải Phòng', '2025-12-31 15:43:03', 'seller', 0.00, NULL, 1, NULL, NULL, 0, NULL);
INSERT INTO `users` VALUES (20, 'Hoàng Văn Nam', 'nam.secondhand@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0945678901', 0, NULL, 'Ninh Kiều, Cần Thơ', '2025-12-31 15:43:03', 'seller', 0.00, NULL, 1, NULL, NULL, 0, NULL);
INSERT INTO `users` VALUES (21, 'Ngô Thị Lan', 'lan.buyer@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0956789012', 0, NULL, 'Đống Đa, Hà Nội', '2025-12-31 15:43:03', 'buyer', 0.00, NULL, 1, NULL, NULL, 0, NULL);
INSERT INTO `users` VALUES (22, 'Đặng Văn Tùng', 'tung.customer@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0967890123', 0, NULL, 'Tân Bình, TP HCM', '2025-12-31 15:43:03', 'buyer', 0.00, NULL, 1, NULL, NULL, 0, NULL);
INSERT INTO `users` VALUES (23, 'Vũ Thị Hương', 'huong.buyer@gmail.com', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1MEOVkbWRN9lLVvMqxGPVrjJJhDFWGq', '0978901234', 0, NULL, 'Thanh Khê, Đà Nẵng', '2025-12-31 15:43:03', 'buyer', 0.00, NULL, 1, NULL, NULL, 0, NULL);
INSERT INTO `users` VALUES (24, 'Đạt Đặng', 'tvmaroka1@gmail.com', '$2y$10$KKeEBgDba9Oba/rGp0ynf.6Wu5zE2HIfPzZ96ys1biBHGNUmNpYxq', NULL, 0, NULL, NULL, '2025-12-31 22:09:31', 'buyer', 0.00, NULL, 1, NULL, NULL, 0, '2026-01-04 01:07:48');
INSERT INTO `users` VALUES (25, 'Tdat', 'tdatdev@gmail.com', '$2y$10$7QLt/VugPI3rTdl2NajpX.zmpeZhOaogtsS8ypM3F1j0P8iAC81S2', '0926531052', 0, NULL, '', '2025-12-31 22:47:58', 'buyer', 0.00, NULL, 1, NULL, NULL, 1, NULL);
INSERT INTO `users` VALUES (27, 'Tdat', 'tdatdev11@gmail.com', '$2y$10$YeiWWqVXbA818yQXlUb92OBJFNS3PGE82GYVQkG9PKNxTLyyrSHGq', '0926531052', 0, NULL, '', '2025-12-31 22:54:36', 'buyer', 0.00, NULL, 1, NULL, NULL, 0, NULL);
INSERT INTO `users` VALUES (28, 'Tdatdev', 'datdt.1140101240018@vtc.edu.vn', '$2y$10$9DKa4XXYLpB5CD565VVM3OLj16/QYBsrCdfwriGL/MaaEyhohH.mK', '0926531052', 0, NULL, '', '2026-01-01 21:25:28', 'buyer', 0.00, NULL, 1, NULL, NULL, 0, NULL);
INSERT INTO `users` VALUES (31, 'Đặng Đạt', 'datmedia206@gmail.com', '$2y$10$c6MWUYfZM6rciGQHQfEQnOuVFfayWdLvo54STiQiFnnQg8TnQ9jNC', '0926531052', 0, NULL, '', '2026-01-21 14:02:21', 'buyer', 0.00, NULL, 0, '41a8ebdbff491825908be793570d43dd578de61de84a79eff7669340e7d29beb|030306', '2026-01-21 08:02:22', 0, NULL);

-- ----------------------------
-- Table structure for wallet_transactions
-- ----------------------------
DROP TABLE IF EXISTS `wallet_transactions`;
CREATE TABLE `wallet_transactions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `wallet_id` bigint UNSIGNED NOT NULL,
  `order_id` int NULL DEFAULT NULL COMMENT 'Đơn hàng liên quan (nếu có)',
  `transaction_type` enum('credit','debit','withdrawal','refund_debit') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15, 2) NOT NULL COMMENT 'Số tiền giao dịch',
  `balance_before` decimal(15, 2) NOT NULL COMMENT 'Số dư trước giao dịch',
  `balance_after` decimal(15, 2) NOT NULL COMMENT 'Số dư sau giao dịch',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Mô tả giao dịch',
  `reference_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Mã tham chiếu (VD: payout_id)',
  `status` enum('pending','completed','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_wallet_id`(`wallet_id` ASC) USING BTREE,
  INDEX `idx_order_id`(`order_id` ASC) USING BTREE,
  INDEX `idx_transaction_type`(`transaction_type` ASC) USING BTREE,
  INDEX `idx_status`(`status` ASC) USING BTREE,
  INDEX `idx_created_at`(`created_at` ASC) USING BTREE,
  CONSTRAINT `fk_wallet_trans_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT,
  CONSTRAINT `fk_wallet_trans_wallet` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'Lịch sử giao dịch ví' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of wallet_transactions
-- ----------------------------

-- ----------------------------
-- Table structure for wallets
-- ----------------------------
DROP TABLE IF EXISTS `wallets`;
CREATE TABLE `wallets`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `balance` decimal(15, 2) NULL DEFAULT 0.00 COMMENT 'Số dư khả dụng',
  `pending_balance` decimal(15, 2) NULL DEFAULT 0.00 COMMENT 'Tiền đang trong escrow',
  `total_earned` decimal(15, 2) NULL DEFAULT 0.00 COMMENT 'Tổng tiền đã nhận từ bán hàng',
  `total_withdrawn` decimal(15, 2) NULL DEFAULT 0.00 COMMENT 'Tổng tiền đã rút',
  `bank_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Tên ngân hàng',
  `bank_account_number` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Số tài khoản',
  `bank_account_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Tên chủ tài khoản',
  `bank_bin` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT 'Mã BIN ngân hàng (VietQR)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `user_id`(`user_id` ASC) USING BTREE,
  INDEX `idx_user_id`(`user_id` ASC) USING BTREE,
  INDEX `idx_balance`(`balance` ASC) USING BTREE,
  CONSTRAINT `fk_wallet_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = 'Ví tiền của seller' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of wallets
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
