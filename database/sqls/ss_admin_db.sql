-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 13, 2025 at 05:43 PM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ss_admin_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_profiles`
--

DROP TABLE IF EXISTS `admin_profiles`;
CREATE TABLE IF NOT EXISTS `admin_profiles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `department` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `permissions_level` int NOT NULL DEFAULT '1',
  `access_level` int NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `preferences` json DEFAULT NULL,
  `settings` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `admin_profiles_user_id_index` (`user_id`),
  KEY `admin_profiles_access_level_index` (`access_level`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_profiles`
--

INSERT INTO `admin_profiles` (`id`, `user_id`, `department`, `position`, `permissions_level`, `access_level`, `notes`, `preferences`, `settings`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, NULL, 1, 1, NULL, NULL, NULL, '2025-11-11 13:55:36', '2025-11-11 13:55:36'),
(2, 3, NULL, NULL, 1, 3, NULL, NULL, NULL, '2025-11-11 14:00:14', '2025-11-11 14:00:14');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_devices`
--

DROP TABLE IF EXISTS `client_devices`;
CREATE TABLE IF NOT EXISTS `client_devices` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `subscription_id` bigint UNSIGNED DEFAULT NULL,
  `device_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_serial` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_type` enum('iphone','ipad','mac','apple_tv','apple_watch') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'iphone',
  `device_model` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ios_version` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `activation_date` timestamp NULL DEFAULT NULL,
  `last_connection` timestamp NULL DEFAULT NULL,
  `device_info` json DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_devices_device_serial_unique` (`device_serial`),
  KEY `client_devices_subscription_id_foreign` (`subscription_id`),
  KEY `client_devices_user_id_status_index` (`user_id`,`status`),
  KEY `client_devices_device_serial_index` (`device_serial`),
  KEY `client_devices_last_connection_index` (`last_connection`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_profiles`
--

DROP TABLE IF EXISTS `client_profiles`;
CREATE TABLE IF NOT EXISTS `client_profiles` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `subscription_type` enum('basic','premium','enterprise') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'basic',
  `subscription_status` enum('active','inactive','suspended','expired','trial') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'trial',
  `subscription_start_date` date DEFAULT NULL,
  `subscription_end_date` date DEFAULT NULL,
  `device_limit` int NOT NULL DEFAULT '1',
  `devices_count` int NOT NULL DEFAULT '0',
  `payment_status` enum('paid','pending','overdue','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `billing_cycle` enum('monthly','quarterly','yearly') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'monthly',
  `client_notes` text COLLATE utf8mb4_unicode_ci,
  `preferences` json DEFAULT NULL,
  `settings` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `client_profiles_user_id_index` (`user_id`),
  KEY `client_profiles_subscription_type_index` (`subscription_type`),
  KEY `client_profiles_subscription_status_index` (`subscription_status`),
  KEY `client_profiles_payment_status_index` (`payment_status`),
  KEY `client_profiles_subscription_end_date_index` (`subscription_end_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

DROP TABLE IF EXISTS `devices`;
CREATE TABLE IF NOT EXISTS `devices` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `subscription_id` bigint UNSIGNED NOT NULL,
  `device_identifier` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'رقم مميز من 10 خانات',
  `iphone_model` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'طراز الآيفون',
  `device_nickname` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'اسم مخصص للجهاز',
  `serial_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'الرقم التسلسلي للجهاز',
  `device_info` json DEFAULT NULL COMMENT 'معلومات إضافية عن الجهاز',
  `last_token_update` timestamp NULL DEFAULT NULL COMMENT 'آخر تحديث للرمز المميز',
  `device_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_version` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `machine_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','active','disabled','blocked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `activated_at` timestamp NULL DEFAULT NULL,
  `last_connected_at` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `devices_device_identifier_unique` (`device_identifier`),
  KEY `devices_subscription_id_status_index` (`subscription_id`,`status`),
  KEY `devices_iphone_model_index` (`iphone_model`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `device_types`
--

DROP TABLE IF EXISTS `device_types`;
CREATE TABLE IF NOT EXISTS `device_types` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `device_type` enum('iPhone','iPad','Mac','Apple Watch','Apple TV') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'iPhone',
  `model` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `updated_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  KEY `updated_by` (`updated_by`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `device_types`
--

INSERT INTO `device_types` (`id`, `device_type`, `model`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(2, 'iPhone', 'ايفون 16 برو ماكس', 1, 1, '2025-11-13 13:40:10', '2025-11-13 13:40:10'),
(3, 'iPad', 'آيباد ميني جي 5', 1, 1, '2025-11-13 13:42:38', '2025-11-13 13:42:38'),
(4, 'Mac', 'MacBook Air (M3', 1, 1, '2025-11-13 13:42:59', '2025-11-13 13:42:59'),
(5, 'Apple Watch', 'Apple Watch Series 9', 1, 1, '2025-11-13 13:43:35', '2025-11-13 13:43:35'),
(6, 'Apple TV', 'Apple TV 4K (الجيل الثالث)', 1, 1, '2025-11-13 13:45:24', '2025-11-13 13:45:24');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `groups_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(6, 'مواقع التواصل', 'مشتركي مواقع التواصل', '2025-11-13 06:05:27', '2025-11-13 06:57:25'),
(7, 'تطبيقات الذكاء الاصطناعي', 'مشتركي تطبيقات الذكاء الاصطناعي', '2025-11-13 07:34:00', '2025-11-13 07:34:00');

-- --------------------------------------------------------

--
-- Table structure for table `group_items`
--

DROP TABLE IF EXISTS `group_items`;
CREATE TABLE IF NOT EXISTS `group_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `group_id` bigint UNSIGNED NOT NULL,
  `name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `group_items`
--

INSERT INTO `group_items` (`id`, `group_id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(9, 6, 'فيسبوك', 'مشتركي موقع فيسبوك', '2025-11-13 06:57:48', '2025-11-13 06:57:48'),
(10, 6, 'الواتساب', 'مشتركي تطبيق الواتساب', '2025-11-13 06:59:58', '2025-11-13 06:59:58'),
(11, 7, 'شات جي بي تي', NULL, '2025-11-13 07:34:10', '2025-11-13 07:34:10'),
(12, 7, 'جيميني', 'نموذج الذكاء الاصطناعي من جوجل', '2025-11-13 07:34:46', '2025-11-13 07:34:46');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_10_22_000001_create_subscription_requests_table', 1),
(5, '2024_10_22_000002_create_subscriptions_table', 1),
(6, '2024_10_22_000003_create_devices_table', 1),
(7, '2024_10_22_000004_create_payments_table', 1),
(8, '2025_10_22_224241_create_subscription_request_devices_table', 1),
(9, '2025_10_27_214708_create_admin_profiles_table', 1),
(10, '2025_10_27_214739_create_client_profiles_table', 1),
(11, '2025_10_27_225221_create_client_devices_table', 1),
(12, '2025_10_31_161625_create_service_packages_table', 1),
(13, '2025_10_31_162312_create_package_features_table', 1),
(14, '2025_10_31_162918_create_package_feature_values_table', 1),
(15, '2025_11_12_081533_create_keys_table', 2),
(16, '2025_11_12_161542_create_groups_table', 2),
(17, '2025_11_12_161711_create_group_items_table', 2),
(18, '2025_11_13_151853_create_device_types_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `package_features`
--

DROP TABLE IF EXISTS `package_features`;
CREATE TABLE IF NOT EXISTS `package_features` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `package_features_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `package_feature_values`
--

DROP TABLE IF EXISTS `package_feature_values`;
CREATE TABLE IF NOT EXISTS `package_feature_values` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `package_id` bigint UNSIGNED NOT NULL,
  `package_feature_id` bigint UNSIGNED NOT NULL,
  `value` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `package_feature_values_package_id_foreign` (`package_id`),
  KEY `package_feature_values_package_feature_id_foreign` (`package_feature_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `subscription_request_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_reference` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receipt_path` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending_verification','verified','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending_verification',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `paid_at` timestamp NULL DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_verified_by_foreign` (`verified_by`),
  KEY `payments_user_id_status_index` (`user_id`,`status`),
  KEY `payments_subscription_request_id_index` (`subscription_request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_packages`
--

DROP TABLE IF EXISTS `service_packages`;
CREATE TABLE IF NOT EXISTS `service_packages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration` int NOT NULL DEFAULT '1',
  `duration_unit` enum('month','year') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `service_packages_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('VjaP3TkVh8JYtbFAbzoCNIXuvtm7P9maLLOCGrvT', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoidVhpYXJUek93MjFOSGt3MThvTjBwTno5Y0s3NGh6akhUQngzSUpSUyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozOToiaHR0cDovL3d3dy5zcy1tbmcubG9jYWwvYWRtaW4vZGFzaGJvYXJkIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly93d3cuc3MtbW5nLmxvY2FsL2FkbWluL2tleXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1763055555);

-- --------------------------------------------------------

--
-- Table structure for table `ss_keys`
--

DROP TABLE IF EXISTS `ss_keys`;
CREATE TABLE IF NOT EXISTS `ss_keys` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `key_string` varchar(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uuid` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `period` enum('week','month','year') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'year',
  `device_type` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `group_item_id` bigint UNSIGNED DEFAULT NULL,
  `status` enum('active','new','blocked','expired') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'new',
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `activated_at` datetime DEFAULT NULL,
  `created_by` bigint DEFAULT NULL,
  `updated_by` bigint DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `keys_key_string_unique` (`key_string`),
  KEY `keys_group_item_id_foreign` (`group_item_id`),
  KEY `keys_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ss_keys`
--

INSERT INTO `ss_keys` (`id`, `key_string`, `uuid`, `period`, `device_type`, `group_item_id`, `status`, `user_id`, `created_at`, `updated_at`, `activated_at`, `created_by`, `updated_by`) VALUES
(19, 'zdf9i066axdp5N8J1nzTiYk14ywUye03BEjc', NULL, 'week', NULL, NULL, 'new', NULL, '2025-11-13 10:17:40', '2025-11-13 10:17:40', NULL, 1, 1),
(11, 'MJFblRsYRQfUnQY78RdUrZwcyAeimOiiYst0', NULL, 'week', NULL, NULL, 'new', NULL, '2025-11-13 10:17:40', '2025-11-13 10:17:40', NULL, 1, 1),
(12, '28SFF7p1kj8Se4RuF5ha9dp6Kcq331e6QCXW', NULL, 'week', NULL, NULL, 'new', NULL, '2025-11-13 10:17:40', '2025-11-13 10:17:40', NULL, 1, 1),
(13, '3zeqq789YE1WEZm2gyR5YAdhKzOlQrFYIux3', NULL, 'week', NULL, NULL, 'new', NULL, '2025-11-13 10:17:40', '2025-11-13 10:17:40', NULL, 1, 1),
(14, 'fAammCLC9WHLOm7FOEguSDSJWAHyIjYqUDwA', NULL, 'week', NULL, NULL, 'new', NULL, '2025-11-13 10:17:40', '2025-11-13 10:17:40', NULL, 1, 1),
(15, 'JMLHeTualVapZQxCx6rRthIfxo1g01eY677p', NULL, 'week', NULL, NULL, 'new', NULL, '2025-11-13 10:17:40', '2025-11-13 10:17:40', NULL, 1, 1),
(16, '1P1pVyzZNUG8JaDUTpiAYeyDHJgCK9yZpGlk', NULL, 'week', NULL, NULL, 'new', NULL, '2025-11-13 10:17:40', '2025-11-13 10:17:40', NULL, 1, 1),
(17, 'jYNWzLQuP61ncKJxuZzLtuyXKcyuZ9iwvDKe', NULL, 'week', NULL, NULL, 'new', NULL, '2025-11-13 10:17:40', '2025-11-13 10:17:40', NULL, 1, 1),
(18, '0bflrBierD1OQvrKCYRcRms5uvPZlxTOdxoM', NULL, 'week', NULL, NULL, 'new', NULL, '2025-11-13 10:17:40', '2025-11-13 10:17:40', NULL, 1, 1),
(20, 'YKYjFiXu5pRXniY7B87shYtMa1XeSfSvuLx4', NULL, 'week', NULL, NULL, 'new', NULL, '2025-11-13 10:17:40', '2025-11-13 10:17:40', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `subscription_request_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_count` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('pending','active','expired','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `description` text COLLATE utf8mb4_unicode_ci,
  `features` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscriptions_user_id_foreign` (`user_id`),
  KEY `subscriptions_subscription_request_id_foreign` (`subscription_request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_requests`
--

DROP TABLE IF EXISTS `subscription_requests`;
CREATE TABLE IF NOT EXISTS `subscription_requests` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `activated_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `suspended_at` timestamp NULL DEFAULT NULL,
  `renewed_at` timestamp NULL DEFAULT NULL,
  `suspension_reason` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint UNSIGNED NOT NULL,
  `subscription_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_count` int NOT NULL,
  `proposed_start_date` date NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','quoted','paid','active','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `quoted_price` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `quoted_at` timestamp NULL DEFAULT NULL,
  `payment_receipt` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'مسار إيصال الدفع',
  `paid_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ الدفع',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subscription_requests_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_request_devices`
--

DROP TABLE IF EXISTS `subscription_request_devices`;
CREATE TABLE IF NOT EXISTS `subscription_request_devices` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `subscription_request_id` bigint UNSIGNED NOT NULL,
  `device_identifier` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'رقم مميز من 10 خانات',
  `iphone_model` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'طراز الآيفون',
  `device_nickname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'اسم مخصص للجهاز',
  `special_requirements` text COLLATE utf8mb4_unicode_ci COMMENT 'متطلبات خاصة',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscription_request_devices_device_identifier_unique` (`device_identifier`),
  KEY `subscription_request_devices_subscription_request_id_index` (`subscription_request_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','pending','blocked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `serial_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_app_admin` tinyint(1) DEFAULT '0',
  `role` enum('admin','client') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'client',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_serial_number_unique` (`serial_number`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `user_name`, `email`, `email_verified_at`, `status`, `serial_number`, `password`, `is_app_admin`, `role`, `remember_token`, `phone`, `address`, `notes`, `last_login_at`, `created_at`, `updated_at`) VALUES
(1, 'Test User', 'User_227', 'test@example.com', NULL, 'active', NULL, '$2y$12$4AJuA/gxROfakjcjC9/SD.vQoE9lbaRmSFIt2LgzgpIx0Dn2IU8nW', 0, 'admin', NULL, NULL, NULL, NULL, NULL, '2025-11-11 13:45:18', '2025-11-11 13:45:18'),
(2, 'مشهور عبد الله العتيبي', 'Abdul', 'abdul@gmail.com', NULL, 'active', 'EMP9911', '$2y$12$DVdgdKGf6PmFc2go2Dn4q.VABoRh12GQ9Ae4k64vKzzeD4ToOm.B.', 0, 'admin', NULL, '0547660005', NULL, NULL, NULL, '2025-11-11 13:55:36', '2025-11-11 13:55:36'),
(3, 'محمد على كلاي', 'Ali Atef', 'murad@test.ext', NULL, 'active', 'EMP7072', '$2y$12$F8EAEQlSMKXzckwASpV4qeOzbjQMJgG5BRyYeQUxcLKWvHAyL.uYy', 0, 'admin', NULL, '0123456987', NULL, NULL, NULL, '2025-11-11 14:00:14', '2025-11-11 14:00:14'),
(4, 'Ahmed Abdul Samee', 'Ahmed Abdu', 'Ahmed@abdu.ext', NULL, 'active', 'EMP7073', '$2y$12$F8EAEQlSMKXzckwASpV4qeOzbjQMJgG5BRyYeQUxcLKWvHAyL.uYy', 0, 'client', NULL, '0123456989', NULL, NULL, NULL, '2025-11-11 14:00:14', '2025-11-11 14:00:14');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_profiles`
--
ALTER TABLE `admin_profiles`
  ADD CONSTRAINT `admin_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `client_devices`
--
ALTER TABLE `client_devices`
  ADD CONSTRAINT `client_devices_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `client_profiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `client_devices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `client_profiles`
--
ALTER TABLE `client_profiles`
  ADD CONSTRAINT `client_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `devices`
--
ALTER TABLE `devices`
  ADD CONSTRAINT `devices_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `device_types`
--
ALTER TABLE `device_types`
  ADD CONSTRAINT `device_types_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `device_types_ibfk_2` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `group_items`
--
ALTER TABLE `group_items`
  ADD CONSTRAINT `group_items_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `package_feature_values`
--
ALTER TABLE `package_feature_values`
  ADD CONSTRAINT `package_feature_values_package_feature_id_foreign` FOREIGN KEY (`package_feature_id`) REFERENCES `package_features` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `package_feature_values_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `service_packages` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_subscription_request_id_foreign` FOREIGN KEY (`subscription_request_id`) REFERENCES `subscription_requests` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_subscription_request_id_foreign` FOREIGN KEY (`subscription_request_id`) REFERENCES `subscription_requests` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscription_requests`
--
ALTER TABLE `subscription_requests`
  ADD CONSTRAINT `subscription_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscription_request_devices`
--
ALTER TABLE `subscription_request_devices`
  ADD CONSTRAINT `subscription_request_devices_subscription_request_id_foreign` FOREIGN KEY (`subscription_request_id`) REFERENCES `subscription_requests` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
