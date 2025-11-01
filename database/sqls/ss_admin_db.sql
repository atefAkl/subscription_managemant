-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 01, 2025 at 07:15 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_profiles`
--

INSERT INTO `admin_profiles` (`id`, `user_id`, `department`, `position`, `permissions_level`, `access_level`, `notes`, `preferences`, `settings`, `created_at`, `updated_at`) VALUES
(1, 13, NULL, NULL, 1, 1, NULL, NULL, NULL, '2025-10-30 16:09:00', '2025-10-30 16:09:00'),
(2, 14, NULL, NULL, 1, 4, NULL, NULL, NULL, '2025-10-30 16:24:04', '2025-10-30 16:24:04'),
(3, 15, NULL, NULL, 1, 4, NULL, NULL, NULL, '2025-10-30 16:51:53', '2025-10-30 16:51:53');

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `client_profiles`
--

INSERT INTO `client_profiles` (`id`, `user_id`, `subscription_type`, `subscription_status`, `subscription_start_date`, `subscription_end_date`, `device_limit`, `devices_count`, `payment_status`, `billing_cycle`, `client_notes`, `preferences`, `settings`, `created_at`, `updated_at`) VALUES
(1, 1, 'premium', 'active', '2025-09-27', '2026-09-27', 3, 0, 'paid', 'yearly', NULL, NULL, NULL, '2025-10-27 20:49:14', '2025-10-28 14:12:39'),
(2, 2, 'basic', 'active', '2025-10-12', '2026-10-12', 2, 1, 'paid', 'monthly', NULL, NULL, NULL, '2025-10-27 20:49:14', '2025-10-27 20:49:14'),
(3, 16, 'basic', 'trial', '2025-10-08', '2025-11-08', 5, 0, 'pending', 'monthly', NULL, NULL, NULL, '2025-10-30 17:08:17', '2025-10-30 17:08:17');

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(8, '2025_10_21_202733_add_role_to_users_table', 1),
(9, '2025_10_22_223108_add_payment_fields_to_subscription_requests_table', 1),
(10, '2025_10_22_223130_update_subscription_requests_status_enum', 1),
(11, '2025_10_22_224241_create_subscription_request_devices_table', 1),
(12, '2025_10_27_201013_add_additional_fields_to_users_table', 1),
(13, '2025_10_27_214435_add_employee_number_to_users_table', 1),
(14, '2025_10_27_214708_create_admin_profiles_table', 1),
(15, '2025_10_27_214739_create_client_profiles_table', 1),
(16, '2025_10_27_225221_create_client_devices_table', 1),
(17, '2025_10_29_180026_add_verified_by_to_payments_table', 2),
(18, '2025_10_29_191403_add_subscription_management_fields_to_subscription_requests_table', 3),
(19, '2025_10_31_161625_create_service_packages_table', 4),
(20, '2025_10_31_162312_create_package_features_table', 4),
(21, '2025_10_31_162918_create_package_feature_values_table', 4);

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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `package_features`
--

INSERT INTO `package_features` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'الدواجن', '2025-10-31 21:03:43', '2025-10-31 21:03:43'),
(2, 'الميزة الثانية', '2025-10-31 21:04:53', '2025-10-31 21:04:53'),
(3, 'الميزة الثالثة', '2025-10-31 21:05:01', '2025-10-31 21:05:01'),
(4, 'الميزة الرابعة', '2025-10-31 21:05:10', '2025-10-31 21:05:10');

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `verified_by` bigint UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_user_id_status_index` (`user_id`,`status`),
  KEY `payments_subscription_request_id_index` (`subscription_request_id`),
  KEY `payments_verified_by_foreign` (`verified_by`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `subscription_request_id`, `user_id`, `amount`, `payment_method`, `transaction_reference`, `receipt_path`, `status`, `admin_notes`, `paid_at`, `verified_at`, `created_at`, `updated_at`, `verified_by`) VALUES
(1, 3, 1, 110.00, 'vodafone_cash', '232435434545', 'payment-receipts/VjtgIqoq6QLot5XQT2Uf3sfiiM9WiTOXQAwlAFG9.jpg', 'verified', 'تم التحقق السريع من لوحة البيانات', '2025-10-29 14:33:11', '2025-10-29 15:46:53', '2025-10-29 14:33:11', '2025-10-29 15:46:53', 3),
(2, 5, 5, 750.00, 'vodafone_cash', 'TXN699430', 'payment-receipts/VjtgIqoq6QLot5XQT2Uf3sfiiM9WiTOXQAwlAFG9.jpg', 'pending_verification', NULL, NULL, NULL, '2025-10-27 08:59:37', '2025-10-29 14:59:37', NULL),
(3, 5, 4, 250.00, 'bank_transfer', 'TXN749973', 'payment-receipts/VjtgIqoq6QLot5XQT2Uf3sfiiM9WiTOXQAwlAFG9.jpg', 'verified', 'تم التحقق السريع من لوحة البيانات', NULL, '2025-10-29 15:46:23', '2025-10-29 11:59:37', '2025-10-29 15:46:23', 3),
(4, 7, 4, 500.00, 'bank_transfer', 'TXN749765', 'payment-receipts/VjtgIqoq6QLot5XQT2Uf3sfiiM9WiTOXQAwlAFG9.jpg', 'verified', 'تم التحقق من المدفوعة', NULL, '2025-10-29 16:03:25', '2025-10-28 15:59:37', '2025-10-29 16:03:25', 3),
(5, 5, 6, 750.00, 'etisalat_cash', 'TXN585099', 'payment-receipts/VjtgIqoq6QLot5XQT2Uf3sfiiM9WiTOXQAwlAFG9.jpg', 'pending_verification', NULL, NULL, NULL, '2025-10-27 03:59:37', '2025-10-29 14:59:37', NULL),
(6, 5, 4, 1000.00, 'etisalat_cash', 'TXN366218', 'payment-receipts/VjtgIqoq6QLot5XQT2Uf3sfiiM9WiTOXQAwlAFG9.jpg', 'verified', 'تم التحقق من المدفوعة', NULL, '2025-10-29 16:03:17', '2025-10-28 23:59:37', '2025-10-29 16:03:17', 3),
(7, 6, 4, 1500.00, 'bank_transfer', 'TXN468292', 'payment-receipts/VjtgIqoq6QLot5XQT2Uf3sfiiM9WiTOXQAwlAFG9.jpg', 'pending_verification', NULL, NULL, NULL, '2025-10-27 09:59:37', '2025-10-29 14:59:37', NULL),
(8, 5, 5, 1500.00, 'etisalat_cash', 'TXN133141', 'payment-receipts/VjtgIqoq6QLot5XQT2Uf3sfiiM9WiTOXQAwlAFG9.jpg', 'pending_verification', NULL, NULL, NULL, '2025-10-28 02:59:37', '2025-10-29 14:59:37', NULL),
(9, 7, 4, 1500.00, 'etisalat_cash', 'TXN918481', 'payment-receipts/VjtgIqoq6QLot5XQT2Uf3sfiiM9WiTOXQAwlAFG9.jpg', 'pending_verification', NULL, NULL, NULL, '2025-10-27 06:59:37', '2025-10-29 14:59:37', NULL),
(10, 7, 5, 1000.00, 'visa_card', 'TXN240358', 'payment-receipts/VjtgIqoq6QLot5XQT2Uf3sfiiM9WiTOXQAwlAFG9.jpg', 'pending_verification', NULL, NULL, NULL, '2025-10-27 02:08:33', '2025-10-29 15:08:33', NULL),
(11, 7, 4, 750.00, 'orange_money', 'TXN121143', 'payment-receipts/VjtgIqoq6QLot5XQT2Uf3sfiiM9WiTOXQAwlAFG9.jpg', 'verified', 'تم التحقق من المدفوعة', NULL, '2025-10-29 15:47:07', '2025-10-29 02:08:33', '2025-10-29 15:47:07', 3),
(12, 5, 4, 1000.00, 'bank_transfer', 'TXN700367', 'payment-receipts/VjtgIqoq6QLot5XQT2Uf3sfiiM9WiTOXQAwlAFG9.jpg', 'pending_verification', NULL, NULL, NULL, '2025-10-26 22:08:33', '2025-10-29 15:08:33', NULL),
(13, 5, 6, 250.00, 'visa_card', 'TXN283587', 'payment-receipts/VjtgIqoq6QLot5XQT2Uf3sfiiM9WiTOXQAwlAFG9.jpg', 'pending_verification', NULL, NULL, NULL, '2025-10-27 04:08:33', '2025-10-29 15:08:33', NULL),
(14, 6, 4, 750.00, 'visa_card', 'TXN640183', 'payment-receipts/VjtgIqoq6QLot5XQT2Uf3sfiiM9WiTOXQAwlAFG9.jpg', 'verified', 'تم التحقق من المدفوعة', NULL, '2025-10-29 15:56:34', '2025-10-29 00:08:33', '2025-10-29 15:56:34', 3),
(15, 5, 6, 250.00, 'visa_card', 'TXN172002', 'payment-receipts/VjtgIqoq6QLot5XQT2Uf3sfiiM9WiTOXQAwlAFG9.jpg', 'verified', 'تم التحقق السريع من لوحة البيانات', NULL, '2025-10-29 15:46:40', '2025-10-29 10:08:33', '2025-10-29 15:46:40', 3),
(16, 6, 4, 750.00, 'vodafone_cash', 'TXN596570', 'payment-receipts/VjtgIqoq6QLot5XQT2Uf3sfiiM9WiTOXQAwlAFG9.jpg', 'verified', 'تم التحقق من المدفوعة', NULL, '2025-10-29 16:19:00', '2025-10-28 07:08:33', '2025-10-29 16:19:00', 3),
(17, 5, 5, 750.00, 'etisalat_cash', 'TXN553154', 'payment-receipts/VjtgIqoq6QLot5XQT2Uf3sfiiM9WiTOXQAwlAFG9.jpg', 'verified', 'تم التحقق من المدفوعة', NULL, '2025-10-29 16:18:56', '2025-10-28 13:08:33', '2025-10-29 16:18:56', 3),
(18, 5, 5, 250.00, 'vodafone_cash', 'TXN288472', 'payment-receipts/VjtgIqoq6QLot5XQT2Uf3sfiiM9WiTOXQAwlAFG9.jpg', 'verified', NULL, NULL, '2025-10-26 15:08:33', '2025-10-27 15:08:33', '2025-10-29 15:08:33', 1),
(19, 5, 5, 1000.00, 'vodafone_cash', 'TXN845736', 'payment-receipts/VjtgIqoq6QLot5XQT2Uf3sfiiM9WiTOXQAwlAFG9.jpg', 'verified', NULL, NULL, '2025-10-20 15:08:33', '2025-10-19 15:08:33', '2025-10-29 15:08:33', 1),
(20, 6, 5, 500.00, 'etisalat_cash', 'TXN126417', 'payment-receipts/VjtgIqoq6QLot5XQT2Uf3sfiiM9WiTOXQAwlAFG9.jpg', 'verified', NULL, NULL, '2025-10-27 15:08:33', '2025-10-15 15:08:33', '2025-10-29 15:08:33', 1),
(21, 7, 5, 500.00, 'visa_card', 'TXN836839', 'payment-receipts/VjtgIqoq6QLot5XQT2Uf3sfiiM9WiTOXQAwlAFG9.jpg', 'verified', NULL, NULL, '2025-10-21 15:08:33', '2025-10-22 15:08:33', '2025-10-29 15:08:33', 1),
(22, 6, 4, 500.00, 'orange_money', 'TXN642274', 'payment-receipts/VjtgIqoq6QLot5XQT2Uf3sfiiM9WiTOXQAwlAFG9.jpg', 'verified', NULL, NULL, '2025-10-19 15:08:33', '2025-10-24 15:08:33', '2025-10-29 15:08:33', 1),
(23, 5, 4, 300.00, 'bank_transfer', 'TXN390039', 'payment-receipts/VjtgIqoq6QLot5XQT2Uf3sfiiM9WiTOXQAwlAFG9.jpg', 'rejected', 'معلومات التحويل غير صحيحة', NULL, NULL, '2025-10-24 15:08:33', '2025-10-29 15:08:33', 1);

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
  `duration_unit` enum('months','years','days','weeks') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `service_packages_name_unique` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_packages`
--

INSERT INTO `service_packages` (`id`, `name`, `description`, `price`, `duration`, `duration_unit`, `created_at`, `updated_at`) VALUES
(1, 'الباقة الأساسية', 'البقة الأساسية للتجربة لمدة ضغيرة وبعد كدة هنلبس الزبون فى باقة حلوة', 0.00, 15, '', '2025-10-31 20:33:32', '2025-10-31 20:33:32');

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('NGMsGsek8HXSu2cDRCKGCgcIdMXVz5Les4oo3Rga', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiMkdlM0d2YmNmaWhva05tdmIxWkxrR2h5dWc2MmFkdzBidFJSR2FjYiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozNjoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2FkbWluL3BhY2thZ2VzIjt9czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wYWNrYWdlcyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjM7fQ==', 1761958269);

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscription_requests`
--

DROP TABLE IF EXISTS `subscription_requests`;
CREATE TABLE IF NOT EXISTS `subscription_requests` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `serial_number` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subscription_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `device_count` int NOT NULL,
  `proposed_start_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','quoted','paid','active','approved','rejected') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `quoted_price` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `payment_receipt` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'مسار إيصال الدفع',
  `paid_at` timestamp NULL DEFAULT NULL COMMENT 'تاريخ الدفع',
  `quoted_at` timestamp NULL DEFAULT NULL,
  `activated_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `suspended_at` timestamp NULL DEFAULT NULL,
  `renewed_at` timestamp NULL DEFAULT NULL,
  `suspension_reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_requests`
--

INSERT INTO `subscription_requests` (`id`, `user_id`, `serial_number`, `subscription_name`, `device_count`, `proposed_start_date`, `notes`, `status`, `quoted_price`, `payment_method`, `admin_notes`, `payment_receipt`, `paid_at`, `quoted_at`, `activated_at`, `expires_at`, `suspended_at`, `renewed_at`, `suspension_reason`, `created_at`, `updated_at`) VALUES
(2, 4, '963DF16ERF789456', 'اشتراك السوبرماركت', 7, '2025-10-28', '123', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 14:29:44', '2025-10-28 14:29:44'),
(3, 1, '123DF16ERF254698', 'Premium Plan', 3, '2025-10-30', 'طلب اشتراك بريميوم لثلاث أجهزة', 'pending', 110.00, 'vodafone_cash', 'sbdbsb', 'payment-receipts/VjtgIqoq6QLot5XQT2Uf3sfiiM9WiTOXQAwlAFG9.jpg', '2025-10-29 14:33:11', '2025-10-29 14:31:48', NULL, NULL, NULL, NULL, NULL, '2025-10-29 14:28:14', '2025-10-29 15:46:53'),
(4, 2, '456DF46ERF254698', 'Basic Plan', 2, '2025-11-03', 'طلب اشتراك أساسي', 'quoted', 500.00, NULL, NULL, NULL, NULL, '2025-10-29 14:28:27', NULL, NULL, NULL, NULL, NULL, '2025-10-29 14:28:27', '2025-10-29 14:28:27'),
(5, 4, '789D33EERF254698', 'Basic Plan', 2, '2025-11-03', 'طلب اشتراك تجريبي رقم 1', 'active', 500.00, 'تحويل بنكي', NULL, NULL, NULL, '2025-10-27 14:55:53', NULL, NULL, NULL, NULL, NULL, '2025-10-29 14:55:53', '2025-10-29 15:46:23'),
(6, 2, '234DF16ERF25FG45', 'Premium Plan', 5, '2025-11-07', 'طلب اشتراك تجريبي رقم 2', 'active', 800.00, 'فيزا/ماستركارد', NULL, NULL, NULL, '2025-10-28 14:55:53', NULL, NULL, NULL, NULL, NULL, '2025-10-29 14:55:53', '2025-10-29 15:56:34'),
(7, 4, '258DF16ERFFFR45A', 'Pro Plan', 10, '2025-10-31', 'طلب اشتراك تجريبي رقم 3', 'active', 1200.00, 'فودافون كاش', NULL, NULL, NULL, '2025-10-26 14:55:53', NULL, NULL, NULL, NULL, NULL, '2025-10-29 14:55:53', '2025-10-29 15:47:07'),
(8, 2, '852DF16ERF2Q4C9W', 'اشتراك المكتب', 10, '2025-10-31', 'عايز الاشتراك يكون مميز', 'quoted', 320.00, 'vodafone_cash', NULL, NULL, NULL, '2025-10-29 17:17:50', NULL, NULL, NULL, NULL, NULL, '2025-10-29 17:11:44', '2025-10-29 17:17:50');

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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_request_devices`
--

INSERT INTO `subscription_request_devices` (`id`, `subscription_request_id`, `device_identifier`, `iphone_model`, `device_nickname`, `special_requirements`, `created_at`, `updated_at`) VALUES
(1, 2, 'T5SG345G24', 'iPhone 14 Pro Max', 'جهاز الحاجة ام مصطفى', NULL, '2025-10-29 16:14:36', '2025-10-29 16:14:36'),
(2, 2, '1234568754', 'iPhone 14', 'جهاز محمد السواق', NULL, '2025-10-29 16:15:00', '2025-10-29 16:15:00'),
(3, 2, '1234568755', 'iPhone 15 Pro Max', 'جهازى الشخصي', NULL, '2025-10-29 16:15:34', '2025-10-29 16:15:34'),
(4, 2, '12R25D54RE', 'iPhone 12 Pro', 'جهاز ام العيال', '121365', '2025-10-29 16:16:59', '2025-10-29 16:16:59'),
(5, 8, '1234568756', 'iPhone 15 Pro', 'جهاز الالبواب', NULL, '2025-10-29 17:12:30', '2025-10-29 17:12:30');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','client') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'client',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `employee_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `is_app_admin` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_employee_number_unique` (`employee_number`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `employee_number`, `password`, `remember_token`, `created_at`, `updated_at`, `phone`, `address`, `notes`, `last_login_at`, `is_app_admin`) VALUES
(1, 'أحمد محمد علي', 'ahmed@test.com', 'client', NULL, NULL, '$2y$12$UQ4PQ0WoSEHMT7Wl6gKBj.ukqd5.itBsEDtExWlW3khJKIht3kuXW', NULL, '2025-10-27 20:49:14', '2025-10-27 20:49:14', '+201234567890', NULL, NULL, NULL, 0),
(2, 'فاطمة سالم', 'fatma@test.com', 'client', NULL, NULL, '$2y$12$JGyQZscVz.WUonPfDeiH/.1bIwxWDSsJQ2NJSjGGfOzq0SOqYwcLW', NULL, '2025-10-27 20:49:14', '2025-10-27 20:49:14', '+201098765432', NULL, NULL, NULL, 0),
(3, 'مدير النظام', 'admin@test.com', 'admin', NULL, 'EMP8565', '$2y$12$qVEiLVDccfvh2n1wUBW0QOHlBWvaeDtP0P.I9F4eKMb6y5QTsaDem', 'IgNw76ouSoNsQfxRjo7OePtyZOuSbkqo6L1XIZ6FTvhv4l9TTOAeYNRJSaQl', '2025-10-27 20:49:14', '2025-10-27 20:49:14', NULL, NULL, NULL, NULL, 0),
(4, 'عميل تجريبي 1', 'client1@test.com', 'client', NULL, NULL, '$2y$12$AI.XVEUnEEom5DXAX9cR6uulAuTtzlx48RE3V9eUBpCJFt86oKney', NULL, '2025-10-29 14:55:52', '2025-10-29 14:55:52', NULL, NULL, NULL, NULL, 0),
(13, 'Atef Aql', 'admin2@test.com', 'admin', NULL, 'EMP9533', '$2y$12$/19Asuv4hkeX2G9pLkeB7On/l0mTYQsY1aN7zODSKp1/NG5CX4.bS', NULL, '2025-10-30 16:09:00', '2025-10-30 16:09:00', '1158954906', 'Helwan, 12 Ahmed Aunsi st.', NULL, NULL, 0),
(14, 'Home Delivery', 'client23@test.com', 'admin', NULL, 'EMP9104', '$2y$12$AyGAr/GZytrVm7sanJ/EsOUiyXuS9HCtF5UYqAca1bg.X802wNyYe', NULL, '2025-10-30 16:24:04', '2025-10-30 16:24:04', '1158930906', 'Helwan, 12 Ahmed Aunsi st.', NULL, NULL, 0),
(15, 'Main Branch', 'client2@test.com', 'admin', NULL, 'EMP8255', '$2y$12$T/ayFlF3PXf8A/t4SImEIuT0fE00RIZ70oOXcHquXI4tfgwU5lWjq', NULL, '2025-10-30 16:51:53', '2025-10-30 16:51:53', '1158950907', 'Helwan, 12 Ahmed Aunsi st.', NULL, NULL, 0),
(16, 'Musallam', 'admin6@test.com', 'client', NULL, NULL, '$2y$12$qSxQp/Rbt5F2RsNkEqVjHue/IFKzA4eJHQGDrIT51.Bfg7EcAyAGG', NULL, '2025-10-30 17:08:17', '2025-10-30 17:08:17', '9661158950904', 'Helwan, 12 Ahmed Aunsi st.', NULL, NULL, 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `subscription_requests`
--
ALTER TABLE `subscription_requests`
  ADD CONSTRAINT `subscription_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
