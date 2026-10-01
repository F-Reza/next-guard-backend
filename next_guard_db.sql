-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 01, 2026 at 02:18 PM
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
-- Database: `next_guard_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(190) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(30) NOT NULL DEFAULT 'admin',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `force_password_change` tinyint(1) NOT NULL DEFAULT 0,
  `failed_login_attempts` int(11) NOT NULL DEFAULT 0,
  `locked_until` timestamp NULL DEFAULT NULL,
  `last_failed_login_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `role`, `status`, `force_password_change`, `failed_login_attempts`, `locked_until`, `last_failed_login_at`, `created_by`, `last_login_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Super Admin', 'admin@nextguard.com', '$2y$12$7SiyHEZ6yuuo9ZwhXYSPFej82RRy8Xgimr2/5ZSgokiSvBu1nXo0q', 'super_admin', 'active', 0, 0, NULL, NULL, NULL, '2026-10-01 06:17:58', '2026-09-24 05:42:00', '2026-10-01 06:17:58', NULL),
(2, 'Support Manager', 'support@nextguard.com', '$2y$12$CheRtqlmNQ4FgdrtMG33.Of4QQmAtNo0mhLoKltdaOJBkvg15qpcS', 'support', 'active', 0, 0, NULL, NULL, 1, '2026-09-26 07:25:19', '2026-09-24 07:22:20', '2026-09-26 07:25:19', NULL),
(3, 'Support Admin', 'support1@nextguard.com', '$2y$12$QLDFdlMdrGQbhnYIbGF7ge5DYMMlQvxsUs7hl9CiZ5dbzZmo78R1a', 'support', 'active', 0, 0, NULL, NULL, 1, NULL, '2026-09-24 08:14:02', '2026-09-24 08:19:52', NULL),
(4, 'Test Admin', 'testadmin@nextguard.com', '$2y$12$s1WEVkPI6nHwSrFGABUYZOqfwDjPCnHAPMbobET6k.lKqssxtmyhm', 'support', 'active', 0, 0, NULL, NULL, 1, NULL, '2026-09-26 06:30:10', '2026-09-26 06:30:10', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_activity_logs`
--

CREATE TABLE `admin_activity_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `severity` varchar(255) NOT NULL DEFAULT 'info',
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `device` varchar(255) DEFAULT NULL,
  `browser` varchar(255) DEFAULT NULL,
  `os` varchar(255) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_activity_logs`
--

INSERT INTO `admin_activity_logs` (`id`, `admin_id`, `action`, `severity`, `description`, `ip_address`, `user_agent`, `device`, `browser`, `os`, `session_id`, `metadata`, `created_at`, `updated_at`) VALUES
(1, 1, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 06:28:37', '2026-09-24 06:28:37'),
(2, 1, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 06:34:01', '2026-09-24 06:34:01'),
(3, 1, 'LICENSE_GENERATED', 'info', 'Generated 3 license codes for Premium plan.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 06:38:01', '2026-09-24 06:38:01'),
(4, 1, 'LICENSE_GENERATED', 'info', 'Generated 1 license codes for Premium plan.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 06:39:29', '2026-09-24 06:39:29'),
(5, 1, 'SUBSCRIPTION_CANCELLED', 'info', 'Cancelled subscription ID: 1', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 06:44:45', '2026-09-24 06:44:45'),
(6, 1, 'PLAN_CREATED', 'info', 'Created subscription plan: Basic', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 06:48:02', '2026-09-24 06:48:02'),
(7, 1, 'PROTECTION_RULE_CREATED', 'info', 'Created protection rule: betway.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 06:50:42', '2026-09-24 06:50:42'),
(8, 2, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 07:25:05', '2026-09-24 07:25:05'),
(9, 2, 'ADMIN_UPDATED', 'info', 'Updated admin ID: 2', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 07:34:29', '2026-09-24 07:34:29'),
(10, 2, 'ADMIN_UPDATED', 'info', 'Updated admin ID: 2', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 07:35:50', '2026-09-24 07:35:50'),
(11, 2, 'ADMIN_UPDATED', 'info', 'Updated admin ID: 2', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 07:36:06', '2026-09-24 07:36:06'),
(12, 2, 'ADMIN_UPDATED', 'info', 'Updated admin ID: 2', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 07:36:29', '2026-09-24 07:36:29'),
(13, 2, 'ADMIN_STATUS_CHANGED', 'info', 'Changed admin ID: 2 status to active', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 07:40:34', '2026-09-24 07:40:34'),
(14, 1, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 07:49:35', '2026-09-24 07:49:35'),
(15, 1, 'ADMIN_PASSWORD_RESET', 'info', 'Reset password for admin ID: 2', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 07:50:02', '2026-09-24 07:50:02'),
(16, 1, 'ADMIN_PASSWORD_RESET', 'info', 'Reset password for admin ID: 2', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 07:58:43', '2026-09-24 07:58:43'),
(17, 1, 'ADMIN_PERMISSION_REMOVED', 'info', 'Removed permission view_logs from admin ID: 2', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 07:58:56', '2026-09-24 07:58:56'),
(18, 1, 'ADMIN_CREATED', 'info', 'Created admin account: support1@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 08:14:02', '2026-09-24 08:14:02'),
(19, 1, 'ADMIN_DELETED', 'info', 'Deleted admin: support1@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 08:14:17', '2026-09-24 08:14:17'),
(20, 1, 'ADMIN_RESTORED', 'info', 'Restored admin: support1@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 08:19:52', '2026-09-24 08:19:52'),
(21, 1, 'ADMIN_RESTORED', 'info', 'Restored admin: support@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 08:36:26', '2026-09-24 08:36:26'),
(22, 2, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 09:07:03', '2026-09-24 09:07:03'),
(23, 2, 'ADMIN_PASSWORD_CHANGED', 'info', 'Admin changed own password.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 09:08:28', '2026-09-24 09:08:28'),
(24, 2, 'ADMIN_PASSWORD_CHANGED', 'info', 'Admin changed own password.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 09:08:50', '2026-09-24 09:08:50'),
(25, 1, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 09:17:35', '2026-09-24 09:17:35'),
(26, 1, 'ADMIN_LOGOUT', 'info', 'Admin logged out.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 09:20:50', '2026-09-24 09:20:50'),
(27, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Failed admin login attempt: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 09:27:02', '2026-09-24 09:27:02'),
(28, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Failed admin login attempt: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 09:27:04', '2026-09-24 09:27:04'),
(29, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Failed admin login attempt: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 09:27:05', '2026-09-24 09:27:05'),
(30, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Failed admin login attempt: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 09:27:06', '2026-09-24 09:27:06'),
(31, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Failed admin login attempt: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 09:27:07', '2026-09-24 09:27:07'),
(32, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Failed admin login attempt: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 09:27:08', '2026-09-24 09:27:08'),
(33, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Failed admin login attempt: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 09:27:09', '2026-09-24 09:27:09'),
(34, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Failed admin login attempt: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 09:27:10', '2026-09-24 09:27:10'),
(35, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Failed admin login attempt: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 09:27:12', '2026-09-24 09:27:12'),
(36, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Failed admin login attempt: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 09:27:15', '2026-09-24 09:27:15'),
(37, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Failed admin login attempt: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 09:27:16', '2026-09-24 09:27:16'),
(38, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Failed admin login attempt: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 09:27:16', '2026-09-24 09:27:16'),
(39, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Failed admin login attempt: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 09:27:17', '2026-09-24 09:27:17'),
(40, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Failed admin login attempt: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 09:27:20', '2026-09-24 09:27:20'),
(41, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Failed admin login attempt: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 09:27:22', '2026-09-24 09:27:22'),
(42, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Failed admin login attempt: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 09:27:25', '2026-09-24 09:27:25'),
(43, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Failed admin login attempt: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 09:27:28', '2026-09-24 09:27:28'),
(44, 1, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 09:29:52', '2026-09-24 09:29:52'),
(45, 1, 'ADMIN_LOGOUT', 'info', 'Admin logged out.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-24 09:30:07', '2026-09-24 09:30:07'),
(46, 1, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, NULL, NULL, NULL, NULL, '2026-09-26 00:01:41', '2026-09-26 00:01:41'),
(47, 1, 'PROTECTION_RULE_CREATED', 'info', 'Created protection rule: test-nextguard.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, NULL, NULL, NULL, NULL, '2026-09-26 00:11:38', '2026-09-26 00:11:38'),
(48, 1, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, NULL, NULL, NULL, NULL, '2026-09-26 01:29:31', '2026-09-26 01:29:31'),
(49, 1, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/153.0.0.0 Safari/537.36', NULL, NULL, NULL, NULL, NULL, '2026-09-26 01:29:32', '2026-09-26 01:29:32'),
(50, 1, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 02:38:50', '2026-09-26 02:38:50'),
(51, 2, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 02:45:21', '2026-09-26 02:45:21'),
(52, 1, 'ADMIN_PERMISSION_UPDATED', 'info', 'Updated permissions for admin ID: 2', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 02:51:39', '2026-09-26 02:51:39'),
(53, 1, 'ADMIN_PERMISSION_UPDATED', 'info', 'Updated permissions for admin ID: 2 Permissions: view_dashboard, view_logs', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 03:06:07', '2026-09-26 03:06:07'),
(54, 1, 'ADMIN_PERMISSION_UPDATED', 'info', 'Updated permissions for admin ID: 3 Permissions: view_dashboard, view_logs', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 03:07:53', '2026-09-26 03:07:53'),
(55, 1, 'ADMIN_PERMISSION_UPDATED', 'info', 'Updated permissions for admin ID: 3 Permissions: view_dashboard, view_logs', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 03:17:46', '2026-09-26 03:17:46'),
(56, 1, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 03:55:33', '2026-09-26 03:55:33'),
(57, 1, 'ADMIN_LOGOUT', 'info', 'Admin logged out.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 03:56:05', '2026-09-26 03:56:05'),
(58, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Invalid password attempt for: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 03:56:14', '2026-09-26 03:56:14'),
(59, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Invalid password attempt for: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 03:56:16', '2026-09-26 03:56:16'),
(60, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Invalid password attempt for: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 03:56:17', '2026-09-26 03:56:17'),
(61, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Invalid password attempt for: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 03:56:18', '2026-09-26 03:56:18'),
(62, NULL, 'ADMIN_ACCOUNT_LOCKED', 'info', 'Admin account locked after failed attempts: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 03:56:19', '2026-09-26 03:56:19'),
(63, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Invalid password attempt for: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 03:56:19', '2026-09-26 03:56:19'),
(64, NULL, 'ADMIN_LOGIN_BLOCKED', 'info', 'Blocked login attempt for locked account: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 03:56:21', '2026-09-26 03:56:21'),
(65, NULL, 'ADMIN_LOGIN_BLOCKED', 'info', 'Blocked login attempt for locked account: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 03:57:30', '2026-09-26 03:57:30'),
(66, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Invalid password attempt for: support@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:03:03', '2026-09-26 04:03:03'),
(67, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Invalid password attempt for: support@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:03:04', '2026-09-26 04:03:04'),
(68, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Invalid password attempt for: support@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:03:04', '2026-09-26 04:03:04'),
(69, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Invalid password attempt for: support@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:03:05', '2026-09-26 04:03:05'),
(70, NULL, 'ADMIN_ACCOUNT_LOCKED', 'info', 'Admin account locked after failed attempts: support@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:03:06', '2026-09-26 04:03:06'),
(71, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Invalid password attempt for: support@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:03:06', '2026-09-26 04:03:06'),
(72, NULL, 'ADMIN_LOGIN_BLOCKED', 'info', 'Blocked login attempt for locked account: support@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:03:06', '2026-09-26 04:03:06'),
(73, NULL, 'ADMIN_LOGIN_BLOCKED', 'info', 'Blocked login attempt for locked account: support@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:03:11', '2026-09-26 04:03:11'),
(74, NULL, 'ADMIN_LOGIN_BLOCKED', 'info', 'Blocked login attempt for locked account: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:04:23', '2026-09-26 04:04:23'),
(75, 1, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:09:58', '2026-09-26 04:09:58'),
(76, 1, 'ADMIN_ACCOUNT_UNLOCKED', 'info', 'Unlocked admin account: support@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:10:16', '2026-09-26 04:10:16'),
(77, 1, 'ADMIN_LOGOUT', 'info', 'Admin logged out.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:10:34', '2026-09-26 04:10:34'),
(78, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Invalid password attempt for: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:10:41', '2026-09-26 04:10:41'),
(79, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Invalid password attempt for: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:10:42', '2026-09-26 04:10:42'),
(80, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Invalid password attempt for: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:10:43', '2026-09-26 04:10:43'),
(81, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Invalid password attempt for: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:10:44', '2026-09-26 04:10:44'),
(82, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Invalid password attempt for: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:10:44', '2026-09-26 04:10:44'),
(83, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Invalid password attempt for: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:10:45', '2026-09-26 04:10:45'),
(84, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Invalid password attempt for: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:10:46', '2026-09-26 04:10:46'),
(85, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Invalid password attempt for: admin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:10:47', '2026-09-26 04:10:47'),
(86, 1, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:10:53', '2026-09-26 04:10:53'),
(87, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Invalid password attempt for: support@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:11:10', '2026-09-26 04:11:10'),
(88, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Invalid password attempt for: support@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:11:11', '2026-09-26 04:11:11'),
(89, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Invalid password attempt for: support@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:11:12', '2026-09-26 04:11:12'),
(90, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Invalid password attempt for: support@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:11:13', '2026-09-26 04:11:13'),
(91, NULL, 'ADMIN_ACCOUNT_LOCKED', 'info', 'Admin account locked after failed attempts: support@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:11:14', '2026-09-26 04:11:14'),
(92, NULL, 'ADMIN_LOGIN_FAILED', 'info', 'Invalid password attempt for: support@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:11:14', '2026-09-26 04:11:14'),
(93, NULL, 'ADMIN_LOGIN_BLOCKED', 'info', 'Blocked login attempt for locked account: support@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:11:15', '2026-09-26 04:11:15'),
(94, NULL, 'ADMIN_LOGIN_BLOCKED', 'info', 'Blocked login attempt for locked account: support@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:11:19', '2026-09-26 04:11:19'),
(95, 1, 'ADMIN_ACCOUNT_UNLOCKED', 'info', 'Unlocked admin account: support@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:11:49', '2026-09-26 04:11:49'),
(96, 2, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:11:54', '2026-09-26 04:11:54'),
(97, 1, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:38:03', '2026-09-26 04:38:03'),
(98, 1, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', NULL, NULL, NULL, NULL, NULL, '2026-09-26 04:42:35', '2026-09-26 04:42:35'),
(99, 1, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', 'Desktop', 'Unknown', 'Unknown', 'YSMAq49rrNQq9tpe2sSHtjPhMGI2CxdWETdkp8oF', '{\"session_created\":true}', '2026-09-26 05:12:06', '2026-09-26 05:12:06'),
(100, 2, 'ADMIN_LOGIN_FAILED', 'warning', 'Invalid password attempt.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', 'Desktop', 'Unknown', 'Unknown', 'ARZp4na1aocnjAoUjgrwinzhUnSuwrho6El8pCGS', '{\"reason\":\"wrong_password\"}', '2026-09-26 05:16:50', '2026-09-26 05:16:50'),
(101, 2, 'ADMIN_LOGIN_FAILED', 'warning', 'Invalid password attempt.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', 'Desktop', 'Unknown', 'Unknown', 'Xe6qrkCx8ee6UU005M7oUGHLkgsk2GZLdDoP6Rpd', '{\"reason\":\"wrong_password\"}', '2026-09-26 05:16:51', '2026-09-26 05:16:51'),
(102, 2, 'ADMIN_LOGIN_FAILED', 'warning', 'Invalid password attempt.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', 'Desktop', 'Unknown', 'Unknown', 'qsvmUZIqjho4tnTCrh1LWelNm9kQlmzGNP5y5GLz', '{\"reason\":\"wrong_password\"}', '2026-09-26 05:16:52', '2026-09-26 05:16:52'),
(103, 2, 'ADMIN_LOGIN_FAILED', 'warning', 'Invalid password attempt.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', 'Desktop', 'Unknown', 'Unknown', 'l7ApshYs24290ZuOY0Uywkh3d78CBDHnoehRHiu5', '{\"reason\":\"wrong_password\"}', '2026-09-26 05:16:53', '2026-09-26 05:16:53'),
(104, 2, 'ADMIN_ACCOUNT_LOCKED', 'critical', 'Admin account locked after failed attempts.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', 'Desktop', 'Unknown', 'Unknown', 'ZBzGS2KeSbCd90qHZzT5UMRafF60zrivvksBQWTP', '{\"attempts\":5}', '2026-09-26 05:16:54', '2026-09-26 05:16:54'),
(105, 2, 'ADMIN_LOGIN_FAILED', 'warning', 'Invalid password attempt.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', 'Desktop', 'Unknown', 'Unknown', 'ZBzGS2KeSbCd90qHZzT5UMRafF60zrivvksBQWTP', '{\"reason\":\"wrong_password\"}', '2026-09-26 05:16:54', '2026-09-26 05:16:54'),
(106, 2, 'ADMIN_LOGIN_BLOCKED', 'critical', 'Login blocked because account is locked.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', 'Desktop', 'Unknown', 'Unknown', 'KU4izf96EkuN34bEHmXiYfgDt5oVJx8r5WuOEHO9', '{\"locked_until\":\"2026-09-26T11:31:54.000000Z\"}', '2026-09-26 05:16:55', '2026-09-26 05:16:55'),
(107, 2, 'ADMIN_LOGIN_BLOCKED', 'critical', 'Login blocked because account is locked.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', 'Desktop', 'Unknown', 'Unknown', 'c94BWR9mi37vAc63FGSLr5OAkkEu4UHCwr6fjcwg', '{\"locked_until\":\"2026-09-26T11:31:54.000000Z\"}', '2026-09-26 05:16:58', '2026-09-26 05:16:58'),
(108, 1, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', 'Desktop', 'Unknown', 'Unknown', 'vU1Wj83PzBqpiTVsBub2rMjlgdC1t3rb3oY1oiAw', '{\"session_created\":true}', '2026-09-26 06:27:17', '2026-09-26 06:27:17'),
(109, 1, 'ADMIN_CREATED', 'info', 'Created admin account: testadmin@nextguard.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', 'Desktop', 'Unknown', 'Unknown', 'ifH5hfAAopm6FRdduVKoEludpbV36WA4oVXKNcxd', '{\"created_admin_id\":4,\"role\":\"support\"}', '2026-09-26 06:30:10', '2026-09-26 06:30:10'),
(110, 1, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', 'Desktop', 'Unknown', 'Unknown', 'T7ZP3b6gaVE4rLWi4PpRaJQrsi1I5hngLC4oK4N8', '{\"session_created\":true}', '2026-09-26 07:02:19', '2026-09-26 07:02:19'),
(111, 2, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', 'Desktop', 'Unknown', 'Unknown', 'CunZf1IdKPwjS85kQcWXVWMCYxqKTz7Ud6MX56VO', '{\"session_created\":true}', '2026-09-26 07:25:19', '2026-09-26 07:25:19'),
(112, 1, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', 'Desktop', 'Unknown', 'Unknown', 'gZhA0YA5fRwLjEZvC4CvvgceVMv3dUT2oV7bxdrW', '{\"session_created\":true}', '2026-09-27 05:09:14', '2026-09-27 05:09:14'),
(113, 1, 'PROTECTION_RULE_CREATED', 'info', 'Created protection rule: example.com', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', 'Desktop', 'Unknown', 'Unknown', 'zHATh94kRBEI2tednpCJHX0BXhqx4NBEegpAsF9p', '[]', '2026-09-27 05:13:12', '2026-09-27 05:13:12'),
(114, 1, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', 'Desktop', 'Unknown', 'Unknown', 'aqmdSd5iNXMJaKpzlyaggQqkXEgRgIZEkpDaOfzA', '{\"session_created\":true}', '2026-09-29 04:12:46', '2026-09-29 04:12:46'),
(115, 1, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', 'Desktop', 'Unknown', 'Unknown', 'UOXVwaB1Uph0mghgXTNGKyvhp1e8LDIQDj6wWRiD', '{\"session_created\":true}', '2026-09-29 08:32:11', '2026-09-29 08:32:11'),
(116, 1, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', 'Desktop', 'Unknown', 'Unknown', 'y0wYCsTAo7GGDSivNq9TWAyxhWExBY582rGxqlwD', '{\"session_created\":true}', '2026-09-30 02:07:55', '2026-09-30 02:07:55'),
(117, 1, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', 'Desktop', 'Unknown', 'Unknown', 'V0CxpKvsVsDINbvfdxQvIbobvKEeYcAgZ3EpT6x8', '{\"session_created\":true}', '2026-10-01 06:17:33', '2026-10-01 06:17:33'),
(118, 1, 'ADMIN_LOGOUT', 'info', 'Admin logged out from web panel.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'Desktop', 'Chrome', 'Windows', 'l1UqSW7BvptDhTISDUhDMWBGISW1AGXiH92LaUK4', '{\"channel\":\"web\"}', '2026-10-01 06:17:44', '2026-10-01 06:17:44'),
(119, 1, 'ADMIN_LOGIN', 'info', 'Admin logged in successfully from web panel.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'Desktop', 'Chrome', 'Windows', 'SwBo0d5v8M7TFyl0XyqnGstQ1xi7AuvCr4IByd8N', '{\"channel\":\"web\"}', '2026-10-01 06:17:58', '2026-10-01 06:17:58');

-- --------------------------------------------------------

--
-- Table structure for table `admin_notifications`
--

CREATE TABLE `admin_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_notifications`
--

INSERT INTO `admin_notifications` (`id`, `admin_id`, `type`, `title`, `message`, `is_read`, `metadata`, `created_at`, `updated_at`) VALUES
(2, 4, 'ACCOUNT', 'Admin Account Created', 'Your admin account has been created.', 0, '{\"created_by\":\"Super Admin\",\"role\":\"support\"}', '2026-09-26 06:30:10', '2026-09-26 06:30:10'),
(3, 1, 'LOGIN', 'New Admin Login', 'Your admin account was logged in successfully.', 0, '{\"ip\":\"127.0.0.1\",\"user_agent\":\"Thunder Client (https:\\/\\/www.thunderclient.com)\",\"time\":\"2026-09-26T13:02:19.422769Z\"}', '2026-09-26 07:02:19', '2026-09-26 07:02:19'),
(4, 2, 'LOGIN', 'New Admin Login', 'Your admin account was logged in successfully.', 0, '{\"ip\":\"127.0.0.1\",\"user_agent\":\"Thunder Client (https:\\/\\/www.thunderclient.com)\",\"time\":\"2026-09-26T13:25:19.766626Z\"}', '2026-09-26 07:25:19', '2026-09-26 07:25:19'),
(5, 1, 'LOGIN', 'New Admin Login', 'Your admin account was logged in successfully.', 0, '{\"ip\":\"127.0.0.1\",\"user_agent\":\"Thunder Client (https:\\/\\/www.thunderclient.com)\",\"time\":\"2026-09-27T11:09:14.764405Z\"}', '2026-09-27 05:09:14', '2026-09-27 05:09:14'),
(6, 1, 'LOGIN', 'New Admin Login', 'Your admin account was logged in successfully.', 0, '{\"ip\":\"127.0.0.1\",\"user_agent\":\"Thunder Client (https:\\/\\/www.thunderclient.com)\",\"time\":\"2026-09-29T10:12:46.125937Z\"}', '2026-09-29 04:12:46', '2026-09-29 04:12:46'),
(7, 1, 'LOGIN', 'New Admin Login', 'Your admin account was logged in successfully.', 0, '{\"ip\":\"127.0.0.1\",\"user_agent\":\"Thunder Client (https:\\/\\/www.thunderclient.com)\",\"time\":\"2026-09-29T14:32:11.071882Z\"}', '2026-09-29 08:32:11', '2026-09-29 08:32:11'),
(8, 1, 'LOGIN', 'New Admin Login', 'Your admin account was logged in successfully.', 0, '{\"ip\":\"127.0.0.1\",\"user_agent\":\"Thunder Client (https:\\/\\/www.thunderclient.com)\",\"time\":\"2026-09-30T08:07:55.170752Z\"}', '2026-09-30 02:07:55', '2026-09-30 02:07:55'),
(9, 1, 'LOGIN', 'New Admin Login', 'Your admin account was logged in successfully.', 0, '{\"ip\":\"127.0.0.1\",\"user_agent\":\"Thunder Client (https:\\/\\/www.thunderclient.com)\",\"time\":\"2026-10-01T12:17:33.355574Z\"}', '2026-10-01 06:17:33', '2026-10-01 06:17:33'),
(10, 1, 'LOGIN', 'New Admin Login', 'Your admin account was logged in successfully.', 0, '{\"ip\":\"127.0.0.1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/154.0.0.0 Safari\\/537.36\",\"time\":\"2026-10-01T12:17:58.917869Z\",\"channel\":\"web\"}', '2026-10-01 06:17:58', '2026-10-01 06:17:58');

-- --------------------------------------------------------

--
-- Table structure for table `admin_permissions`
--

CREATE TABLE `admin_permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_permissions`
--

INSERT INTO `admin_permissions` (`id`, `admin_id`, `permission_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL),
(2, 1, 2, NULL, NULL),
(3, 1, 3, NULL, NULL),
(4, 1, 4, NULL, NULL),
(5, 1, 5, NULL, NULL),
(6, 1, 6, NULL, NULL),
(7, 1, 7, NULL, NULL),
(8, 1, 8, NULL, NULL),
(11, 2, 6, NULL, NULL),
(12, 2, 7, NULL, NULL),
(13, 3, 6, NULL, NULL),
(14, 3, 7, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `admin_sessions`
--

CREATE TABLE `admin_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `admin_id` bigint(20) UNSIGNED NOT NULL,
  `token_hash` text NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `last_activity` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_sessions`
--

INSERT INTO `admin_sessions` (`id`, `admin_id`, `token_hash`, `ip_address`, `user_agent`, `last_activity`, `expires_at`, `created_at`, `updated_at`) VALUES
(3, 1, '$2y$12$KsrDGsgFvGnrOevMqn/gyOSGPZKs7vEdkx1Or91OrDxYqwxUDtrwW', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-26 05:12:06', '2026-09-26 06:12:06', '2026-09-26 05:12:06', '2026-09-26 05:12:06'),
(4, 1, '$2y$12$SnJm/wkDcfYfCwZ62kTvcOvLm/q.B.BOZCXKVpGVQ6CPJDz.B7xaW', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-26 06:27:17', '2026-09-26 07:27:17', '2026-09-26 06:27:17', '2026-09-26 06:27:17'),
(5, 1, '$2y$12$nn5wUqG7X3KI3nAE/3qmnet7ZQYqQ6FQS8E4/WWnl.7pEvnVcWaxi', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-26 07:02:19', '2026-09-26 08:02:19', '2026-09-26 07:02:19', '2026-09-26 07:02:19'),
(6, 2, '$2y$12$uoivJ5mn00iu7ai/h2cD2OW/pUjBavSbM9OLOBbbUkWrIrfqUgkwG', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-26 07:25:19', '2026-09-26 08:25:19', '2026-09-26 07:25:19', '2026-09-26 07:25:19'),
(7, 1, '$2y$12$u64JohZt28EDZw.LCdh0/OKBpsEqfkl1XMv1M83QtyaOLN9ADEuK.', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-27 05:09:14', '2026-09-27 06:09:14', '2026-09-27 05:09:14', '2026-09-27 05:09:14'),
(8, 1, '$2y$12$JxwLsDewelQY0/Tgo45s3.Z3eLetB2HkgNKHxivynLMKvbMIGn4aq', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-29 04:12:46', '2026-09-29 05:12:46', '2026-09-29 04:12:46', '2026-09-29 04:12:46'),
(9, 1, '$2y$12$Um2RyKpbe9Gal2SRu0tkIuiZR3UORqpcPRUMEEeeUbjJtCRxwcMii', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-29 08:32:11', '2026-09-29 09:32:11', '2026-09-29 08:32:11', '2026-09-29 08:32:11'),
(10, 1, '$2y$12$Jr6co15XljyFyV5VC9eFy.q1TbVpTX1VUhEv7EAF0PEfEBoX0WnWm', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-30 02:07:55', '2026-09-30 03:07:55', '2026-09-30 02:07:55', '2026-09-30 02:07:55'),
(11, 1, '$2y$12$kiHZ/10w03rodKL8j3JQxusnEgTxB/rerAk5NOVn.J6ZUt725rfWi', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-10-01 06:17:33', '2026-10-01 07:17:33', '2026-10-01 06:17:33', '2026-10-01 06:17:33');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('next-guard-api-cache-e8bbf2e46c3f627d043d862ef33befe3', 'i:2;', 1790857112),
('next-guard-api-cache-e8bbf2e46c3f627d043d862ef33befe3:timer', 'i:1790857112;', 1790857112);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache_locks`
--

INSERT INTO `cache_locks` (`key`, `owner`, `expiration`) VALUES
('next-guard-api-cache-laravel_unique_job:App\\Jobs\\RetryFailedProtectionSync:', 'p403i7EqeuuOD9Oz', 1790687700);

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `device_uuid_hash` char(64) NOT NULL,
  `platform` varchar(20) NOT NULL DEFAULT 'android',
  `model` varchar(120) DEFAULT NULL,
  `manufacturer` varchar(120) DEFAULT NULL,
  `android_version` varchar(30) DEFAULT NULL,
  `app_version` varchar(30) DEFAULT NULL,
  `management_mode` varchar(20) NOT NULL DEFAULT 'standard',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `last_seen_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`id`, `user_id`, `name`, `device_uuid_hash`, `platform`, `model`, `manufacturer`, `android_version`, `app_version`, `management_mode`, `status`, `last_seen_at`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, '9a99afcdb6c372ef38297f86cb266b5553f8e96839ab190fd599cef9ae2dce91', 'android', 'Test Phone', 'Test Manufacturer', '16', '1.0.0', 'standard', 'offline', '2026-09-24 02:41:36', '2026-09-24 02:39:43', '2026-09-28 08:05:29'),
(2, 1, NULL, 'a1ddee54421b782cfba548d4ff4efd63fbca9c01503da23908dccaa34eca2a1d', 'android', 'Test Phone 2', 'Test Manufacturer', '16', '1.0.0', 'standard', 'offline', '2026-09-24 03:40:36', '2026-09-24 03:40:36', '2026-09-28 08:05:29'),
(3, 2, 'My Android Phone', '2fab4650568803abb681c779d88f0c8b4807ef852bd2268690e832fdafd176c5', 'android', 'Samsung S24', 'Samsung', '14', '1.0.2', 'standard', 'active', '2026-09-30 02:08:09', '2026-09-27 03:20:49', '2026-09-30 02:08:09');

-- --------------------------------------------------------

--
-- Table structure for table `device_events`
--

CREATE TABLE `device_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `device_id` bigint(20) UNSIGNED NOT NULL,
  `event` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `device_events`
--

INSERT INTO `device_events` (`id`, `device_id`, `event`, `created_at`) VALUES
(1, 3, 'offline', '2026-09-28 14:20:45'),
(2, 3, 'online', '2026-09-28 14:25:28'),
(3, 3, 'online', '2026-09-28 14:26:08'),
(4, 3, 'offline', '2026-09-28 14:32:46'),
(5, 3, 'online', '2026-09-28 14:32:55'),
(6, 3, 'offline', '2026-09-29 05:55:26'),
(7, 3, 'offline', '2026-09-29 07:55:51'),
(8, 3, 'offline', '2026-09-29 11:55:02'),
(9, 3, 'offline', '2026-09-29 13:00:02'),
(10, 3, 'protection_sync_failed', '2026-09-29 15:53:03');

-- --------------------------------------------------------

--
-- Table structure for table `device_protection_settings`
--

CREATE TABLE `device_protection_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `device_id` bigint(20) UNSIGNED NOT NULL,
  `betting_block` tinyint(1) NOT NULL DEFAULT 0,
  `adult_content_block` tinyint(1) NOT NULL DEFAULT 0,
  `facebook_ad_block` tinyint(1) NOT NULL DEFAULT 0,
  `youtube_ad_block` tinyint(1) NOT NULL DEFAULT 0,
  `safe_search` tinyint(1) NOT NULL DEFAULT 0,
  `dns_protection` tinyint(1) NOT NULL DEFAULT 0,
  `protection_status` varchar(30) NOT NULL DEFAULT 'inactive',
  `disabled_reason` varchar(255) DEFAULT NULL,
  `last_sync_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `device_protection_settings`
--

INSERT INTO `device_protection_settings` (`id`, `device_id`, `betting_block`, `adult_content_block`, `facebook_ad_block`, `youtube_ad_block`, `safe_search`, `dns_protection`, `protection_status`, `disabled_reason`, `last_sync_at`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1, 'active', NULL, '2026-09-24 04:32:52', '2026-09-24 04:30:47', '2026-09-24 04:32:52'),
(2, 3, 1, 0, 0, 0, 0, 1, 'active', NULL, '2026-09-29 08:37:03', '2026-09-27 04:34:07', '2026-09-29 09:33:14');

-- --------------------------------------------------------

--
-- Table structure for table `device_sessions`
--

CREATE TABLE `device_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `device_id` bigint(20) UNSIGNED NOT NULL,
  `refresh_token_hash` varchar(64) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_used_at` timestamp NULL DEFAULT NULL,
  `revoked_at` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `device_sessions`
--

INSERT INTO `device_sessions` (`id`, `user_id`, `device_id`, `refresh_token_hash`, `status`, `expires_at`, `last_used_at`, `revoked_at`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '3a224e2d7fc9bbc6ebf0a0b021ead5f404587d57a319f8ef2a58da9e7b3469a2', 'revoked', '2026-09-24 10:04:27', '2026-09-24 03:58:32', '2026-09-24 04:04:27', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-24 03:58:32', '2026-09-24 04:04:27'),
(2, 1, 1, '9c6331c6f4050c9a53aeba721b009dea7854587be4a6b4098a3eb49294b9353d', 'revoked', '2026-09-24 10:04:27', '2026-09-24 04:02:04', '2026-09-24 04:04:27', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-24 04:01:15', '2026-09-24 04:04:27'),
(3, 1, 1, '98a4e12bed4a57e189ac207c359fadb50954003f48722f38a0fd9994316069c4', 'active', '2026-10-24 05:08:26', '2026-09-24 05:08:26', NULL, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-24 05:08:26', '2026-09-24 05:08:26'),
(4, 1, 1, '815b75895f28a00c46d0fa35c17dc07140de2becc75f0a4e7a77ed6b1f21c656', 'active', '2026-10-24 06:06:55', '2026-09-24 06:06:55', NULL, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-24 06:06:55', '2026-09-24 06:06:55'),
(14, 2, 3, '9e4f851ededacc5f2ae29af97f8bc15532f5b824ecfad96c09548aa6df1ad0cb', 'active', '2026-10-28 03:39:32', '2026-09-28 03:39:32', NULL, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-28 03:39:32', '2026-09-28 03:39:32'),
(15, 2, 3, '218afaa943a30754d8879a86d15c28bf07e3552d190ce02ba099c4a4d9222910', 'active', '2026-10-28 05:24:51', '2026-09-28 05:24:51', NULL, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-28 05:24:51', '2026-09-28 05:24:51'),
(16, 2, 3, '11b75bbb0a746313d6b96a96f00e0d812c09e14a2a50f48bd8511ac17ae2e0e0', 'active', '2026-10-28 06:34:19', '2026-09-28 06:34:19', NULL, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-28 06:34:19', '2026-09-28 06:34:19'),
(17, 2, 3, '710168fd952c7f0f8bab19c9461faa1e56fca51669987cf7c22ef8955e7377ad', 'active', '2026-10-28 06:52:40', '2026-09-28 06:52:40', NULL, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-28 06:52:40', '2026-09-28 06:52:40'),
(18, 2, 3, 'e2a3e2403afed43f0e2d7a8c3753d049d76c014ee7260f639d2b886a0df57435', 'active', '2026-10-28 07:58:03', '2026-09-28 07:58:03', NULL, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-28 07:58:03', '2026-09-28 07:58:03'),
(19, 2, 3, 'ea7da1c67112bd97cb482a88a328913470fab645bf607168ed5bf077655ff351', 'active', '2026-10-28 09:06:09', '2026-09-28 09:06:09', NULL, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-28 09:06:09', '2026-09-28 09:06:09'),
(20, 2, 3, '6ab00a52a07621a9dafab97dab255aedd6cb238b9df4e707e6969f52b63bc160', 'active', '2026-10-29 00:35:40', '2026-09-29 00:35:40', NULL, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-29 00:35:40', '2026-09-29 00:35:40'),
(21, 2, 3, 'b60dd79e32c8dd168751c5f38624d7c51367ba286ec9210ab2ffcc472b511356', 'active', '2026-10-29 00:40:03', '2026-09-29 00:40:03', NULL, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-29 00:40:03', '2026-09-29 00:40:03'),
(22, 2, 3, 'b5acc9e48bcd7fe21d77193b7733a3ba409c003c8d54d1be3474d5d970485b0b', 'active', '2026-10-29 01:03:37', '2026-09-29 01:03:37', NULL, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-29 01:03:37', '2026-09-29 01:03:37'),
(23, 2, 3, 'c73c1aea0f57697217d1e34a704d47d97776e265e4be69e284856e2a5c97a9cd', 'active', '2026-10-29 02:14:54', '2026-09-29 02:14:54', NULL, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-29 02:14:54', '2026-09-29 02:14:54'),
(24, 2, 3, 'b1a9a4f21e94ff09cc70517c85ef56fa898835f04e1f396ab932db673683734b', 'active', '2026-10-29 03:21:35', '2026-09-29 03:21:35', NULL, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-29 03:21:35', '2026-09-29 03:21:35'),
(25, 2, 3, '8774808d20af06ea16da6a1191c0da90a95e9222fbf3946ee4e9b9b98fe570b1', 'active', '2026-10-29 04:13:02', '2026-09-29 04:13:02', NULL, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-29 04:13:02', '2026-09-29 04:13:02'),
(26, 2, 3, '9eaf0de641059607bc8eca1c29ab2122d9c7b61881ad82d85a05365a7dbb78e6', 'active', '2026-10-29 04:23:07', '2026-09-29 04:23:07', NULL, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-29 04:23:07', '2026-09-29 04:23:07'),
(27, 2, 3, '7d572f0a74811de05600542fa03bcbbe4afa76b936b65be21af8c9a6bc62d7de', 'active', '2026-10-29 05:40:21', '2026-09-29 05:40:21', NULL, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-29 05:40:21', '2026-09-29 05:40:21'),
(28, 2, 3, '82d9a535b09c99113f04182575c2098f12bbc7bda5bd918c1b7fb8cff2598d47', 'active', '2026-10-29 06:20:09', '2026-09-29 06:20:09', NULL, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-29 06:20:09', '2026-09-29 06:20:09'),
(29, 2, 3, '9705a4c0d1c6dba7ec30d7f0850b670c1cee6a639b1f2b30a06b41dcce82179f', 'active', '2026-10-29 07:28:00', '2026-09-29 07:28:00', NULL, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-29 07:28:00', '2026-09-29 07:28:00'),
(30, 2, 3, '005ed9e74e449218ef2009377b79e80fac30b9bc63cc533836623f0ab2e4580a', 'active', '2026-10-29 08:35:28', '2026-09-29 08:35:28', NULL, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-29 08:35:28', '2026-09-29 08:35:28'),
(31, 2, 3, 'fbeeeb284ca53fe0dd0e12c1aca3ed876c156020aaecaf6bb1e9d37b6f9704c3', 'active', '2026-10-29 08:40:35', '2026-09-29 08:40:35', NULL, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-29 08:40:35', '2026-09-29 08:40:35'),
(32, 2, 3, '556e7b1e470899506405ba16d693d00710c34f1c8a971d74eb31ac800298b24c', 'active', '2026-10-29 09:40:58', '2026-09-29 09:40:58', NULL, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-29 09:40:58', '2026-09-29 09:40:58'),
(33, 2, 3, '91178de76b6ebe9a2f00a6abafc5076bc78c33f983e40d0693f81778e3a96ad0', 'active', '2026-10-29 22:58:31', '2026-09-29 22:58:31', NULL, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-29 22:58:31', '2026-09-29 22:58:31'),
(34, 2, 3, 'b4848c94e23abde3356ed94cb2f60091fd0a631da57c03b1bdcc42840ca747fe', 'active', '2026-10-30 02:08:09', '2026-09-30 02:08:09', NULL, '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-30 02:08:09', '2026-09-30 02:08:09');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(22, 'default', '{\"uuid\":\"bddc5b27-470e-4de0-afec-cb1d584cdf7c\",\"displayName\":\"App\\\\Jobs\\\\RetryFailedProtectionSync\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\RetryFailedProtectionSync\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\RetryFailedProtectionSync\\\":0:{}\",\"batchId\":null},\"createdAt\":1790682900,\"delay\":null}', 0, NULL, 1790682900, 1790682900),
(23, 'default', '{\"uuid\":\"10127b60-4e98-44ba-b69f-f35185c9e29b\",\"displayName\":\"App\\\\Jobs\\\\RetryFailedProtectionSync\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\RetryFailedProtectionSync\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\RetryFailedProtectionSync\\\":0:{}\",\"batchId\":null},\"createdAt\":1790683200,\"delay\":null}', 0, NULL, 1790683200, 1790683200),
(24, 'default', '{\"uuid\":\"1b311128-7ca1-473a-9ab0-6415a8aca564\",\"displayName\":\"App\\\\Jobs\\\\RetryFailedProtectionSync\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\RetryFailedProtectionSync\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\RetryFailedProtectionSync\\\":0:{}\",\"batchId\":null},\"createdAt\":1790683500,\"delay\":null}', 0, NULL, 1790683500, 1790683500),
(25, 'default', '{\"uuid\":\"fb0cbbe1-c371-4ca1-8d32-d1ed668975f9\",\"displayName\":\"App\\\\Jobs\\\\RetryFailedProtectionSync\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\RetryFailedProtectionSync\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\RetryFailedProtectionSync\\\":0:{}\",\"batchId\":null},\"createdAt\":1790683800,\"delay\":null}', 0, NULL, 1790683800, 1790683800),
(26, 'default', '{\"uuid\":\"a872ae83-1c37-480a-9d81-5b2e7583af61\",\"displayName\":\"App\\\\Jobs\\\\RetryFailedProtectionSync\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\RetryFailedProtectionSync\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\RetryFailedProtectionSync\\\":0:{}\",\"batchId\":null},\"createdAt\":1790684100,\"delay\":null}', 0, NULL, 1790684100, 1790684100),
(27, 'default', '{\"uuid\":\"be0cc143-97c6-4e85-9b74-f580975893de\",\"displayName\":\"App\\\\Jobs\\\\RetryFailedProtectionSync\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\RetryFailedProtectionSync\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\RetryFailedProtectionSync\\\":0:{}\",\"batchId\":null},\"createdAt\":1790686800,\"delay\":null}', 0, NULL, 1790686800, 1790686800),
(28, 'default', '{\"uuid\":\"1f4e70f0-dec1-4bae-be42-f019dda6720e\",\"displayName\":\"App\\\\Jobs\\\\RetryFailedProtectionSync\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\RetryFailedProtectionSync\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\RetryFailedProtectionSync\\\":0:{}\",\"batchId\":null},\"createdAt\":1790687100,\"delay\":null}', 0, NULL, 1790687100, 1790687100),
(29, 'default', '{\"uuid\":\"6150c788-8ec7-4aff-b921-080a5e5f059f\",\"displayName\":\"App\\\\Jobs\\\\RetryFailedProtectionSync\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\RetryFailedProtectionSync\",\"command\":\"O:34:\\\"App\\\\Jobs\\\\RetryFailedProtectionSync\\\":0:{}\",\"batchId\":null},\"createdAt\":1790687400,\"delay\":null}', 0, NULL, 1790687400, 1790687400);

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `license_codes`
--

CREATE TABLE `license_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(100) NOT NULL,
  `subscription_plan_id` bigint(20) UNSIGNED NOT NULL,
  `duration_days` int(11) NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `used_by` bigint(20) UNSIGNED DEFAULT NULL,
  `used_device_id` bigint(20) UNSIGNED DEFAULT NULL,
  `used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `license_codes`
--

INSERT INTO `license_codes` (`id`, `code`, `subscription_plan_id`, `duration_days`, `status`, `used_by`, `used_device_id`, `used_at`, `created_at`, `updated_at`) VALUES
(1, 'NG-PREMIUM-R0QOZHED', 1, 365, 'used', 1, 1, '2026-09-24 05:37:27', '2026-09-24 05:34:26', '2026-09-24 05:37:27'),
(2, 'NG-PREMIUM-DHKLTFYJ', 1, 365, 'active', NULL, NULL, NULL, '2026-09-24 05:34:26', '2026-09-24 05:34:26'),
(3, 'NG-PREMIUM-7NQRFV1D', 1, 365, 'active', NULL, NULL, NULL, '2026-09-24 05:34:26', '2026-09-24 05:34:26'),
(4, 'NG-PREMIUM-BCDZG4E0', 1, 365, 'active', NULL, NULL, NULL, '2026-09-24 06:38:01', '2026-09-24 06:38:01'),
(5, 'NG-PREMIUM-FXDX70U2', 1, 365, 'active', NULL, NULL, NULL, '2026-09-24 06:38:01', '2026-09-24 06:38:01'),
(6, 'NG-PREMIUM-HUIIMYO5', 1, 365, 'active', NULL, NULL, NULL, '2026-09-24 06:38:01', '2026-09-24 06:38:01'),
(7, 'NG-PREMIUM-ERTLEYTQ', 1, 365, 'active', NULL, NULL, NULL, '2026-09-24 06:39:29', '2026-09-24 06:39:29'),
(8, 'NG-PREMIUM-TEST001', 1, 365, 'active', NULL, NULL, NULL, '2026-09-28 04:22:40', '2026-09-28 04:22:40'),
(9, 'NG-PREMIUM-TEST002', 1, 365, 'used', 2, 3, '2026-09-28 04:49:16', '2026-09-28 04:46:43', '2026-09-28 04:49:16');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_09_24_065703_create_devices_table', 1),
(5, '2026_09_24_065733_create_device_sessions_table', 1),
(6, '2026_09_24_084325_create_trial_entitlements_table', 2),
(7, '2026_09_24_084425_create_trial_events_table', 3),
(8, '2026_09_24_101920_create_device_protection_settings_table', 4),
(9, '2026_09_24_103552_create_protection_rules_table', 5),
(10, '2026_09_24_104734_create_subscription_plans_table', 6),
(11, '2026_09_24_105450_create_subscriptions_table', 7),
(12, '2026_09_24_112715_create_license_codes_table', 8),
(13, '2026_09_24_113846_create_admins_table', 9),
(14, '2026_09_24_121647_create_admin_activity_logs_table', 10),
(15, '2026_09_24_125206_create_permissions_table', 11),
(17, '2026_09_24_125512_create_admin_permissions_table', 12),
(18, '2026_09_24_131017_add_created_by_to_admins_table', 13),
(19, '2026_09_24_134243_add_force_password_change_to_admins_table', 14),
(20, '2026_09_24_141053_add_deleted_at_to_admins_table', 15),
(21, '2026_09_24_142745_add_deleted_at_to_admins_table', 16),
(22, '2026_09_24_151128_add_security_fields_to_admins_table', 16),
(23, '2026_09_24_152324_make_admin_id_nullable_in_admin_activity_logs', 17),
(25, '2026_09_26_102448_create_admin_sessions_table', 18),
(26, '2026_09_26_104829_add_security_fields_to_admin_activity_logs_table', 19),
(27, '2026_09_26_112608_create_admin_notifications_table', 20),
(28, '2026_09_27_111249_add_device_id_to_protection_rules_table', 21),
(29, '2026_09_27_113634_create_protection_sync_logs_table', 22),
(30, '2026_09_27_115659_add_ack_fields_to_protection_sync_logs_table', 23),
(31, '2026_09_27_123145_add_retry_fields_to_protection_sync_logs_table', 24),
(32, '2026_09_27_124859_add_max_retry_fields_to_protection_sync_logs_table', 25),
(33, '2026_09_27_135531_add_name_to_devices_table', 26),
(34, '2026_09_27_140517_add_device_limit_to_subscription_plans_table', 27),
(35, '2026_09_27_144039_create_subscription_events_table', 28),
(36, '2026_09_27_151617_create_payments_table', 29),
(37, '2026_09_27_151704_create_subscription_invoices_table', 30),
(38, '2026_09_28_121438_create_protection_violation_logs_table', 31),
(39, '2026_09_28_141104_create_device_events_table', 32),
(40, '2026_09_28_145839_add_disabled_reason_to_device_protection_settings_table', 33),
(41, '2026_09_29_082249_add_auto_renew_to_subscriptions_table', 34),
(42, '2026_09_29_142026_add_indexes_to_protection_sync_logs_table', 35),
(43, '2026_09_29_150646_add_indexes_to_protection_violation_logs_table', 36),
(44, '2026_09_29_153027_create_protection_notifications_table', 37),
(45, '2026_09_30_095829_add_gateway_fields_to_payments_table', 38),
(46, '2026_09_30_103119_create_payment_webhooks_table', 39);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `subscription_id` bigint(20) UNSIGNED DEFAULT NULL,
  `gateway` varchar(255) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `provider_transaction_id` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(255) NOT NULL DEFAULT 'USD',
  `status` enum('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `gateway_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gateway_response`)),
  `verified_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `subscription_id`, `gateway`, `provider`, `transaction_id`, `provider_transaction_id`, `amount`, `currency`, `status`, `paid_at`, `created_at`, `updated_at`, `gateway_response`, `verified_at`) VALUES
(1, 2, 3, 'manual', NULL, 'TXN-10001', NULL, 99.00, 'USD', 'paid', '2026-09-27 09:25:00', '2026-09-27 09:25:00', '2026-09-27 09:25:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_webhooks`
--

CREATE TABLE `payment_webhooks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gateway` varchar(255) NOT NULL,
  `event_id` varchar(255) NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload`)),
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `processed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'manage_users', 'Create, view and manage users', '2026-09-24 07:01:41', '2026-09-24 07:01:41'),
(2, 'manage_plans', 'Create and manage subscription plans', '2026-09-24 07:01:41', '2026-09-24 07:01:41'),
(3, 'manage_licenses', 'Generate and manage license codes', '2026-09-24 07:01:41', '2026-09-24 07:01:41'),
(4, 'manage_subscriptions', 'View and manage subscriptions', '2026-09-24 07:01:41', '2026-09-24 07:01:41'),
(5, 'manage_rules', 'Create and manage protection rules', '2026-09-24 07:01:41', '2026-09-24 07:01:41'),
(6, 'view_dashboard', 'View admin dashboard statistics', '2026-09-24 07:01:41', '2026-09-24 07:01:41'),
(7, 'view_logs', 'View admin activity logs', '2026-09-24 07:01:41', '2026-09-24 07:01:41'),
(8, 'manage_admins', 'Create and manage admin accounts', '2026-09-24 07:11:40', '2026-09-24 07:11:40');

-- --------------------------------------------------------

--
-- Table structure for table `protection_notifications`
--

CREATE TABLE `protection_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `device_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'protection_alert',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `domain` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `protection_notifications`
--

INSERT INTO `protection_notifications` (`id`, `device_id`, `user_id`, `type`, `title`, `message`, `domain`, `category`, `read_at`, `created_at`, `updated_at`) VALUES
(1, 3, 2, 'protection_alert', 'Blocked website detected', 'bet365.com was blocked by protection.', 'bet365.com', 'betting', '2026-09-29 09:42:48', '2026-09-29 09:33:34', '2026-09-29 09:42:48'),
(2, 3, 2, 'protection_alert', 'Blocked website detected', 'bet365.com was blocked by protection.', 'bet365.com', 'betting', NULL, '2026-09-30 02:08:57', '2026-09-30 02:08:57');

-- --------------------------------------------------------

--
-- Table structure for table `protection_rules`
--

CREATE TABLE `protection_rules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `device_id` bigint(20) UNSIGNED DEFAULT NULL,
  `category` varchar(50) NOT NULL,
  `domain` varchar(255) NOT NULL,
  `rule_type` varchar(30) NOT NULL DEFAULT 'domain',
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `protection_rules`
--

INSERT INTO `protection_rules` (`id`, `device_id`, `category`, `domain`, `rule_type`, `status`, `description`, `created_at`, `updated_at`) VALUES
(1, NULL, 'betting', 'bet365.com', 'domain', 'active', 'Betting website blocking', '2026-09-24 04:42:53', '2026-09-24 04:42:53'),
(2, NULL, 'betting', '1xbet.com', 'domain', 'active', NULL, '2026-09-24 04:43:20', '2026-09-24 04:43:20'),
(3, NULL, 'adult', 'exampleadult.com', 'domain', 'active', NULL, '2026-09-24 04:43:29', '2026-09-24 04:43:29'),
(4, NULL, 'youtube_ads', 'youtube.com', 'domain', 'active', NULL, '2026-09-24 04:43:37', '2026-09-24 04:43:37'),
(5, NULL, 'betting', 'betway.com', 'domain', 'active', 'Betting site blocking', '2026-09-24 06:50:42', '2026-09-24 06:50:42'),
(6, NULL, 'gambling', 'test-nextguard.com', 'domain', 'active', 'Next Guard protection rule test', '2026-09-26 00:11:38', '2026-09-26 00:11:38'),
(17, NULL, 'gambling', 'test2-nextguard.com', 'domain', 'active', 'Next Guard protection rule test 2', '2026-09-26 01:29:52', '2026-09-26 01:29:52'),
(18, 3, 'adult', 'example.com', 'domain', 'active', 'Block adult website', '2026-09-27 05:13:12', '2026-09-27 05:13:12');

-- --------------------------------------------------------

--
-- Table structure for table `protection_sync_logs`
--

CREATE TABLE `protection_sync_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `device_id` bigint(20) UNSIGNED NOT NULL,
  `sync_version` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `rules_hash` varchar(64) NOT NULL,
  `apply_status` varchar(255) NOT NULL DEFAULT 'pending',
  `retry_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `max_retry` int(10) UNSIGNED NOT NULL DEFAULT 3,
  `failure_reason` text DEFAULT NULL,
  `last_retry_at` timestamp NULL DEFAULT NULL,
  `applied_at` timestamp NULL DEFAULT NULL,
  `device_version` varchar(255) DEFAULT NULL,
  `ip_address` varchar(255) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `synced_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `protection_sync_logs`
--

INSERT INTO `protection_sync_logs` (`id`, `device_id`, `sync_version`, `rules_hash`, `apply_status`, `retry_count`, `max_retry`, `failure_reason`, `last_retry_at`, `applied_at`, `device_version`, `ip_address`, `user_agent`, `synced_at`, `created_at`, `updated_at`) VALUES
(8, 3, 1, '76ab818b0c45138963c87a893e2102c8577f6f7316030ad04dcd99a3ea2d5fbc', 'applied', 0, 3, NULL, NULL, '2026-09-27 06:17:58', '1.0.0', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-27 06:07:18', '2026-09-27 06:07:18', '2026-09-27 06:17:58'),
(9, 3, 2, '76ab818b0c45138963c87a893e2102c8577f6f7316030ad04dcd99a3ea2d5fbc', 'applied', 1, 3, NULL, '2026-09-27 07:30:00', '2026-09-28 05:30:19', '1.0.0', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-28 05:30:19', '2026-09-27 06:18:16', '2026-09-28 05:30:19'),
(11, 3, 3, '39c0914ee6e3e17f2dc72aeec561bf2aefec75b527582b8e0745aacee9079a43', 'applied', 2, 3, NULL, '2026-09-28 07:22:07', '2026-09-28 07:35:04', '1.0.0', NULL, NULL, '2026-09-28 07:35:04', '2026-09-28 06:32:14', '2026-09-28 07:35:04'),
(12, 3, 4, 'b9606708cc28dc40a8ee5e497d186d7d2062e0e5dad6feb1c81d32acd7fb2d87', 'applied', 0, 3, NULL, NULL, '2026-09-29 00:41:23', '1.0.2', NULL, NULL, '2026-09-29 00:41:23', '2026-09-29 00:03:32', '2026-09-29 00:41:23'),
(13, 3, 5, 'b9606708cc28dc40a8ee5e497d186d7d2062e0e5dad6feb1c81d32acd7fb2d87', 'applied', 0, 3, NULL, NULL, '2026-09-29 01:24:39', '1.0.2', NULL, NULL, '2026-09-29 01:24:39', '2026-09-29 01:22:33', '2026-09-29 01:24:39'),
(14, 3, 6, '6609fc20e4bbb75061c990d0a2e993aa82ee1b549738eed8d27e6835124e5297', 'failed', 3, 3, 'Maximum retry reached.', '2026-09-29 07:42:18', NULL, '1.0.2', NULL, NULL, NULL, '2026-09-29 01:22:38', '2026-09-29 07:42:18'),
(15, 3, 7, 'b9606708cc28dc40a8ee5e497d186d7d2062e0e5dad6feb1c81d32acd7fb2d87', 'failed', 3, 3, 'Device ACK timeout.', NULL, NULL, NULL, NULL, NULL, NULL, '2026-09-29 02:40:53', '2026-09-29 04:43:40'),
(16, 3, 8, 'a3c17cb4e2f11a6fa1392a8b18b1b5d5fbab4c4415d9eca1bfb3f688955e2004', 'failed', 3, 3, 'Device ACK timeout.', NULL, NULL, '1.0.2', NULL, NULL, NULL, '2026-09-29 02:45:51', '2026-09-29 04:43:40'),
(17, 3, 9, 'a3c17cb4e2f11a6fa1392a8b18b1b5d5fbab4c4415d9eca1bfb3f688955e2004', 'applied', 0, 3, NULL, NULL, '2026-09-29 03:22:20', '1.0.2', NULL, NULL, '2026-09-29 03:22:20', '2026-09-29 02:48:07', '2026-09-29 03:22:20'),
(18, 3, 10, 'b9606708cc28dc40a8ee5e497d186d7d2062e0e5dad6feb1c81d32acd7fb2d87', 'failed', 3, 3, 'Maximum retry reached.', '2026-09-29 07:40:03', NULL, '1.0.2', NULL, NULL, NULL, '2026-09-29 03:22:49', '2026-09-29 07:40:03'),
(19, 3, 11, 'b9606708cc28dc40a8ee5e497d186d7d2062e0e5dad6feb1c81d32acd7fb2d87', 'applied', 0, 3, NULL, NULL, '2026-09-29 03:28:13', '1.0.2', NULL, NULL, '2026-09-29 03:28:13', '2026-09-29 03:24:10', '2026-09-29 03:28:13'),
(20, 3, 12, 'a3c17cb4e2f11a6fa1392a8b18b1b5d5fbab4c4415d9eca1bfb3f688955e2004', 'applied', 0, 3, NULL, NULL, '2026-09-29 03:57:57', '1.0.2', NULL, NULL, '2026-09-29 03:57:57', '2026-09-29 03:53:59', '2026-09-29 03:57:57'),
(21, 3, 13, 'a3c17cb4e2f11a6fa1392a8b18b1b5d5fbab4c4415d9eca1bfb3f688955e2004', 'applied', 0, 3, NULL, NULL, '2026-09-29 04:23:27', '1.0.2', NULL, NULL, '2026-09-29 04:23:27', '2026-09-29 04:21:37', '2026-09-29 04:23:27'),
(22, 3, 14, 'a3c17cb4e2f11a6fa1392a8b18b1b5d5fbab4c4415d9eca1bfb3f688955e2004', 'applied', 2, 3, NULL, '2026-09-29 07:43:30', '2026-09-29 07:46:11', '1.0.2', NULL, NULL, '2026-09-29 07:46:11', '2026-09-29 04:29:09', '2026-09-29 07:46:11'),
(23, 3, 15, '0bb0ede3671c67864d167a767e487698c7356799d975f847135223e831835068', 'applied', 0, 3, NULL, NULL, '2026-09-29 08:37:03', '1.0.2', '127.0.0.1', 'Thunder Client (https://www.thunderclient.com)', '2026-09-29 08:37:03', '2026-09-29 08:35:42', '2026-09-29 08:37:03'),
(24, 3, 16, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 'failed', 1, 3, 'Device ACK timeout.', '2026-09-29 09:53:03', NULL, '1.0.2', NULL, NULL, NULL, '2026-09-29 09:52:53', '2026-09-29 09:53:03');

-- --------------------------------------------------------

--
-- Table structure for table `protection_violation_logs`
--

CREATE TABLE `protection_violation_logs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `device_id` bigint(20) UNSIGNED NOT NULL,
  `rule_id` bigint(20) UNSIGNED DEFAULT NULL,
  `domain` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `action` varchar(255) NOT NULL DEFAULT 'blocked',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `protection_violation_logs`
--

INSERT INTO `protection_violation_logs` (`id`, `device_id`, `rule_id`, `domain`, `category`, `action`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 'bet365.com', 'betting', 'blocked', '2026-09-28 06:18:29', '2026-09-28 06:18:29'),
(2, 3, 1, 'bet365.com', 'betting', 'blocked', '2026-09-29 08:59:06', '2026-09-29 08:59:06'),
(3, 3, 1, 'bet365.com', 'betting', 'blocked', '2026-09-29 09:33:34', '2026-09-29 09:33:34'),
(4, 3, 1, 'bet365.com', 'betting', 'blocked', '2026-09-30 02:08:57', '2026-09-30 02:08:57');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('SwBo0d5v8M7TFyl0XyqnGstQ1xi7AuvCr4IByd8N', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/154.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNk1xc2ZNdERZS0pUbEw4YkRiQmxLUWpWU082UTF2Z1BsejBsNzVEUyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi91c2VycyI7czo1OiJyb3V0ZSI7czoxMToiYWRtaW4udXNlcnMiO31zOjU2OiJsb2dpbl9hZG1pbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1790857083);

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `subscription_plan_id` bigint(20) UNSIGNED NOT NULL,
  `device_id` bigint(20) UNSIGNED DEFAULT NULL,
  `starts_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `source` varchar(30) NOT NULL DEFAULT 'payment',
  `auto_renew` tinyint(1) NOT NULL DEFAULT 0,
  `payment_reference` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `user_id`, `subscription_plan_id`, `device_id`, `starts_at`, `expires_at`, `status`, `source`, `auto_renew`, `payment_reference`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '2026-09-24 05:09:20', '2027-09-24 05:09:20', 'cancelled', 'admin', 0, NULL, '2026-09-24 05:09:20', '2026-09-24 06:44:45'),
(2, 1, 1, 1, '2026-09-24 05:37:27', '2027-09-24 05:37:27', 'active', 'license', 0, NULL, '2026-09-24 05:37:27', '2026-09-24 05:37:27'),
(3, 2, 1, 3, '2026-09-27 10:33:49', '2026-09-26 09:13:43', 'changed', 'admin', 0, 'TXN-10001', '2026-09-27 10:33:49', '2026-09-28 03:47:12'),
(4, 2, 2, 3, '2026-09-28 03:47:12', '2026-10-28 03:47:12', 'changed', 'upgrade', 0, NULL, '2026-09-28 03:47:12', '2026-09-28 04:01:06'),
(5, 2, 2, 3, '2026-09-28 04:01:06', '2026-10-28 04:01:06', 'changed', 'upgrade', 0, NULL, '2026-09-28 04:01:06', '2026-09-28 04:05:15'),
(6, 2, 2, 3, '2026-09-28 04:05:15', '2026-10-28 04:05:15', 'changed', 'upgrade', 0, NULL, '2026-09-28 04:05:15', '2026-09-28 04:05:22'),
(7, 2, 2, 3, '2026-09-28 04:05:22', '2026-10-28 04:05:22', 'changed', 'upgrade', 0, NULL, '2026-09-28 04:05:22', '2026-09-28 04:05:24'),
(8, 2, 2, 3, '2026-09-28 04:05:24', '2026-10-28 04:05:24', 'active', 'upgrade', 0, NULL, '2026-09-28 04:05:24', '2026-09-28 04:05:24'),
(9, 2, 1, 3, '2026-09-28 04:49:16', '2026-10-29 03:53:58', 'active', 'license', 0, NULL, '2026-09-28 04:49:16', '2026-09-29 03:53:58');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_events`
--

CREATE TABLE `subscription_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subscription_id` bigint(20) UNSIGNED NOT NULL,
  `event` varchar(255) NOT NULL,
  `old_status` varchar(255) DEFAULT NULL,
  `new_status` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_events`
--

INSERT INTO `subscription_events` (`id`, `subscription_id`, `event`, `old_status`, `new_status`, `description`, `created_at`, `updated_at`) VALUES
(1, 3, 'expired', 'active', 'expired', 'Subscription expired automatically.', '2026-09-27 08:44:47', '2026-09-27 08:44:47'),
(2, 3, 'plan_changed', 'active', 'changed', 'Subscription plan changed.', '2026-09-28 03:47:12', '2026-09-28 03:47:12'),
(3, 4, 'activated', NULL, 'active', 'New subscription activated.', '2026-09-28 03:47:12', '2026-09-28 03:47:12'),
(4, 4, 'plan_changed', 'active', 'changed', 'Subscription plan changed.', '2026-09-28 04:01:06', '2026-09-28 04:01:06'),
(5, 5, 'activated', NULL, 'active', 'New subscription activated.', '2026-09-28 04:01:06', '2026-09-28 04:01:06'),
(6, 5, 'plan_changed', 'active', 'changed', 'Subscription plan changed.', '2026-09-28 04:05:15', '2026-09-28 04:05:15'),
(7, 6, 'activated', NULL, 'active', 'New subscription activated.', '2026-09-28 04:05:15', '2026-09-28 04:05:15'),
(8, 6, 'plan_changed', 'active', 'changed', 'Subscription plan changed.', '2026-09-28 04:05:22', '2026-09-28 04:05:22'),
(9, 7, 'activated', NULL, 'active', 'New subscription activated.', '2026-09-28 04:05:22', '2026-09-28 04:05:22'),
(10, 7, 'plan_changed', 'active', 'changed', 'Subscription plan changed.', '2026-09-28 04:05:24', '2026-09-28 04:05:24'),
(11, 8, 'activated', NULL, 'active', 'New subscription activated.', '2026-09-28 04:05:24', '2026-09-28 04:05:24'),
(12, 9, 'expired', 'active', 'expired', 'Subscription expired automatically.', '2026-09-28 06:32:14', '2026-09-28 06:32:14');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_invoices`
--

CREATE TABLE `subscription_invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `subscription_id` bigint(20) UNSIGNED NOT NULL,
  `invoice_no` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(255) NOT NULL DEFAULT 'USD',
  `status` enum('paid','unpaid','cancelled') NOT NULL DEFAULT 'paid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_invoices`
--

INSERT INTO `subscription_invoices` (`id`, `user_id`, `subscription_id`, `invoice_no`, `amount`, `currency`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 3, 'INV-20260927-3TRZPI', 99.00, 'USD', 'paid', '2026-09-27 09:25:00', '2026-09-27 09:25:00');

-- --------------------------------------------------------

--
-- Table structure for table `subscription_plans`
--

CREATE TABLE `subscription_plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `device_limit` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `currency` varchar(10) NOT NULL DEFAULT 'BDT',
  `duration_days` int(11) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `features` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`features`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscription_plans`
--

INSERT INTO `subscription_plans` (`id`, `name`, `description`, `price`, `device_limit`, `currency`, `duration_days`, `status`, `features`, `created_at`, `updated_at`) VALUES
(1, 'Premium', 'Full Next Guard protection', 999.00, 5, 'BDT', 365, 'active', '{\"betting_block\":true,\"adult_block\":true,\"dns_protection\":true}', '2026-09-24 04:53:03', '2026-09-24 04:53:03'),
(2, 'Basic', 'Basic protection', 499.00, 1, 'BDT', 30, 'active', '{\"betting_block\":true,\"dns_protection\":true}', '2026-09-24 06:48:02', '2026-09-24 06:48:02');

-- --------------------------------------------------------

--
-- Table structure for table `trial_entitlements`
--

CREATE TABLE `trial_entitlements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `device_id` bigint(20) UNSIGNED NOT NULL,
  `started_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'eligible',
  `base_trial` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trial_entitlements`
--

INSERT INTO `trial_entitlements` (`id`, `user_id`, `device_id`, `started_at`, `expires_at`, `status`, `base_trial`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-09-24 02:55:05', '2026-09-27 02:55:05', 'active', 1, '2026-09-24 02:55:05', '2026-09-24 02:55:05'),
(2, 1, 2, '2026-09-24 03:41:06', '2026-09-24 03:44:16', 'expired', 1, '2026-09-24 03:41:06', '2026-09-24 03:46:30'),
(3, 2, 3, '2026-09-27 04:05:52', '2026-09-30 04:05:52', 'active', 1, '2026-09-27 04:05:52', '2026-09-27 04:05:52');

-- --------------------------------------------------------

--
-- Table structure for table `trial_events`
--

CREATE TABLE `trial_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `device_id` bigint(20) UNSIGNED NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `old_expires_at` timestamp NULL DEFAULT NULL,
  `new_expires_at` timestamp NULL DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `admin_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `trial_events`
--

INSERT INTO `trial_events` (`id`, `user_id`, `device_id`, `event_type`, `old_expires_at`, `new_expires_at`, `reason`, `admin_id`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 'TRIAL_STARTED', NULL, '2026-09-27 03:41:06', 'Base trial started.', NULL, '2026-09-24 03:41:06', '2026-09-24 03:41:06'),
(2, 1, 2, 'TRIAL_EXPIRED', '2026-09-24 03:44:16', '2026-09-24 03:44:16', 'Trial expired automatically.', NULL, '2026-09-24 03:46:30', '2026-09-24 03:46:30'),
(3, 2, 3, 'TRIAL_STARTED', NULL, '2026-09-30 04:05:52', 'Base trial started.', NULL, '2026-09-27 04:05:52', '2026-09-27 04:05:52');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `email` varchar(190) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `status`, `email_verified_at`, `phone_verified_at`, `last_login_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Reza Test', 'reza@example.com', '01700000000', '$2y$12$Mc3EnPGTWLX8aMGStfTStOEcrT0AcfydI5v6mUs/g2YulC83ZYkNu', 'active', NULL, NULL, NULL, NULL, '2026-09-24 02:06:49', '2026-09-24 02:06:49'),
(2, 'Test User', 'user@test.com', '01700000001', '$2y$12$H3OGu8yLg6SZSL0zqamSmem79W9i.vA5c949nwYL1WmHRagZ5HtK.', 'active', NULL, NULL, NULL, NULL, '2026-09-27 03:17:42', '2026-09-27 03:17:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`),
  ADD KEY `admins_created_by_foreign` (`created_by`);

--
-- Indexes for table `admin_activity_logs`
--
ALTER TABLE `admin_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_activity_logs_admin_id_created_at_index` (`admin_id`,`created_at`),
  ADD KEY `admin_activity_logs_action_index` (`action`);

--
-- Indexes for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_notifications_admin_id_foreign` (`admin_id`);

--
-- Indexes for table `admin_permissions`
--
ALTER TABLE `admin_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_permissions_admin_id_permission_id_unique` (`admin_id`,`permission_id`),
  ADD KEY `admin_permissions_permission_id_foreign` (`permission_id`);

--
-- Indexes for table `admin_sessions`
--
ALTER TABLE `admin_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_sessions_admin_id_last_activity_index` (`admin_id`,`last_activity`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `devices_user_id_device_uuid_hash_unique` (`user_id`,`device_uuid_hash`),
  ADD KEY `devices_last_seen_at_index` (`last_seen_at`);

--
-- Indexes for table `device_events`
--
ALTER TABLE `device_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `device_events_device_id_foreign` (`device_id`);

--
-- Indexes for table `device_protection_settings`
--
ALTER TABLE `device_protection_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `device_protection_settings_device_id_unique` (`device_id`);

--
-- Indexes for table `device_sessions`
--
ALTER TABLE `device_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `device_sessions_user_id_status_index` (`user_id`,`status`),
  ADD KEY `device_sessions_device_id_status_index` (`device_id`,`status`),
  ADD KEY `device_sessions_expires_at_index` (`expires_at`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `license_codes`
--
ALTER TABLE `license_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `license_codes_code_unique` (`code`),
  ADD KEY `license_codes_subscription_plan_id_foreign` (`subscription_plan_id`),
  ADD KEY `license_codes_used_by_foreign` (`used_by`),
  ADD KEY `license_codes_used_device_id_foreign` (`used_device_id`),
  ADD KEY `license_codes_status_code_index` (`status`,`code`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_transaction_id_unique` (`transaction_id`),
  ADD KEY `payments_user_id_foreign` (`user_id`),
  ADD KEY `payments_subscription_id_foreign` (`subscription_id`);

--
-- Indexes for table `payment_webhooks`
--
ALTER TABLE `payment_webhooks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_webhooks_event_id_unique` (`event_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_unique` (`name`);

--
-- Indexes for table `protection_notifications`
--
ALTER TABLE `protection_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `protection_notifications_device_id_created_at_index` (`device_id`,`created_at`),
  ADD KEY `protection_notifications_user_id_read_at_index` (`user_id`,`read_at`);

--
-- Indexes for table `protection_rules`
--
ALTER TABLE `protection_rules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `protection_rules_category_domain_unique` (`category`,`domain`),
  ADD KEY `protection_rules_category_status_index` (`category`,`status`),
  ADD KEY `protection_rules_device_id_foreign` (`device_id`);

--
-- Indexes for table `protection_sync_logs`
--
ALTER TABLE `protection_sync_logs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `protection_sync_logs_device_id_sync_version_unique` (`device_id`,`sync_version`),
  ADD KEY `protection_sync_logs_device_id_foreign` (`device_id`),
  ADD KEY `protection_sync_logs_device_id_apply_status_index` (`device_id`,`apply_status`),
  ADD KEY `protection_sync_logs_device_id_sync_version_index` (`device_id`,`sync_version`);

--
-- Indexes for table `protection_violation_logs`
--
ALTER TABLE `protection_violation_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `protection_violation_logs_rule_id_foreign` (`rule_id`),
  ADD KEY `protection_violation_logs_device_id_created_at_index` (`device_id`,`created_at`),
  ADD KEY `protection_violation_logs_device_id_category_index` (`device_id`,`category`),
  ADD KEY `protection_violation_logs_device_id_action_index` (`device_id`,`action`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscriptions_subscription_plan_id_foreign` (`subscription_plan_id`),
  ADD KEY `subscriptions_user_id_status_index` (`user_id`,`status`),
  ADD KEY `subscriptions_device_id_status_index` (`device_id`,`status`),
  ADD KEY `subscriptions_expires_at_index` (`expires_at`);

--
-- Indexes for table `subscription_events`
--
ALTER TABLE `subscription_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscription_events_subscription_id_foreign` (`subscription_id`);

--
-- Indexes for table `subscription_invoices`
--
ALTER TABLE `subscription_invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscription_invoices_invoice_no_unique` (`invoice_no`),
  ADD KEY `subscription_invoices_user_id_foreign` (`user_id`),
  ADD KEY `subscription_invoices_subscription_id_foreign` (`subscription_id`);

--
-- Indexes for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subscription_plans_status_index` (`status`);

--
-- Indexes for table `trial_entitlements`
--
ALTER TABLE `trial_entitlements`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `trial_entitlements_user_device_unique` (`user_id`,`device_id`),
  ADD KEY `trial_entitlements_user_id_status_index` (`user_id`,`status`),
  ADD KEY `trial_entitlements_device_id_status_index` (`device_id`,`status`),
  ADD KEY `trial_entitlements_expires_at_index` (`expires_at`);

--
-- Indexes for table `trial_events`
--
ALTER TABLE `trial_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trial_events_user_id_event_type_index` (`user_id`,`event_type`),
  ADD KEY `trial_events_device_id_event_type_index` (`device_id`,`event_type`),
  ADD KEY `trial_events_admin_id_index` (`admin_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `admin_activity_logs`
--
ALTER TABLE `admin_activity_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `admin_permissions`
--
ALTER TABLE `admin_permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `admin_sessions`
--
ALTER TABLE `admin_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `device_events`
--
ALTER TABLE `device_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `device_protection_settings`
--
ALTER TABLE `device_protection_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `device_sessions`
--
ALTER TABLE `device_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `license_codes`
--
ALTER TABLE `license_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `payment_webhooks`
--
ALTER TABLE `payment_webhooks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `protection_notifications`
--
ALTER TABLE `protection_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `protection_rules`
--
ALTER TABLE `protection_rules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `protection_sync_logs`
--
ALTER TABLE `protection_sync_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `protection_violation_logs`
--
ALTER TABLE `protection_violation_logs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `subscription_events`
--
ALTER TABLE `subscription_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `subscription_invoices`
--
ALTER TABLE `subscription_invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `subscription_plans`
--
ALTER TABLE `subscription_plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `trial_entitlements`
--
ALTER TABLE `trial_entitlements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `trial_events`
--
ALTER TABLE `trial_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `admins_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `admins` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `admin_activity_logs`
--
ALTER TABLE `admin_activity_logs`
  ADD CONSTRAINT `admin_activity_logs_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `admin_notifications`
--
ALTER TABLE `admin_notifications`
  ADD CONSTRAINT `admin_notifications_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `admin_permissions`
--
ALTER TABLE `admin_permissions`
  ADD CONSTRAINT `admin_permissions_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `admin_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `admin_sessions`
--
ALTER TABLE `admin_sessions`
  ADD CONSTRAINT `admin_sessions_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `devices`
--
ALTER TABLE `devices`
  ADD CONSTRAINT `devices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `device_events`
--
ALTER TABLE `device_events`
  ADD CONSTRAINT `device_events_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `device_protection_settings`
--
ALTER TABLE `device_protection_settings`
  ADD CONSTRAINT `device_protection_settings_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `device_sessions`
--
ALTER TABLE `device_sessions`
  ADD CONSTRAINT `device_sessions_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `device_sessions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `license_codes`
--
ALTER TABLE `license_codes`
  ADD CONSTRAINT `license_codes_subscription_plan_id_foreign` FOREIGN KEY (`subscription_plan_id`) REFERENCES `subscription_plans` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `license_codes_used_by_foreign` FOREIGN KEY (`used_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `license_codes_used_device_id_foreign` FOREIGN KEY (`used_device_id`) REFERENCES `devices` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `protection_notifications`
--
ALTER TABLE `protection_notifications`
  ADD CONSTRAINT `protection_notifications_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `protection_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `protection_rules`
--
ALTER TABLE `protection_rules`
  ADD CONSTRAINT `protection_rules_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `protection_sync_logs`
--
ALTER TABLE `protection_sync_logs`
  ADD CONSTRAINT `protection_sync_logs_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `protection_violation_logs`
--
ALTER TABLE `protection_violation_logs`
  ADD CONSTRAINT `protection_violation_logs_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `protection_violation_logs_rule_id_foreign` FOREIGN KEY (`rule_id`) REFERENCES `protection_rules` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `subscriptions_subscription_plan_id_foreign` FOREIGN KEY (`subscription_plan_id`) REFERENCES `subscription_plans` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `subscription_events`
--
ALTER TABLE `subscription_events`
  ADD CONSTRAINT `subscription_events_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subscription_invoices`
--
ALTER TABLE `subscription_invoices`
  ADD CONSTRAINT `subscription_invoices_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscription_invoices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trial_entitlements`
--
ALTER TABLE `trial_entitlements`
  ADD CONSTRAINT `trial_entitlements_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `trial_entitlements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trial_events`
--
ALTER TABLE `trial_events`
  ADD CONSTRAINT `trial_events_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `trial_events_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
