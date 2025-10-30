-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2025 at 06:20 AM
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
-- Database: `cafe_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `no_of_persons` int(11) NOT NULL,
  `table_id` int(11) NOT NULL,
  `status` varchar(20) DEFAULT 'Confirmed',
  `customer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `user_name`, `user_email`, `booking_date`, `booking_time`, `no_of_persons`, `table_id`, `status`, `customer_id`) VALUES
(1, 'Jahnvi Makwana', 'jahnvi@gmail.com', '2025-09-18', '00:00:13', 2, 1, 'Booked', 1),
(2, 'Jahnvi Makwana', 'jahnvi@gmail.com', '2025-09-18', '00:00:18', 3, 5, 'Booked', 2),
(3, 'Bansi Lathiya', 'bansi@gmail.com', '2025-09-19', '00:00:12', 3, 4, 'Booked', 3),
(4, 'Arpan', 'a@gmail.com', '2025-09-19', '00:00:10', 2, 2, 'Booked', 4);

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `table_name` text NOT NULL,
  `child` int(11) DEFAULT NULL,
  `adult` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `name`, `table_name`, `child`, `adult`) VALUES
(1, 'Jahnvi Makwana', 'Table_1', 0, 2),
(2, 'Jahnvi Makwana', 'Table_5', 1, 2),
(3, 'Bansi Lathiya', 'Table_4', 1, 2),
(4, 'Arpan', 'Table_2', 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_price` decimal(10,2) NOT NULL,
  `item_main_image` varchar(255) NOT NULL,
  `item_gallery_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `item_captions` text DEFAULT NULL,
  `item_category` enum('Dinner','Starter','Lunch') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `item_name`, `item_price`, `item_main_image`, `item_gallery_image`, `item_captions`, `item_category`) VALUES
(1, 'Pizza', 499.00, 'pizza.jpg', NULL, 'A classic pizza with fresh mozzarella and basil. A delicious and simple Italian dish.', 'Lunch'),
(2, 'Sandwich', 99.00, 'sandwich.jpg', NULL, 'A warm, grilled sandwich with melted cheese. A perfect lunch for a quick, fulfilling meal.', 'Dinner'),
(3, 'Pasta', 189.00, 'room-2.jpg', NULL, 'An Italian food made from unleavened dough of durum wheat flour mixed with water or eggs.', 'Starter'),
(4, 'Burger', 299.00, 'burger.jpeg', NULL, 'A sandwich consisting of one or more cooked patties of ground meat, usually beef, placed inside a sliced bun.', 'Lunch'),
(5, 'Noodles', 59.00, 'about-2.jpg', NULL, 'askiudiew', 'Dinner');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `checkin_time` time NOT NULL,
  `checkin_date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('open','paid','canceled') NOT NULL DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `customer_id`, `checkin_time`, `checkin_date`, `amount`, `status`, `created_at`) VALUES
(1, 1, '13:52:01', '2025-09-17', 1475.00, 'paid', '2025-09-17 08:22:01'),
(2, 2, '14:09:59', '2025-09-17', 1196.00, 'paid', '2025-09-17 08:39:59'),
(3, 3, '12:47:40', '2025-09-19', 697.00, 'open', '2025-09-19 07:17:40'),
(4, 3, '12:57:33', '2025-09-19', 299.00, 'open', '2025-09-19 07:27:33'),
(5, 3, '12:58:07', '2025-09-19', 497.00, 'paid', '2025-09-19 07:28:07'),
(6, 4, '10:14:17', '2025-09-20', 499.00, 'paid', '2025-09-20 04:44:17');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `item_id`, `item_name`, `item_price`, `quantity`) VALUES
(1, 1, 3, 'Pasta', 189.00, 2),
(2, 1, 4, 'Burger', 299.00, 2),
(3, 1, 1, 'Pizza', 499.00, 1),
(4, 2, 2, 'Sandwich', 99.00, 2),
(5, 2, 1, 'Pizza', 499.00, 2),
(6, 3, 1, 'Pizza', 499.00, 1),
(7, 3, 2, 'Sandwich', 99.00, 2),
(8, 4, 4, 'Burger', 299.00, 1),
(9, 5, 4, 'Burger', 299.00, 1),
(10, 5, 2, 'Sandwich', 99.00, 2),
(11, 6, 1, 'Pizza', 499.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `table_name` varchar(255) DEFAULT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `payment_id_from_gateway` varchar(255) NOT NULL,
  `razorpay_order_id` varchar(255) NOT NULL,
  `razorpay_signature` varchar(255) NOT NULL,
  `payment_date` datetime NOT NULL DEFAULT current_timestamp(),
  `payment_status` enum('pending','completed','failed') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `user_id`, `order_id`, `user_name`, `user_email`, `table_name`, `total_amount`, `payment_id_from_gateway`, `razorpay_order_id`, `razorpay_signature`, `payment_date`, `payment_status`) VALUES
(1, 1, 1, 'Jahnvi Makwana', 'jahnvi@gmail.com', 'Table_1', 1475.00, 'pay_RIbLL52XOzPd1I', 'order_RIbKVD9iPBipt8', '343a12a5af37d935bd3adb9ce19b84fec9a67c35ce359a2b9b9e0e4d13504c91', '2025-09-17 13:53:18', 'completed'),
(2, 1, 2, 'Jahnvi Makwana', 'jahnvi@gmail.com', 'Table_5', 1196.00, 'pay_RIbdt909bNgqzI', 'order_RIbdSFZcT82q7h', 'afa9ecdd4b6e5beecbae6dbc89b11a0df6d05c030b73fc801dcc007054382888', '2025-09-17 14:11:06', 'completed'),
(3, 2, 5, 'Bansi Lathiya', 'bansi@gmail.com', 'Table_4', 497.00, 'pay_RJNTriiC6fIuoQ', 'order_RJNTmvoqsngqSb', '4a05675281521487dbe05e8fcc60664641c23b8824c1c2e8ed32ef47ee68d6c7', '2025-09-19 12:58:25', 'completed'),
(4, 4, 6, 'Arpan', 'a@gmail.com', 'Table_2', 499.00, 'pay_RJjE1O6MuZMXlK', 'order_RJjDqbgRfnSckp', '1ea290c2f0ef15cc753bb65ba759eedf745c7a3ad6f3a1531fff0e041eef5e22', '2025-09-20 10:14:45', 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `ID` int(11) NOT NULL,
  `service_Name` varchar(255) DEFAULT NULL,
  `service_des` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`ID`, `service_Name`, `service_des`) VALUES
(1, 'Online Ordering', 'Allows customers to place orders through a website or app'),
(2, 'Drive-Thru', 'Offers a convenient way for customers to order and receive food from their car'),
(3, 'Outdoor Seating', 'Provides a comfortable outdoor area for dining'),
(4, 'Live Music', 'Features live musical performances on certain days');

-- --------------------------------------------------------

--
-- Table structure for table `table_entry`
--

CREATE TABLE `table_entry` (
  `id` int(11) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `capacity` int(11) NOT NULL,
  `IsActive` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `table_entry`
--

INSERT INTO `table_entry` (`id`, `table_name`, `capacity`, `IsActive`) VALUES
(1, 'Table_1', 2, 1),
(2, 'Table_2', 2, 1),
(3, 'Table_3', 2, 1),
(4, 'Table_4', 3, 1),
(5, 'Table_5', 3, 1),
(6, 'Table_6', 3, 1),
(7, 'Table_7', 4, 1),
(8, 'Table_8', 4, 1),
(9, 'Table_9', 4, 1),
(10, 'Table_10', 5, 1),
(11, 'Table_11', 5, 1),
(12, 'Table_12', 5, 1),
(13, 'Table_13', 6, 1),
(14, 'Table_14', 6, 1),
(15, 'Table_15', 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `table_status`
--

CREATE TABLE `table_status` (
  `status_id` int(11) NOT NULL,
  `table_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `booking_time` time NOT NULL,
  `status` varchar(20) DEFAULT 'Booked'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `table_status`
--

INSERT INTO `table_status` (`status_id`, `table_id`, `booking_id`, `booking_date`, `booking_time`, `status`) VALUES
(1, 1, 1, '2025-09-18', '13:50:00', 'Booked'),
(2, 5, 2, '2025-09-18', '18:10:00', 'Booked'),
(3, 4, 3, '2025-09-19', '12:45:00', 'Booked'),
(4, 2, 4, '2025-09-19', '10:15:00', 'Booked');

-- --------------------------------------------------------

--
-- Table structure for table `users_list`
--

CREATE TABLE `users_list` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_list`
--

INSERT INTO `users_list` (`id`, `firstname`, `lastname`, `email`, `password`) VALUES
(1, 'Jahnvi', 'Makwana', 'jahnvi@gmail.com', '$2y$10$fTOXOEa4j/.FMXk65Du04eqzAP8EKLkHLeXsgGJOgEUwtsz3U7AHW'),
(2, 'Bansi', 'Lathiya', 'bansi@gmail.com', '$2y$10$tOEaw5ZQUE45/aC9sHqeg.c.K0bt16ReVgcff4RPW6paBcyZ1V7JW'),
(3, 'Yashvi', 'Gondaliya', 'yashvi@gmail.com', '$2y$10$wR/82apaT.ImYXH2ToS3B.e56/26VIbCaoonXWdOOqhJhRh3eZ9eW'),
(4, 'arpan', 'raval', 'a@gmail.com', '$2y$10$t0xQDuKYF7mD2DLs6a82KOOSDlwEbxuWvVYuAYRK5K7vJC6Fi25Ei');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `table_entry`
--
ALTER TABLE `table_entry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `table_status`
--
ALTER TABLE `table_status`
  ADD PRIMARY KEY (`status_id`);

--
-- Indexes for table `users_list`
--
ALTER TABLE `users_list`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `table_entry`
--
ALTER TABLE `table_entry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `table_status`
--
ALTER TABLE `table_status`
  MODIFY `status_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users_list`
--
ALTER TABLE `users_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
