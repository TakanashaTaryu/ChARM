-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 28, 2024 at 10:06 AM
-- Server version: 8.0.39
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `charm_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_permission`
--

CREATE TABLE `admin_permission` (
  `id` int UNSIGNED NOT NULL,
  `admin_value` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_item`
--

CREATE TABLE `cart_item` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `session_id` int NOT NULL,
  `quantity` int NOT NULL,
  `rent_start` timestamp NOT NULL,
  `rent_done` timestamp NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `costumes`
--

CREATE TABLE `costumes` (
  `id` int UNSIGNED NOT NULL,
  `owner_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `price_per_day` decimal(8,2) NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon`
--

CREATE TABLE `coupon` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `coupon_code` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `value` decimal(8,2) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `midtrans`
--

CREATE TABLE `midtrans` (
  `id` int UNSIGNED NOT NULL,
  `payment_id` int UNSIGNED NOT NULL,
  `payment_amount` decimal(8,2) NOT NULL,
  `payment_url` varchar(255) NOT NULL,
  `payment_status_code` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_details`
--

CREATE TABLE `payment_details` (
  `id` int UNSIGNED NOT NULL,
  `rent_id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `shipping_cost` decimal(8,2) NOT NULL,
  `coupon_value` decimal(8,2) NOT NULL,
  `coupon_id` int UNSIGNED NOT NULL,
  `payment_gateway` int UNSIGNED NOT NULL,
  `payment_code` varchar(255) NOT NULL,
  `payment_url_API` varchar(255) NOT NULL,
  `status_code` int NOT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_notif`
--

CREATE TABLE `payment_notif` (
  `id` int UNSIGNED NOT NULL,
  `rent_id` int UNSIGNED NOT NULL,
  `notif_type` varchar(255) NOT NULL,
  `status_code` int NOT NULL,
  `status` varchar(255) NOT NULL,
  `payment_id` int UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `fav_waifu` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rent_details`
--

CREATE TABLE `rent_details` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `total` decimal(8,2) NOT NULL,
  `payment_id` int UNSIGNED NOT NULL,
  `address_id` int UNSIGNED NOT NULL,
  `coupon_id` int UNSIGNED DEFAULT NULL,
  `coupon_name` varchar(255) DEFAULT NULL,
  `shipping_cost` decimal(8,2) NOT NULL,
  `rent_start` timestamp NOT NULL,
  `rent_done` timestamp NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rent_items`
--

CREATE TABLE `rent_items` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `rent_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `coupon_id` int UNSIGNED NOT NULL,
  `coupon_name` varchar(255) NOT NULL,
  `quantity` int NOT NULL,
  `rent_start` timestamp NOT NULL,
  `rent_done` timestamp NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` int UNSIGNED NOT NULL,
  `costume_id` int UNSIGNED NOT NULL,
  `quantity_available` int NOT NULL,
  `quantity_rented` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int UNSIGNED NOT NULL,
  `admin_value` int NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_address`
--

CREATE TABLE `user_address` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) DEFAULT NULL,
  `delivery_note` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wish_list`
--

CREATE TABLE `wish_list` (
  `id` int UNSIGNED NOT NULL,
  `user_id` int UNSIGNED NOT NULL,
  `item_id` int UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_permission`
--
ALTER TABLE `admin_permission`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_admin_user` (`admin_value`);

--
-- Indexes for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cart_user` (`user_id`),
  ADD KEY `fk_cart_product` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `costumes`
--
ALTER TABLE `costumes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_costume_category` (`category_id`);

--
-- Indexes for table `coupon`
--
ALTER TABLE `coupon`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `value` (`value`);

--
-- Indexes for table `midtrans`
--
ALTER TABLE `midtrans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_url` (`payment_url`),
  ADD UNIQUE KEY `payment_status_code` (`payment_status_code`),
  ADD KEY `fk_midtrans_payment` (`payment_id`);

--
-- Indexes for table `payment_details`
--
ALTER TABLE `payment_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_payment_user` (`user_id`),
  ADD KEY `fk_payment_rent` (`rent_id`),
  ADD KEY `fk_payment_url_API` (`payment_url_API`),
  ADD KEY `fk_payment_gateway` (`payment_gateway`),
  ADD KEY `fk_payment_status_code` (`status_code`),
  ADD KEY `fk_payment_coupon_value` (`coupon_value`),
  ADD KEY `fk_payment_coupon_id` (`coupon_id`),
  ADD KEY `fk_payment_shipping_cost` (`shipping_cost`);

--
-- Indexes for table `payment_notif`
--
ALTER TABLE `payment_notif`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_notif_rent` (`rent_id`),
  ADD KEY `fk_notif_payment` (`payment_id`),
  ADD KEY `fk_notif_status_code` (`status_code`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_profile_user` (`user_id`);

--
-- Indexes for table `rent_details`
--
ALTER TABLE `rent_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipping_cost` (`shipping_cost`),
  ADD KEY `fk_rent_user` (`user_id`),
  ADD KEY `fk_rent_payment` (`payment_id`),
  ADD KEY `fk_rent_address` (`address_id`),
  ADD KEY `fk_rent_coupon` (`coupon_id`);

--
-- Indexes for table `rent_items`
--
ALTER TABLE `rent_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_rent_item_user` (`user_id`),
  ADD KEY `fk_rent_item_rent` (`rent_id`),
  ADD KEY `fk_rent_item_product` (`product_id`),
  ADD KEY `fk_rent_item_coupon` (`coupon_id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_stock_costume` (`costume_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `admin_value` (`admin_value`),
  ADD KEY `idx_admin_value` (`admin_value`);

--
-- Indexes for table `user_address`
--
ALTER TABLE `user_address`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_address_user` (`user_id`);

--
-- Indexes for table `wish_list`
--
ALTER TABLE `wish_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_wishlist_user` (`user_id`),
  ADD KEY `fk_wishlist_item` (`item_id`);

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `costumes`
--
ALTER TABLE `costumes`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon`
--
ALTER TABLE `coupon`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `midtrans`
--
ALTER TABLE `midtrans`
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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `user_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

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
  ADD CONSTRAINT `fk_admin_user` FOREIGN KEY (`admin_value`) REFERENCES `users` (`admin_value`);

--
-- Constraints for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`product_id`) REFERENCES `costumes` (`id`),
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `costumes`
--
ALTER TABLE `costumes`
  ADD CONSTRAINT `fk_costume_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `midtrans`
--
ALTER TABLE `midtrans`
  ADD CONSTRAINT `fk_midtrans_payment` FOREIGN KEY (`payment_id`) REFERENCES `payment_details` (`id`);

--
-- Constraints for table `payment_details`
--
ALTER TABLE `payment_details`
  ADD CONSTRAINT `fk_payment_coupon_id` FOREIGN KEY (`coupon_id`) REFERENCES `coupon` (`id`),
  ADD CONSTRAINT `fk_payment_coupon_value` FOREIGN KEY (`coupon_value`) REFERENCES `coupon` (`value`),
  ADD CONSTRAINT `fk_payment_gateway` FOREIGN KEY (`payment_gateway`) REFERENCES `midtrans` (`id`),
  ADD CONSTRAINT `fk_payment_rent` FOREIGN KEY (`rent_id`) REFERENCES `rent_details` (`id`),
  ADD CONSTRAINT `fk_payment_shipping_cost` FOREIGN KEY (`shipping_cost`) REFERENCES `rent_details` (`shipping_cost`),
  ADD CONSTRAINT `fk_payment_status_code` FOREIGN KEY (`status_code`) REFERENCES `midtrans` (`payment_status_code`),
  ADD CONSTRAINT `fk_payment_url_API` FOREIGN KEY (`payment_url_API`) REFERENCES `midtrans` (`payment_url`),
  ADD CONSTRAINT `fk_payment_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `payment_notif`
--
ALTER TABLE `payment_notif`
  ADD CONSTRAINT `fk_notif_payment` FOREIGN KEY (`payment_id`) REFERENCES `payment_details` (`id`),
  ADD CONSTRAINT `fk_notif_rent` FOREIGN KEY (`rent_id`) REFERENCES `rent_details` (`id`),
  ADD CONSTRAINT `fk_notif_status_code` FOREIGN KEY (`status_code`) REFERENCES `midtrans` (`payment_status_code`);

--
-- Constraints for table `profiles`
--
ALTER TABLE `profiles`
  ADD CONSTRAINT `fk_profile_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `rent_details`
--
ALTER TABLE `rent_details`
  ADD CONSTRAINT `fk_rent_address` FOREIGN KEY (`address_id`) REFERENCES `user_address` (`id`),
  ADD CONSTRAINT `fk_rent_coupon` FOREIGN KEY (`coupon_id`) REFERENCES `coupon` (`id`),
  ADD CONSTRAINT `fk_rent_payment` FOREIGN KEY (`payment_id`) REFERENCES `payment_details` (`id`),
  ADD CONSTRAINT `fk_rent_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `rent_items`
--
ALTER TABLE `rent_items`
  ADD CONSTRAINT `fk_rent_item_coupon` FOREIGN KEY (`coupon_id`) REFERENCES `coupon` (`id`),
  ADD CONSTRAINT `fk_rent_item_product` FOREIGN KEY (`product_id`) REFERENCES `costumes` (`id`),
  ADD CONSTRAINT `fk_rent_item_rent` FOREIGN KEY (`rent_id`) REFERENCES `rent_details` (`id`),
  ADD CONSTRAINT `fk_rent_item_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `fk_stock_costume` FOREIGN KEY (`costume_id`) REFERENCES `costumes` (`id`);

--
-- Constraints for table `user_address`
--
ALTER TABLE `user_address`
  ADD CONSTRAINT `fk_address_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `wish_list`
--
ALTER TABLE `wish_list`
  ADD CONSTRAINT `fk_wishlist_item` FOREIGN KEY (`item_id`) REFERENCES `costumes` (`id`),
  ADD CONSTRAINT `fk_wishlist_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
