/*
 Navicat Premium Dump SQL

 Source Server         : MariaDB
 Source Server Type    : MariaDB
 Source Server Version : 110802 (11.8.2-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : pos_db

 Target Server Type    : MariaDB
 Target Server Version : 110802 (11.8.2-MariaDB)
 File Encoding         : 65001

 Date: 09/12/2025 16:12:05
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cache
-- ----------------------------
INSERT INTO `cache` VALUES ('e-book-cache-admin@admin.com|127.0.0.1', 'i:1;', 1761923376);
INSERT INTO `cache` VALUES ('e-book-cache-admin@admin.com|127.0.0.1:timer', 'i:1761923376;', 1761923376);

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of cache_locks
-- ----------------------------

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '0001_01_01_0000002_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO `migrations` VALUES (3, '0001_01_01_000001_create_page_table', 1);

-- ----------------------------
-- Table structure for modules
-- ----------------------------
DROP TABLE IF EXISTS `modules`;
CREATE TABLE `modules`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_en` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name_kh` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `order` tinyint(4) NULL DEFAULT NULL,
  `top_slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `type` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of modules
-- ----------------------------
INSERT INTO `modules` VALUES (1, 'Users Management', 'ការគ្រប់គ្រងអ្នកប្រើប្រាស់', 'usres-management', 1, 'usres-management', 'ph ph-user-gear', 'module', 1, '2025-10-04 10:06:01', '2025-10-04 10:06:01', NULL);

-- ----------------------------
-- Table structure for page_actions
-- ----------------------------
DROP TABLE IF EXISTS `page_actions`;
CREATE TABLE `page_actions`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_id` int(11) NULL DEFAULT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name_kh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `route_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `position` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `type` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `function_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `function_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `page_idx`(`page_id` ASC) USING BTREE,
  INDEX `type_idx`(`type` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of page_actions
-- ----------------------------
INSERT INTO `page_actions` VALUES (1, 2, 'List Users', 'បញ្ជី អ្នកប្រើប្រាស់', 'list', 'usres-management.usres.index', NULL, 'icon-file-text2', 'list', NULL, NULL, '2025-10-04 10:06:01', '2025-10-04 10:06:01', NULL);
INSERT INTO `page_actions` VALUES (2, 2, 'Add Users', 'បង្កើត អ្នកប្រើប្រាស់', 'add', 'usres-management.usres.create', NULL, 'icon-add-to-list', 'insert', NULL, NULL, '2025-10-04 10:06:01', '2025-10-04 10:06:01', NULL);
INSERT INTO `page_actions` VALUES (3, 2, 'Edit Users', 'កែប្រែ អ្នកប្រើប្រាស់', 'update', 'usres-management.usres.edit', NULL, 'icon-pencil5', 'update', NULL, NULL, '2025-10-04 10:06:01', '2025-10-04 10:06:01', NULL);
INSERT INTO `page_actions` VALUES (4, 2, 'Delete Users', 'លុប អ្នកប្រើប្រាស់', 'delete', 'usres-management.usres.destroy', NULL, 'icon-trash', 'delete', NULL, NULL, '2025-10-04 10:06:01', '2025-10-04 10:06:01', NULL);
INSERT INTO `page_actions` VALUES (5, 3, 'List Roles', 'បញ្ជី តួនាទី', 'list', 'usres-management.role.index', NULL, 'icon-file-text2', 'list', NULL, NULL, '2025-10-04 10:06:01', '2025-10-04 10:06:01', NULL);
INSERT INTO `page_actions` VALUES (6, 3, 'Add Roles', 'បង្កើត តួនាទី', 'add', 'usres-management.role.create', NULL, 'icon-add-to-list', 'insert', NULL, NULL, '2025-10-04 10:06:01', '2025-10-04 10:06:01', NULL);
INSERT INTO `page_actions` VALUES (7, 3, 'Edit Roles', 'កែប្រែ តួនាទី', 'update', 'usres-management.role.edit', NULL, 'icon-pencil5', 'update', NULL, NULL, '2025-10-04 10:06:01', '2025-10-04 10:06:01', NULL);
INSERT INTO `page_actions` VALUES (8, 3, 'Delete Roles', 'លុប តួនាទី', 'delete', 'usres-management.role.destroy', NULL, 'icon-trash', 'delete', NULL, NULL, '2025-10-04 10:06:01', '2025-10-04 10:06:01', NULL);

-- ----------------------------
-- Table structure for pages
-- ----------------------------
DROP TABLE IF EXISTS `pages`;
CREATE TABLE `pages`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `module_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `sub_module_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `section_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name_kh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `route_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `can_access` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `order` int(11) NULL DEFAULT NULL,
  `default_action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_service_fee` tinyint(4) NOT NULL DEFAULT 0,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `pages_slug_unique`(`slug` ASC) USING BTREE,
  INDEX `idx`(`id` ASC) USING BTREE,
  INDEX `module_idx`(`module_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of pages
-- ----------------------------
INSERT INTO `pages` VALUES (1, NULL, NULL, NULL, 'Dashboard', 'ផ្ទារគ្រប់គ្រង', 'dashboard', 'dashboard', NULL, 'ph ph-house', 1, NULL, 0, 1, '2025-10-04 10:06:01', '2025-10-04 10:06:01', NULL);
INSERT INTO `pages` VALUES (2, 1, NULL, NULL, 'Users', 'អ្នកប្រើប្រាស់', 'usres', 'users-management.users.index', NULL, 'ph ph-user', 1, NULL, 0, 1, '2025-10-04 10:06:01', '2025-10-04 10:06:01', NULL);
INSERT INTO `pages` VALUES (3, 1, NULL, NULL, 'Roles', 'តួនាទី', 'role', 'users-management.roles.index', NULL, 'ph ph-person-simple-circle', 2, NULL, 0, 1, '2025-10-04 10:06:01', '2025-10-04 10:06:01', NULL);

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of password_reset_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_kh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_menu` tinyint(4) NOT NULL DEFAULT 0,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `menu_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `permissions_slug_unique`(`slug` ASC) USING BTREE,
  INDEX `permissions_menu_id_index`(`menu_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of permissions
-- ----------------------------

-- ----------------------------
-- Table structure for role_permission
-- ----------------------------
DROP TABLE IF EXISTS `role_permission`;
CREATE TABLE `role_permission`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `role_permission_role_id_index`(`role_id` ASC) USING BTREE,
  INDEX `role_permission_permission_id_index`(`permission_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role_permission
-- ----------------------------

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` bigint(8) NOT NULL DEFAULT 0,
  `scope` enum('limited','full') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'limited',
  `description` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `roles_slug_unique`(`slug` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'Admin', 'administrator', 1, 'limited', 'Administrator with full access', 1, NULL, NULL, '2025-10-04 10:05:58', '2025-10-04 10:05:58', NULL);
INSERT INTO `roles` VALUES (2, 'User', 'users', 2, 'limited', 'Regular user with limited access', 1, NULL, NULL, '2025-10-04 10:05:59', '2025-10-04 10:05:59', NULL);

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sessions_user_id_index`(`user_id` ASC) USING BTREE,
  INDEX `sessions_last_activity_index`(`last_activity` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sessions
-- ----------------------------
INSERT INTO `sessions` VALUES ('HVbPHBDyEKeLz3WyAQm7aAILI4LrjVCkz3Q6ieVK', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiSEpvaHJTcE1SSnhUWE4xY3BDbmVQNDVuSUZIRDdDRWNLeUNZR1A4eCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6MzE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9kYXNoYm9hcmQiO31zOjIyOiJQSFBERUJVR0JBUl9TVEFDS19EQVRBIjthOjA6e319', 1761923321);

-- ----------------------------
-- Table structure for sub_modules
-- ----------------------------
DROP TABLE IF EXISTS `sub_modules`;
CREATE TABLE `sub_modules`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `module_id` bigint(20) UNSIGNED NOT NULL,
  `name_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `name_kh` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `order` tinyint(4) NULL DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sub_modules_module_id_foreign`(`module_id` ASC) USING BTREE,
  CONSTRAINT `sub_modules_module_id_foreign` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sub_modules
-- ----------------------------

-- ----------------------------
-- Table structure for user_role
-- ----------------------------
DROP TABLE IF EXISTS `user_role`;
CREATE TABLE `user_role`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_role_user_id_index`(`user_id` ASC) USING BTREE,
  INDEX `user_role_role_id_index`(`role_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_role
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `locale` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `enabled` tinyint(4) NOT NULL DEFAULT 1,
  `role_id` int(11) NOT NULL,
  `he_scholarship` tinyint(4) NOT NULL DEFAULT 0,
  `scholarship_category` tinyint(4) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `deleted_by` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_username_unique`(`username` ASC) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE,
  INDEX `users_role_id_index`(`role_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'admin', 'Admin', 'admin@gmail.com', NULL, '$2y$12$GJqJBKkZ0t3jwufCDpk7auz4W4gIdcyrXXAONHKqybemxLUno9tze', NULL, 'en', 1, 1, 0, 0, NULL, 1, NULL, NULL, '2025-10-04 10:06:00', '2025-10-04 10:06:00', NULL);
INSERT INTO `users` VALUES (2, 'user', 'User', 'user@wintech.com.kh', NULL, '$2y$12$fTWhxA0DI3jfMWLA0KaaROuTGoDTp4YDnxtCuQSAVeDpHZ7sYSl4q', NULL, 'en', 1, 2, 0, 0, NULL, 1, NULL, NULL, '2025-10-04 10:06:00', '2025-10-04 10:06:00', NULL);

SET FOREIGN_KEY_CHECKS = 1;
