-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 29, 2025 at 06:00 PM
-- Server version: 8.0.30
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `penduduk_lara`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kk`
--

CREATE TABLE `kk` (
  `id` bigint UNSIGNED NOT NULL,
  `kepala_keluarga` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_kk` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rt_id` bigint UNSIGNED NOT NULL,
  `rw_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `alamat` longtext COLLATE utf8mb4_unicode_ci,
  `status_ekonomi` enum('Mampu','Tidak Mampu') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kk`
--

INSERT INTO `kk` (`id`, `kepala_keluarga`, `image`, `no_kk`, `rt_id`, `rw_id`, `user_id`, `alamat`, `status_ekonomi`, `created_at`, `updated_at`) VALUES
(3, 'Handoyo', '17fddaea-c03e-44a6-a3b5-93669c3b9d26.jpg', '2172021410010002', 1, 1, 7, 'Jl. Gatot Subroto Gg. Putri Gunung No. 71', 'Mampu', '2025-02-24 00:09:58', '2025-04-29 10:34:20'),
(5, 'bagas', '9a8d13fa-5766-49fa-89ea-5dbc0489db63.jpg', '1234567891011120', 2, 1, 25, 'Tanjungpinang', 'Mampu', '2025-04-24 04:35:13', '2025-04-29 10:24:47'),
(6, 'imam', '232ddd66-07fc-4f1e-9f1b-de47bb780d47.jpg', '9999999999999999', 6, 3, 26, 'Tanjungpinang', 'Mampu', '2025-04-24 04:42:52', '2025-04-29 10:25:15'),
(7, 'Sersanda Bagas', '689b0db1-d4b9-49ff-9294-db752009c5f2.jpg', '1010101010101010', 1, 1, 31, 'Tanjungpinang', 'Mampu', '2025-04-27 04:55:22', '2025-04-29 10:25:42');

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lurah`
--

CREATE TABLE `lurah` (
  `id` bigint NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `nip` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `lurah`
--

INSERT INTO `lurah` (`id`, `nama`, `jabatan`, `nip`, `created_at`, `updated_at`) VALUES
(1, 'Zurfariza, S.IP', 'Penata Tk.I', '19780204 200701 2 020', '2025-04-29 04:26:15', '2025-04-29 08:12:58');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2022_06_13_084225_create_rw_table', 1),
(5, '2022_06_13_084234_create_rt_table', 1),
(6, '2022_06_13_084240_create_kk_table', 1),
(7, '2022_06_13_084246_create_penduduk_table', 1),
(8, '2022_06_16_032048_create_permission_tables', 1),
(11, '2025_03_15_184306_create_logs_table', 2),
(12, '2025_03_15_191819_create_history_logs_table', 2),
(13, '2025_04_23_073505_create_activity_logs_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint UNSIGNED NOT NULL,
  `model_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\User', 1),
(2, 'App\\User', 2),
(3, 'App\\User', 3),
(2, 'App\\User', 4),
(4, 'App\\User', 5),
(4, 'App\\User', 6),
(4, 'App\\User', 7),
(4, 'App\\User', 8),
(3, 'App\\User', 9),
(2, 'App\\User', 10),
(2, 'App\\User', 11),
(3, 'App\\User', 12),
(3, 'App\\User', 13),
(3, 'App\\User', 14),
(3, 'App\\User', 15),
(2, 'App\\User', 17),
(2, 'App\\User', 18),
(2, 'App\\User', 19),
(2, 'App\\User', 20),
(2, 'App\\User', 21),
(2, 'App\\User', 22),
(2, 'App\\User', 23),
(2, 'App\\User', 24),
(4, 'App\\User', 25),
(4, 'App\\User', 26),
(3, 'App\\User', 28),
(3, 'App\\User', 29),
(4, 'App\\User', 31);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penduduk`
--

CREATE TABLE `penduduk` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rw_id` bigint UNSIGNED NOT NULL,
  `rt_id` bigint UNSIGNED NOT NULL,
  `kk_id` bigint UNSIGNED NOT NULL,
  `gender` enum('Perempuan','Laki-laki') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `usia` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tmp_lahir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tgl_lahir` date NOT NULL,
  `agama` enum('Islam','Katolik','Protestan','Konghucu','Buddha','Hindu') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_pernikahan` enum('Kawin','Belum Kawin','Cerai') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_keluarga` enum('Kepala Rumah Tangga','Isteri','Anak','Lainnya') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_sosial` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pekerjaan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_ktp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penduduk`
--

INSERT INTO `penduduk` (`id`, `nama`, `nik`, `rw_id`, `rt_id`, `kk_id`, `gender`, `usia`, `tmp_lahir`, `tgl_lahir`, `agama`, `alamat`, `status_pernikahan`, `status_keluarga`, `status_sosial`, `pekerjaan`, `no_hp`, `image_ktp`, `created_at`, `updated_at`) VALUES
(4, 'Sersanda Bagas Oktavio', '2172021410010091', 1, 1, 3, 'Laki-laki', '25', 'Tanjungpinang', '1999-10-14', 'Islam', 'Jl. Gatot Subroto Gg. Putri Gunung No.71', 'Belum Kawin', 'Anak', 'hidup', 'mahasiswa', '087875538815', 'KTP - BAGAS (1)_page-0001.jpg', '2025-02-25 23:24:14', '2025-04-29 01:36:49'),
(6, 'Handoyo', '1234563123121231', 1, 1, 3, 'Laki-laki', '64', 'Jepara', '1960-10-04', 'Islam', 'kkas', 'Kawin', 'Kepala Rumah Tangga', 'hidup', 'Pensiunan TNI', '121223123123', 'af781306-5410-43e1-9d06-89f4da72f9e9.jpg', '2025-02-26 01:37:32', '2025-04-29 10:43:44'),
(8, 'Sutarmi', '2172021410010009', 1, 1, 3, 'Perempuan', '56', 'Tanjungpinang', '1968-10-14', 'Islam', 'jl.gatot subroto', 'Kawin', 'Isteri', 'hidup', 'asd', '087875538815', '6900111f-fd4b-4ead-83a4-b0f563ef4295.jpg', '2025-02-26 09:52:53', '2025-04-29 10:44:04'),
(9, 'bagas', '1122334455667788', 1, 2, 5, 'Laki-laki', '23', 'Tanjungpinang', '2001-10-14', 'Islam', 'jl. Gatot Subroto', 'Kawin', 'Kepala Rumah Tangga', 'hidup', 'Wiraswasta', '081234567890', 'ktp kosong.jpg', '2025-04-24 04:36:50', '2025-04-24 04:36:50'),
(11, 'wenny', '2172112233445566', 1, 2, 5, 'Perempuan', '21', 'Karimun', '2003-07-14', 'Islam', 'karimun', 'Kawin', 'Isteri', 'hidup', 'ibu rumah tangga', '082170245484', 'ktp kosong.jpg', '2025-04-24 04:40:23', '2025-04-24 04:40:23'),
(12, 'imam', '8888888888888888', 3, 6, 6, 'Laki-laki', '22', 'tarempa', '2002-11-12', 'Islam', 'tarempa', 'Cerai', 'Kepala Rumah Tangga', 'hidup', 'tni', '000000000000', 'ktp kosong.jpg', '2025-04-24 04:45:32', '2025-04-24 04:45:32'),
(13, 'Antok', '2109812312718298', 1, 1, 3, 'Laki-laki', '24', 'Tanjungpinang', '2000-10-14', 'Islam', 'tanjungpinang', 'Belum Kawin', 'Anak', 'hidup', 'mahasiswa', '098812312333', '8e9c6c20-a5da-45ba-a298-10dfd9b0369f.jpg', '2025-04-29 01:37:59', '2025-04-29 10:44:24');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'superadmin', 'web', '2023-10-17 00:16:23', '2023-10-17 00:16:23'),
(2, 'rw', 'web', '2023-10-17 00:16:24', '2023-10-17 00:16:24'),
(3, 'rt', 'web', '2023-10-17 00:16:24', '2023-10-17 00:16:24'),
(4, 'warga', 'web', '2023-10-17 00:16:24', '2023-10-17 00:16:24');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint UNSIGNED NOT NULL,
  `role_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rt`
--

CREATE TABLE `rt` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rw_id` bigint UNSIGNED DEFAULT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `periode_awal` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `periode_akhir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rt`
--

INSERT INTO `rt` (`id`, `nama`, `no_hp`, `rt`, `rw_id`, `user_id`, `periode_awal`, `periode_akhir`, `created_at`, `updated_at`) VALUES
(1, 'Wenny', '082170245484', '1', 1, 3, '2020', '2025', '2025-02-21 03:33:33', '2025-04-27 04:16:44'),
(2, 'Bejo', '087876543122', '3', 1, 9, '2010', '2030', '2025-03-15 11:20:17', '2025-04-24 01:30:43'),
(6, 'Dudung', '081234567898', '2', 3, 15, '2021', '2025', '2025-04-23 02:30:33', '2025-04-24 04:34:18'),
(7, 'lung', '085264277768', '1', 3, 28, '2023', '2025', '2025-04-26 01:11:13', '2025-04-26 01:41:32');

-- --------------------------------------------------------

--
-- Table structure for table `rw`
--

CREATE TABLE `rw` (
  `id` bigint UNSIGNED NOT NULL,
  `nama` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rw` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `periode_awal` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `periode_akhir` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rw`
--

INSERT INTO `rw` (`id`, `nama`, `no_hp`, `rw`, `user_id`, `periode_awal`, `periode_akhir`, `created_at`, `updated_at`) VALUES
(1, 'Tulus', '087875538816', '1', 2, '2020', '2025', '2025-02-21 03:33:07', '2025-04-23 23:32:54'),
(3, 'Handoyo', '121223123124', '2', 10, '2010', '2020', '2025-03-15 14:49:08', '2025-04-24 05:18:02'),
(12, 'wenny', '082170245484', '3', 24, '2021', '2025', '2025-04-24 01:23:50', '2025-04-24 03:51:54');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@admin.com', NULL, '$2y$10$sXNXZXsMsuYmbHK4xJEDBuNL.WIB7G973R9epG6RDIims5eRT17gq', NULL, '2023-10-17 00:16:24', '2023-10-17 00:16:24'),
(2, 'Tulus', 'ketua-rw1@kampungbulang', NULL, '$2y$10$6tez.Rj2OSzd2ZMfnv1IduPmFWlJlmYMOioqtJvFYbnkzHZAhGR3i', NULL, '2025-02-21 03:33:07', '2025-04-23 23:32:54'),
(3, 'Wenny', 'ketua-rt1.1@kampungbulang', NULL, '$2y$10$Xz7W.6ENH4ABuAfSnVC/o.t35CfCYlaRFI1BZMyPsvIMGZoitU9B.', NULL, '2025-02-21 03:33:33', '2025-04-27 04:16:44'),
(7, 'Handoyo', '2172021410010002', NULL, '$2y$10$Uyv/Ht1aPbOKZMs4sX/wfeGIJnu7O2sGygPXxNkDYBiCbzo2/bnyq', NULL, '2025-02-24 00:09:58', '2025-04-29 10:34:20'),
(9, 'Bejo', 'ketua-rt3.1@kampungbulang', NULL, '$2y$10$.Cqx7TtTOpFwhEBOcxPvMeXVxYnLeQbbthFyBSDQMxTHWURZTTrUG', NULL, '2025-03-15 11:20:17', '2025-04-24 03:59:45'),
(10, 'Handoyo', 'ketua-rw2@kampungbulang', NULL, '$2y$10$FupKiMMkft10XSsDfmRUne56AhQfG10.Nc3lj.MUlHneNA/H1X8W.', NULL, '2025-03-15 14:49:08', '2025-04-24 05:18:02'),
(12, 'wenny', 'ketua-rt1.12@kampungbulang', NULL, '$2y$10$GU71ccjRILvzdH71in80Q.uTRGtExnVsfPhjJEXLvqPbs6SlX5sH2', NULL, '2025-04-23 02:01:43', '2025-04-23 02:01:43'),
(14, 'Handoyo', 'ketua-rt24.12@kampungbulang', NULL, '$2y$10$D6Qhr4sDtcEfGO5t6PBOZ.VEbhixYe7OKSzY2fjjr4PzX7D5qii76', NULL, '2025-04-23 02:11:31', '2025-04-23 02:11:31'),
(15, 'Dudung', 'ketua-rt2.2@kampungbulang', NULL, '$2y$10$IblvFY1ynSPBXGjO3zsokuEX14rXzC4e6OndT4urfs57BxQ9.p.my', NULL, '2025-04-23 02:30:33', '2025-04-24 04:34:18'),
(24, 'wenny', 'ketua-rw3@kampungbulang', NULL, '$2y$10$/jWZUJ8Nss35ib/WFhAqs.9sA65Sv8JB9X/3RjsgMFNIAHggoqFNS', NULL, '2025-04-24 01:23:50', '2025-04-24 04:10:14'),
(25, 'bagas', '1234567891011120', NULL, '$2y$10$0oGJMIyfQbGfTWr94ukcEOLjR/J6mrooHfip3vupf7dM261HwXvT.', NULL, '2025-04-24 04:35:12', '2025-04-29 10:24:47'),
(26, 'imam', '9999999999999999', NULL, '$2y$10$PfPCh4nkpni2JNtZcG1bjujR1D2T4dv4goehXd4mTrejx.GX3CrVC', NULL, '2025-04-24 04:42:52', '2025-04-29 10:25:15'),
(28, 'lung', 'ketua-rt1.2@kampungbulang', NULL, '$2y$10$sya.sLB/4cbexAb5B86jle5F/gTisA9g0CEj5E2WFhol1CkhzllgK', NULL, '2025-04-26 01:11:13', '2025-04-26 01:41:32'),
(31, 'Sersanda Bagas', '1010101010101010', NULL, '$2y$10$49.d9IyrNxPFbbqlth22suzJ7PNnOgLUcb76wzDyD.2ZkZTKcWtG6', NULL, '2025-04-27 04:55:21', '2025-04-29 10:25:42');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kk`
--
ALTER TABLE `kk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kk_rt_id_foreign` (`rt_id`),
  ADD KEY `kk_rw_id_foreign` (`rw_id`),
  ADD KEY `kk_user_id_foreign` (`user_id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `logs_user_id_foreign` (`user_id`);

--
-- Indexes for table `lurah`
--
ALTER TABLE `lurah`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `penduduk`
--
ALTER TABLE `penduduk`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `penduduk_nik_unique` (`nik`),
  ADD KEY `penduduk_rw_id_foreign` (`rw_id`),
  ADD KEY `penduduk_rt_id_foreign` (`rt_id`),
  ADD KEY `penduduk_kk_id_foreign` (`kk_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `rt`
--
ALTER TABLE `rt`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rt_rw_id_foreign` (`rw_id`),
  ADD KEY `rt_user_id_foreign` (`user_id`);

--
-- Indexes for table `rw`
--
ALTER TABLE `rw`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rw_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `kk`
--
ALTER TABLE `kk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lurah`
--
ALTER TABLE `lurah`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `penduduk`
--
ALTER TABLE `penduduk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rt`
--
ALTER TABLE `rt`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `rw`
--
ALTER TABLE `rw`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kk`
--
ALTER TABLE `kk`
  ADD CONSTRAINT `kk_rt_id_foreign` FOREIGN KEY (`rt_id`) REFERENCES `rt` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kk_rw_id_foreign` FOREIGN KEY (`rw_id`) REFERENCES `rw` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `kk_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `penduduk`
--
ALTER TABLE `penduduk`
  ADD CONSTRAINT `penduduk_kk_id_foreign` FOREIGN KEY (`kk_id`) REFERENCES `kk` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `penduduk_rt_id_foreign` FOREIGN KEY (`rt_id`) REFERENCES `rt` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `penduduk_rw_id_foreign` FOREIGN KEY (`rw_id`) REFERENCES `rw` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rt`
--
ALTER TABLE `rt`
  ADD CONSTRAINT `rt_rw_id_foreign` FOREIGN KEY (`rw_id`) REFERENCES `rw` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rt_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `rw`
--
ALTER TABLE `rw`
  ADD CONSTRAINT `rw_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
