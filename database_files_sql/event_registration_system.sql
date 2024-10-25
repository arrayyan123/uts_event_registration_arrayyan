-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 25, 2024 at 08:04 AM
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
-- Database: `event_registration_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `banner_promote`
--

CREATE TABLE `banner_promote` (
  `id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `subtitle` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `banner_promote`
--

INSERT INTO `banner_promote` (`id`, `image_path`, `created_at`, `subtitle`, `description`) VALUES
(1, 'banner_6716d9fd4dc058.36107613.webp', '2024-10-21 22:47:25', 'Jcafe Culture', 'event tahunan jejepangan yang di adakan di jakarta oleh UKM Jcafe Culture'),
(2, 'banner_6716dabf8614c2.59410065.jpg', '2024-10-21 22:50:39', 'Meet &amp; Greet Bocchi Voice Actor', 'Meet &amp; greet dengan voice actor dan band dari anime &quot;bocchi the rock!&quot;'),
(3, 'banner_6716db48432745.04390608.jpg', '2024-10-21 22:52:56', 'UMN MATSURI', 'Event yang di adakan oleh UMN mengenai Budaya jejepangan'),
(4, 'banner_671716c3c7b781.14428310.jpeg', '2024-10-22 03:06:43', 'Honkai Impact', 'Senadina dan yoyo nya'),
(5, 'banner_67171723bb1e39.88716196.jpg', '2024-10-22 03:08:19', 'HoYoFest', 'Event tahunan anniversary hoyoverse'),
(6, 'banner_671a7e1aac7190.11904772.png', '2024-10-24 17:04:26', 'Honkai Impact', 'honkai impact character'),
(7, 'banner_671a7e3e7122e8.86982034.png', '2024-10-24 17:05:02', 'Honkai Impact', 'Seele and vollerei'),
(8, 'banner_671a7ec86132a3.78720586.png', '2024-10-24 17:07:20', 'Honkai Impact', 'senadina');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `event_time` time NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `max_participants` int(11) NOT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `status` enum('open','close','cancel') DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_name`, `event_date`, `event_time`, `location`, `description`, `max_participants`, `banner`, `status`, `price`, `start_date`, `end_date`) VALUES
(16, 'HoyoFest', '2025-04-02', '15:00:00', 'Jakarta, Indonesia', 'sbdvsdujvoid', 2000, '1000002191.jpg', 'open', 20000.00, '2025-04-02', '2025-04-04'),
(17, 'Embrace Your Enemy World Tour', '9998-09-09', '09:09:00', 'Earth', 'askjhgKJdsfuygJCHDgALKHJawjkheawjhvawjehV', 999, '114333245_p0_master1200.jpg', 'open', 999.00, '9998-09-09', '9999-09-09'),
(18, 'JCAFE FESTIVAL', '2025-02-20', '20:00:00', 'Jakarta, Indonesia', 'Event Tahunan JCAFE CULTURE yang di adakan di jakarta', 2000, 'banner_6717166eea65d6.27706598.png', 'open', 200000.00, '2025-02-20', '2025-02-21'),
(19, 'Meet &amp; Greet Bocchi Voice Actor', '2040-02-04', '20:00:00', 'Multimedia Nusantara University, Tangerang', 'Meet &amp; greet dengan voice actor dan band dari anime &quot;bocchi the rock!&quot;', 3000, 'bocchi-the-rock-anime-anime-girls-hd-wallpaper-preview.jpg', 'open', 400000.00, '2040-02-04', '2040-02-07'),
(20, 'UMN MATSURI', '2025-07-20', '08:00:00', 'Universitas Multimedia Nusantara', 'Event yang di adakan oleh UMN mengenai Budaya jejepangan', 4000, '114333245_p0_master1200.jpg', 'open', 20000.00, '2025-07-20', '2025-07-24');

-- --------------------------------------------------------

--
-- Table structure for table `event_participants`
--

CREATE TABLE `event_participants` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `participant_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`id`, `user_id`, `event_id`, `registration_date`) VALUES
(10, 2, 17, '2024-10-19 04:33:14'),
(11, 2, 17, '2024-10-19 04:33:22'),
(12, 2, 17, '2024-10-19 04:34:47'),
(13, 2, 17, '2024-10-19 04:41:32'),
(14, 2, 17, '2024-10-19 04:47:30'),
(15, 2, 17, '2024-10-19 04:54:54'),
(16, 2, 17, '2024-10-19 05:16:48'),
(17, 2, 17, '2024-10-19 05:34:35'),
(18, 2, 17, '2024-10-19 05:38:21'),
(19, 2, 17, '2024-10-19 06:25:42'),
(20, 2, 17, '2024-10-20 01:45:54'),
(21, 2, 17, '2024-10-22 02:56:08'),
(22, 6, 17, '2024-10-24 23:11:01'),
(23, 6, 20, '2024-10-24 23:20:02'),
(24, 6, 17, '2024-10-24 23:22:39'),
(25, 6, 17, '2024-10-24 23:24:05'),
(26, 6, 17, '2024-10-24 23:26:09'),
(27, 6, 17, '2024-10-24 23:27:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('organizer','user') DEFAULT 'user',
  `profile_pic` varchar(255) DEFAULT 'default.png',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `profile_pic`, `reset_token`, `reset_token_expiry`) VALUES
(1, 'michael', 'michael@gmail.com', '$2y$10$31zvoGYDkEZ5bl4ROJLT4.QHPvvv28IvPbQx/i3jOUTCS/KkJYv1C', 'organizer', '1_profile_pic.png', NULL, NULL),
(2, 'ben Cecep', 'ben.alfa@gmail.com', '$2y$10$31rhlXBIOOnwuSTljnHuf.o2gYPSwRSa.WCEEaQw0eKwOlLVvTYIi', 'user', '2_profile_pic.png', NULL, NULL),
(4, 'arrayyan', 'arrayyan.aprilyanto@gmail.com', '$2y$10$kwCCRDGpEt/UPAvT7KmTs.bzfMCVoxWK86nNCC.WHYT/GxZ.zgN1i', 'organizer', '4_profile_pic.png', NULL, NULL),
(6, 'arrayyan', 'benzetri78@gmail.com', '$2y$10$iWKX5RVvdm0i8KKpDDMiD.CNG02WKTElrjfHbkf0cppkeVQ55b4.a', 'user', 'default.png', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

CREATE TABLE `user_activity` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `page` varchar(255) NOT NULL,
  `time_spent` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_activity`
--

INSERT INTO `user_activity` (`id`, `user_id`, `page`, `time_spent`, `date`) VALUES
(1, 4, '/uts_event_registration_arrayyan/src/views/home.php', 9, '2024-10-13 01:53:30'),
(2, 4, '/uts_event_registration_arrayyan/src/views/home.php', 54, '2024-10-13 01:54:25'),
(3, 4, '/uts_event_registration_arrayyan/src/views/home.php', 3, '2024-10-13 01:54:31'),
(4, 4, '/uts_event_registration_arrayyan/src/views/home.php', 7, '2024-10-13 01:57:22'),
(5, 2, '/uts_event_registration_arrayyan/src/views/home.php', 10, '2024-10-13 01:57:40'),
(6, 2, '/uts_event_registration_arrayyan/src/views/register_participant.php', 33, '2024-10-13 01:58:13'),
(7, 2, '/uts_event_registration_arrayyan/src/views/home.php', 6, '2024-10-13 01:58:18'),
(8, 1, '/uts_event_registration_arrayyan/src/views/home.php', 4, '2024-10-13 01:58:28'),
(9, 1, '/uts_event_registration_arrayyan/src/views/home.php', 123, '2024-10-13 02:29:44'),
(10, 1, '/uts_event_registration_arrayyan/src/views/home.php', 751, '2024-10-13 02:42:15'),
(11, 1, '/uts_event_registration_arrayyan/src/views/home.php', 84, '2024-10-13 02:43:40'),
(12, 1, '/uts_event_registration_arrayyan/src/views/home.php', 58, '2024-10-13 02:44:38'),
(13, 1, '/uts_event_registration_arrayyan/src/views/home.php', 16, '2024-10-13 02:44:54'),
(14, 1, '/uts_event_registration_arrayyan/src/views/home.php', 16, '2024-10-13 02:45:10'),
(15, 1, '/uts_event_registration_arrayyan/src/views/home.php', 78, '2024-10-13 02:46:28'),
(16, 1, '/uts_event_registration_arrayyan/src/views/home.php', 20, '2024-10-13 02:46:49'),
(17, 1, '/uts_event_registration_arrayyan/src/views/home.php', 377, '2024-10-13 02:53:06'),
(18, 1, '/uts_event_registration_arrayyan/src/views/home.php', 4, '2024-10-13 02:53:10'),
(19, 1, '/uts_event_registration_arrayyan/src/views/home.php', 114, '2024-10-13 02:55:05'),
(20, 1, '/uts_event_registration_arrayyan/src/views/home.php', 7, '2024-10-13 02:55:12'),
(21, 1, '/uts_event_registration_arrayyan/src/views/home.php', 27, '2024-10-13 02:55:38'),
(22, 1, '/uts_event_registration_arrayyan/src/views/home.php', 25, '2024-10-13 02:56:03'),
(23, 1, '/uts_event_registration_arrayyan/src/views/home.php', 11, '2024-10-13 02:56:15'),
(24, 1, '/uts_event_registration_arrayyan/src/views/home.php', 71, '2024-10-13 02:57:26'),
(25, 1, '/uts_event_registration_arrayyan/src/views/home.php', 22, '2024-10-13 02:57:48'),
(26, 1, '/uts_event_registration_arrayyan/src/views/home.php', 16, '2024-10-13 02:58:05'),
(27, 1, '/uts_event_registration_arrayyan/src/views/home.php', 22, '2024-10-13 02:58:27'),
(28, 1, '/uts_event_registration_arrayyan/src/views/home.php', 14, '2024-10-13 02:58:41'),
(29, 1, '/uts_event_registration_arrayyan/src/views/home.php', 40, '2024-10-13 02:59:22'),
(30, 1, '/uts_event_registration_arrayyan/src/views/home.php', 23, '2024-10-13 02:59:45'),
(31, 1, '/uts_event_registration_arrayyan/src/views/home.php', 19, '2024-10-13 03:00:04'),
(32, 1, '/uts_event_registration_arrayyan/src/views/home.php', 27, '2024-10-13 03:00:32'),
(33, 1, '/uts_event_registration_arrayyan/src/views/home.php', 21, '2024-10-13 03:00:53'),
(34, 1, '/uts_event_registration_arrayyan/src/views/home.php', 18, '2024-10-13 03:01:11'),
(35, 1, '/uts_event_registration_arrayyan/src/views/home.php', 10, '2024-10-13 03:01:21'),
(36, 1, '/uts_event_registration_arrayyan/src/views/home.php', 59, '2024-10-13 03:02:21'),
(37, 1, '/uts_event_registration_arrayyan/src/views/home.php', 39, '2024-10-13 03:03:00'),
(38, 1, '/uts_event_registration_arrayyan/src/views/home.php', 45, '2024-10-13 03:03:45'),
(39, 1, '/uts_event_registration_arrayyan/src/views/home.php', 84, '2024-10-13 03:05:09'),
(40, 1, '/uts_event_registration_arrayyan/src/views/home.php', 70, '2024-10-13 03:06:19'),
(41, 1, '/uts_event_registration_arrayyan/src/views/home.php', 23, '2024-10-13 03:06:43'),
(42, 1, '/uts_event_registration_arrayyan/src/views/home.php', 85, '2024-10-13 03:08:08'),
(43, 1, '/uts_event_registration_arrayyan/src/views/home.php', 22, '2024-10-13 03:08:30'),
(44, 1, '/uts_event_registration_arrayyan/src/views/home.php', 27, '2024-10-13 03:08:57'),
(45, 1, '/uts_event_registration_arrayyan/src/views/home.php', 20, '2024-10-13 03:09:17'),
(46, 1, '/uts_event_registration_arrayyan/src/views/home.php', 3, '2024-10-13 03:09:20'),
(47, 1, '/uts_event_registration_arrayyan/src/views/home.php', 15, '2024-10-13 03:09:36'),
(48, 1, '/uts_event_registration_arrayyan/src/views/home.php', 18, '2024-10-13 03:09:54'),
(49, 1, '/uts_event_registration_arrayyan/src/views/home.php', 30, '2024-10-13 03:10:24'),
(50, 1, '/uts_event_registration_arrayyan/src/views/home.php', 473, '2024-10-13 10:16:35'),
(51, 1, '/uts_event_registration_arrayyan/src/views/event_detail.php', 3, '2024-10-13 10:16:38'),
(52, 1, '/uts_event_registration_arrayyan/src/views/event_detail.php', 1, '2024-10-13 10:16:40'),
(53, 1, '/uts_event_registration_arrayyan/src/views/home.php', 14, '2024-10-13 10:16:54'),
(54, 1, '/uts_event_registration_arrayyan/src/views/home.php', 46, '2024-10-13 10:18:41'),
(55, 1, '/uts_event_registration_arrayyan/src/views/home.php', 12, '2024-10-13 10:20:45'),
(56, 1, '/uts_event_registration_arrayyan/src/views/home.php', 40, '2024-10-13 10:21:59'),
(57, 4, '/uts_event_registration_arrayyan/src/views/home.php', 29, '2024-10-13 10:24:52'),
(58, 1, '/uts_event_registration_arrayyan/src/views/home.php', 9, '2024-10-19 09:16:06'),
(59, 1, '/uts_event_registration_arrayyan/src/views/home.php', 45, '2024-10-19 09:16:50'),
(60, 1, '/uts_event_registration_arrayyan/src/views/home.php', 899, '2024-10-19 09:31:55'),
(61, 2, '/uts_event_registration_arrayyan/src/views/home.php', 10, '2024-10-19 09:33:12'),
(62, 2, '/uts_event_registration_arrayyan/src/views/event_detail.php', 1, '2024-10-19 09:33:14'),
(63, 2, '/uts_event_registration_arrayyan/src/views/home.php', 2, '2024-10-19 09:33:16'),
(64, 2, '/uts_event_registration_arrayyan/src/views/register_participant.php', 3, '2024-10-19 09:33:19'),
(65, 2, '/uts_event_registration_arrayyan/src/views/home.php', 2, '2024-10-19 09:33:21'),
(66, 2, '/uts_event_registration_arrayyan/src/views/event_detail.php', 1, '2024-10-19 09:33:22'),
(67, 2, '/uts_event_registration_arrayyan/src/views/home.php', 7, '2024-10-19 09:33:29'),
(68, 2, '/uts_event_registration_arrayyan/src/views/event_detail.php', 78, '2024-10-19 09:34:47'),
(69, 2, '/uts_event_registration_arrayyan/src/views/home.php', 311, '2024-10-19 09:39:58'),
(70, 2, '/uts_event_registration_arrayyan/src/views/home.php', 3, '2024-10-19 09:40:01'),
(71, 2, '/uts_event_registration_arrayyan/src/views/register_participant.php', 2, '2024-10-19 09:40:03'),
(72, 2, '/uts_event_registration_arrayyan/src/views/home.php', 86, '2024-10-19 09:41:29'),
(73, 2, '/uts_event_registration_arrayyan/src/views/home.php', 2, '2024-10-19 09:41:31'),
(74, 2, '/uts_event_registration_arrayyan/src/views/event_detail.php', 1, '2024-10-19 09:41:32'),
(75, 2, '/uts_event_registration_arrayyan/src/views/home.php', 15, '2024-10-19 09:41:47'),
(76, 2, '/uts_event_registration_arrayyan/src/views/event_detail.php', 342, '2024-10-19 09:47:30'),
(77, 2, '/uts_event_registration_arrayyan/src/views/home.php', 85, '2024-10-19 09:48:54'),
(78, 2, '/uts_event_registration_arrayyan/src/views/register_participant.php', 3, '2024-10-19 09:48:57'),
(79, 2, '/uts_event_registration_arrayyan/src/views/home.php', 100, '2024-10-19 09:50:37'),
(80, 2, '/uts_event_registration_arrayyan/src/views/home.php', 196, '2024-10-19 09:53:53'),
(81, 2, '/uts_event_registration_arrayyan/src/views/home.php', 2, '2024-10-19 09:53:55'),
(82, 2, '/uts_event_registration_arrayyan/src/views/event_detail.php', 4, '2024-10-19 09:53:59'),
(83, 2, '/uts_event_registration_arrayyan/src/views/home.php', 1, '2024-10-19 09:54:00'),
(84, 2, '/uts_event_registration_arrayyan/src/views/event_detail.php', 52, '2024-10-19 09:54:52'),
(85, 2, '/uts_event_registration_arrayyan/src/views/event_detail.php', 1, '2024-10-19 09:54:54'),
(86, 2, '/uts_event_registration_arrayyan/src/views/home.php', 4, '2024-10-19 09:54:58'),
(87, 2, '/uts_event_registration_arrayyan/src/views/event_detail.php', 977, '2024-10-19 10:11:15'),
(88, 2, '/uts_event_registration_arrayyan/src/views/home.php', 3, '2024-10-19 10:16:47'),
(89, 2, '/uts_event_registration_arrayyan/src/views/event_detail.php', 1, '2024-10-19 10:16:48'),
(90, 2, '/uts_event_registration_arrayyan/src/views/home.php', 1063, '2024-10-19 10:34:31'),
(91, 2, '/uts_event_registration_arrayyan/src/views/home.php', 2, '2024-10-19 10:34:33'),
(92, 2, '/uts_event_registration_arrayyan/src/views/event_detail.php', 1, '2024-10-19 10:34:34'),
(93, 2, '/uts_event_registration_arrayyan/src/views/home.php', 221, '2024-10-19 10:38:16'),
(94, 2, '/uts_event_registration_arrayyan/src/views/home.php', 2, '2024-10-19 10:38:18'),
(95, 2, '/uts_event_registration_arrayyan/src/views/event_detail.php', 2, '2024-10-19 10:38:20'),
(96, 2, '/uts_event_registration_arrayyan/src/views/event_detail.php', 1, '2024-10-19 10:38:21'),
(97, 2, '/uts_event_registration_arrayyan/src/views/home.php', 8, '2024-10-19 10:38:29'),
(98, 2, '/uts_event_registration_arrayyan/src/views/home.php', 6, '2024-10-19 10:38:35'),
(99, 2, '/uts_event_registration_arrayyan/src/views/register_participant.php', 7, '2024-10-19 10:38:43'),
(100, 2, '/uts_event_registration_arrayyan/src/views/home.php', 6, '2024-10-19 10:38:48'),
(101, 2, '/uts_event_registration_arrayyan/src/views/home.php', 5, '2024-10-19 10:39:05'),
(102, 2, '/uts_event_registration_arrayyan/src/views/event_detail.php', 5, '2024-10-19 10:39:10'),
(103, 2, '/uts_event_registration_arrayyan/src/views/event_detail.php', 2, '2024-10-19 11:25:42'),
(104, 2, '/uts_event_registration_arrayyan/src/views/home.php', 69605, '2024-10-20 06:45:47'),
(105, 2, '/uts_event_registration_arrayyan/src/views/home.php', 3, '2024-10-20 06:45:52'),
(106, 2, '/uts_event_registration_arrayyan/src/views/event_detail.php', 1, '2024-10-20 06:45:54'),
(107, 2, '/uts_event_registration_arrayyan/src/views/home.php', 67416, '2024-10-21 01:29:29'),
(108, 2, '/uts_event_registration_arrayyan/src/views/home.php', 279, '2024-10-21 01:34:08'),
(109, 2, '/uts_event_registration_arrayyan/src/views/home.php', 99, '2024-10-21 01:35:47'),
(110, 2, '/uts_event_registration_arrayyan/src/views/home.php', 12, '2024-10-21 01:36:00'),
(111, 2, '/uts_event_registration_arrayyan/src/views/home.php', 12, '2024-10-21 01:36:12'),
(112, 2, '/uts_event_registration_arrayyan/src/views/home.php', 49, '2024-10-21 01:37:00'),
(113, 2, '/uts_event_registration_arrayyan/src/views/home.php', 16, '2024-10-21 01:37:17'),
(114, 2, '/uts_event_registration_arrayyan/src/views/home.php', 24, '2024-10-21 01:37:41'),
(115, 2, '/uts_event_registration_arrayyan/src/views/home.php', 39, '2024-10-21 01:38:20'),
(116, 2, '/uts_event_registration_arrayyan/src/views/home.php', 30, '2024-10-21 01:38:50'),
(117, 2, '/uts_event_registration_arrayyan/src/views/home.php', 17, '2024-10-21 01:39:07'),
(118, 2, '/uts_event_registration_arrayyan/src/views/home.php', 165, '2024-10-21 01:41:52'),
(119, 2, '/uts_event_registration_arrayyan/src/views/home.php', 44, '2024-10-21 01:42:36'),
(120, 2, '/uts_event_registration_arrayyan/src/views/home.php', 14, '2024-10-21 01:42:49'),
(121, 2, '/uts_event_registration_arrayyan/src/views/home.php', 16, '2024-10-21 01:43:05'),
(122, 2, '/uts_event_registration_arrayyan/src/views/home.php', 24, '2024-10-21 01:43:30'),
(123, 2, '/uts_event_registration_arrayyan/src/views/home.php', 8, '2024-10-21 01:43:38'),
(124, 2, '/uts_event_registration_arrayyan/src/views/home.php', 9, '2024-10-21 01:43:46'),
(125, 2, '/uts_event_registration_arrayyan/src/views/home.php', 24, '2024-10-21 01:44:10'),
(126, 2, '/uts_event_registration_arrayyan/src/views/home.php', 31, '2024-10-21 01:44:42'),
(127, 2, '/uts_event_registration_arrayyan/src/views/home.php', 23, '2024-10-21 01:45:05'),
(128, 2, '/uts_event_registration_arrayyan/src/views/home.php', 14, '2024-10-21 01:45:19'),
(129, 2, '/uts_event_registration_arrayyan/src/views/home.php', 46, '2024-10-21 01:46:06'),
(130, 2, '/uts_event_registration_arrayyan/src/views/home.php', 74, '2024-10-21 01:47:20'),
(131, 2, '/uts_event_registration_arrayyan/src/views/home.php', 53, '2024-10-21 01:48:12'),
(132, 2, '/uts_event_registration_arrayyan/src/views/home.php', 119, '2024-10-21 01:50:11'),
(133, 1, '/uts_event_registration_arrayyan/src/views/home.php', 36, '2024-10-21 02:20:34'),
(134, 1, '/uts_event_registration_arrayyan/src/views/home.php', 44, '2024-10-21 02:21:18'),
(135, 1, '/uts_event_registration_arrayyan/src/views/home.php', 28, '2024-10-21 02:21:46'),
(136, 1, '/uts_event_registration_arrayyan/src/views/home.php', 20, '2024-10-21 02:22:06'),
(137, 1, '/uts_event_registration_arrayyan/src/views/home.php', 12, '2024-10-21 02:22:18'),
(138, 1, '/uts_event_registration_arrayyan/src/views/home.php', 1, '2024-10-21 02:22:19'),
(139, 1, '/uts_event_registration_arrayyan/src/views/home.php', 11, '2024-10-21 02:22:31'),
(140, 1, '/uts_event_registration_arrayyan/src/views/home.php', 39, '2024-10-21 02:23:10'),
(141, 1, '/uts_event_registration_arrayyan/src/views/home.php', 9, '2024-10-21 02:23:19'),
(142, 1, '/uts_event_registration_arrayyan/src/views/home.php', 0, '2024-10-21 02:23:19'),
(143, 1, '/uts_event_registration_arrayyan/src/views/home.php', 13, '2024-10-21 02:23:32'),
(144, 1, '/uts_event_registration_arrayyan/src/views/home.php', 47, '2024-10-21 02:24:19'),
(145, 1, '/uts_event_registration_arrayyan/src/views/home.php', 21, '2024-10-21 02:24:40'),
(146, 1, '/uts_event_registration_arrayyan/src/views/home.php', 11, '2024-10-21 02:24:51'),
(147, 1, '/uts_event_registration_arrayyan/src/views/home.php', 7, '2024-10-21 02:24:58'),
(148, 1, '/uts_event_registration_arrayyan/src/views/home.php', 31, '2024-10-21 02:25:29'),
(149, 1, '/uts_event_registration_arrayyan/src/views/home.php', 650, '2024-10-21 02:36:19'),
(150, 1, '/uts_event_registration_arrayyan/src/views/home.php', 91, '2024-10-21 02:37:51'),
(151, 1, '/uts_event_registration_arrayyan/src/views/home.php', 9, '2024-10-21 02:38:00'),
(152, 1, '/uts_event_registration_arrayyan/src/views/home.php', 3, '2024-10-21 02:38:03'),
(153, 1, '/uts_event_registration_arrayyan/src/views/home.php', 50, '2024-10-21 02:38:53'),
(154, 1, '/uts_event_registration_arrayyan/src/views/home.php', 107, '2024-10-21 02:40:40'),
(155, 1, '/uts_event_registration_arrayyan/src/views/home.php', 49, '2024-10-21 02:41:29'),
(156, 1, '/uts_event_registration_arrayyan/src/views/home.php', 20, '2024-10-21 02:41:49'),
(157, 1, '/uts_event_registration_arrayyan/src/views/home.php', 23, '2024-10-21 02:42:12'),
(158, 1, '/uts_event_registration_arrayyan/src/views/home.php', 18, '2024-10-21 02:42:30'),
(159, 1, '/uts_event_registration_arrayyan/src/views/home.php', 21, '2024-10-21 02:42:51'),
(160, 1, '/uts_event_registration_arrayyan/src/views/home.php', 1, '2024-10-21 02:42:52'),
(161, 1, '/uts_event_registration_arrayyan/src/views/home.php', 0, '2024-10-21 02:42:52'),
(162, 1, '/uts_event_registration_arrayyan/src/views/home.php', 5, '2024-10-21 02:42:57'),
(163, 1, '/uts_event_registration_arrayyan/src/views/home.php', 27, '2024-10-21 02:43:24'),
(164, 1, '/uts_event_registration_arrayyan/src/views/home.php', 2, '2024-10-21 02:43:26'),
(165, 1, '/uts_event_registration_arrayyan/src/views/home.php', 41, '2024-10-21 02:44:09'),
(166, 1, '/uts_event_registration_arrayyan/src/views/home.php', 19, '2024-10-21 02:44:28'),
(167, 1, '/uts_event_registration_arrayyan/src/views/home.php', 7, '2024-10-21 02:44:35'),
(168, 1, '/uts_event_registration_arrayyan/src/views/home.php', 7, '2024-10-21 02:44:42'),
(169, 1, '/uts_event_registration_arrayyan/src/views/home.php', 13, '2024-10-21 02:44:55'),
(170, 1, '/uts_event_registration_arrayyan/src/views/home.php', 10, '2024-10-21 02:45:05'),
(171, 1, '/uts_event_registration_arrayyan/src/views/home.php', 32, '2024-10-21 02:45:37'),
(172, 1, '/uts_event_registration_arrayyan/src/views/home.php', 4, '2024-10-21 02:45:40'),
(173, 1, '/uts_event_registration_arrayyan/src/views/home.php', 17, '2024-10-21 02:45:58'),
(174, 1, '/uts_event_registration_arrayyan/src/views/home.php', 8, '2024-10-21 02:46:06'),
(175, 1, '/uts_event_registration_arrayyan/src/views/home.php', 11, '2024-10-21 02:46:17'),
(176, 1, '/uts_event_registration_arrayyan/src/views/home.php', 63, '2024-10-21 02:47:20'),
(177, 1, '/uts_event_registration_arrayyan/src/views/home.php', 15, '2024-10-21 02:47:35'),
(178, 1, '/uts_event_registration_arrayyan/src/views/home.php', 163, '2024-10-21 02:50:18'),
(179, 1, '/uts_event_registration_arrayyan/src/views/home.php', 34, '2024-10-21 02:50:53'),
(180, 1, '/uts_event_registration_arrayyan/src/views/home.php', 13, '2024-10-21 02:51:05'),
(181, 1, '/uts_event_registration_arrayyan/src/views/home.php', 2, '2024-10-21 02:51:08'),
(182, 1, '/uts_event_registration_arrayyan/src/views/home.php', 4, '2024-10-21 02:51:12'),
(183, 1, '/uts_event_registration_arrayyan/src/views/home.php', 2, '2024-10-21 02:51:14'),
(184, 1, '/uts_event_registration_arrayyan/src/views/home.php', 190, '2024-10-21 02:54:24'),
(185, 1, '/uts_event_registration_arrayyan/src/views/home.php', 1, '2024-10-21 02:54:25'),
(186, 1, '/uts_event_registration_arrayyan/src/views/home.php', 119, '2024-10-21 02:56:24'),
(187, 1, '/uts_event_registration_arrayyan/src/views/home.php', 0, '2024-10-21 02:56:25'),
(188, 1, '/uts_event_registration_arrayyan/src/views/home.php', 0, '2024-10-21 02:56:25'),
(189, 1, '/uts_event_registration_arrayyan/src/views/home.php', 0, '2024-10-21 02:56:25'),
(190, 1, '/uts_event_registration_arrayyan/src/views/home.php', 0, '2024-10-21 02:56:25'),
(191, 1, '/uts_event_registration_arrayyan/src/views/home.php', 0, '2024-10-21 02:56:26'),
(192, 1, '/uts_event_registration_arrayyan/src/views/home.php', 0, '2024-10-21 02:56:26'),
(193, 1, '/uts_event_registration_arrayyan/src/views/home.php', 0, '2024-10-21 02:56:26'),
(194, 1, '/uts_event_registration_arrayyan/src/views/home.php', 58, '2024-10-21 02:57:24'),
(195, 1, '/uts_event_registration_arrayyan/src/views/home.php', 0, '2024-10-21 02:57:25'),
(196, 1, '/uts_event_registration_arrayyan/src/views/home.php', 6, '2024-10-21 02:57:31'),
(197, 1, '/uts_event_registration_arrayyan/src/views/home.php', 206, '2024-10-21 03:00:57'),
(198, 1, '/uts_event_registration_arrayyan/src/views/home.php', 9, '2024-10-21 03:01:06'),
(199, 1, '/uts_event_registration_arrayyan/src/views/home.php', 369, '2024-10-21 03:07:15'),
(200, 1, '/uts_event_registration_arrayyan/src/views/home.php', 26, '2024-10-21 03:07:40'),
(201, 1, '/uts_event_registration_arrayyan/src/views/home.php', 27, '2024-10-21 03:08:07'),
(202, 1, '/uts_event_registration_arrayyan/src/views/home.php', 9, '2024-10-21 07:17:44'),
(203, 1, '/uts_event_registration_arrayyan/src/views/home.php', 18, '2024-10-21 07:18:02'),
(204, 1, '/uts_event_registration_arrayyan/src/views/home.php', 18, '2024-10-21 07:18:21'),
(205, 1, '/uts_event_registration_arrayyan/src/views/home.php', 362, '2024-10-21 07:24:23'),
(206, 1, '/uts_event_registration_arrayyan/src/views/home.php', 133, '2024-10-21 07:26:36'),
(207, 1, '/uts_event_registration_arrayyan/src/views/home.php', 370, '2024-10-21 07:32:46'),
(208, 1, '/uts_event_registration_arrayyan/src/views/home.php', 59, '2024-10-21 07:33:45'),
(209, 1, '/uts_event_registration_arrayyan/src/views/home.php', 12, '2024-10-21 07:33:57'),
(210, 1, '/uts_event_registration_arrayyan/src/views/home.php', 54, '2024-10-21 07:34:51'),
(211, 1, '/uts_event_registration_arrayyan/src/views/home.php', 60, '2024-10-21 07:38:18'),
(212, 1, '/uts_event_registration_arrayyan/src/views/home.php', 21, '2024-10-21 07:38:39'),
(213, 1, '/uts_event_registration_arrayyan/src/views/home.php', 233, '2024-10-21 07:42:31'),
(214, 1, '/uts_event_registration_arrayyan/src/views/home.php', 586, '2024-10-21 07:52:17'),
(215, 1, '/uts_event_registration_arrayyan/src/views/home.php', 2, '2024-10-21 07:52:19'),
(216, 1, '/uts_event_registration_arrayyan/src/views/home.php', 69, '2024-10-21 08:12:33'),
(217, 1, '/uts_event_registration_arrayyan/src/views/home.php', 36, '2024-10-21 08:13:09'),
(218, 1, '/uts_event_registration_arrayyan/src/views/home.php', 17, '2024-10-21 08:13:26'),
(219, 1, '/uts_event_registration_arrayyan/src/views/home.php', 17, '2024-10-21 13:26:11'),
(220, 1, '/uts_event_registration_arrayyan/src/views/home.php', 76, '2024-10-21 13:27:27'),
(221, 1, '/uts_event_registration_arrayyan/src/views/home.php', 9, '2024-10-21 13:27:37'),
(222, 1, '/uts_event_registration_arrayyan/src/views/home.php', 40, '2024-10-21 13:28:17'),
(223, 1, '/uts_event_registration_arrayyan/src/views/home.php', 17, '2024-10-21 13:28:34'),
(224, 1, '/uts_event_registration_arrayyan/src/views/home.php', 23, '2024-10-21 13:28:57'),
(225, 1, '/uts_event_registration_arrayyan/src/views/home.php', 6, '2024-10-21 13:29:03'),
(226, 1, '/uts_event_registration_arrayyan/src/views/home.php', 14, '2024-10-21 13:29:18'),
(227, 1, '/uts_event_registration_arrayyan/src/views/home.php', 11, '2024-10-21 13:29:36'),
(228, 1, '/uts_event_registration_arrayyan/src/views/home.php', 1, '2024-10-21 13:29:37'),
(229, 1, '/uts_event_registration_arrayyan/src/views/home.php', 217, '2024-10-21 13:35:20'),
(230, 1, '/uts_event_registration_arrayyan/src/views/home.php', 11, '2024-10-21 13:35:31'),
(231, 1, '/uts_event_registration_arrayyan/src/views/home.php', 98, '2024-10-21 22:14:45'),
(232, 1, '/uts_event_registration_arrayyan/src/views/home.php', 32, '2024-10-21 22:15:17'),
(233, 1, '/uts_event_registration_arrayyan/src/views/home.php', 14, '2024-10-21 22:15:30'),
(234, 1, '/uts_event_registration_arrayyan/src/views/home.php', 25, '2024-10-21 22:15:55'),
(235, 1, '/uts_event_registration_arrayyan/src/views/home.php', 29, '2024-10-21 22:16:24'),
(236, 1, '/uts_event_registration_arrayyan/src/views/home.php', 30, '2024-10-21 22:16:53'),
(237, 1, '/uts_event_registration_arrayyan/src/views/home.php', 76, '2024-10-21 22:18:10'),
(238, 1, '/uts_event_registration_arrayyan/src/views/home.php', 39, '2024-10-21 22:18:49'),
(239, 1, '/uts_event_registration_arrayyan/src/views/home.php', 11, '2024-10-21 22:18:59'),
(240, 1, '/uts_event_registration_arrayyan/src/views/home.php', 263, '2024-10-21 22:23:23'),
(241, 1, '/uts_event_registration_arrayyan/src/views/home.php', 23, '2024-10-21 22:23:46'),
(242, 1, '/uts_event_registration_arrayyan/src/views/home.php', 5, '2024-10-21 22:23:52'),
(243, 1, '/uts_event_registration_arrayyan/src/views/home.php', 92, '2024-10-21 22:25:24'),
(244, 1, '/uts_event_registration_arrayyan/src/views/home.php', 426, '2024-10-21 22:32:30'),
(245, 1, '/uts_event_registration_arrayyan/src/views/home.php', 99, '2024-10-21 22:34:10'),
(246, 1, '/uts_event_registration_arrayyan/src/views/home.php', 6, '2024-10-21 22:34:16'),
(247, 1, '/uts_event_registration_arrayyan/src/views/home.php', 8, '2024-10-21 22:34:25'),
(248, 1, '/uts_event_registration_arrayyan/src/views/home.php', 32, '2024-10-21 22:34:57'),
(249, 1, '/uts_event_registration_arrayyan/src/views/home.php', 8, '2024-10-21 22:35:05'),
(250, 1, '/uts_event_registration_arrayyan/src/views/home.php', 11, '2024-10-21 22:35:17'),
(251, 1, '/uts_event_registration_arrayyan/src/views/home.php', 12, '2024-10-21 22:35:28'),
(252, 1, '/uts_event_registration_arrayyan/src/views/home.php', 18, '2024-10-21 22:35:46'),
(253, 1, '/uts_event_registration_arrayyan/src/views/home.php', 42, '2024-10-21 22:36:28'),
(254, 1, '/uts_event_registration_arrayyan/src/views/home.php', 12, '2024-10-21 22:36:40'),
(255, 1, '/uts_event_registration_arrayyan/src/views/event_detail.php', 2, '2024-10-21 22:36:42'),
(256, 1, '/uts_event_registration_arrayyan/src/views/home.php', 43, '2024-10-21 22:37:25'),
(257, 1, '/uts_event_registration_arrayyan/src/views/home.php', 8, '2024-10-21 22:37:32'),
(258, 1, '/uts_event_registration_arrayyan/src/views/home.php', 40, '2024-10-21 22:38:13'),
(259, 1, '/uts_event_registration_arrayyan/src/views/home.php', 22, '2024-10-21 22:38:35'),
(260, 1, '/uts_event_registration_arrayyan/src/views/home.php', 9, '2024-10-21 22:38:44'),
(261, 1, '/uts_event_registration_arrayyan/src/views/home.php', 0, '2024-10-21 22:38:44'),
(262, 1, '/uts_event_registration_arrayyan/src/views/home.php', 0, '2024-10-21 22:38:44'),
(263, 1, '/uts_event_registration_arrayyan/src/views/home.php', 0, '2024-10-21 22:38:44'),
(264, 1, '/uts_event_registration_arrayyan/src/views/home.php', 0, '2024-10-21 22:38:44'),
(265, 1, '/uts_event_registration_arrayyan/src/views/home.php', 0, '2024-10-21 22:38:45'),
(266, 1, '/uts_event_registration_arrayyan/src/views/home.php', 8, '2024-10-21 22:38:52'),
(267, 1, '/uts_event_registration_arrayyan/src/views/home.php', 10, '2024-10-21 22:39:02'),
(268, 1, '/uts_event_registration_arrayyan/src/views/home.php', 29, '2024-10-21 22:43:41'),
(269, 1, '/uts_event_registration_arrayyan/src/views/home.php', 9, '2024-10-21 22:43:55'),
(270, 1, '/uts_event_registration_arrayyan/src/views/home.php', 199, '2024-10-21 22:51:10'),
(271, 1, '/uts_event_registration_arrayyan/src/views/home.php', 108, '2024-10-21 22:52:59'),
(272, 1, '/uts_event_registration_arrayyan/src/views/home.php', 79, '2024-10-21 22:54:18'),
(273, 1, '/uts_event_registration_arrayyan/src/views/home.php', 120, '2024-10-21 22:56:18'),
(274, 1, '/uts_event_registration_arrayyan/src/views/home.php', 10, '2024-10-21 22:56:29'),
(275, 1, '/uts_event_registration_arrayyan/src/views/home.php', 137, '2024-10-21 22:58:46'),
(276, 1, '/uts_event_registration_arrayyan/src/views/home.php', 12, '2024-10-21 22:58:58'),
(277, 1, '/uts_event_registration_arrayyan/src/views/home.php', 36, '2024-10-21 22:59:34'),
(278, 1, '/uts_event_registration_arrayyan/src/views/home.php', 20, '2024-10-21 22:59:54'),
(279, 1, '/uts_event_registration_arrayyan/src/views/home.php', 10, '2024-10-21 23:00:04'),
(280, 1, '/uts_event_registration_arrayyan/src/views/home.php', 9, '2024-10-21 23:00:13'),
(281, 1, '/uts_event_registration_arrayyan/src/views/home.php', 904, '2024-10-21 23:15:17'),
(282, 4, '/uts_event_registration_arrayyan/src/views/home.php', 526, '2024-10-21 23:31:39'),
(283, 4, '/uts_event_registration_arrayyan/src/views/home.php', 195, '2024-10-21 23:56:42'),
(284, 4, '/uts_event_registration_arrayyan/src/views/home.php', 1, '2024-10-21 23:56:43'),
(285, 4, '/uts_event_registration_arrayyan/src/views/home.php', 33, '2024-10-21 23:57:17'),
(286, 4, '/uts_event_registration_arrayyan/src/views/home.php', 18, '2024-10-21 23:57:35'),
(287, 4, '/uts_event_registration_arrayyan/src/views/home.php', 24, '2024-10-21 23:57:58'),
(288, 4, '/uts_event_registration_arrayyan/src/views/home.php', 114, '2024-10-21 23:59:52'),
(289, 4, '/uts_event_registration_arrayyan/src/views/home.php', 13, '2024-10-22 00:00:06'),
(290, 4, '/uts_event_registration_arrayyan/src/views/home.php', 53, '2024-10-22 00:05:49'),
(291, 4, '/uts_event_registration_arrayyan/src/views/home.php', 333, '2024-10-22 00:11:22'),
(292, 4, '/uts_event_registration_arrayyan/src/views/home.php', 49, '2024-10-22 00:12:11'),
(293, 4, '/uts_event_registration_arrayyan/src/views/home.php', 56, '2024-10-22 00:13:07'),
(294, 4, '/uts_event_registration_arrayyan/src/views/home.php', 93, '2024-10-22 00:14:41'),
(295, 4, '/uts_event_registration_arrayyan/src/views/home.php', 4, '2024-10-22 00:14:45'),
(296, 4, '/uts_event_registration_arrayyan/src/views/home.php', 170, '2024-10-22 00:17:35'),
(297, 4, '/uts_event_registration_arrayyan/src/views/home.php', 11, '2024-10-22 00:17:46'),
(298, 4, '/uts_event_registration_arrayyan/src/views/home.php', 8, '2024-10-22 00:17:54'),
(299, 4, '/uts_event_registration_arrayyan/src/views/home.php', 64, '2024-10-22 00:18:58'),
(300, 4, '/uts_event_registration_arrayyan/src/views/home.php', 261, '2024-10-22 00:23:20'),
(301, 4, '/uts_event_registration_arrayyan/src/views/home.php', 49, '2024-10-22 00:24:09'),
(302, 4, '/uts_event_registration_arrayyan/src/views/home.php', 29, '2024-10-22 00:24:38'),
(303, 4, '/uts_event_registration_arrayyan/src/views/home.php', 17, '2024-10-22 00:24:55'),
(304, 4, '/uts_event_registration_arrayyan/src/views/home.php', 13, '2024-10-22 00:25:08'),
(305, 4, '/uts_event_registration_arrayyan/src/views/home.php', 319, '2024-10-22 00:30:27'),
(306, 4, '/uts_event_registration_arrayyan/src/views/home.php', 27, '2024-10-22 00:30:54'),
(307, 4, '/uts_event_registration_arrayyan/src/views/home.php', 67, '2024-10-22 00:32:01'),
(308, 4, '/uts_event_registration_arrayyan/src/views/home.php', 15, '2024-10-22 00:32:17'),
(309, 4, '/uts_event_registration_arrayyan/src/views/home.php', 38, '2024-10-22 00:32:55'),
(310, 4, '/uts_event_registration_arrayyan/src/views/home.php', 145, '2024-10-22 00:35:20'),
(311, 4, '/uts_event_registration_arrayyan/src/views/home.php', 34, '2024-10-22 00:35:54'),
(312, 4, '/uts_event_registration_arrayyan/src/views/home.php', 20, '2024-10-22 00:36:14'),
(313, 4, '/uts_event_registration_arrayyan/src/views/home.php', 95, '2024-10-22 00:37:49'),
(314, 4, '/uts_event_registration_arrayyan/src/views/home.php', 13, '2024-10-22 00:38:02'),
(315, 4, '/uts_event_registration_arrayyan/src/views/home.php', 29, '2024-10-22 00:38:31'),
(316, 4, '/uts_event_registration_arrayyan/src/views/home.php', 35, '2024-10-22 00:39:07'),
(317, 4, '/uts_event_registration_arrayyan/src/views/home.php', 117, '2024-10-22 00:41:04'),
(318, 4, '/uts_event_registration_arrayyan/src/views/home.php', 27, '2024-10-22 00:41:31'),
(319, 4, '/uts_event_registration_arrayyan/src/views/home.php', 74, '2024-10-22 00:42:46'),
(320, 4, '/uts_event_registration_arrayyan/src/views/home.php', 30, '2024-10-22 00:43:16'),
(321, 4, '/uts_event_registration_arrayyan/src/views/home.php', 15, '2024-10-22 00:43:31'),
(322, 4, '/uts_event_registration_arrayyan/src/views/home.php', 32, '2024-10-22 00:44:03'),
(323, 4, '/uts_event_registration_arrayyan/src/views/home.php', 13, '2024-10-22 00:44:17'),
(324, 4, '/uts_event_registration_arrayyan/src/views/home.php', 201, '2024-10-22 00:47:38'),
(325, 4, '/uts_event_registration_arrayyan/src/views/home.php', 18, '2024-10-22 00:47:56'),
(326, 4, '/uts_event_registration_arrayyan/src/views/home.php', 264, '2024-10-22 00:52:21'),
(327, 4, '/uts_event_registration_arrayyan/src/views/home.php', 91, '2024-10-22 00:53:52'),
(328, 4, '/uts_event_registration_arrayyan/src/views/home.php', 75, '2024-10-22 00:55:08'),
(329, 4, '/uts_event_registration_arrayyan/src/views/home.php', 18, '2024-10-22 03:06:18'),
(330, 4, '/uts_event_registration_arrayyan/src/views/home.php', 35, '2024-10-22 03:07:25'),
(331, 4, '/uts_event_registration_arrayyan/src/views/home.php', 48, '2024-10-22 03:09:09'),
(332, 4, '/uts_event_registration_arrayyan/src/views/home.php', 14, '2024-10-22 03:09:23'),
(333, 4, '/uts_event_registration_arrayyan/src/views/home.php', 10, '2024-10-22 03:09:34'),
(334, 4, '/uts_event_registration_arrayyan/src/views/home.php', 16, '2024-10-22 03:09:50'),
(335, 4, '/uts_event_registration_arrayyan/src/views/home.php', 10, '2024-10-22 03:10:00'),
(336, 4, '/uts_event_registration_arrayyan/src/views/home.php', 18, '2024-10-22 03:10:18'),
(337, 4, '/uts_event_registration_arrayyan/src/views/home.php', 33, '2024-10-22 03:10:51'),
(338, 4, '/uts_event_registration_arrayyan/src/views/home.php', 12, '2024-10-22 03:11:03'),
(339, 4, '/uts_event_registration_arrayyan/src/views/home.php', 46, '2024-10-22 03:11:49'),
(340, 4, '/uts_event_registration_arrayyan/src/views/home.php', 83, '2024-10-22 03:13:13'),
(341, 4, '/uts_event_registration_arrayyan/src/views/home.php', 31, '2024-10-22 03:13:44'),
(342, 4, '/uts_event_registration_arrayyan/src/views/home.php', 25, '2024-10-22 03:14:09'),
(343, 4, '/uts_event_registration_arrayyan/src/views/home.php', 97, '2024-10-22 03:15:47'),
(344, 4, '/uts_event_registration_arrayyan/src/views/home.php', 41, '2024-10-22 03:16:28'),
(345, 4, '/uts_event_registration_arrayyan/src/views/home.php', 32, '2024-10-22 03:17:01'),
(346, 4, '/uts_event_registration_arrayyan/src/views/home.php', 1, '2024-10-22 03:17:02'),
(347, 4, '/uts_event_registration_arrayyan/src/views/home.php', 0, '2024-10-22 03:17:03'),
(348, 4, '/uts_event_registration_arrayyan/src/views/home.php', 0, '2024-10-22 03:17:03'),
(349, 4, '/uts_event_registration_arrayyan/src/views/home.php', 22, '2024-10-22 03:17:25'),
(350, 4, '/uts_event_registration_arrayyan/src/views/home.php', 178, '2024-10-22 03:20:23'),
(351, 4, '/uts_event_registration_arrayyan/src/views/home.php', 55, '2024-10-22 03:21:18'),
(352, 4, '/uts_event_registration_arrayyan/src/views/home.php', 84, '2024-10-22 03:22:43'),
(353, 4, '/uts_event_registration_arrayyan/src/views/home.php', 165, '2024-10-22 03:25:28'),
(354, 4, '/uts_event_registration_arrayyan/src/views/home.php', 892, '2024-10-22 03:40:20'),
(355, 4, '/uts_event_registration_arrayyan/src/views/home.php', 11, '2024-10-22 03:40:31'),
(356, 4, '/uts_event_registration_arrayyan/src/views/home.php', 24, '2024-10-22 03:40:55'),
(357, 4, '/uts_event_registration_arrayyan/src/views/home.php', 16, '2024-10-22 03:41:11'),
(358, 4, '/uts_event_registration_arrayyan/src/views/home.php', 11, '2024-10-22 03:41:22'),
(359, 4, '/uts_event_registration_arrayyan/src/views/home.php', 11, '2024-10-22 03:41:33'),
(360, 4, '/uts_event_registration_arrayyan/src/views/home.php', 23, '2024-10-22 03:41:57'),
(361, 4, '/uts_event_registration_arrayyan/src/views/home.php', 9, '2024-10-22 03:42:05'),
(362, 4, '/uts_event_registration_arrayyan/src/views/home.php', 10, '2024-10-22 03:42:16'),
(363, 4, '/uts_event_registration_arrayyan/src/views/home.php', 47, '2024-10-22 03:43:03'),
(364, 4, '/uts_event_registration_arrayyan/src/views/home.php', 47, '2024-10-22 03:43:50'),
(365, 4, '/uts_event_registration_arrayyan/src/views/home.php', 24, '2024-10-22 03:44:14'),
(366, 4, '/uts_event_registration_arrayyan/src/views/home.php', 8, '2024-10-22 03:44:23'),
(367, 4, '/uts_event_registration_arrayyan/src/views/home.php', 14, '2024-10-22 03:44:37'),
(368, 4, '/uts_event_registration_arrayyan/src/views/home.php', 25, '2024-10-22 03:45:03'),
(369, 4, '/uts_event_registration_arrayyan/src/views/home.php', 35, '2024-10-22 03:45:38'),
(370, 4, '/uts_event_registration_arrayyan/src/views/home.php', 25, '2024-10-22 03:46:04'),
(371, 4, '/uts_event_registration_arrayyan/src/views/home.php', 18, '2024-10-22 03:46:21'),
(372, 4, '/uts_event_registration_arrayyan/src/views/home.php', 109, '2024-10-22 03:48:10'),
(373, 4, '/uts_event_registration_arrayyan/src/views/home.php', 35, '2024-10-22 03:48:45'),
(374, 4, '/uts_event_registration_arrayyan/src/views/home.php', 344, '2024-10-22 03:54:29'),
(375, 4, '/uts_event_registration_arrayyan/src/views/home.php', 5, '2024-10-22 03:54:34'),
(376, 4, '/uts_event_registration_arrayyan/src/views/home.php', 4, '2024-10-22 03:54:39'),
(377, 4, '/uts_event_registration_arrayyan/src/views/home.php', 4, '2024-10-22 03:54:43'),
(378, 4, '/uts_event_registration_arrayyan/src/views/home.php', 132, '2024-10-22 03:56:55'),
(379, 4, '/uts_event_registration_arrayyan/src/views/home.php', 3, '2024-10-22 03:56:58'),
(380, 4, '/uts_event_registration_arrayyan/src/views/home.php', 2, '2024-10-22 03:57:00'),
(381, 4, '/uts_event_registration_arrayyan/src/views/home.php', 4, '2024-10-22 03:57:05'),
(382, 4, '/uts_event_registration_arrayyan/src/views/home.php', 27, '2024-10-22 03:57:32'),
(383, 4, '/uts_event_registration_arrayyan/src/views/home.php', 5, '2024-10-22 03:57:37'),
(384, 4, '/uts_event_registration_arrayyan/src/views/home.php', 120, '2024-10-22 03:59:36'),
(385, 4, '/uts_event_registration_arrayyan/src/views/home.php', 4, '2024-10-22 03:59:40'),
(386, 4, '/uts_event_registration_arrayyan/src/views/home.php', 2, '2024-10-22 03:59:42'),
(387, 4, '/uts_event_registration_arrayyan/src/views/home.php', 5, '2024-10-22 03:59:47'),
(388, 4, '/uts_event_registration_arrayyan/src/views/home.php', 5, '2024-10-22 03:59:52'),
(389, 4, '/uts_event_registration_arrayyan/src/views/home.php', 6, '2024-10-22 03:59:58'),
(390, 4, '/uts_event_registration_arrayyan/src/views/home.php', 60, '2024-10-22 04:00:58'),
(391, 4, '/uts_event_registration_arrayyan/src/views/home.php', 85, '2024-10-22 04:02:25'),
(392, 4, '/uts_event_registration_arrayyan/src/views/home.php', 119, '2024-10-22 04:04:25'),
(393, 4, '/uts_event_registration_arrayyan/src/views/home.php', 64, '2024-10-22 04:05:29'),
(394, 4, '/uts_event_registration_arrayyan/src/views/home.php', 28, '2024-10-22 04:05:57'),
(395, 4, '/uts_event_registration_arrayyan/src/views/home.php', 16, '2024-10-22 04:06:13'),
(396, 4, '/uts_event_registration_arrayyan/src/views/home.php', 14, '2024-10-22 04:06:27'),
(397, 4, '/uts_event_registration_arrayyan/src/views/home.php', 72, '2024-10-22 04:07:39'),
(398, 4, '/uts_event_registration_arrayyan/src/views/home.php', 54, '2024-10-22 04:08:33'),
(399, 4, '/uts_event_registration_arrayyan/src/views/home.php', 9, '2024-10-22 04:08:42'),
(400, 4, '/uts_event_registration_arrayyan/src/views/home.php', 9, '2024-10-22 04:08:51'),
(401, 4, '/uts_event_registration_arrayyan/src/views/home.php', 24, '2024-10-22 04:09:15'),
(402, 4, '/uts_event_registration_arrayyan/src/views/home.php', 24, '2024-10-22 04:09:38'),
(403, 4, '/uts_event_registration_arrayyan/src/views/home.php', 60, '2024-10-22 04:10:38'),
(404, 4, '/uts_event_registration_arrayyan/src/views/home.php', 77, '2024-10-22 04:11:55'),
(405, 4, '/uts_event_registration_arrayyan/src/views/home.php', 49, '2024-10-22 04:12:44'),
(406, 4, '/uts_event_registration_arrayyan/src/views/home.php', 55, '2024-10-22 04:13:39'),
(407, 4, '/uts_event_registration_arrayyan/src/views/home.php', 76, '2024-10-22 04:14:55'),
(408, 4, '/uts_event_registration_arrayyan/src/views/home.php', 75, '2024-10-22 04:16:11'),
(409, 4, '/uts_event_registration_arrayyan/src/views/home.php', 32, '2024-10-22 04:16:43'),
(410, 4, '/uts_event_registration_arrayyan/src/views/home.php', 14, '2024-10-22 04:16:58'),
(411, 4, '/uts_event_registration_arrayyan/src/views/home.php', 54, '2024-10-22 04:17:53'),
(412, 4, '/uts_event_registration_arrayyan/src/views/home.php', 6, '2024-10-22 04:17:59'),
(413, 4, '/uts_event_registration_arrayyan/src/views/home.php', 13, '2024-10-22 04:18:12'),
(414, 4, '/uts_event_registration_arrayyan/src/views/home.php', 42, '2024-10-22 04:18:54'),
(415, 4, '/uts_event_registration_arrayyan/src/views/home.php', 4, '2024-10-22 04:18:59'),
(416, 4, '/uts_event_registration_arrayyan/src/views/home.php', 0, '2024-10-22 04:19:16'),
(417, 4, '/uts_event_registration_arrayyan/src/views/home.php', 1, '2024-10-22 04:19:17'),
(418, 4, '/uts_event_registration_arrayyan/src/views/home.php', 0, '2024-10-22 04:19:17'),
(419, 4, '/uts_event_registration_arrayyan/src/views/home.php', 0, '2024-10-22 04:19:18'),
(420, 4, '/uts_event_registration_arrayyan/src/views/home.php', 0, '2024-10-22 04:19:18'),
(421, 4, '/uts_event_registration_arrayyan/src/views/home.php', 202, '2024-10-22 04:22:40'),
(422, 4, '/uts_event_registration_arrayyan/src/views/home.php', 17, '2024-10-22 04:22:58'),
(423, 4, '/uts_event_registration_arrayyan/src/views/home.php', 67, '2024-10-22 04:24:08'),
(424, 4, '/uts_event_registration_arrayyan/src/views/home.php', 6, '2024-10-22 04:24:28'),
(425, 2, '/uts_event_registration_arrayyan/src/views/home.php', 12360, '2024-10-22 07:50:33'),
(426, 2, '/uts_event_registration_arrayyan/src/views/register_participant.php', 4, '2024-10-22 07:50:37'),
(427, 2, '/uts_event_registration_arrayyan/src/views/home.php', 5, '2024-10-22 07:50:41'),
(428, 2, '/uts_event_registration_arrayyan/src/views/home.php', 253, '2024-10-22 07:54:54'),
(429, 2, '/uts_event_registration_arrayyan/src/views/event_detail.php', 6, '2024-10-22 07:55:00'),
(430, 2, '/uts_event_registration_arrayyan/src/views/home.php', 11, '2024-10-22 07:55:11'),
(431, 2, '/uts_event_registration_arrayyan/src/views/event_detail.php', 37, '2024-10-22 07:55:48'),
(432, 2, '/uts_event_registration_arrayyan/src/views/event_detail.php', 15, '2024-10-22 07:56:03'),
(433, 2, '/uts_event_registration_arrayyan/src/views/event_detail.php', 5, '2024-10-22 07:56:08'),
(434, 2, '/uts_event_registration_arrayyan/src/views/home.php', 20, '2024-10-22 07:56:28'),
(435, 2, '/uts_event_registration_arrayyan/src/views/home.php', 29, '2024-10-22 07:56:57'),
(436, 2, '/uts_event_registration_arrayyan/src/views/event_detail.php', 2, '2024-10-22 07:56:59'),
(437, 2, '/uts_event_registration_arrayyan/src/views/home.php', 14, '2024-10-22 07:57:13'),
(438, 2, '/uts_event_registration_arrayyan/src/views/home.php', 117, '2024-10-22 07:59:14'),
(439, 2, '/uts_event_registration_arrayyan/src/views/home.php', 34, '2024-10-22 07:59:48'),
(440, 2, '/uts_event_registration_arrayyan/src/views/home.php', 3, '2024-10-22 07:59:51'),
(441, 2, '/uts_event_registration_arrayyan/src/views/home.php', 34, '2024-10-22 08:00:25'),
(442, 2, '/uts_event_registration_arrayyan/src/views/home.php', 97, '2024-10-22 08:02:02'),
(443, 2, '/uts_event_registration_arrayyan/src/views/home.php', 37, '2024-10-22 08:02:39'),
(444, 2, '/uts_event_registration_arrayyan/src/views/home.php', 23, '2024-10-22 08:03:02'),
(445, 1, '/uts_event_registration_arrayyan/src/views/home.php', 68, '2024-10-22 08:40:21'),
(446, 1, '/uts_event_registration_arrayyan/src/views/home.php', 8, '2024-10-22 08:40:29'),
(447, 4, '/uts_event_registration_arrayyan/src/views/home.php', 1795, '2024-10-22 09:10:28'),
(448, 4, '/uts_event_registration_arrayyan/src/views/home.php', 8, '2024-10-22 09:21:18'),
(449, 4, '/uts_event_registration_arrayyan/src/views/home.php', 3, '2024-10-22 09:41:07'),
(450, 4, '/uts_event_registration_arrayyan/src/views/home.php', 5, '2024-10-22 09:48:56'),
(451, 4, '/uts_event_registration_arrayyan/src/views/home.php', 207, '2024-10-22 11:06:08'),
(452, 4, '/uts_event_registration_arrayyan/src/views/home.php', 73, '2024-10-22 11:07:21'),
(453, 4, '/uts_event_registration_arrayyan/src/views/home.php', 194, '2024-10-22 12:55:10'),
(466, 4, '/uts_event_registration_arrayyan/src/views/home.php', 3, '2024-10-24 17:04:08'),
(467, 4, '/uts_event_registration_arrayyan/src/views/home.php', 49, '2024-10-24 17:06:19'),
(468, 4, '/uts_event_registration_arrayyan/src/views/home.php', 41, '2024-10-24 17:07:00'),
(469, 4, '/uts_event_registration_arrayyan/src/views/home.php', 10, '2024-10-24 17:07:41'),
(470, 4, '/uts_event_registration_arrayyan/src/views/home.php', 183, '2024-10-24 17:10:44'),
(471, 4, '/uts_event_registration_arrayyan/src/views/home.php', 51, '2024-10-24 17:11:36'),
(472, 4, '/uts_event_registration_arrayyan/src/views/home.php', 4260, '2024-10-24 22:46:04'),
(520, 6, '/uts_event_registration_arrayyan/src/views/home.php', 7, '2024-10-25 04:10:50'),
(521, 6, '/uts_event_registration_arrayyan/src/views/register_participant.php', 5, '2024-10-25 04:10:55'),
(522, 6, '/uts_event_registration_arrayyan/src/views/home.php', 4, '2024-10-25 04:10:59'),
(523, 6, '/uts_event_registration_arrayyan/src/views/event_detail.php', 2, '2024-10-25 04:11:01'),
(524, 6, '/uts_event_registration_arrayyan/src/views/home.php', 106, '2024-10-25 04:12:47'),
(525, 6, '/uts_event_registration_arrayyan/src/views/event_detail.php', 422, '2024-10-25 04:19:49'),
(526, 6, '/uts_event_registration_arrayyan/src/views/event_detail.php', 1, '2024-10-25 04:19:50'),
(527, 6, '/uts_event_registration_arrayyan/src/views/event_detail.php', 1, '2024-10-25 04:19:51'),
(528, 6, '/uts_event_registration_arrayyan/src/views/home.php', 10, '2024-10-25 04:20:01'),
(529, 6, '/uts_event_registration_arrayyan/src/views/event_detail.php', 1, '2024-10-25 04:20:02'),
(530, 6, '/uts_event_registration_arrayyan/src/views/home.php', 147, '2024-10-25 04:22:29'),
(531, 6, '/uts_event_registration_arrayyan/src/views/event_detail.php', 9, '2024-10-25 04:22:39'),
(532, 6, '/uts_event_registration_arrayyan/src/views/home.php', 1, '2024-10-25 04:22:40'),
(533, 6, '/uts_event_registration_arrayyan/src/views/event_detail.php', 80, '2024-10-25 04:24:00'),
(534, 6, '/uts_event_registration_arrayyan/src/views/event_detail.php', 4, '2024-10-25 04:24:05'),
(535, 6, '/uts_event_registration_arrayyan/src/views/event_detail.php', 1, '2024-10-25 04:26:08'),
(536, 6, '/uts_event_registration_arrayyan/src/views/event_detail.php', 1, '2024-10-25 04:26:09'),
(537, 6, '/uts_event_registration_arrayyan/src/views/home.php', 34, '2024-10-25 04:26:44'),
(538, 6, '/uts_event_registration_arrayyan/src/views/event_detail.php', 39, '2024-10-25 04:27:22'),
(539, 6, '/uts_event_registration_arrayyan/src/views/home.php', 3, '2024-10-25 04:27:26'),
(540, 6, '/uts_event_registration_arrayyan/src/views/home.php', 4, '2024-10-25 04:27:32'),
(541, 6, '/uts_event_registration_arrayyan/src/views/register_participant.php', 2, '2024-10-25 04:27:35'),
(542, 6, '/uts_event_registration_arrayyan/src/views/home.php', 3, '2024-10-25 04:27:38'),
(543, 6, '/uts_event_registration_arrayyan/src/views/event_detail.php', 2, '2024-10-25 04:27:40'),
(544, 6, '/uts_event_registration_arrayyan/src/views/home.php', 5, '2024-10-25 04:27:45'),
(545, 6, '/uts_event_registration_arrayyan/src/views/event_detail.php', 5, '2024-10-25 04:27:50'),
(546, 6, '/uts_event_registration_arrayyan/src/views/home.php', 4, '2024-10-25 04:27:55'),
(547, 6, '/uts_event_registration_arrayyan/src/views/register_participant.php', 175, '2024-10-25 04:30:49'),
(548, 6, '/uts_event_registration_arrayyan/src/views/register_participant.php', 2, '2024-10-25 04:30:51'),
(549, 6, '/uts_event_registration_arrayyan/src/views/home.php', 4, '2024-10-25 04:30:55'),
(550, 6, '/uts_event_registration_arrayyan/src/views/event_detail.php', 2, '2024-10-25 05:34:04'),
(551, 6, '/uts_event_registration_arrayyan/src/views/register_participant.php', 75, '2024-10-25 05:35:19'),
(552, 6, '/uts_event_registration_arrayyan/src/views/register_participant.php', 23, '2024-10-25 05:35:43'),
(553, 6, '/uts_event_registration_arrayyan/src/views/register_participant.php', 19, '2024-10-25 05:36:02'),
(554, 6, '/uts_event_registration_arrayyan/src/views/register_participant.php', 117, '2024-10-25 05:37:59'),
(555, 6, '/uts_event_registration_arrayyan/src/views/home.php', 41, '2024-10-25 05:38:40'),
(556, 6, '/uts_event_registration_arrayyan/src/views/home.php', 65, '2024-10-25 05:39:47'),
(557, 6, '/uts_event_registration_arrayyan/src/views/home.php', 6, '2024-10-25 05:39:56'),
(558, 6, '/uts_event_registration_arrayyan/src/views/event_detail.php', 21, '2024-10-25 05:40:17'),
(559, 6, '/uts_event_registration_arrayyan/src/views/event_detail.php', 16, '2024-10-25 05:40:32'),
(560, 6, '/uts_event_registration_arrayyan/src/views/event_detail.php', 33, '2024-10-25 05:41:05'),
(561, 6, '/uts_event_registration_arrayyan/src/views/event_detail.php', 9, '2024-10-25 05:41:15'),
(562, 6, '/uts_event_registration_arrayyan/src/views/event_detail.php', 8, '2024-10-25 05:41:23'),
(563, 6, '/uts_event_registration_arrayyan/src/views/event_detail.php', 1098, '2024-10-25 05:59:41'),
(564, 6, '/uts_event_registration_arrayyan/src/views/event_detail.php', 40, '2024-10-25 06:00:22'),
(565, 6, '/uts_event_registration_arrayyan/src/views/home.php', 66, '2024-10-25 06:01:28'),
(566, 6, '/uts_event_registration_arrayyan/src/views/home.php', 98, '2024-10-25 06:03:06'),
(567, 6, '/uts_event_registration_arrayyan/src/views/home.php', 58, '2024-10-25 06:04:04');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banner_promote`
--
ALTER TABLE `banner_promote`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_participants`
--
ALTER TABLE `event_participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banner_promote`
--
ALTER TABLE `banner_promote`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `event_participants`
--
ALTER TABLE `event_participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_activity`
--
ALTER TABLE `user_activity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=568;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `event_participants`
--
ALTER TABLE `event_participants`
  ADD CONSTRAINT `event_participants_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_participants_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD CONSTRAINT `user_activity_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
