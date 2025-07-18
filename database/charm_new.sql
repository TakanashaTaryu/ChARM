-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 16, 2025 at 05:26 PM
-- Server version: 8.0.30
-- PHP Version: 8.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `charm_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_permission`
--

CREATE TABLE `admin_permission` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `permission_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permission_value` tinyint(1) DEFAULT '1',
  `granted_by` int UNSIGNED DEFAULT NULL,
  `granted_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Admin permission management';

-- --------------------------------------------------------

--
-- Table structure for table `cart_item`
--

CREATE TABLE `cart_item` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `quantity` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `rent_start` date NOT NULL,
  `rent_end` date NOT NULL,
  `rental_days` tinyint UNSIGNED GENERATED ALWAYS AS (((to_days(`rent_end`) - to_days(`rent_start`)) + 1)) STORED,
  `unit_price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) GENERATED ALWAYS AS (((`quantity` * `unit_price`) * `rental_days`)) STORED,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `sort_order` int DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Costume categories';

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `image_url`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Anime Characters', 'Costumes inspired by popular anime characters', NULL, 1, 1, '2025-07-16 16:49:57', '2025-07-16 16:49:57'),
(2, 'Movie Characters', 'Costumes from famous movies and TV shows', NULL, 1, 2, '2025-07-16 16:49:57', '2025-07-16 16:49:57'),
(3, 'Historical', 'Historical period costumes and traditional wear', NULL, 1, 3, '2025-07-16 16:49:57', '2025-07-16 16:49:57'),
(4, 'Fantasy', 'Fantasy and mythical character costumes', NULL, 1, 4, '2025-07-16 16:49:57', '2025-07-16 16:49:57'),
(5, 'Superheroes', 'Superhero and comic book character costumes', NULL, 1, 5, '2025-07-16 16:49:57', '2025-07-16 16:49:57'),
(6, 'Casual Cosplay', 'Everyday wearable cosplay items', NULL, 1, 6, '2025-07-16 16:49:57', '2025-07-16 16:49:57'),
(10, 'Manga Heroes', 'Manga character costumes', NULL, 1, 0, '2025-07-16 17:15:47', '2025-07-16 17:15:47'),
(11, 'Gaming', 'Video game character costumes', NULL, 1, 0, '2025-07-16 17:15:47', '2025-07-16 17:15:47');

-- --------------------------------------------------------

--
-- Table structure for table `costumes`
--

CREATE TABLE `costumes` (
  `id` int UNSIGNED NOT NULL,
  `owner_id` int UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `size` enum('XS','S','M','L','XL','XXL','One Size') COLLATE utf8mb4_unicode_ci DEFAULT 'M',
  `condition_rating` tinyint UNSIGNED DEFAULT '5' COMMENT '1-5 rating',
  `image1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Primary image path',
  `image2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Secondary image path',
  `image3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Additional image path',
  `price_per_day` decimal(10,2) NOT NULL,
  `security_deposit` decimal(10,2) DEFAULT '0.00',
  `status` enum('available','rented','maintenance','retired') COLLATE utf8mb4_unicode_ci DEFAULT 'available',
  `min_rental_days` tinyint UNSIGNED DEFAULT '1',
  `max_rental_days` tinyint UNSIGNED DEFAULT '7',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Costume inventory';

--
-- Dumping data for table `costumes`
--

INSERT INTO `costumes` (`id`, `owner_id`, `name`, `category_id`, `description`, `size`, `condition_rating`, `image1`, `image2`, `image3`, `price_per_day`, `security_deposit`, `status`, `min_rental_days`, `max_rental_days`, `created_at`, `updated_at`) VALUES
(1, 1, 'Gojo Satoru Costume', 1, 'Jujutsu Kaisen Gojo Satoru cosplay outfit', 'M', 9, NULL, NULL, NULL, 50000.00, 0.00, 'available', 1, 7, '2025-07-16 17:16:34', '2025-07-16 17:16:34'),
(2, 1, 'Miku Hatsune Costume', 1, 'Vocaloid Hatsune Miku cosplay dress', 'S', 8, NULL, NULL, NULL, 45000.00, 0.00, 'available', 1, 7, '2025-07-16 17:16:34', '2025-07-16 17:16:34'),
(3, 1, 'Naruto Uzumaki Costume', 1, 'Naruto Shippuden orange jumpsuit', 'L', 9, NULL, NULL, NULL, 40000.00, 0.00, 'available', 1, 7, '2025-07-16 17:16:34', '2025-07-16 17:16:34');

-- --------------------------------------------------------

--
-- Table structure for table `coupon`
--

CREATE TABLE `coupon` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coupon_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('percentage','fixed_amount') COLLATE utf8mb4_unicode_ci DEFAULT 'percentage',
  `value` decimal(10,2) NOT NULL,
  `minimum_order` decimal(10,2) DEFAULT '0.00',
  `maximum_discount` decimal(10,2) DEFAULT NULL,
  `usage_limit` int UNSIGNED DEFAULT NULL,
  `used_count` int UNSIGNED DEFAULT '0',
  `valid_from` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `valid_until` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ;

--
-- Dumping data for table `coupon`
--

INSERT INTO `coupon` (`id`, `name`, `coupon_code`, `type`, `value`, `minimum_order`, `maximum_discount`, `usage_limit`, `used_count`, `valid_from`, `valid_until`, `is_active`, `description`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Welcome Discount', 'WELCOME10', 'percentage', 10.00, 50000.00, NULL, NULL, 0, '2025-07-16 16:49:57', '2026-07-16 16:49:57', 1, 'Welcome discount for new users', '2025-07-16 16:49:57', '2025-07-16 16:49:57', NULL),
(2, 'Student Discount', 'STUDENT15', 'percentage', 15.00, 30000.00, NULL, NULL, 0, '2025-07-16 16:49:57', '2026-07-16 16:49:57', 1, 'Special discount for students', '2025-07-16 16:49:57', '2025-07-16 16:49:57', NULL),
(3, 'Weekend Special', 'WEEKEND20', 'percentage', 20.00, 100000.00, NULL, NULL, 0, '2025-07-16 16:49:57', '2026-01-16 16:49:57', 1, 'Weekend special discount', '2025-07-16 16:49:57', '2025-07-16 16:49:57', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `otp_codes`
--

CREATE TABLE `otp_codes` (
  `id` int UNSIGNED NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `otp_code` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `purpose` enum('email_verification','password_reset','2fa') COLLATE utf8mb4_unicode_ci DEFAULT 'email_verification',
  `attempts` tinyint UNSIGNED DEFAULT '0',
  `max_attempts` tinyint UNSIGNED DEFAULT '3',
  `is_used` tinyint(1) DEFAULT '0',
  `expires_at` timestamp NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `otp_codes`
--

INSERT INTO `otp_codes` (`id`, `email`, `otp_code`, `purpose`, `attempts`, `max_attempts`, `is_used`, `expires_at`, `created_at`) VALUES
(1, 'fiqrifirmansyah15@gmail.com', '919737', 'email_verification', 0, 3, 1, '2025-07-17 10:10:31', '2025-07-16 17:10:31');

-- --------------------------------------------------------

--
-- Table structure for table `payment_details`
--

CREATE TABLE `payment_details` (
  `id` int UNSIGNED NOT NULL,
  `rent_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `payment_method` enum('midtrans','bank_transfer','cash','ewallet') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'midtrans',
  `payment_gateway` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) COLLATE utf8mb4_unicode_ci DEFAULT 'IDR',
  `exchange_rate` decimal(10,4) DEFAULT '1.0000',
  `payment_url` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','processing','completed','failed','cancelled','expired','refunded') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `status_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Payment transaction details';

-- --------------------------------------------------------

--
-- Table structure for table `payment_notif`
--

CREATE TABLE `payment_notif` (
  `id` int UNSIGNED NOT NULL,
  `payment_id` int UNSIGNED NOT NULL,
  `rent_id` int UNSIGNED NOT NULL,
  `notification_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transaction_status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_message` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `raw_notification` json DEFAULT NULL,
  `processed` tinyint(1) DEFAULT '0',
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Payment gateway notifications';

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `preferred_payment_method` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Extended user profiles';

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id`, `user_id`, `phone`, `date_of_birth`, `gender`, `preferred_payment_method`, `avatar_url`, `bio`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-16 16:56:49', '2025-07-16 16:56:49'),
(2, 3, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-16 17:10:31', '2025-07-16 17:10:31');

-- --------------------------------------------------------

--
-- Table structure for table `rent_details`
--

CREATE TABLE `rent_details` (
  `id` int UNSIGNED NOT NULL,
  `order_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `address_id` int UNSIGNED NOT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `shipping_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `coupon_id` int UNSIGNED DEFAULT NULL,
  `coupon_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_discount` decimal(10,2) DEFAULT '0.00',
  `status` enum('pending','confirmed','shipped','delivered','returned','cancelled','completed') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `payment_status` enum('pending','paid','failed','refunded','partial_refund') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `rent_start` date NOT NULL,
  `rent_end` date NOT NULL,
  `actual_return_date` date DEFAULT NULL,
  `late_fee` decimal(10,2) DEFAULT '0.00',
  `damage_fee` decimal(10,2) DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ;

-- --------------------------------------------------------

--
-- Table structure for table `rent_items`
--

CREATE TABLE `rent_items` (
  `id` int UNSIGNED NOT NULL,
  `rent_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `quantity` tinyint UNSIGNED NOT NULL DEFAULT '1',
  `unit_price` decimal(10,2) NOT NULL,
  `rental_days` tinyint UNSIGNED NOT NULL,
  `subtotal` decimal(10,2) GENERATED ALWAYS AS (((`quantity` * `unit_price`) * `rental_days`)) STORED,
  `item_status` enum('pending','confirmed','shipped','delivered','returned','damaged') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `condition_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Individual rental items';

--
-- Triggers `rent_items`
--
DELIMITER $$
CREATE TRIGGER `update_stock_on_rent` AFTER INSERT ON `rent_items` FOR EACH ROW BEGIN
    UPDATE `stocks` 
    SET 
        `quantity_available` = `quantity_available` - NEW.`quantity`,
        `quantity_rented` = `quantity_rented` + NEW.`quantity`
    WHERE `costume_id` = NEW.`product_id`;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_stock_on_return` AFTER UPDATE ON `rent_items` FOR EACH ROW BEGIN
    IF OLD.`item_status` != 'returned' AND NEW.`item_status` = 'returned' THEN
        UPDATE `stocks` 
        SET 
            `quantity_available` = `quantity_available` + NEW.`quantity`,
            `quantity_rented` = `quantity_rented` - NEW.`quantity`
        WHERE `costume_id` = NEW.`product_id`;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` int UNSIGNED NOT NULL,
  `costume_id` int UNSIGNED NOT NULL,
  `quantity_total` int UNSIGNED NOT NULL DEFAULT '1',
  `quantity_available` int UNSIGNED NOT NULL DEFAULT '1',
  `quantity_rented` int UNSIGNED NOT NULL DEFAULT '0',
  `quantity_maintenance` int UNSIGNED NOT NULL DEFAULT '0',
  `last_inventory_check` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `costume_id`, `quantity_total`, `quantity_available`, `quantity_rented`, `quantity_maintenance`, `last_inventory_check`, `created_at`, `updated_at`) VALUES
(7, 1, 2, 2, 0, 0, NULL, '2025-07-16 17:17:26', '2025-07-16 17:17:26'),
(8, 2, 1, 1, 0, 0, NULL, '2025-07-16 17:17:26', '2025-07-16 17:17:26'),
(9, 3, 3, 3, 0, 0, NULL, '2025-07-16 17:17:26', '2025-07-16 17:17:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int UNSIGNED NOT NULL,
  `admin_value` tinyint UNSIGNED DEFAULT '0' COMMENT '0=user, 1=admin, 2=super_admin',
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','inactive','suspended') COLLATE utf8mb4_unicode_ci DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='User accounts and authentication';

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `admin_value`, `username`, `password`, `email`, `first_name`, `last_name`, `email_verified_at`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin', '$2y$10$53ac0Fp1cAk5Mv3ifrWuOOmmCzXPmXImLCtyuK3nteTANC45AbC2K', 'admin@charm.com', 'System', 'Administrator', NULL, 'active', '2025-07-16 16:49:57', '2025-07-16 16:49:57'),
(2, 0, 'tatsuyaryu', '$2y$10$UfRHZkcgOn2oZtBj2bG2DuOxDLlJHrvD1DhYWejKAfoe3FsC6EYpS', 'fiqrifirmansyah945@gmail.com', NULL, NULL, NULL, 'active', '2025-07-16 16:56:49', '2025-07-16 16:56:49'),
(3, 0, 'akun1', '$2y$10$fJRMZpXRfZd7GBtTKAYiZOnVFBqxCtB7GUCeqIz1dZDABin5Rhhmm', 'fiqrifirmansyah15@gmail.com', NULL, NULL, '2025-07-16 17:10:56', 'active', '2025-07-16 17:10:31', '2025-07-16 17:10:56');

-- --------------------------------------------------------

--
-- Table structure for table `user_address`
--

CREATE TABLE `user_address` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `label` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT 'Home' COMMENT 'Address label like Home, Office',
  `full_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Indonesia',
  `delivery_note` text COLLATE utf8mb4_unicode_ci,
  `is_default` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='User shipping addresses';

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_available_costumes`
-- (See below for the actual view)
--
CREATE TABLE `v_available_costumes` (
`category_name` varchar(100)
,`condition_rating` tinyint unsigned
,`description` text
,`id` int unsigned
,`image1` varchar(255)
,`name` varchar(200)
,`owner_username` varchar(50)
,`price_per_day` decimal(10,2)
,`quantity_available` int unsigned
,`size` enum('XS','S','M','L','XL','XXL','One Size')
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_user_rental_history`
-- (See below for the actual view)
--
CREATE TABLE `v_user_rental_history` (
`created_at` timestamp
,`email` varchar(100)
,`order_number` varchar(50)
,`payment_status` enum('pending','paid','failed','refunded','partial_refund')
,`rent_end` date
,`rent_start` date
,`rental_id` int unsigned
,`status` enum('pending','confirmed','shipped','delivered','returned','cancelled','completed')
,`total_amount` decimal(10,2)
,`total_items` bigint
,`username` varchar(50)
);

-- --------------------------------------------------------

--
-- Table structure for table `wish_list`
--

CREATE TABLE `wish_list` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `item_id` int UNSIGNED NOT NULL,
  `priority` tinyint UNSIGNED DEFAULT '1' COMMENT '1=low, 5=high',
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='User wishlist';

-- --------------------------------------------------------

--
-- Structure for view `v_available_costumes`
--
DROP TABLE IF EXISTS `v_available_costumes`;

CREATE ALGORITHM=UNDEFINED DEFINER=`admin`@`localhost` SQL SECURITY DEFINER VIEW `v_available_costumes`  AS SELECT `c`.`id` AS `id`, `c`.`name` AS `name`, `c`.`description` AS `description`, `c`.`price_per_day` AS `price_per_day`, `c`.`image1` AS `image1`, `c`.`size` AS `size`, `c`.`condition_rating` AS `condition_rating`, `cat`.`name` AS `category_name`, `s`.`quantity_available` AS `quantity_available`, `u`.`username` AS `owner_username` FROM (((`costumes` `c` join `categories` `cat` on((`c`.`category_id` = `cat`.`id`))) join `stocks` `s` on((`c`.`id` = `s`.`costume_id`))) join `users` `u` on((`c`.`owner_id` = `u`.`user_id`))) WHERE ((`c`.`status` = 'available') AND (`s`.`quantity_available` > 0) AND (`cat`.`is_active` = true)) ;

-- --------------------------------------------------------

--
-- Structure for view `v_user_rental_history`
--
DROP TABLE IF EXISTS `v_user_rental_history`;

CREATE ALGORITHM=UNDEFINED DEFINER=`admin`@`localhost` SQL SECURITY DEFINER VIEW `v_user_rental_history`  AS SELECT `rd`.`id` AS `rental_id`, `rd`.`order_number` AS `order_number`, `rd`.`status` AS `status`, `rd`.`payment_status` AS `payment_status`, `rd`.`total_amount` AS `total_amount`, `rd`.`rent_start` AS `rent_start`, `rd`.`rent_end` AS `rent_end`, `rd`.`created_at` AS `created_at`, `u`.`username` AS `username`, `u`.`email` AS `email`, count(`ri`.`id`) AS `total_items` FROM ((`rent_details` `rd` join `users` `u` on((`rd`.`user_id` = `u`.`user_id`))) left join `rent_items` `ri` on((`rd`.`id` = `ri`.`rent_id`))) GROUP BY `rd`.`id`, `rd`.`order_number`, `rd`.`status`, `rd`.`payment_status`, `rd`.`total_amount`, `rd`.`rent_start`, `rd`.`rent_end`, `rd`.`created_at`, `u`.`username`, `u`.`email` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_permission`
--
ALTER TABLE `admin_permission`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_user_permission` (`user_id`,`permission_name`),
  ADD KEY `fk_admin_granted_by` (`granted_by`),
  ADD KEY `idx_permission_name` (`permission_name`);

--
-- Indexes for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_cart_user_product` (`user_id`,`product_id`),
  ADD KEY `fk_cart_product` (`product_id`),
  ADD KEY `idx_cart_dates` (`rent_start`,`rent_end`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `idx_name` (`name`),
  ADD KEY `idx_active_sort` (`is_active`,`sort_order`);

--
-- Indexes for table `costumes`
--
ALTER TABLE `costumes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_costume_category` (`category_id`),
  ADD KEY `fk_costume_owner` (`owner_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_price` (`price_per_day`),
  ADD KEY `idx_name` (`name`);

--
-- Indexes for table `coupon`
--
ALTER TABLE `coupon`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupon_code` (`coupon_code`),
  ADD UNIQUE KEY `uk_coupon_code` (`coupon_code`),
  ADD KEY `idx_active_dates` (`is_active`,`valid_from`,`valid_until`),
  ADD KEY `idx_code_active` (`coupon_code`,`is_active`);

--
-- Indexes for table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email_purpose` (`email`,`purpose`),
  ADD KEY `idx_otp_code` (`otp_code`),
  ADD KEY `idx_expires` (`expires_at`);

--
-- Indexes for table `payment_details`
--
ALTER TABLE `payment_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_payment_transaction` (`transaction_id`),
  ADD KEY `fk_payment_rent` (`rent_id`),
  ADD KEY `fk_payment_user` (`user_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_payment_method` (`payment_method`),
  ADD KEY `idx_transaction_id` (`transaction_id`);

--
-- Indexes for table `payment_notif`
--
ALTER TABLE `payment_notif`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notif_payment` (`payment_id`),
  ADD KEY `fk_notif_rent` (`rent_id`),
  ADD KEY `idx_processed` (`processed`),
  ADD KEY `idx_notification_type` (`notification_type`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `uk_profile_user` (`user_id`);

--
-- Indexes for table `rent_details`
--
ALTER TABLE `rent_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD UNIQUE KEY `uk_order_number` (`order_number`),
  ADD KEY `fk_rent_user` (`user_id`),
  ADD KEY `fk_rent_address` (`address_id`),
  ADD KEY `fk_rent_coupon` (`coupon_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_payment_status` (`payment_status`),
  ADD KEY `idx_dates` (`rent_start`,`rent_end`);

--
-- Indexes for table `rent_items`
--
ALTER TABLE `rent_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_rent_item_rent` (`rent_id`),
  ADD KEY `fk_rent_item_user` (`user_id`),
  ADD KEY `fk_rent_item_product` (`product_id`),
  ADD KEY `idx_item_status` (`item_status`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `costume_id` (`costume_id`),
  ADD UNIQUE KEY `uk_stock_costume` (`costume_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_admin_value` (`admin_value`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_username` (`username`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `user_address`
--
ALTER TABLE `user_address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_address_user` (`user_id`),
  ADD KEY `idx_default` (`user_id`,`is_default`);

--
-- Indexes for table `wish_list`
--
ALTER TABLE `wish_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_wishlist_user_item` (`user_id`,`item_id`),
  ADD KEY `fk_wishlist_item` (`item_id`),
  ADD KEY `idx_priority` (`priority`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_permission`
--
ALTER TABLE `admin_permission`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_item`
--
ALTER TABLE `cart_item`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `costumes`
--
ALTER TABLE `costumes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `coupon`
--
ALTER TABLE `coupon`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_details`
--
ALTER TABLE `payment_details`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_notif`
--
ALTER TABLE `payment_notif`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profiles`
--
ALTER TABLE `profiles`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rent_details`
--
ALTER TABLE `rent_details`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rent_items`
--
ALTER TABLE `rent_items`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user_address`
--
ALTER TABLE `user_address`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wish_list`
--
ALTER TABLE `wish_list`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_permission`
--
ALTER TABLE `admin_permission`
  ADD CONSTRAINT `fk_admin_granted_by` FOREIGN KEY (`granted_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_admin_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `costumes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `costumes`
--
ALTER TABLE `costumes`
  ADD CONSTRAINT `fk_costume_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_costume_owner` FOREIGN KEY (`owner_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `payment_details`
--
ALTER TABLE `payment_details`
  ADD CONSTRAINT `fk_payment_rent` FOREIGN KEY (`rent_id`) REFERENCES `rent_details` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_payment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `payment_notif`
--
ALTER TABLE `payment_notif`
  ADD CONSTRAINT `fk_notif_payment` FOREIGN KEY (`payment_id`) REFERENCES `payment_details` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_notif_rent` FOREIGN KEY (`rent_id`) REFERENCES `rent_details` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `fk_profile_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `rent_details`
--
ALTER TABLE `rent_details`
  ADD CONSTRAINT `fk_rent_address` FOREIGN KEY (`address_id`) REFERENCES `user_address` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rent_coupon` FOREIGN KEY (`coupon_id`) REFERENCES `coupon` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rent_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `rent_items`
--
ALTER TABLE `rent_items`
  ADD CONSTRAINT `fk_rent_item_product` FOREIGN KEY (`product_id`) REFERENCES `costumes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rent_item_rent` FOREIGN KEY (`rent_id`) REFERENCES `rent_details` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_rent_item_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `fk_stock_costume` FOREIGN KEY (`costume_id`) REFERENCES `costumes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_address`
--
ALTER TABLE `user_address`
  ADD CONSTRAINT `fk_address_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `wish_list`
--
ALTER TABLE `wish_list`
  ADD CONSTRAINT `fk_wishlist_item` FOREIGN KEY (`item_id`) REFERENCES `costumes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_wishlist_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
