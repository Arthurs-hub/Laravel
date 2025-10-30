-- Hotel Booking Platform Database Schema
-- Laravel 12 Framework
-- Multi-language support: English, Russian, Arabic, French, German, Italian, Spanish
-- Full timezone support for global deployment
-- 
-- SETUP INSTRUCTIONS:
-- 1. Create database: CREATE DATABASE hotel_booking_platform;
-- 2. Import this file: mysql -u root -p hotel_booking_platform < database.sql
-- 3. Run Laravel setup: php artisan app:quick-setup

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS `hotel_booking_platform` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `hotel_booking_platform`;

DROP TABLE IF EXISTS `bookings`;

CREATE TABLE `bookings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `room_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `started_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `finished_at` timestamp NULL DEFAULT NULL,
  `days` int(11) NOT NULL,
  `adults` int(11) NOT NULL DEFAULT 1,
  `children` int(11) NOT NULL DEFAULT 0,
  `price` int(11) NOT NULL,
  `special_requests` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bookings_room_id_foreign` (`room_id`),
  KEY `bookings_user_id_foreign` (`user_id`),
  CONSTRAINT `bookings_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

/*!40101 SET character_set_client = @saved_cs_client */
;

DROP TABLE IF EXISTS `cache`;

/*!40101 SET @saved_cs_client     = @@character_set_client */
;

/*!40101 SET character_set_client = utf8 */
;

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache_locks`;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `facilities`;

CREATE TABLE `facilities` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `facilities_title_unique` (`title`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;
DROP TABLE IF EXISTS `facility_hotel`;
CREATE TABLE `facility_hotel` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `facility_id` bigint(20) unsigned NOT NULL,
  `hotel_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `facility_hotel_facility_id_foreign` (`facility_id`),
  KEY `facility_hotel_hotel_id_foreign` (`hotel_id`),
  CONSTRAINT `facility_hotel_facility_id_foreign` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `facility_hotel_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `facility_room`;

CREATE TABLE `facility_room` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `facility_id` bigint(20) unsigned NOT NULL,
  `room_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `facility_room_facility_id_foreign` (`facility_id`),
  KEY `facility_room_room_id_foreign` (`room_id`),
  CONSTRAINT `facility_room_facility_id_foreign` FOREIGN KEY (`facility_id`) REFERENCES `facilities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `facility_room_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `hotel_arabic_addresses`;

CREATE TABLE `hotel_arabic_addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `english_title` varchar(255) NOT NULL,
  `arabic_address` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_title` (`english_title`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `hotel_arabic_descriptions`;

CREATE TABLE `hotel_arabic_descriptions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `english_title` varchar(255) NOT NULL,
  `arabic_description` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_title` (`english_title`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `hotel_translations`;

CREATE TABLE `hotel_translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `original_title` varchar(255) NOT NULL,
  `arabic_title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_original` (`original_title`),
  UNIQUE KEY `unique_slug` (`slug`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `hotels`;

CREATE TABLE `hotels` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `poster_url` varchar(500) DEFAULT NULL,
  `address` varchar(500) NOT NULL,
  `country` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `rating` decimal(2, 1) NOT NULL DEFAULT 4.5,
  `manager_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hotels_manager_id_foreign` (`manager_id`),
  CONSTRAINT `hotels_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE
  SET
    NULL
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `job_batches`;

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `notifications`;

CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`, `notifiable_id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `reviews`;

CREATE TABLE `reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `reviewable_type` varchar(255) NOT NULL,
  `reviewable_id` bigint(20) unsigned NOT NULL,
  `content` text NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_user_id_foreign` (`user_id`),
  KEY `reviews_reviewable_type_reviewable_id_index` (`reviewable_type`, `reviewable_id`),
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `rooms`;

CREATE TABLE `rooms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `hotel_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `poster_url` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `floor_area` decimal(8, 2) unsigned NOT NULL,
  `type` varchar(255) NOT NULL,
  `price` decimal(10, 2) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `rooms_hotel_id_foreign` (`hotel_id`),
  CONSTRAINT `rooms_hotel_id_foreign` FOREIGN KEY (`hotel_id`) REFERENCES `hotels` (`id`) ON DELETE CASCADE
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `sessions`;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male', 'female', 'other') DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `passport_number` varchar(50) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `email_verified` tinyint(1) NOT NULL DEFAULT 0,
  `timezone` varchar(255) NOT NULL DEFAULT 'UTC',
  `language` varchar(5) NOT NULL DEFAULT 'en',
  `password` varchar(255) NOT NULL,
  `two_factor_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `two_factor_method` enum('email', 'google_authenticator') DEFAULT NULL,
  `two_factor_secret` varchar(255) DEFAULT NULL,
  `two_factor_code` varchar(255) DEFAULT NULL,
  `two_factor_expires_at` timestamp NULL DEFAULT NULL,
  `email_verification_token` varchar(255) DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (1, '0001_01_01_000000_create_users_table', 1);

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (2, '0001_01_01_000001_create_cache_table', 1);

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (3, '0001_01_01_000002_create_jobs_table', 1);

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (
    4,
    '2024_12_25_091500_add_avatar_to_users_table',
    1
  );

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (5, '2025_01_20_000000_add_role_to_users_table', 1);

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (6, '2025_01_20_000002_add_user_profile_fields', 1);

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (7, '2025_09_19_103253_create_hotels_table', 1);

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (8, '2025_09_19_103254_create_rooms_table', 1);

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (9, '2025_09_19_103255_create_facilities_table', 1);

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (10, '2025_09_19_103256_create_bookings_table', 1);

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (
    11,
    '2025_09_19_103257_create_facility_hotel_table',
    1
  );

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (
    12,
    '2025_09_19_103258_create_facility_room_table',
    1
  );

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (
    13,
    '2025_09_19_103259_add_missing_fields_to_rooms_table',
    1
  );

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (14, '2025_09_19_103261_create_reviews_table', 1);

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (
    15,
    '2025_09_19_103262_create_notifications_table',
    1
  );

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (
    16,
    '2025_09_20_000001_add_manager_id_to_hotels_table',
    1
  );

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (
    17,
    '2025_09_20_000001_add_rating_to_hotels_table',
    1
  );

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (
    18,
    '2025_09_20_000002_add_guests_to_bookings_table',
    1
  );

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (
    19,
    '2025_10_14_120504_add_timezone_to_users_table',
    1
  );

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (
    20,
    '2025_10_20_000001_add_status_to_bookings_table',
    1
  );

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (
    21,
    '2025_10_20_000002_add_status_to_reviews_table',
    1
  );

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (
    22,
    '2025_10_20_000003_add_email_verified_and_language_to_users_table',
    1
  );

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (
    23,
    '2025_10_20_191149_add_special_requests_to_bookings_table',
    1
  );

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (
    24,
    '2025_10_20_000004_add_city_to_hotels_table',
    1
  );

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (
    25,
    '2025_10_20_000005_add_icon_to_facilities_table',
    1
  );

INSERT INTO
  `migrations` (`id`, `migration`, `batch`)
VALUES
  (
    26,
    '2025_01_15_154810_add_image_url_to_rooms_table',
    1
  );

INSERT INTO
  `facilities` (`id`, `title`, `icon`, `created_at`, `updated_at`)
VALUES
  (1, 'Pool', 'swimming-pool', NOW(), NOW()),
  (2, 'Parking', 'parking', NOW(), NOW()),
  (3, 'Wi-Fi', 'wifi', NOW(), NOW()),
  (4, 'Restaurant', 'restaurant', NOW(), NOW()),
  (5, 'Bar', 'bar', NOW(), NOW()),
  (6, 'Fitness Center', 'fitness-center', NOW(), NOW()),
  (7, 'Spa Center', 'spa', NOW(), NOW()),
  (8, 'Airport Transfer', 'transfer', NOW(), NOW()),
  (9, 'Air Conditioning', 'ac', NOW(), NOW()),
  (10, 'Private Bathroom', 'private-bathroom', NOW(), NOW()),
  (11, 'Flat TV', 'flat-tv', NOW(), NOW()),
  (12, 'Mini-bar', 'minibar', NOW(), NOW()),
  (13, 'Safe', 'safe', NOW(), NOW()),
  (14, 'Tea/Coffee Maker', 'tea-coffee', NOW(), NOW()),
  (15, 'Free Wi-Fi in Room', 'free-wifi-in-room', NOW(), NOW()),
  (16, 'Balcony', 'balcony', NOW(), NOW()),
  (17, 'Work Desk', 'work-desk', NOW(), NOW()),
  (18, 'Closet', 'closet', NOW(), NOW()),
  (19, 'Hair Dryer', 'hair-dryer', NOW(), NOW()),
  (20, 'Bathrobe', 'bathrobe', NOW(), NOW()),
  (21, 'Slippers', 'slippers', NOW(), NOW()),
  (22, 'Concierge', 'concierge', NOW(), NOW()),
  (23, 'Transfer', 'transfer', NOW(), NOW()),
  (24, 'Conference Hall', 'conference-hall', NOW(), NOW());

-- Users table is intentionally left empty
-- No default users are created automatically
-- Users must register through the application interface