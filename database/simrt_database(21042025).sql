-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 21, 2025 at 06:11 AM
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
  `status_ekonomi` enum('Mampu','Tidak Mampu') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kk`
--

INSERT INTO `kk` (`id`, `kepala_keluarga`, `image`, `no_kk`, `rt_id`, `rw_id`, `user_id`, `status_ekonomi`, `created_at`, `updated_at`) VALUES
(3, 'Handoyo', 'Screenshot (1).png', '2172021410010002', 1, 1, 7, 'Mampu', '2025-02-24 00:09:58', '2025-03-15 10:35:53');

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
(9, '2025_03_15_184306_create_logs_table', 2),
(10, '2025_03_15_191819_create_history_logs_table', 3);

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
(2, 'App\\User', 10);

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
(4, 'Sersanda Bagas Oktavio', '2172021410010090', 1, 1, 3, 'Laki-laki', '25', 'Tanjungpinang', '1999-10-14', 'Islam', 'Jl. Gatot Subroto Gg. Putri Gunung No.71', 'Belum Kawin', 'Lainnya', 'hidup', 'mahasiswa', '087875538815', 'KTP - BAGAS (1)_page-0001.jpg', '2025-02-25 23:24:14', '2025-03-15 16:53:01'),
(6, 'Handoyo', '123456', 1, 1, 3, 'Laki-laki', '64', 'Jepara', '1960-10-04', 'Islam', 'kkas', 'Kawin', 'Kepala Rumah Tangga', 'hidup', 'Pensiunan TNI', '121223123123', 'IMG-20250310-WA0007.jpeg', '2025-02-26 01:37:32', '2025-03-15 16:38:30'),
(8, 'Sutarmi', '2172021410010009', 1, 1, 3, 'Perempuan', '56', 'Tanjungpinang', '1968-10-14', 'Islam', 'jl.gatot subroto', 'Kawin', 'Isteri', 'hidup', 'asd', '087875538815', 'Lambang_Kota_Tanjungpinang.png', '2025-02-26 09:52:53', '2025-03-13 08:42:47');

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
(1, 'Sutarmi', '085264277766', '1', 1, 3, '2020', '2025', '2025-02-21 03:33:33', '2025-03-15 13:49:13'),
(2, 'bejo', '087876543122', '2', 1, 9, '2010', '2030', '2025-03-15 11:20:17', '2025-03-15 14:11:39');

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
(1, 'Tulus', '087875538817', '1', 2, '2020', '2025', '2025-02-21 03:33:07', '2025-03-15 14:57:33'),
(3, 'Handoyo', '121223123124', '2', 10, '2010', '2020', '2025-03-15 14:49:08', '2025-03-15 14:49:08');

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
(2, 'Tulus', 'rw1@gmail.com', NULL, '$2y$10$6tez.Rj2OSzd2ZMfnv1IduPmFWlJlmYMOioqtJvFYbnkzHZAhGR3i', NULL, '2025-02-21 03:33:07', '2025-03-15 14:57:33'),
(3, 'Sutarmi', 'rt11@gmail.com', NULL, '$2y$10$UhHhc4/n4nktx5BB4VEEF.A2RjX6Kf/XJBDKsrXlFskhERUu/ZtE2', NULL, '2025-02-21 03:33:33', '2025-03-15 13:49:13'),
(7, 'Handoyo', '2172021410010002', NULL, '$2y$10$U13Vojnf1h4sCOu.nJN4qOBRXBYnL8ju08ZGL9Z6eGFLnJl7Od2d2', NULL, '2025-02-24 00:09:58', '2025-03-15 10:35:53'),
(9, 'bejo', 'rt21@gmail.com', NULL, '$2y$10$DhCbWtNDR1pxvRycmyfOMugC6Clv98DT7q6FIXsKsvv1nwfquudwe', NULL, '2025-03-15 11:20:17', '2025-03-15 14:11:39'),
(10, 'Handoyo', 'rw2@gmail.com', NULL, '$2y$10$FupKiMMkft10XSsDfmRUne56AhQfG10.Nc3lj.MUlHneNA/H1X8W.', NULL, '2025-03-15 14:49:08', '2025-03-15 14:49:08');

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `penduduk`
--
ALTER TABLE `penduduk`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rw`
--
ALTER TABLE `rw`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
