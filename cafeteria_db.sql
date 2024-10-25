-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 04, 2024 at 05:17 AM
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
-- Database: `cafeteria_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'hot-drink', '2024-08-01 10:25:14', '2024-08-01 10:25:14'),
(2, 'coffe', '2024-08-01 10:25:14', '2024-08-01 10:25:14'),
(3, 'freach-juice', '2024-08-01 10:25:14', '2024-08-01 10:25:14'),
(4, 'cold-drink', '2024-08-01 10:25:14', '2024-08-01 10:25:14'),
(5, 'icecream', '2024-08-01 10:25:14', '2024-08-01 10:25:14'),
(6, 'sweets', '2024-08-01 10:25:14', '2024-08-01 10:25:14');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` enum('Processing','Out for Delivery','Done') DEFAULT 'Processing',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_price`, `status`, `created_at`, `updated_at`) VALUES
(11, 6, 12.00, 'Done', '2024-08-01 23:37:05', '2024-08-03 02:02:01'),
(13, 6, 32.00, 'Out for Delivery', '2024-08-02 00:09:02', '2024-08-03 02:22:33'),
(14, 6, 10.00, 'Done', '2024-08-02 18:59:03', '2024-08-03 02:02:05'),
(15, 6, 375.00, 'Out for Delivery', '2024-08-02 18:59:41', '2024-08-03 02:22:37'),
(16, 6, 35.00, 'Done', '2024-08-02 19:06:11', '2024-08-03 02:22:39'),
(17, 6, 0.00, 'Done', '2024-08-02 19:47:32', '2024-08-03 02:22:58'),
(18, 6, 10.00, 'Done', '2024-08-02 19:47:40', '2024-08-03 02:22:43'),
(19, 6, 20.00, 'Out for Delivery', '2024-08-02 20:35:29', '2024-08-03 02:22:46'),
(20, 6, 0.00, 'Out for Delivery', '2024-08-02 21:38:54', '2024-08-03 02:22:48'),
(21, 6, 10.00, 'Out for Delivery', '2024-08-02 21:39:02', '2024-08-03 02:22:52'),
(26, 12, 12.00, 'Out for Delivery', '2024-08-03 01:58:18', '2024-08-03 02:22:54'),
(27, 13, 123.00, 'Out for Delivery', '2024-08-03 01:59:03', '2024-08-03 02:08:13'),
(28, 13, 123.00, 'Done', '2024-08-03 02:00:33', '2024-08-03 02:02:16'),
(29, 13, 123.00, 'Done', '2024-08-03 02:00:36', '2024-08-03 02:02:13'),
(30, 12, 12.00, 'Done', '2024-08-03 02:00:45', '2024-08-03 02:02:10'),
(31, 15, 30.00, 'Done', '2024-08-03 11:36:57', '2024-08-03 11:38:21'),
(32, 15, 32.00, 'Done', '2024-08-03 11:43:17', '2024-08-03 11:43:59'),
(33, 15, 30.00, 'Done', '2024-08-03 12:35:48', '2024-08-03 12:36:08'),
(34, 13, 13.00, 'Done', '2024-08-03 12:59:24', '2024-08-03 12:59:42'),
(35, 15, 12.00, 'Processing', '2024-08-04 01:52:30', '2024-08-04 01:52:30'),
(36, 15, 23.00, 'Processing', '2024-08-04 02:52:05', '2024-08-04 02:52:05');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `notes`, `created_at`, `updated_at`) VALUES
(1, 26, 4, 1, '', '2024-08-03 01:58:18', '2024-08-03 01:58:18'),
(2, 27, 8, 1, '', '2024-08-03 01:59:03', '2024-08-03 01:59:03'),
(3, 28, 8, 1, '', '2024-08-03 02:00:33', '2024-08-03 02:00:33'),
(4, 29, 8, 1, '', '2024-08-03 02:00:36', '2024-08-03 02:00:36'),
(5, 30, 4, 1, '', '2024-08-03 02:00:45', '2024-08-03 02:00:45'),
(6, 33, 2, 4, '', '2024-08-03 12:35:48', '2024-08-03 12:35:48'),
(7, 34, 6, 5, 'extra sugar', '2024-08-03 12:59:24', '2024-08-03 12:59:24');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires`, `created_at`) VALUES
(1, 'tallata363@gmail.com', '6945da9b4677e18cb77d4d69b7c4b831a5c20f2214b475da197f7c40f56e121b7f507427fae8cd7f5b6831f3c29f651ba32c', 1722698056, '2024-08-03 14:14:16'),
(2, 'tallata363@gmail.com', '282f55fb75fcc149f1c4bedec05f97b9f4ba94c8b23e4a9a94e628a080b77418b56e7dec8f765a9e00e5991ad74d5d23ff9a', 1722698209, '2024-08-03 14:16:49'),
(3, 'tallata363@gmail.com', 'e377b832e3588dfd11b24d11d5d0d3a65b00dfa5b8632538b86bfb11d23b3a83c0f6294ad66dd97827d938f540acbbf39c14', 1722698211, '2024-08-03 14:16:51'),
(4, 'tallata363@gmail.com', '0bf8fdc9e0d52f06f077889d1d417fcb47c8dce44b01f0eb300d8612fe28e6dd5f2fab403f4b07a80b107e8f054027e7cca5', 1722698219, '2024-08-03 14:16:59'),
(5, 'tallata363@gmail.com', 'a159a9036733da82ba761e8afc93c0ca5b82aebd9dab3ba3b071087039da56e936373c6e22b29c491a3d68d18ee1dc851d5c', 1722698461, '2024-08-03 14:21:01'),
(6, 'tallata363@gmail.com', '47ab32df1e44b483caaa99438191954d5a862dfb2c87f2f1f2da81059af7432f814241cbdc2d92fe8126238007b9acad9688', 1722698558, '2024-08-03 14:22:38'),
(7, 'tallata363@gmail.com', 'e86607d56a91df9db42d4de01df9704f1a2fd48d759158e816c5abbe5e403501a7c44d6b53dca8be4e1d877cf2a184979d27', 1722698560, '2024-08-03 14:22:40'),
(8, 'radwagamal482@gmail.com', 'fadd573e9c8fbcccff623fc7811bf510b53ae86e497c4d5ed3167c8cad6732449e0595e2cef9c73f5034dace533a9290d88c', 1722698694, '2024-08-03 14:24:54'),
(9, 'radwagamal482@gmail.com', 'e6ce5971062221d5dc5914f8d199c273cc9018fb36bde6d73c062db7f36ddfba9bdd9d15b7936bbd604e532114bd9ce3c73d', 1722699388, '2024-08-03 14:36:28'),
(10, 'abdallah192351@fci.bu.edu.eg', 'c27896998d1f41ff44aa0f94d2e20374758e4fb069316c50bbf10088e3875343efec2595a2a4f046196b96b0f1d0bdcfb188', 1722699443, '2024-08-03 14:37:23'),
(11, 'abdallah192351@fci.bu.edu.eg', '08d59e0fd2c50c472f71cb90885de728f71db2bfa4dad6a0326a9cf6cf1c9f4d7eab2cf600bde7014a4e97f7c5b998e1db96', 1722713201, '2024-08-03 18:26:41');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`, `category_id`, `created_at`, `updated_at`) VALUES
(2, 'tea&mint', 'hot drink', 12.00, '../assets/images/prudacts/download (1).jpg', 1, '2024-08-02 18:53:28', '2024-08-02 18:53:28'),
(3, 'tea&milk', 'hot drink', 13.00, '../assets/images/prudacts/download (2).jpg', 1, '2024-08-02 18:53:28', '2024-08-02 18:53:28'),
(4, 'green tea', 'hot drink', 10.00, '../assets/images/prudacts/download (4).jpg', 1, '2024-08-02 18:53:28', '2024-08-02 18:53:28'),
(5, 'milk', 'hot drink', 30.00, '../assets/images/prudacts/images (4).jpg', 1, '2024-08-02 18:53:28', '2024-08-02 18:53:28'),
(6, 'حلبه', 'مشروب ساخن', 12.00, '../assets/images/prudacts/images (1).jpg', 1, '2024-08-02 18:53:28', '2024-08-02 18:53:28'),
(7, 'leson', 'hot drink', 13.00, '../assets/images/prudacts/images (3).jpg', 1, '2024-08-02 18:53:28', '2024-08-02 18:53:28'),
(8, 'cacu', 'hot drink', 30.00, '../assets/images/prudacts/download (4).jpg', 1, '2024-08-02 18:53:28', '2024-08-02 18:53:28'),
(9, 'hot chouclet', 'hot drink in wintter', 40.00, '../assets/images/prudacts/images (4).jpg', 1, '2024-08-02 18:53:28', '2024-08-02 18:53:28'),
(10, 'coffee', 'coffe black', 20.00, '../assets/images/prudacts/coffee/694524cf653f18816f463b3be86a0479.jpg', 2, '2024-08-02 22:44:10', '2024-08-02 22:44:10'),
(11, 'coffee2', 'coffe black', 20.00, '../assets/images/prudacts/coffee/coffe-1354786_640.jpg', 2, '2024-08-02 22:44:10', '2024-08-02 22:44:10'),
(12, 'coffee3', 'coffe black', 23.00, '../assets/images/prudacts/coffee/download.jpg', 2, '2024-08-02 22:44:10', '2024-08-02 22:44:10'),
(13, 'coffee4', 'coffe black', 33.00, '../assets/images/prudacts/coffee/images (1).jpg', 2, '2024-08-02 22:44:10', '2024-08-02 22:44:10'),
(14, 'coffee5', 'coffe black', 34.00, '../assets/images/prudacts/coffee/images (3).jpg', 2, '2024-08-02 22:44:10', '2024-08-02 22:44:10'),
(15, 'coffee6', 'coffe black', 12.00, '../assets/images/prudacts/coffee/images (5).jpg', 2, '2024-08-02 22:44:10', '2024-08-02 22:44:10'),
(16, 'coffee7', 'coffe black', 22.00, '../assets/images/prudacts/coffee/images (10).jpg', 2, '2024-08-02 22:44:10', '2024-08-02 22:44:10'),
(17, 'coffee8', 'coffe black', 32.00, '../assets/images/prudacts/coffee/images (7).jpg', 2, '2024-08-02 22:44:10', '2024-08-02 22:44:10'),
(18, 'coffee9', 'coffe black\r\n\r\n\r\n\r\n', 35.00, '../assets/images/prudacts/coffee/images (7).jpg', 2, '2024-08-02 22:44:10', '2024-08-02 22:44:10'),
(23, 'tea', 'hot drink', 12.00, '66ae21bf305d2.jpg', 1, '2024-08-03 12:25:35', '2024-08-03 12:25:35'),
(24, 'tea', 'hot tea', 12.00, '66ae21e074e91.jpg', 1, '2024-08-03 12:26:08', '2024-08-03 12:26:08'),
(25, 'فروله', 'فراوله', 30.00, '66ae224fe98e9.jpg', 3, '2024-08-03 12:27:59', '2024-08-03 12:27:59'),
(26, 'مانجا', 'عصير فريش', 10.00, '66aeeadf6bc5f.jpg', 3, '2024-08-04 02:43:43', '2024-08-04 02:43:43'),
(27, 'برتقال', 'فريش', 10.00, '66aeeb04b0a72.jpg', 3, '2024-08-04 02:44:20', '2024-08-04 02:44:20'),
(28, 'افوكادو', 'فريش', 30.00, '66aeeb1b0e93e.jpg', 3, '2024-08-04 02:44:43', '2024-08-04 02:44:43'),
(29, 'كيوي', 'فريش', 25.00, '66aeeb339acf8.jpg', 3, '2024-08-04 02:45:07', '2024-08-04 02:45:07'),
(30, 'تفاح ', 'فريش', 30.00, '66aeeb4d72ac5.jpg', 3, '2024-08-04 02:45:33', '2024-08-04 02:45:33'),
(31, 'كوكتيل', 'فريش', 30.00, '66aeec48f04a8.jpg', 3, '2024-08-04 02:49:44', '2024-08-04 02:49:44'),
(32, 'بطيخ', 'افريش', 30.00, '66aeec61a0847.jpg', 3, '2024-08-04 02:50:09', '2024-08-04 02:50:09'),
(33, 'توت', 'فريش', 23.00, '66aeec92efaae.jpg', 3, '2024-08-04 02:50:58', '2024-08-04 02:50:58'),
(34, 'كولا', 'ساقعه', 15.00, '66aeed35b4f31.jpg', 4, '2024-08-04 02:53:41', '2024-08-04 02:53:41'),
(35, 'فسن', 'ساقعه', 15.00, '66aeed51c011e.jpg', 4, '2024-08-04 02:54:09', '2024-08-04 02:54:09'),
(36, 'ديو', 'ساقعه', 15.00, '66aeed63deef4.jpg', 4, '2024-08-04 02:54:27', '2024-08-04 02:54:27'),
(37, 'سبريت', 'ساقعه', 15.00, '66aeed75b6570.jpg', 4, '2024-08-04 02:54:45', '2024-08-04 02:54:45'),
(38, 'فانتا', 'ساقعه', 15.00, '66aeed8b8ebb3.jpg', 4, '2024-08-04 02:55:07', '2024-08-04 02:55:07'),
(39, 'برتقال فانتا', 'ساقعه', 18.00, '66aeed9fade30.jpg', 4, '2024-08-04 02:55:27', '2024-08-04 02:55:27'),
(40, 'تفاح فانتا', 'ساقعه', 19.00, '66aeedb4075e4.jpg', 4, '2024-08-04 02:55:48', '2024-08-04 02:55:48'),
(41, 'فراوله', 'ايس كريم ', 20.00, '66aeedf3012fc.jpg', 5, '2024-08-04 02:56:51', '2024-08-04 02:56:51'),
(42, 'توت', 'ايس كريم ', 20.00, '66aeee05490bf.jpg', 5, '2024-08-04 02:57:09', '2024-08-04 02:57:09'),
(43, 'اوريو', 'ايس كريم ', 20.00, '66aeee1acd0c7.jpg', 5, '2024-08-04 02:57:30', '2024-08-04 02:57:30'),
(44, 'كوكتيل', 'ايس كريم ', 30.00, '66aeee35d3367.jpg', 5, '2024-08-04 02:57:57', '2024-08-04 02:57:57'),
(45, 'بولا', 'ايس كريم ', 40.00, '66aeee4cc3200.jpg', 5, '2024-08-04 02:58:20', '2024-08-04 02:58:20'),
(46, 'مولتون كيك', 'تورته جميله', 60.00, '66aeee872e2e8.jpg', 6, '2024-08-04 02:59:19', '2024-08-04 02:59:19'),
(47, 'اتشيزكيك', 'ايس كريم ', 60.00, '66aeee9cc4193.webp', 6, '2024-08-04 02:59:40', '2024-08-04 02:59:40'),
(48, 'اتشيزكيك', 'ايس كريم ', 60.00, '66aeeeae9d0cc.jpg', 6, '2024-08-04 02:59:58', '2024-08-04 03:03:57'),
(49, 'اتشيزكيك', 'ايس كريم ', 60.00, '66aeeebb56ad4.jpg', 6, '2024-08-04 03:00:11', '2024-08-04 03:00:11'),
(50, 'كوبايه', 'ايس كريم ', 60.00, '66aeeecf06511.jpg', 6, '2024-08-04 03:00:31', '2024-08-04 03:00:31'),
(51, 'كيك', 'كيك', 40.00, '66aeeee16d8ba.jpg', 6, '2024-08-04 03:00:49', '2024-08-04 03:00:49'),
(52, 'كيك', 'شسيبلبي', 40.00, '66aeeef269e6c.jpg', 6, '2024-08-04 03:01:06', '2024-08-04 03:01:06'),
(53, 'مولتون كيك', 'قصي', 345.00, '66aeef03450aa.jpg', 6, '2024-08-04 03:01:23', '2024-08-04 03:01:23'),
(54, 'صييض', 'يث', 344.00, '66aeef19c176a.jpg', 6, '2024-08-04 03:01:45', '2024-08-04 03:01:45');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `num_people` int(11) NOT NULL,
  `reservation_time` datetime NOT NULL,
  `table_number` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `name`, `email`, `num_people`, `reservation_time`, `table_number`) VALUES
(5, 'AbdullahTallat', 'tallata363@gmail.com', 4, '2024-08-09 22:26:00', '2T1');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `table_number` varchar(10) NOT NULL,
  `seats` int(11) NOT NULL,
  `status` enum('available','reserved') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `table_number`, `seats`, `status`) VALUES
(1, '1T1', 4, 'available'),
(2, '2T1', 4, 'reserved'),
(3, '3T1', 6, 'available'),
(4, '1A1', 8, 'available'),
(5, '2A1', 8, 'available'),
(6, '3A1', 8, 'available'),
(7, '1a6', 8, 'available'),
(8, '2a3', 8, 'available'),
(9, '1B1', 10, 'available'),
(10, '2B1', 10, 'available'),
(11, '3B1', 10, 'available'),
(12, '4B1', 10, 'available'),
(13, '5B1', 10, 'available'),
(14, '6B1', 10, 'available'),
(15, '1CLASS', 13, 'available');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `created_at`, `updated_at`, `profile_image`) VALUES
(5, 'tata', '$2y$10$1UL0rnrahtjLDmFRfSTNDe85hdiH00oowK1cpV3J0.VMn6v4OqdHe', 'piqiteducemu@jollyfree.com', 'user', '2024-08-01 19:52:29', '2024-08-01 19:52:29', '66abe77d26aa6.jpeg'),
(6, 'mama', '$2y$10$r1EQF9RWRijSiB4FY/Tt6.VOJ9bAXGCQlH1faAmwz0Qd5/qcVivg2', 'abdallah192351@fci.bu.edu.eg', 'user', '2024-08-01 23:27:40', '2024-08-01 23:27:40', '66ac19ec352ea.jpg'),
(10, 'rwda', '$2y$10$ZFPLa25ZjP3/dKn1AJHj1umoujQLV04dELSbXADx7vaMz7aUcz6ei', 'dada@gmail.com', 'admin', '2024-08-03 00:59:26', '2024-08-03 00:59:26', '66ad80ede9edc.jpeg'),
(12, 'hajar', '$2y$10$gVd3Z6K5UgwVRGjkqdp.Ku12tOp8lYBLP6ND3wrrZgjsyDKvlBsG2', 'dada@gmail.com', 'user', '2024-08-03 01:47:03', '2024-08-03 01:47:03', '6--Ways-of-dealing-with-verbally-abusive-healthyplace.jpg'),
(13, 'boda', '$2y$10$2/XUfbs0QXD3ojtWyi6yiuAMg6/1ZsO/kGBt/.4Ft8G3m1bjhvGzm', 'sssaa@gmil.com', 'user', '2024-08-03 01:51:35', '2024-08-03 01:51:35', 'WhatsApp Image 2024-06-25 at 9.33.59 AM.jpeg'),
(14, 'M ADEL', '$2y$10$NuP9PT4efrgd6yoOYZ3ksOdu5LXrcH4JM6XTzbwQ7opkhROfKst3y', 'tallata363@gmail.com', 'admin', '2024-08-03 11:19:55', '2024-08-03 11:19:55', 'child-crying-original_1164198319.jpg'),
(15, 'SASA', '$2y$10$AbFecXeb7B5E8ma0bc67C.GNqn8GQVaD1Rhm26zPQ0OPDMRLGQyLG', 'dada@gmail.com', 'user', '2024-08-03 11:35:31', '2024-08-03 11:35:31', '66ae1602e956e.jpeg'),
(16, 'radwa', '$2y$10$rUvIPiQ9zNB50/DvN5kJ9OPwn3gFPhbOD5AbHNO48181MlIPt1R3a', 'radwagamal482@gmail.com', 'user', '2024-08-03 14:24:45', '2024-08-03 14:24:45', '66ae3dad7f461.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `table_number` (`table_number`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `table_number` (`table_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

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
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`table_number`) REFERENCES `rooms` (`table_number`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
