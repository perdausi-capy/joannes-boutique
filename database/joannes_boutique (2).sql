-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2025 at 03:15 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `joannes_boutique`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `service_type` enum('consultation','fitting','alteration','custom') NOT NULL,
  `preferred_date` date DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_orders`
--

CREATE TABLE `booking_orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_type` enum('rental','package') NOT NULL,
  `item_id` int(11) NOT NULL,
  `event_date` date DEFAULT NULL,
  `rental_start` date DEFAULT NULL,
  `rental_end` date DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_status` enum('verified','failed') DEFAULT 'verified',
  `penalty_amount` decimal(10,2) DEFAULT 0.00,
  `penalty_paid` tinyint(1) DEFAULT 0,
  `actual_return_date` date DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT 'GCash',
  `reference_number` varchar(100) DEFAULT NULL,
  `paymongo_payment_id` varchar(255) DEFAULT NULL,
  `proof_image` varchar(255) DEFAULT NULL,
  `contact_name` varchar(100) DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `size` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_penalty_paid` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_orders`
--

INSERT INTO `booking_orders` (`order_id`, `user_id`, `order_type`, `item_id`, `event_date`, `rental_start`, `rental_end`, `total_amount`, `payment_status`, `penalty_amount`, `penalty_paid`, `actual_return_date`, `payment_method`, `reference_number`, `paymongo_payment_id`, `proof_image`, `contact_name`, `contact_email`, `contact_phone`, `quantity`, `size`, `created_at`, `is_penalty_paid`) VALUES
(62, 4, 'rental', 3, NULL, '2025-11-27', '2025-11-30', 899.00, '', 0.00, 0, NULL, 'GCash', 'BOOKING_62_1764231111', 'link_tvLeLYft2pn6CkgupEYsK659', NULL, 'customer test', 'customer@test.com', '09317151400', 1, '', '2025-11-27 08:11:48', 1),
(74, 4, 'package', 11, '2025-11-29', NULL, NULL, 10000.00, 'verified', 0.00, 0, NULL, 'GCash', 'BOOKING_74_1764500424', 'link_FocdrDWNBq9YQ5vFQwyJdJ6Q', NULL, 'customer test', 'customer@test.com', '09317151466', 1, NULL, '2025-11-30 11:00:22', 0),
(78, 8, 'rental', 1, NULL, '2025-11-30', '2025-12-02', 1299.00, 'verified', 0.00, 0, NULL, 'GCash', 'BOOKING_78_1764503897', 'link_PT7p8zKQMZXhLvSBgJ5Tm5xg', NULL, 'june orias', 'june2@gmail.com', '09598151365', 1, '', '2025-11-30 11:58:15', 0),
(79, 8, 'package', 11, '2025-11-30', NULL, NULL, 10000.00, 'verified', 0.00, 0, NULL, 'GCash', 'BOOKING_79_1764503985', 'link_ExHSL9ikXtF4pEwyM3FEaaR8', NULL, 'customer test', 'customer@gmail.com', '09929166903', 1, NULL, '2025-11-30 11:59:43', 0),
(80, 8, 'package', 12, '2025-11-25', NULL, NULL, 20000.00, 'verified', 0.00, 0, NULL, 'GCash', 'BOOKING_80_1764504302', 'link_fjyvvML5C73JYKW3Ezb2JSWM', NULL, 'customer test', 'customer@gmail.com', '09929166903', 1, NULL, '2025-11-30 12:05:00', 0),
(82, 5, 'package', 11, '2025-12-01', NULL, NULL, 10000.00, 'verified', 0.00, 0, NULL, 'GCash', 'BOOKING_82_1764554240', 'link_P2RFn7X2kmzwcRz1BeRfcKhL', NULL, 'joannes gowns', 'admin@joannesgowns.com', '09562625959', 1, NULL, '2025-12-01 01:57:17', 0),
(83, 5, 'rental', 12, NULL, '2025-12-01', '2025-12-03', 1200.00, 'verified', 0.00, 0, NULL, 'GCash', 'BOOKING_83_1764554399', 'link_Kq19uBJ2YMipbxxsALUmqJsU', NULL, 'joannes gowns', 'admin@joannesgowns.com', '09562625959', 1, '', '2025-12-01 01:59:56', 0),
(84, 4, 'rental', 1, NULL, '2025-12-09', '2025-12-11', 1299.00, 'verified', 0.00, 0, NULL, 'GCash', 'BOOKING_84_1765263787', 'link_b3wsXbQxGBSzf6BcRWqEbdDd', NULL, 'customer test', 'customer@test.com', '09317151400', 1, '', '2025-12-09 07:03:04', 0);

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(100) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `is_active`, `created_at`) VALUES
(1, 'Gowns', 'gowns', '', NULL, 1, '2025-10-19 17:33:47'),
(2, 'Wedding Dresses', 'wedding-dresses', 'Beautiful bridal gowns for your special day', NULL, 1, '2025-10-19 17:33:47'),
(3, 'Suits', 'suits', 'Professional and formal suits', NULL, 1, '2025-10-19 17:33:47'),
(15, 'New Arrival', 'new-arrival', '', NULL, 1, '2025-12-12 05:58:28');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('new','read','responded') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_number` varchar(50) NOT NULL,
  `status` enum('pending','processing','completed','cancelled') DEFAULT 'pending',
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_address` text DEFAULT NULL,
  `billing_address` text DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `packages`
--

CREATE TABLE `packages` (
  `package_id` int(11) NOT NULL,
  `package_name` varchar(255) DEFAULT NULL,
  `hotel_name` varchar(255) DEFAULT NULL,
  `hotel_address` text DEFAULT NULL,
  `hotel_description` text DEFAULT NULL,
  `number_of_guests` int(11) DEFAULT NULL,
  `inclusions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`inclusions`)),
  `freebies` text DEFAULT NULL,
  `background_image` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_reserved` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `packages`
--

INSERT INTO `packages` (`package_id`, `package_name`, `hotel_name`, `hotel_address`, `hotel_description`, `number_of_guests`, `inclusions`, `freebies`, `background_image`, `price`, `created_at`, `is_reserved`) VALUES
(11, 'Basic Joanne\'s Package', 'Sofitel Philippine Plaza', 'CCP Complex, Roxas Boulevard, Manila', 'Experience luxury and sophistication at one of Manila\'s most prestigious venues with stunning views of Manila Bay.', 20, '{\"Venue Rental\":[\"Grand Ballroom (500 pax)\",\"Garden Cocktail Reception\",\"Valet Parking\"],\"Decor\":[\"Elegant floral centerpieces\"],\"Catering\":[\"3-course plated dinner\",\"Unlimited beverages\",\"Coffee and dessert station\"]}', '', 'pkg_bg_69282b8ae1c6a1.74084329.jpg', 10000.00, '2025-11-27 09:57:39', 0),
(12, 'Platinum Joanne\'s Package', 'Sofitel Philippine Plaza', 'CCP Complex, Roxas Boulevard, Manila', 'Experience luxury and sophistication at one of Manila\'s most prestigious venues with stunning views of Manila Bay.', 20, '{\"Venue Rental\":[\"Grand Ballroom (500 pax)\",\"Garden Cocktail Reception\"],\"Catering\":[\"3-course plated dinner\",\"Unlimited beverages\"]}', '', 'pkg_bg_69282a268c96d4.02590884.jpg', 20000.00, '2025-11-27 10:38:30', 0);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock_quantity` int(11) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `image`, `stock_quantity`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Elegant White Gown', 'Sophisticated floor-length gown perfect for formal events and galas\r\n\r\nQuantity: 1\r\nSize: M', 1299.00, 'prod_68f5332c1f80b9.24922026.jpeg', 5, 1, 1, '2025-10-19 17:33:47', '2025-11-26 10:07:02'),
(2, 2, 'Classic Bridal Gown', 'Timeless wedding dress with intricate lace details and cathedral train\r\n\r\nQuantity: 1\r\nSize: S', 2099.00, 'prod_68f53470c9ab57.66314518.jpeg', 3, 1, 1, '2025-10-19 17:33:47', '2025-11-26 10:09:14'),
(3, 3, 'Premium Business Suit', 'Tailored three-piece suit with premium wool fabric\r\n\r\nQuantity: 1\r\nSize: M', 899.00, 'prod_68f5335631a740.47086495.jpeg', 10, 1, 1, '2025-10-19 17:33:47', '2025-11-26 10:09:55'),
(12, 3, 'Sample Toxido', 'New Arrival\r\n\r\nThis is just a sample Toxido\r\n\r\nQuantity: 1\r\nSize: L', 1200.00, 'prod_690b5b88a64188.05745833.jpg', 1, 1, 1, '2025-11-05 14:13:28', '2025-12-12 02:54:37');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `sort_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `filename`, `sort_order`, `created_at`) VALUES
(6, 12, 'prod_extra_690b5b88a77476.77462873.jpg', 0, '2025-11-05 14:13:28'),
(7, 12, 'prod_extra_690b5b88a819a5.94263114.jpg', 1, '2025-11-05 14:13:28');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`id`, `user_id`, `name`, `email`, `message`, `rating`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Sarah Martinez', 'sarah@example.com', 'Absolutely stunning work! Joanne created the perfect wedding dress for me. The attention to detail was incredible, and the fit was flawless. I felt like a princess on my special day.', 5, 'approved', '2025-10-19 17:33:47', '2025-11-05 14:06:54'),
(2, NULL, 'Michael Rodriguez', 'michael@example.com', 'The custom suit I ordered exceeded all my expectations. Professional, elegant, and perfectly tailored. I have received countless compliments and will definitely be returning.', 5, 'approved', '2025-10-19 17:33:47', '2025-11-05 14:06:54'),
(3, NULL, 'Emma Thompson', 'emma@example.com', 'From consultation to final fitting, the service was exceptional. Joanne understood my vision perfectly and created a gown that made me feel confident and beautiful.', 5, 'approved', '2025-10-19 17:33:47', '2025-11-05 14:06:54'),
(5, NULL, 'Basala Pogi', 'basala@gmail.com', 'yan lang ang kaya ko', 3, 'pending', '2025-11-07 06:35:21', '2025-11-07 06:35:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` enum('admin','customer') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`, `phone`, `role`, `created_at`, `updated_at`) VALUES
(2, 'Jane', 'Smith', 'customer@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+63 917 123 4567', 'admin', '2025-10-19 17:33:47', '2025-10-19 17:37:52'),
(3, 'test', 'user', 'test@gmail.com', '$2y$10$ji6N45jMELsA5TRERlCDe.AXh2ASrU6Cv9iKtpDa50Nq4PNLvtEOq', '09598151365', 'admin', '2025-10-19 17:34:45', '2025-10-19 17:38:06'),
(4, 'customer', 'test', 'customer@test.com', '$2y$10$Cm8M87EKOMEt/CJbHEbm0e0BtNeDVvCeGb7Bfo4LCYwVrTYM7SbRO', '09317151400', 'customer', '2025-10-19 17:51:46', '2025-11-09 02:53:03'),
(5, 'joannes', 'gowns', 'admin@joannesgowns.com', '$2y$10$xC9Nm/CqM.yUwbbpGcVgneN9wWvfWzb0Jt7rPVAA1KtdnWLAny/Ti', '09562625959', 'admin', '2025-10-19 19:09:13', '2025-10-19 19:09:31'),
(6, 'andrea', 'brillantes', 'adrea@gmail.com', '$2y$10$aQfuf0SycN/ONiFATFLo2ewfA1bbsOs.NdAdAHXZrAF0DUaXP3hfK', '09998556156', 'customer', '2025-11-01 15:47:08', '2025-11-01 15:47:08'),
(7, 'ceciles', 'store', 'cecile@gmail.com', '$2y$10$vGlVdS72d/J8CXKE/iWr/uyvcLtZq1U7Yly6gnjHOALQbjR62ZmAe', '09787878787', 'customer', '2025-11-27 08:51:03', '2025-11-27 08:51:03'),
(8, 'june', 'orias', 'june2@gmail.com', '$2y$10$zdqAf6eMOhjesAc1VZtNcOCxFnFViiCXpA7nWsE/BOZILbgpMMK9K', '09598151365', 'customer', '2025-11-30 01:12:21', '2025-11-30 01:12:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `booking_orders`
--
ALTER TABLE `booking_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_booking_rental_dates` (`order_type`,`item_id`,`rental_start`,`rental_end`,`payment_status`),
  ADD KEY `idx_booking_package_date` (`order_type`,`item_id`,`event_date`,`payment_status`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_cart` (`user_id`,`product_id`),
  ADD UNIQUE KEY `unique_session_cart` (`session_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `packages`
--
ALTER TABLE `packages`
  ADD PRIMARY KEY (`package_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_orders`
--
ALTER TABLE `booking_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `packages`
--
ALTER TABLE `packages`
  MODIFY `package_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `booking_orders`
--
ALTER TABLE `booking_orders`
  ADD CONSTRAINT `booking_orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD CONSTRAINT `testimonials_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
