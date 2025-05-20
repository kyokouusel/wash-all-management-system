-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2025 at 06:54 AM
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
-- Database: `laundry_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `email`) VALUES
(1, 'admin', 'adminpassword', ''),
(2, 'Aneurin', '$2y$10$0OiC1Xi6WaYw7ZyGc4nrC.NBBtQNggVWHF4haHRs/OKQYwvxhDwAC', ''),
(42, 'Ezra', '$2y$10$0uMEUq46mAsJTX7wQrhFde6r7It.8VBss8PgrCfgHO5ijCix8Qb4m', 'aneureza@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `admin_password_resets`
--

CREATE TABLE `admin_password_resets` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `code` varchar(6) NOT NULL,
  `expires_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_frequency` int(11) DEFAULT 0,
  `total_spend` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `address`, `phone`, `email`, `password`, `created_at`, `order_frequency`, `total_spend`) VALUES
(1, 'Nov Najliz', 'Purok 1 - Aglayan, Malaybalay City Bukidnon', '09924783790', 'von4delacruz@gmail.com', '$2y$10$v2ZNyQ.rDJ8lqdkLSTyn/O3l1/cXLxhdGECwA7d8L5fYcKA4fW4Nq', '2025-04-05 07:50:06', 11, 1385.00),
(6, 'john', 'Natidasan', '09172362243', 'johndyllricablanca@gmail.com', '$2y$10$Yj..VuEWA42ULH3vz1rv2ekghHbUxmbdAXcnnEWvdQ/RkXM2/P1QC', '2025-04-21 05:00:57', 0, 0.00),
(7, 'Yumeko', 'Purok 2 Aglayan, Malaybalay City Bukidnon', '09355197275', 'yumekodelacruz@gmail.com', '$2y$10$Z.1XfXcp6ObqC5L1qzMBVuupdfvaV6iIksZYZYqgkcgUCjFRiIPtq', '2025-05-01 23:37:07', 1, 70.00),
(13, 'nami', 'landing', '2128845543', 'jeishapetancio294@gmail.com', '$2y$10$ppAjAfhsqmB4sbTgzmVMu.kx0nPDooXwR37yl1yANzfLWmfjhFHpu', '2025-05-13 15:06:36', 1, 30.00),
(15, 'Von Ziljan', 'Valencia', '1231231231', '2301110261@student.buksu.edu.ph', '$2y$10$xTYzrhpMf148J22k2p4hqevcu3jqI6R4Lt3T/dSdvW27Hv2fxdueq', '2025-05-18 04:50:23', 0, 0.00),
(16, 'sad', 'CDO', '09123123121', 'sad@gmail.com', '$2y$10$b29L.Q2HL.nT6f5nBk73ZOArBfn0O.VtzlIVNBhdxVy8JpFefwYXS', '2025-05-19 03:48:41', 0, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `service_type` varchar(100) NOT NULL,
  `weight` float NOT NULL,
  `order_date` datetime NOT NULL,
  `status` varchar(50) NOT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `detergent` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `address` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `service_type`, `weight`, `order_date`, `status`, `total_amount`, `detergent`, `created_at`, `address`, `phone`) VALUES
(29, 1, 'Wash + Dry + Fold with Detergent and Fabcon', 8, '2025-05-04 13:34:22', 'In Progress', 185.00, NULL, '2025-05-05 09:52:29', '', ''),
(30, 1, 'Dry', 8, '2025-05-04 13:48:52', 'In Progress', 70.00, NULL, '2025-05-05 09:52:29', '', ''),
(31, 1, 'Wash', 8, '2025-05-04 13:49:02', 'In Progress', 60.00, NULL, '2025-05-05 09:52:29', '', ''),
(32, 1, 'Fold', 8, '2025-05-04 13:49:06', 'In Progress', 30.00, NULL, '2025-05-05 09:52:29', '', ''),
(33, 1, 'Dry + Fold', 8, '2025-05-04 13:49:10', 'In Progress', 100.00, NULL, '2025-05-05 09:52:29', '', ''),
(34, 1, 'Wash + Dry', 8, '2025-05-04 13:49:14', 'In Progress', 130.00, NULL, '2025-05-05 09:52:29', '', ''),
(35, 1, 'Wash + Fold', 8, '2025-05-04 13:49:18', 'In Progress', 90.00, NULL, '2025-05-05 09:52:29', '', ''),
(36, 1, 'Wash + Dry + Fold', 8, '2025-05-04 13:49:22', 'In Progress', 160.00, NULL, '2025-05-05 09:52:29', '', ''),
(37, 1, 'Wash + Dry + Fold with Detergent and Fabcon', 8, '2025-05-04 13:49:46', 'In Progress', 185.00, NULL, '2025-05-05 09:52:29', '', ''),
(38, 1, 'Wash + Dry + Fold with Detergent and Fabcon', 32, '2025-05-04 13:49:51', 'In Progress', 740.00, NULL, '2025-05-05 09:52:29', '', ''),
(39, 1, 'Dry', 8, '2025-05-04 13:49:55', 'In Progress', 70.00, NULL, '2025-05-05 09:52:29', '', ''),
(40, 1, 'Wash', 32, '2025-05-05 04:06:47', 'Pending', 240.00, NULL, '2025-05-05 10:06:47', '', ''),
(41, 1, 'Fold', 8, '2025-05-05 04:08:02', 'Pending', 30.00, NULL, '2025-05-05 10:08:02', '', ''),
(42, 1, 'Wash', 32, '2025-05-05 04:08:19', 'Pending', 240.00, NULL, '2025-05-05 10:08:19', '', ''),
(43, 1, 'Dry', 8, '2025-05-05 04:08:55', 'Complete', 70.00, NULL, '2025-05-05 10:08:55', '', ''),
(44, 1, 'Dry + Fold', 16, '2025-05-05 04:13:11', 'Complete', 200.00, NULL, '2025-05-05 10:13:11', '', ''),
(45, 1, 'Wash + Dry', 8, '2025-05-05 04:17:17', 'Pending', 130.00, NULL, '2025-05-05 10:17:17', '', ''),
(46, 1, 'Dry', 8, '2025-05-05 04:17:40', 'Pending', 70.00, NULL, '2025-05-05 10:17:40', '', ''),
(47, 1, 'Dry', 8, '2025-05-05 05:03:47', 'Pending', 70.00, NULL, '2025-05-05 11:03:47', 'Purok 1 - Aglayan, Malaybalay City Bukidnon', '09924783790'),
(49, 1, 'Wash + Dry + Fold', 8, '2025-05-11 04:54:30', 'Cancelled', 160.00, NULL, '2025-05-11 10:54:30', 'Purok 2 Aglayan, Malaybalay City Bukidnon', '09355197275'),
(50, 1, 'Dry + Fold', 16, '2025-05-13 04:22:37', 'Complete', 200.00, NULL, '2025-05-13 10:22:37', 'Purok 1 Aglayan Malaybalay City Bukidnon Philippines', '09924783790'),
(51, 1, 'Dry', 8, '2025-05-13 04:22:58', 'Complete', 70.00, NULL, '2025-05-13 10:22:58', 'Purok 1 Aglayan Malaybalay City Bukidnon Philippines', '09924783790'),
(52, 1, 'Wash + Dry + Fold with Detergent and Fabcon', 8, '2025-05-13 04:28:44', 'Complete', 185.00, NULL, '2025-05-13 10:28:44', 'Purok 1 Aglayan Malaybalay City Bukidnon Philippines', '09924783790'),
(53, 1, 'Wash + Dry + Fold', 8, '2025-05-13 10:33:13', 'Complete', 160.00, NULL, '2025-05-13 10:33:13', 'Purok 1 Aglayan Malaybalay City Bukidnon Philippines', '09924783790'),
(54, 1, 'Dry', 8, '2025-05-13 10:35:23', 'Complete', 70.00, NULL, '2025-05-13 10:35:23', 'Purok 1 Aglayan Malaybalay City Bukidnon Philippines', '09924783790'),
(55, 1, 'Wash + Dry', 8, '2025-05-13 10:39:14', 'Complete', 130.00, NULL, '2025-05-13 10:39:14', 'Purok 1 Aglayan Malaybalay City Bukidnon Philippines', '09924783790'),
(58, 7, 'Dry', 8, '2025-05-13 11:00:43', 'Complete', 70.00, NULL, '2025-05-13 11:00:43', 'Purok 2 Aglayan, Malaybalay City Bukidnon', '09355197275'),
(59, 1, 'Dry + Fold', 8, '2025-05-13 11:06:37', 'Complete', 100.00, NULL, '2025-05-13 11:06:37', 'Purok 1 Aglayan Malaybalay City Bukidnon Philippines', '09924783790'),
(60, 1, 'Dry + Fold', 8, '2025-05-13 11:18:14', 'Complete', 100.00, NULL, '2025-05-13 11:18:14', 'Purok 1 Aglayan Malaybalay City Bukidnon Philippines', '09924783790'),
(61, 1, 'Dry + Fold', 8, '2025-05-13 11:22:21', 'Complete', 100.00, NULL, '2025-05-13 11:22:21', 'Purok 1 Aglayan Malaybalay City Bukidnon Philippines', '09924783790'),
(72, 13, 'Fold', 8, '2025-05-14 20:45:38', 'Complete', 30.00, NULL, '2025-05-14 20:45:38', 'sisang', '123123'),
(73, 15, 'Dry', 32, '2025-05-18 13:11:07', 'Complete', 280.00, NULL, '2025-05-18 13:11:07', 'Valencia', '1231231231'),
(74, 1, 'Dry', 8, '2025-05-19 13:35:31', 'Pending', 70.00, NULL, '2025-05-19 13:35:31', 'Purok 1 Aglayan Malaybalay City Bukidnon Philippines', '09924783790');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `user_id` int(11) NOT NULL,
  `code` varchar(6) NOT NULL,
  `expires_at` datetime NOT NULL,
  `user_type` enum('customer','admin') NOT NULL DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_password_resets`
--
ALTER TABLE `admin_password_resets`
  ADD CONSTRAINT `admin_password_resets_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD CONSTRAINT `fk_user_reset` FOREIGN KEY (`user_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
