-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 15, 2025 at 03:59 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `food_ordering`
--

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `menu_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `availability` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`menu_id`, `name`, `description`, `price`, `category`, `image`, `created_at`, `availability`) VALUES
(0, 'Chicken Tiger', '🐔 Chicken Tiger Bold, juicy, and bursting with flavor — our Chicken Tiger is a feast for meat lovers! Marinated in a special blend of herbs and spices, then perfectly grilled or crispy-fried to golden perfection. Served with your choice of sauce and a side of rice or fries. A fierce bite you won\'t forget!', 99.00, 'Main Dish', '1747317223_chicken.jpg', '2025-05-15 13:53:43', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','canceled','preparing') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `customer_name` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_price`, `status`, `created_at`, `customer_name`, `address`) VALUES
(0, 0, 99.00, 'completed', '2025-05-15 13:54:57', 'Alyza Jimenez', '139 F. Ramon Street. Caloocan City');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `menu_id`, `quantity`, `price`) VALUES
(0, 0, 0, 1, 99.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','customer') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'alyadmin@food.tiger.com', 'Password123!', 'admin', '2025-05-10 07:49:16'),
(2, 'bossadmin@food.tiger.com', 'Foodfood000!', 'admin', '2025-05-10 07:49:16'),
(3, 'mercyswsw@gmail.com', '$2y$10$3ntEend.JwC5mMZEj0rGhe.DLrIXqXl44Z8rQYZoDTFxlYZK.o2Ru', 'customer', '2025-05-10 08:00:26'),
(4, 'dextermorgan@yahoo.com', '$2y$10$AK1XF4GWrt7KkaQSKEb7HeHCyCl1fNyFD2UPwSiDctD7E9NkqMpha', 'customer', '2025-05-10 08:05:00'),
(5, 'ethanbruce@gmail.com', '$2y$10$dW3fbftn9E04slnkza.1je3ZLkqio8iINAdo9dWMkq/vw67Q7vgxq', 'customer', '2025-05-10 08:21:16'),
(0, 'alyzajimenez@gmail.com', '$2y$10$vDvpVpgNLlx4FgyT1EQNqehuUMH5DLVzBJLFq/vmnzF2iidC1.ye2', 'customer', '2025-05-15 13:54:29');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
