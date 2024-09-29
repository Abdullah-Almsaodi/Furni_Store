-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 29, 2024 at 11:01 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `furniture_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `blog_posts`
--

CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL,
  `title` text DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `post_date` date DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `blog_posts`
--

INSERT INTO `blog_posts` (`id`, `title`, `author`, `post_date`, `image_url`) VALUES
(1, 'Donec facilisis.', 'Maria Jones', '1990-01-01', 'post-1.jpg'),
(2, 'Another testimonial quote.', 'John Smith', '1990-01-01', 'post-2.jpg'),
(3, 'Yet another testimonial quote.', 'Emma Johnson', '1990-01-01', 'post-3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `cust_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `description`) VALUES
(2, 'Dining Set', ' fa-utensils-alk'),
(17, 'small', 'this is a small'),
(18, 'Miduim', 'this is miduim'),
(19, 'Almsaodi', ' zxcvbnm, mscbzxnbcnmzb');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `price` double(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `sales` int(100) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `category_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `image`, `sales`, `stock_quantity`, `category_id`, `created_at`, `updated_at`) VALUES
(14, 'chair', 'this chair', 10000.00, 'product-3.png', 100, 0, 17, '2024-09-06 01:51:33', '2024-09-29 02:44:18'),
(15, 'chair', 'this chair', 10000.00, 'product-1.png', 50, 0, 2, '2024-09-06 01:52:05', '2024-09-29 02:44:54'),
(16, 'chair', 'this chair', 10000.00, 'sofa.png', 12, 0, 18, '2024-09-06 01:52:22', '2024-09-29 02:45:14'),
(17, 'chair', 'this chair', 10000.00, 'product-2.png', 55, 0, 18, '2024-09-06 01:52:28', '2024-09-29 02:44:34');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `rating` int(11) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `description`) VALUES
(1, 'Admin', NULL),
(2, 'User', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE `shipments` (
  `shipment_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `tracking_number` varchar(50) DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`id`, `name`, `position`, `description`, `image`) VALUES
(1, 'Lawson Arnold', 'CEO, Founder, Atty.', 'Separated they live in. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.', 'person_1.jpg'),
(2, 'Jeremy Walker', 'CEO, Founder, Atty.', 'Separated they live in. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.', 'person_2.jpg'),
(3, 'Patrik White', 'CEO, Founder, Atty.', 'Separated they live in. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.', 'person_3.jpg'),
(4, 'Kathryn Ryan', 'CEO, Founder, Atty.', 'Separated they live in. Separated they live in Bookmarksgrove right at the coast of the Semantics, a large language ocean.', 'person_4.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `testimonial_id` int(11) NOT NULL,
  `quote` text DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `testimonials`
--

INSERT INTO `testimonials` (`testimonial_id`, `quote`, `author`, `position`, `image_url`) VALUES
(1, 'Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Integer convallis volutpat dui quis scelerisque.', 'Maria Jones', 'CEO, Co-Founder, XYZ Inc.', 'person_1.jpg'),
(2, 'Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Integer convallis volutpat dui quis scelerisque.', 'John Smith', 'CTO, ABC Company', 'person_2.jpg'),
(3, 'Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate velit imperdiet dolor tempor tristique. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Integer convallis volutpat dui quis scelerisque.', 'Emma Johnson', 'Marketing Manager, XYZ Inc.', 'person_3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `activation_token` varchar(255) DEFAULT NULL,
  `delete_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `update_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `role_id`, `username`, `email`, `password`, `is_active`, `activation_token`, `delete_at`, `created_at`, `update_at`) VALUES
(7, 1, 'Abdullah Qaid', 'Abdullah.Qaid@outlook.com', '$2y$10$NDxpf7aHjDAtJIsDkOU4ve7hgc8y0AbNsbZDiZuQmmkA9/5D8KNzy', 1, '8177c096cacdc84e23c0805060535466', '2024-08-20 15:54:51', '2024-08-20 15:54:51', '2024-09-19 23:23:57'),
(60, 1, 'ali', 'ali1@su.edu.ye', '$2y$10$51MpVJ41dBMCZqoJXhYh.ONS39BRp5enRfPeNMSbf3703NY35YX7G', 1, '8d94d1b2acd9f98fc1cf50172acd4820', '2024-09-14 00:07:43', '2024-09-14 00:07:43', '2024-09-14 12:16:50'),
(66, 2, 'newuser', 'newuser@example.com', '$2y$10$XiB6y/dMEpNDfIFsC8j6PuIvsXL9ulEbq5FJxzlkctvUTcLy7IyQC', 1, 'bdbc2c0a610979f7b44e94fee8f8dcfe', '2024-09-16 21:06:56', '2024-09-16 21:06:56', '2024-09-16 21:06:56'),
(67, 2, 'hesham', 'heshamalammar4@gmail.com', '$2y$10$dQx2lQPNXte2n0nGDFXNV.rVsITmMI672se4k.6JCQ1Jdw5eMQ8gK', 1, 'd942a43efbefb98dd754e1272f49866d', '2024-09-17 00:44:00', '2024-09-17 00:44:00', '2024-09-17 00:44:00'),
(68, 2, 'ali', 'ali@gmail.com', '$2y$10$PZZ0u7fR1z/Afkcc8yltduZT4RE30dHSf2j9nYI1YUnR27pmk7pEW', 1, '202b0deddf708f059b84ac55b7a76555', '2024-09-18 18:57:03', '2024-09-18 18:57:03', '2024-09-18 18:57:03'),
(69, 1, 'yy', 'yy@gmail.com', '$2y$10$TcXwBY5VoCrdiIcOG5mfXO4YbYFaRtmiJReNJpprihYUVvmP0lqyq', 1, '4cb7fb60a917347499193169d8099da0', '2024-09-19 21:30:44', '2024-09-19 21:30:44', '2024-09-19 23:23:30'),
(70, 2, 'l', 'yyee@gmail.com', '$2y$10$IsTs5BfNp5iRPRwoQ4HGIu0kj3Y6iS2ugMnrpmJGVLN7B7NqquWi.', 2, '3a00e61ef5025f61155f1b5a2bc2d2b8', '2024-09-19 21:32:15', '2024-09-19 21:32:15', '2024-09-19 21:34:50'),
(71, 1, 'yy', 'yy1@gmail.com', '$2y$10$obmMYmbjfMTWYS/lDEpZguNI07BiuYmYOZSA4QgLsZUFjMPYS2/D2', 1, '4e15371fa370c33d80f53b1e28a30f6e', '2024-09-19 22:53:41', '2024-09-19 22:53:41', '2024-09-19 23:22:43'),
(72, 2, 'yytow', 'yy2@gmail.com', '$2y$10$gC2YCLV54aTxqE/nqj4a4epoV9RyF4xFHmmXIRTO55C8KXBLJof7m', 1, 'c6a9716d587d8349c686e733281f4697', '2024-09-20 00:02:37', '2024-09-20 00:02:37', '2024-09-20 00:02:37'),
(73, 2, 'aliali', 'aliali@gmail.com', '$2y$10$e.6t9fVookNukBJ04ap2ye56l3eZre9xYyTqwvuBdJyc77LNbOsga', 1, '0d9cda6b00d190820055fe7bdea3304b', '2024-09-20 00:06:58', '2024-09-20 00:06:58', '2024-09-20 00:06:58'),
(74, 2, 'm', 'm@gmail.com', '$2y$10$smITtHOp/AWkZIVK/HRzxe.3E9SVwKpZmZRJhNkNFSduN2hfhQ9ZG', 1, '17f634b72435e8bd6bf5fa99233e69b7', '2024-09-20 00:10:55', '2024-09-20 00:10:55', '2024-09-20 00:10:55'),
(86, 2, 'hhhh', 'hhhh@gmail.com', '$2y$10$93wHOudm/t4T1bEGYIdj1.l01RkNZJbxKXlxQeFAtXWbcTg16IChy', 1, 'ab3a7eac52f1468e1d039fd1c0433d45', '2024-09-29 05:09:34', '2024-09-29 05:09:34', '2024-09-29 05:09:34'),
(87, 2, 'newuserr', 'newuser9@example.com', '$2y$10$lv8UzrCMVt.8na6mdEOf.uTePSQMmlMpIivFA7utdo4hYtwvTXQp2', 1, 'be07f61358a322be46de8f7f2b0beeb3', '2024-09-29 19:11:33', '2024-09-29 19:11:33', '2024-09-29 19:11:33'),
(88, 2, 'ali', 'ali2@gmail.com', '$2y$10$mhVn5wLa8ecNGjixqhpz5umTwjq/mgaJ0gipsFqzrAhGrY6RRpGzm', 1, '74579f99437758b61e613462c99e73f9', '2024-09-29 19:33:50', '2024-09-29 19:33:50', '2024-09-29 19:33:50');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `profile_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `social_links` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`social_links`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `cust_id` (`cust_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`shipment_id`),
  ADD UNIQUE KEY `tracking_number` (`tracking_number`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`testimonial_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_role_id` (`role_id`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`profile_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `shipments`
--
ALTER TABLE `shipments`
  MODIFY `shipment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `testimonial_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `profile_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`cust_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE SET NULL;

--
-- Constraints for table `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `shipments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE SET NULL;

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
