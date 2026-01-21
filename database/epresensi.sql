-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 20 Jan 2026 pada 08.49
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `epresensi`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `departemens`
--

CREATE TABLE `departemens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_departemen` varchar(255) NOT NULL,
  `kode_departemen` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `departemens`
--

INSERT INTO `departemens` (`id`, `nama_departemen`, `kode_departemen`, `created_at`, `updated_at`) VALUES
(1, 'IGD', 'IGD01', '2026-01-10 11:53:53', '2026-01-10 11:53:53'),
(2, 'Rawat Inap', 'RI01', '2026-01-10 11:53:53', '2026-01-10 11:53:53'),
(3, 'Farmasi', 'FAR01', '2026-01-10 11:53:53', '2026-01-10 11:53:53'),
(4, 'Administrasi', 'ADM01', '2026-01-10 11:53:53', '2026-01-10 11:53:53'),
(5, 'IT', 'IT01', '2026-01-10 11:53:53', '2026-01-10 11:53:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `jobs`
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

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
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
-- Struktur dari tabel `kehadirans`
--

CREATE TABLE `kehadirans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `shift_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `jam_masuk` time DEFAULT NULL,
  `jam_pulang` time DEFAULT NULL,
  `lokasi_masuk` text DEFAULT NULL,
  `lokasi_pulang` text DEFAULT NULL,
  `ip_address_masuk` varchar(255) DEFAULT NULL,
  `ip_address_pulang` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Alpha',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kehadirans`
--

INSERT INTO `kehadirans` (`id`, `user_id`, `shift_id`, `tanggal`, `jam_masuk`, `jam_pulang`, `lokasi_masuk`, `lokasi_pulang`, `ip_address_masuk`, `ip_address_pulang`, `status`, `created_at`, `updated_at`) VALUES
(5, 3, 1, '2026-01-19', '07:59:00', '14:00:00', '-7.03450411049392,112.74362264825102', '-7.034509885429639,112.74363534371106', '192.168.110.154', '192.168.110.154', 'Hadir', '2026-01-19 03:26:20', '2026-01-19 04:09:57'),
(7, 4, 1, '2026-01-19', '07:00:00', '14:00:00', '-7.034507836517104,112.74364736783652', '-7.034507836517104,112.74364736783652', '127.0.0.1', '127.0.0.1', 'Hadir', '2026-01-19 03:44:43', '2026-01-19 03:44:43'),
(9, 2, 1, '2026-01-19', '07:00:00', '14:00:00', '-7.0345084842729975,112.74362209376855', '-7.0345084842729975,112.74362209376855', '127.0.0.1', '127.0.0.1', 'Hadir', '2026-01-19 05:18:14', '2026-01-19 05:18:14'),
(10, 5, 3, '2026-01-19', '23:00:00', '07:00:00', '-7.0345200000000006,112.74362', '-7.03451602762348,112.74362', '127.0.0.1', '127.0.0.1', 'Hadir (Terlambat)', '2026-01-19 05:20:07', '2026-01-19 05:28:33');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_departemens_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000001_create_shifts_table', 1),
(4, '0001_01_01_000002_create_jobs_table', 1),
(5, '0001_01_01_000002_create_users_table', 1),
(6, '0001_01_01_000003_create_kehadirans_table', 1),
(7, '0001_01_01_000004_create_pengajuans_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengajuans`
--

CREATE TABLE `pengajuans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_pengajuan` enum('Cuti','Sakit','Izin') NOT NULL,
  `tgl_mulai` date NOT NULL,
  `tgl_selesai` date NOT NULL,
  `alasan` text NOT NULL,
  `bukti` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Disetujui','Ditolak') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `pengajuans`
--

INSERT INTO `pengajuans` (`id`, `user_id`, `jenis_pengajuan`, `tgl_mulai`, `tgl_selesai`, `alasan`, `bukti`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 'Sakit', '2026-01-11', '2026-01-12', 'Demam tinggi, butuh istirahat.', '1768823644_Screenshot (1).png', 'Pending', '2026-01-10 11:53:54', '2026-01-19 04:54:04'),
(2, 2, 'Cuti', '2026-01-20', '2026-01-21', 'ada urusan keluarga', NULL, 'Disetujui', '2026-01-19 04:45:12', '2026-01-19 04:45:28'),
(3, 3, 'Sakit', '2026-01-15', '2026-01-20', 'yela', NULL, 'Disetujui', '2026-01-19 04:52:33', '2026-01-19 04:52:51'),
(4, 4, 'Sakit', '2026-01-01', '2026-01-07', '6', '1768823778_Screenshot (30).png', 'Ditolak', '2026-01-19 04:56:18', '2026-01-19 04:56:34'),
(5, 2, 'Sakit', '2026-01-01', '2026-01-08', 'y', '1768825157_Screenshot (32).png', 'Pending', '2026-01-19 05:19:17', '2026-01-19 05:19:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
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
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('ewW56wV8WT4WuTY9gBec97XI5gD7pf9PeIckaXuD', 1, '192.168.110.154', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiR2RVWWQwdG5EQ3RuT2t3ck83ejRGVG9YZGh2VUhYQnRjaHVlbERtQSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xOTIuMTY4LjExMC4xNTQ6ODAwMC9wZW5nYWp1YW4iO3M6NToicm91dGUiO3M6OToicGVuZ2FqdWFuIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1768821590),
('LPHWUmNEx8ChO968h2iB8lyw1TkiXmKibfQwjc6q', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSVVIdGJkTkFkeEdZTFJqMTlURHo0TXhKd3M1d1JBS2Nja2t5OVR6WCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9fQ==', 1768827744);

-- --------------------------------------------------------

--
-- Struktur dari tabel `shifts`
--

CREATE TABLE `shifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_shift` varchar(255) NOT NULL,
  `jam_masuk` time NOT NULL,
  `jam_pulang` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `shifts`
--

INSERT INTO `shifts` (`id`, `nama_shift`, `jam_masuk`, `jam_pulang`, `created_at`, `updated_at`) VALUES
(1, 'Pagi', '07:00:00', '14:00:00', '2026-01-10 11:53:53', '2026-01-10 11:53:53'),
(2, 'Siang', '14:00:00', '21:00:00', '2026-01-10 11:53:53', '2026-01-10 11:53:53'),
(3, 'Malam', '21:00:00', '07:00:00', '2026-01-10 11:53:53', '2026-01-10 11:53:53'),
(4, 'Non-Shift', '08:00:00', '16:00:00', '2026-01-10 11:53:53', '2026-01-10 11:53:53');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `kuota_cuti` int(11) DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `foto` varchar(255) DEFAULT 'default.jpg',
  `password` varchar(255) NOT NULL,
  `departemen_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nik` varchar(255) DEFAULT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `delete_requested_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `kuota_cuti`, `email_verified_at`, `foto`, `password`, `departemen_id`, `nik`, `jabatan`, `is_admin`, `status`, `delete_requested_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin RSU Anna Medika', 'admin@gmail.com', 0, NULL, '1768745018_admin.png', '$2y$12$j5kMJFYv2zYqXJD9qX0OfuMC6XeUV6dG8lgnzD3rn8XzuqOFuPTQG', 5, '12345678', 'Kepala IT', 1, 'active', NULL, 'H9KJYFrOC9E5rbcRHHK24OfWSwJLjkMm6fUgH0k5r8Yzi3baCv4qbxb1hSfU', '2026-01-10 11:53:54', '2026-01-18 07:03:38'),
(2, 'Abdul Malik A', 'malikdark17@gmail.com', 13, '2026-01-10 11:57:51', '1768744768_Jay Jo _ for desktop.jpeg', '$2y$12$OqIEGeLvB3KXUns6DheVjuetM88KifYgDzwUJwrwfW12JgyE.RXXO', 1, '11112222', 'Perawat', 0, 'active', NULL, 'e80RufddqH0UyfsRLiQqxG8guLeAk0XGdVuoJzx7Jki6xDAhPOIcit7tkpwF', '2026-01-10 11:53:54', '2026-01-19 04:45:28'),
(3, 'Irna Khalda', 'irnakhalda@gmail.com', 15, NULL, 'default.jpg', '$2y$12$xNRtjV0GYhq.8NzYNEa45.d9SxuhXj1zhIP7YZfQwKlXkZQeukw5C', 3, '33334444', 'Apoteker', 0, 'active', NULL, NULL, '2026-01-10 11:53:54', '2026-01-19 04:30:33'),
(4, 'ali', 'ali@gmail.com', 15, NULL, '1768819091_Screenshot (53).png', '$2y$12$0oHzUoSdeav9VwmldjFaNON6KzzeOqbt8NV0G6Ic5n7LOYpPaWATW', NULL, '33334444890', 'Perawat', 0, 'active', NULL, NULL, '2026-01-19 03:37:59', '2026-01-19 04:30:33'),
(5, 'anjar', 'anjar@gmail.com', 15, NULL, '1768821198_Screenshot 2025-11-24 100953.png', '$2y$12$u/nGZZ3jWmUxvs/IFRiZT.Q7fUn6dB.DDSG0Ed6RPLmUoNU1A9Phy', NULL, '123456666', 'Apoteker', 0, 'active', NULL, NULL, '2026-01-19 04:13:18', '2026-01-19 04:30:33');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indeks untuk tabel `departemens`
--
ALTER TABLE `departemens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departemens_kode_departemen_unique` (`kode_departemen`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kehadirans`
--
ALTER TABLE `kehadirans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kehadirans_user_id_foreign` (`user_id`),
  ADD KEY `kehadirans_shift_id_foreign` (`shift_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `pengajuans`
--
ALTER TABLE `pengajuans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pengajuans_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_nik_unique` (`nik`),
  ADD KEY `users_departemen_id_foreign` (`departemen_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `departemens`
--
ALTER TABLE `departemens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kehadirans`
--
ALTER TABLE `kehadirans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `pengajuans`
--
ALTER TABLE `pengajuans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `kehadirans`
--
ALTER TABLE `kehadirans`
  ADD CONSTRAINT `kehadirans_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`),
  ADD CONSTRAINT `kehadirans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengajuans`
--
ALTER TABLE `pengajuans`
  ADD CONSTRAINT `pengajuans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_departemen_id_foreign` FOREIGN KEY (`departemen_id`) REFERENCES `departemens` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
