-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 16, 2025 at 09:12 PM
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
(2, 'Chicken Tiger', 'Chicken Tiger Bold, juicy, and bursting with flavor, our Chicken Tiger is a feast for meat lovers! A fierce bite you won\'t forget!', 199.00, 'Main Dish', '1747413743_chicken.jpg', '2025-05-14 06:19:12', 1),
(3, 'Tiger Wrap Supreme', 'Soft tortilla wrap filled with grilled chicken, shredded lettuce, cheese, and a kick of chipotle mayo. A perfect grab-and-go tiger treat!', 99.00, 'Main Dish', '1747413825_tortilla.jpg', '2025-05-14 06:19:42', 1),
(4, 'Tiger Wings', 'Crispy, juicy chicken wings tossed in your choice of signature sauces. Watch out for the Tiger Wings, it is extremely tasty.', 159.00, 'Main Dish', '1747413846_chicken wings.jpg', '2025-05-14 06:19:50', 1),
(5, 'Spicy Tiger Bites', 'Crispy chicken tossed in our signature hot chili glaze. Fiery, crunchy, and dangerously addictive. Careful, it is spicy like the eye of the tiger!', 199.00, 'Main Dish', '1747414289_buffalo-chicken-bites-13.jpg', '2025-05-14 06:30:51', 1),
(6, 'Wild Tiger Chicken BBQ', 'Smoky grilled chicken quarters slathered in house-made BBQ sauce. Served with rice and fresh slaw. Customer\'s choice!', 219.00, 'Main Dish', '1747414044_bbq.jpg', '2025-05-14 06:33:04', 1),
(7, 'Savory Stripes Chicken Bowl', 'Grilled chicken strips, vegetables, and teriyaki glaze over garlic rice. The best stripes in the town that you will never ever forget!', 145.00, 'Rice Meals – Jungle Bowls', '1747414205_Gochujang-Chicken-Rice-Bowls-0189.jpg', '2025-05-14 06:36:31', 1),
(12, 'Tiger Chicken Fried Rice Bowl', 'Savory fried rice packed with chicken, egg, and veggies. Tiger\'s tummy will sure love this! Try it now and you will love it later.', 99.00, 'Rice Meals – Jungle Bowls	', '1747414317_fried rice.jpg', '2025-05-15 10:17:07', 1),
(13, 'Lemon Herb Chicken Bowl', 'Juicy grilled chicken glazed with lemon herb sauce on a bed of warm rice and greens. This is for all the lemon lovers out there!', 109.00, 'Rice Meals – Jungle Bowls', '1747414400_lemon.jpg', '2025-05-16 16:53:20', 1),
(14, 'Crunchy Claw Chicken Sandwich', 'Crispy fillet, lettuce, tomato, pickles, and tiger sauce on a brioche bun. You will sure crunch your jaw like a tiger and like a beast!', 189.00, 'Handhelds – Tiger Grabs', '1747414439_chicken sandwich.jpg', '2025-05-16 16:53:59', 1),
(15, 'Cheesy Chicken Melt Sandwich', 'Melted cheese and grilled chicken stuffed in toasted flatbread with garlic butter drizzle. Cheeseness overload, this is for the cheese is life!', 99.00, 'Handhelds – Tiger Grabs', '1747414524_chicken wich.jpg', '2025-05-16 16:55:24', 1),
(16, 'Crunchy Beast Burger', 'You were warned by this burger. Try it now like beast.', 129.00, 'Handhelds – Tiger Grabs', '1747414661_burger.jpg', '2025-05-16 16:57:41', 1),
(17, 'Tiger Milk Tea', 'Classic brown sugar wintermelon flavors with chewy pearls.', 99.00, 'Drinks – Jungle Coolers', '1747414730_milktea.jpg', '2025-05-16 16:58:50', 1),
(18, 'Citrus Roar Lemonade', 'Zesty lemonade with a punch of lime and mint.', 89.00, 'Drinks – Jungle Coolers', '1747416984_homemade-citrus-lemonade-2.jpg', '2025-05-16 17:00:28', 1),
(19, 'Tropical Tiger Shake', 'Creamy mango shake made with real fruit.', 99.00, 'Drinks – Jungle Coolers', '1747414884_mango-smoothie-recipe-featured.jpg', '2025-05-16 17:01:24', 1),
(20, 'Tiger Flan', 'Silky caramel custard topped with crushed tiger crackers for crunch.', 79.00, ' Desserts – Sweet Stripes', '1747414932_Spanish-Flan-S2.jpg', '2025-05-16 17:02:12', 1),
(21, 'Chocolate Jungle Lava Cake', 'Molten chocolate cake with a gooey center. Served warm.', 109.00, 'Desserts – Sweet Stripes', '1747414985_cake.jpg', '2025-05-16 17:03:05', 1),
(22, 'Tiger Twist Ice Cream ChocVan', 'Soft-serve chocolate-vanilla swirled with chocolate tiger stripes.', 69.00, 'Desserts – Sweet Stripes', '1747415092_ice cream.jpg', '2025-05-16 17:04:52', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','canceled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `customer_name` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_price`, `status`, `created_at`, `customer_name`, `address`) VALUES
(1, 0, 221.99, 'completed', '2025-05-14 08:39:45', 'tey', 'manila city'),
(3, 0, 246.00, 'completed', '2025-05-14 09:40:06', 'alyza', '10th ave caloocan');

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
(1, 1, 2, 1, 221.99),
(2, 3, 4, 2, 123.00);

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
(0, 'alyzajimenez@gmail.com', '$2y$10$V0VENlDCg0acQFlwEoIJ/OJs44X6nxEUDRoNK7hXbANWIdRkzXS8O', 'customer', '2025-05-16 17:05:13'),
(1, 'alyadmin@food.tiger.com', 'Password123!', 'admin', '2025-05-10 15:49:16'),
(2, 'bossadmin@food.tiger.com', 'Foodfood000!', 'admin', '2025-05-10 15:49:16'),
(3, 'mercyswsw@gmail.com', '$2y$10$3ntEend.JwC5mMZEj0rGhe.DLrIXqXl44Z8rQYZoDTFxlYZK.o2Ru', 'customer', '2025-05-10 16:00:26'),
(4, 'dextermorgan@yahoo.com', '$2y$10$AK1XF4GWrt7KkaQSKEb7HeHCyCl1fNyFD2UPwSiDctD7E9NkqMpha', 'customer', '2025-05-10 16:05:00'),
(5, 'ethanbruce@gmail.com', '$2y$10$dW3fbftn9E04slnkza.1je3ZLkqio8iINAdo9dWMkq/vw67Q7vgxq', 'customer', '2025-05-10 16:21:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu`
--
ALTER TABLE `menu`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
