-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 29 Bulan Mei 2026 pada 20.05
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `posyandu_pintar_api`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `api_keys`
--

CREATE TABLE `api_keys` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(64) NOT NULL,
  `name` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('posyandu-pintar-cache-a3affa0d1e1a3c72b78aa984c3367a05', 'i:1;', 1780077055),
('posyandu-pintar-cache-a3affa0d1e1a3c72b78aa984c3367a05:timer', 'i:1780077055;', 1780077055),
('posyandu-pintar-cache-a75f3f172bfb296f2e10cbfc6dfc1883', 'i:3;', 1780077936),
('posyandu-pintar-cache-a75f3f172bfb296f2e10cbfc6dfc1883:timer', 'i:1780077936;', 1780077936),
('posyandu-pintar-cache-df21bfa12c4e294c70f64916c0fbc9a5', 'i:2;', 1780077170),
('posyandu-pintar-cache-df21bfa12c4e294c70f64916c0fbc9a5:timer', 'i:1780077170;', 1780077170),
('posyandu-pintar-cache-f1f70ec40aaa556905d4a030501c0ba4', 'i:3;', 1780077961),
('posyandu-pintar-cache-f1f70ec40aaa556905d4a030501c0ba4:timer', 'i:1780077961;', 1780077961);

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
-- Struktur dari tabel `imunisasis`
--

CREATE TABLE `imunisasis` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warga_id` bigint(20) UNSIGNED NOT NULL,
  `kader_id` bigint(20) UNSIGNED NOT NULL,
  `jenis_imunisasi` varchar(255) NOT NULL,
  `tanggal_pemberian` date NOT NULL,
  `tanggal_berikutnya` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `imunisasis`
--

INSERT INTO `imunisasis` (`id`, `warga_id`, `kader_id`, `jenis_imunisasi`, `tanggal_pemberian`, `tanggal_berikutnya`, `keterangan`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'Polio 1', '2026-05-30', '2026-05-31', NULL, '2026-05-28 06:12:01', '2026-05-28 06:12:01'),
(2, 2, 1, 'BCG', '2026-05-31', '2026-06-10', 'test', '2026-05-28 18:18:13', '2026-05-28 18:18:13'),
(3, 8, 1, 'DPT-HB-Hib 1', '2026-05-30', '2026-06-03', 'sadas', '2026-05-29 00:15:18', '2026-05-29 00:15:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_posyandus`
--

CREATE TABLE `jadwal_posyandus` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tanggal_pelaksanaan` date NOT NULL,
  `waktu_mulai` time NOT NULL,
  `waktu_selesai` time NOT NULL,
  `kategori_posyandu` enum('balita','ibu_hamil','lansia') NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jadwal_posyandus`
--

INSERT INTO `jadwal_posyandus` (`id`, `tanggal_pelaksanaan`, `waktu_mulai`, `waktu_selesai`, `kategori_posyandu`, `lokasi`, `created_at`, `updated_at`) VALUES
(1, '2026-06-01', '09:00:00', '11:00:00', 'ibu_hamil', 'rumah_pak_rt_terdekat', '2026-05-28 09:37:42', '2026-05-28 09:43:51'),
(2, '2026-05-29', '11:00:00', '13:00:00', 'balita', 'balai_rw_terdekat', '2026-05-28 17:04:40', '2026-05-28 17:45:31'),
(3, '2026-06-10', '10:00:00', '13:00:00', 'balita', 'balai rw terdekat', '2026-05-28 22:59:03', '2026-05-28 22:59:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jadwal_posyandu_warga`
--

CREATE TABLE `jadwal_posyandu_warga` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `jadwal_posyandu_id` bigint(20) UNSIGNED NOT NULL,
  `warga_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('assigned','attended','cancelled') NOT NULL DEFAULT 'assigned',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `jadwal_posyandu_warga`
--

INSERT INTO `jadwal_posyandu_warga` (`id`, `jadwal_posyandu_id`, `warga_id`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 6, 'assigned', NULL, '2026-05-28 23:09:30', '2026-05-28 23:09:30'),
(2, 1, 7, 'assigned', NULL, '2026-05-28 23:16:16', '2026-05-28 23:16:16'),
(3, 1, 7, 'assigned', NULL, '2026-05-28 23:16:24', '2026-05-28 23:16:24'),
(4, 2, 8, 'assigned', NULL, '2026-05-28 23:18:13', '2026-05-28 23:18:13'),
(5, 1, 9, 'assigned', NULL, '2026-05-29 00:10:00', '2026-05-29 00:10:00'),
(6, 2, 10, 'assigned', NULL, '2026-05-29 08:34:19', '2026-05-29 08:34:19'),
(7, 2, 11, 'assigned', NULL, '2026-05-29 09:29:27', '2026-05-29 09:29:27'),
(8, 2, 12, 'assigned', NULL, '2026-05-29 09:29:44', '2026-05-29 09:29:44'),
(9, 1, 13, 'assigned', NULL, '2026-05-29 09:31:03', '2026-05-29 09:31:03'),
(10, 1, 14, 'assigned', NULL, '2026-05-29 09:31:07', '2026-05-29 09:31:07'),
(11, 2, 15, 'assigned', NULL, '2026-05-29 09:31:16', '2026-05-29 09:31:16'),
(12, 3, 16, 'assigned', NULL, '2026-05-29 09:31:18', '2026-05-29 09:31:18'),
(13, 2, 17, 'assigned', NULL, '2026-05-29 09:47:38', '2026-05-29 09:47:38'),
(14, 1, 18, 'assigned', NULL, '2026-05-29 10:25:08', '2026-05-29 10:25:08');

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
-- Struktur dari tabel `kaders`
--

CREATE TABLE `kaders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `no_hp` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `wilayah` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kaders`
--

INSERT INTO `kaders` (`id`, `user_id`, `nama_lengkap`, `no_hp`, `alamat`, `wilayah`, `created_at`, `updated_at`) VALUES
(1, 5, 'kaderkeren', '081234567899', 'Bandung, Bojongsoang, Cikoneng', 'Bandung', '2026-05-24 05:01:03', '2026-05-24 05:01:03');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_05_16_103113_create_kaders_table', 1),
(5, '2026_05_16_103116_create_wargas_table', 1),
(6, '2026_05_16_103120_create_penimbangans_table', 1),
(7, '2026_05_16_103124_create_imunisasis_table', 1),
(8, '2026_05_16_103128_create_api_keys_table', 1),
(9, '2026_05_16_104921_create_personal_access_tokens_table', 1),
(10, '2026_05_23_000001_alter_users_role_enum_to_include_user', 2),
(11, '2026_05_24_000001_add_verification_fields_to_wargas_table', 3),
(12, '2019_12_14_000001_create_personal_access_tokens_table', 4),
(13, '2026_05_28_000001_add_measurements_to_penimbangans_table', 5),
(14, '2026_05_28_000002_create_jadwal_posyandus_table', 6),
(15, '2026_05_29_000003_create_jadwal_posyandu_warga_table', 7);

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
-- Struktur dari tabel `penimbangans`
--

CREATE TABLE `penimbangans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warga_id` bigint(20) UNSIGNED NOT NULL,
  `kader_id` bigint(20) UNSIGNED NOT NULL,
  `tanggal` date NOT NULL,
  `berat_badan` decimal(5,2) NOT NULL,
  `tinggi_badan` decimal(5,2) NOT NULL,
  `lingkar_kepala` decimal(5,2) DEFAULT NULL,
  `status_gizi` varchar(255) DEFAULT NULL,
  `catatan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tekanan_darah` varchar(255) DEFAULT NULL,
  `lingkar_lengan_atas` decimal(5,2) DEFAULT NULL,
  `lingkar_perut` decimal(5,2) DEFAULT NULL,
  `kolesterol` decimal(8,2) DEFAULT NULL,
  `asam_urat` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `penimbangans`
--

INSERT INTO `penimbangans` (`id`, `warga_id`, `kader_id`, `tanggal`, `berat_badan`, `tinggi_badan`, `lingkar_kepala`, `status_gizi`, `catatan`, `created_at`, `updated_at`, `tekanan_darah`, `lingkar_lengan_atas`, `lingkar_perut`, `kolesterol`, `asam_urat`) VALUES
(1, 2, 1, '2026-05-30', 3.00, 60.00, 3.00, 'baik', NULL, '2026-05-28 04:40:43', '2026-05-28 04:40:43', NULL, NULL, NULL, NULL, NULL),
(2, 1, 1, '2026-05-31', 5.00, 70.00, 5.00, 'kurang', NULL, '2026-05-28 04:46:17', '2026-05-28 04:46:17', NULL, NULL, NULL, NULL, NULL),
(3, 4, 1, '2026-05-31', 3.00, 60.00, NULL, 'buruk', 'tidak ada', '2026-05-28 17:56:53', '2026-05-28 17:56:53', '120/80', 6.00, 40.00, 10.00, 5.00),
(4, 3, 1, '2026-05-29', 8.00, 60.00, NULL, 'baik', 'THR', '2026-05-29 00:17:16', '2026-05-29 00:17:16', '120/80', 15.00, 60.00, 5.00, 5.00),
(5, 17, 1, '2026-05-31', 10.00, 60.00, 10.00, 'buruk', 'asdasda', '2026-05-29 10:10:21', '2026-05-29 10:10:21', NULL, NULL, NULL, NULL, NULL),
(6, 16, 1, '2026-06-11', 12.00, 70.00, 5.00, 'buruk', 'dsg', '2026-05-29 10:20:01', '2026-05-29 10:20:01', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
('7V9uJwPOB9PLIm93NLB31xnpzBGFmbnpflxA2QgV', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibG1DbmtKUXRkTHo2TkVKS3N6Qkpjd0JEeURYYk1Da2lyTE5HcTEzbiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9rYWRlci9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1780066160),
('GpHHrwUjG7yBasOzknWBwHGOHZbzsGTncGdbOmaj', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.122.1 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQnhidjMzb0ZvenZnaERsZzlpRWhVT295RzRtSUdEV2lydGZwT0xacSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1780076986),
('rt6I2SLmmJubZPo3oBJFPqcGcy7AKUkDRat8Y7MZ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVnVDWWRWVXF3VENVM0doU29KYndWTGdZZ2Znc2lobzVCc0ZEa1dZSCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1780077893),
('syAg11oLPzW2S7WWlBSDCTv7o1fDEfB8NYDqlLym', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.121.0 Chrome/142.0.7444.265 Electron/39.8.8 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRjkxVHNLbFp3Mjl6blJpVmJIZWdEU29KdWhjTllqd3N4RXdiTTZXZSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9wZW5pbWJhbmdhbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1780075389),
('xjipAzZ3EpJaa8CWH3cnTIeyYP12s221dC7kTb5a', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSThGSFd4QTN1YWJHdzlJZTZ4YTJ4Tkd2SEI1bUl3SFJhd1NCNmhtVCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1780077911);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','kader','user') NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'admin@posyandu.com', NULL, '$2y$12$YctAhiLZQoOGvzO2G2jq.u5akhGENHsf683yjmdo7vhC/T/Or2/cS', 'admin', NULL, '2026-05-23 04:51:57', '2026-05-23 04:51:57'),
(4, 'nasywan', 'nasywan@gmail.com', NULL, '$2y$12$06.OSWmhvNd0hL4/Lhuh1O89bk6GuZL1.k6Gcz7f9/kvMDWqA5yhy', 'user', NULL, '2026-05-23 08:42:27', '2026-05-23 08:42:27'),
(5, 'kader', 'kader@posyandu.com', NULL, '$2y$12$atVuXt4IwZ8Ft4uBXVvY6.uDXC4/6uSHkJzLjNETYRGRxNbBZ00pu', 'kader', NULL, '2026-05-24 05:01:03', '2026-05-29 09:17:36'),
(6, 'test1', 'test1@gmail.com', NULL, '$2y$12$jQ05ntmqeTmeuRbXH5hA0.O23w7fxKoe734zSwnNvcb3kz7X6vbcG', 'user', NULL, '2026-05-24 05:24:13', '2026-05-24 05:24:13');

-- --------------------------------------------------------

--
-- Struktur dari tabel `wargas`
--

CREATE TABLE `wargas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `nik` varchar(255) NOT NULL,
  `tempat_lahir` varchar(255) DEFAULT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `kategori` enum('balita','ibu_hamil','lansia') NOT NULL,
  `nama_orang_tua` varchar(255) DEFAULT NULL,
  `no_hp` varchar(255) DEFAULT NULL,
  `no_hp_wali` varchar(255) DEFAULT NULL,
  `alamat` varchar(255) NOT NULL,
  `rt_rw` varchar(255) NOT NULL,
  `status_verifikasi` enum('pending','disetujui','ditolak') NOT NULL DEFAULT 'disetujui',
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `wargas`
--

INSERT INTO `wargas` (`id`, `nama_lengkap`, `nik`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `kategori`, `nama_orang_tua`, `no_hp`, `no_hp_wali`, `alamat`, `rt_rw`, `status_verifikasi`, `verified_by`, `verified_at`, `created_at`, `updated_at`) VALUES
(1, 'Warga Uji 758429', '3201000000758429', 'Bandung', '1995-05-24', 'L', 'balita', 'Ibu Uji', '0812758429', '0813758429', 'Jl. Uji Coba No. 1', '01/02', 'disetujui', 1, '2026-05-24 06:24:27', '2026-05-24 06:19:20', '2026-05-24 06:24:27'),
(2, 'test1', '3211111111111111', 'bandung', '2025-01-01', 'L', 'balita', 'orangtuatest1', '08111111111', '08111111119', 'bandung, bojongsoang', '1', 'disetujui', 1, '2026-05-26 20:28:05', '2026-05-26 20:12:29', '2026-05-26 20:28:05'),
(3, 'dua', '32222222222222', 'jawa', '2025-02-26', 'L', 'lansia', 'duo', '08222222222', '08822222222', 'bandung', '04/13', 'disetujui', 5, '2026-05-28 10:07:09', '2026-05-28 09:46:01', '2026-05-28 10:07:09'),
(4, 'tiga', '323333', 'jambi', '2025-03-13', 'L', 'lansia', 'jimba', '0833333333', '0833433333', 'jambi, jambi', '01/02', 'disetujui', 5, '2026-05-28 17:51:48', '2026-05-28 17:51:09', '2026-05-28 17:51:48'),
(5, 'empat', '32444', 'sikit', '2023-05-25', 'P', 'balita', 'oawpd', '08921371290', '0812371232', 'sikitskitk', '01/02', 'disetujui', 5, '2026-05-28 18:45:47', '2026-05-28 18:45:22', '2026-05-28 18:45:47'),
(6, 'hiji', '3211111111111113', 'genteng', '2021-02-09', 'L', 'balita', 'ganteng', '08111111111', '088111111', 'gentenggenteng', '05/15', 'disetujui', 5, '2026-05-28 23:09:30', '2026-05-28 22:57:56', '2026-05-28 23:09:30'),
(7, 'duo', '3222222222222221', 'rumah heo', '2021-02-09', 'L', 'ibu_hamil', 'siksepen', '088888888', '081235523', 'rumah heo di jogja', '06/07', 'disetujui', 5, '2026-05-28 23:16:25', '2026-05-28 23:16:03', '2026-05-28 23:16:25'),
(8, 'asdf', '3222222222222223', 'drfg', '2026-05-05', 'L', 'balita', 'uijsdfh', '087891246462', '08983124123', 'wrsdgdfg', '01/44', 'disetujui', 5, '2026-05-28 23:18:13', '2026-05-28 23:17:37', '2026-05-28 23:18:13'),
(9, 'emtigas', '3244444444444444', 'rydjn', '2025-06-10', 'L', 'ibu_hamil', 'uidas', '08932178472', '08591283471', 'iuasdfh', '01/13', 'disetujui', 5, '2026-05-29 00:10:00', '2026-05-29 00:09:49', '2026-05-29 00:10:00'),
(10, 'isdufh', '3266666666666666', 'kjdsf', '2023-06-06', 'L', 'balita', 'dfiho', '08991231233', '08836712344', 'asiodh', '01/20', 'disetujui', 5, '2026-05-29 08:34:20', '2026-05-29 08:33:55', '2026-05-29 08:34:20'),
(11, 'asuidgh', '3234444444444444', 'asduiy', '2023-02-07', 'L', 'lansia', 'asduhg', '0898321874242', '089237123432', 'asdiasuhh', '09/10', 'disetujui', 5, '2026-05-29 09:29:27', '2026-05-29 08:58:42', '2026-05-29 09:29:27'),
(12, 'asdasiohjp', '3234896712341232', 'dfgdfgd', '2020-02-04', 'L', 'ibu_hamil', 'asdadfs', '089132798423', '0831923674234', 'poskadjioasjd', '01/01', 'disetujui', 5, '2026-05-29 09:29:44', '2026-05-29 09:02:27', '2026-05-29 09:29:44'),
(13, 'doivasd', '3234896712341277', 'oiaushd', '2022-02-10', 'L', 'balita', 'oiasdhd', '08912389732', '0891237981237', 'asdoibnasd', '09/23', 'disetujui', 5, '2026-05-29 09:31:03', '2026-05-29 09:03:28', '2026-05-29 09:31:03'),
(14, 'asdoih', '3234896712341299', 'sdadfg', '2017-02-06', 'L', 'ibu_hamil', 'asuiodjbhsd', '08921378244', '088812398732', 'jkasdjk', '09/123', 'disetujui', 5, '2026-05-29 09:31:07', '2026-05-29 09:07:38', '2026-05-29 09:31:07'),
(15, 'Test Notifikasi Baru', '3201999900010001', NULL, '2000-01-01', 'L', 'balita', NULL, '081234567890', NULL, 'Jl. Melati No. 99', '01/02', 'disetujui', 5, '2026-05-29 09:31:16', '2026-05-29 09:10:54', '2026-05-29 09:31:16'),
(16, 'dsgsdf', '3234896712341242', 'asdasda', '2023-06-09', 'L', 'balita', 'asdfs', '08123897321', '0882136782', 'sdafdsfgs', '09/28', 'disetujui', 5, '2026-05-29 09:31:18', '2026-05-29 09:26:05', '2026-05-29 09:31:18'),
(17, 'asdd', '3234896712341291', 'sadasda', '2025-02-05', 'L', 'balita', 'asdiugh', '081293234234', '081230934278', 'asdliojkh', '02/20', 'disetujui', 5, '2026-05-29 09:47:38', '2026-05-29 09:47:09', '2026-05-29 09:47:38'),
(18, 'asdasd', '3234896712341292', 'asdas', '2015-02-18', 'L', 'ibu_hamil', 'iosadjasio', '08129037321', '089123798213', 'iugqdw', '09/10', 'disetujui', 5, '2026-05-29 10:25:08', '2026-05-29 10:24:51', '2026-05-29 10:25:08');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `api_keys_key_unique` (`key`),
  ADD KEY `api_keys_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `imunisasis`
--
ALTER TABLE `imunisasis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `imunisasis_warga_id_foreign` (`warga_id`),
  ADD KEY `imunisasis_kader_id_foreign` (`kader_id`);

--
-- Indeks untuk tabel `jadwal_posyandus`
--
ALTER TABLE `jadwal_posyandus`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jadwal_posyandu_warga`
--
ALTER TABLE `jadwal_posyandu_warga`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jadwal_posyandu_warga_jadwal_posyandu_id_foreign` (`jadwal_posyandu_id`),
  ADD KEY `jadwal_posyandu_warga_warga_id_foreign` (`warga_id`);

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
-- Indeks untuk tabel `kaders`
--
ALTER TABLE `kaders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kaders_user_id_foreign` (`user_id`);

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
-- Indeks untuk tabel `penimbangans`
--
ALTER TABLE `penimbangans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `penimbangans_warga_id_foreign` (`warga_id`),
  ADD KEY `penimbangans_kader_id_foreign` (`kader_id`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indeks untuk tabel `wargas`
--
ALTER TABLE `wargas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wargas_nik_unique` (`nik`),
  ADD KEY `wargas_verified_by_foreign` (`verified_by`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `imunisasis`
--
ALTER TABLE `imunisasis`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `jadwal_posyandus`
--
ALTER TABLE `jadwal_posyandus`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `jadwal_posyandu_warga`
--
ALTER TABLE `jadwal_posyandu_warga`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kaders`
--
ALTER TABLE `kaders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT untuk tabel `penimbangans`
--
ALTER TABLE `penimbangans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `wargas`
--
ALTER TABLE `wargas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `api_keys`
--
ALTER TABLE `api_keys`
  ADD CONSTRAINT `api_keys_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `imunisasis`
--
ALTER TABLE `imunisasis`
  ADD CONSTRAINT `imunisasis_kader_id_foreign` FOREIGN KEY (`kader_id`) REFERENCES `kaders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `imunisasis_warga_id_foreign` FOREIGN KEY (`warga_id`) REFERENCES `wargas` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `jadwal_posyandu_warga`
--
ALTER TABLE `jadwal_posyandu_warga`
  ADD CONSTRAINT `jadwal_posyandu_warga_jadwal_posyandu_id_foreign` FOREIGN KEY (`jadwal_posyandu_id`) REFERENCES `jadwal_posyandus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `jadwal_posyandu_warga_warga_id_foreign` FOREIGN KEY (`warga_id`) REFERENCES `wargas` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kaders`
--
ALTER TABLE `kaders`
  ADD CONSTRAINT `kaders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `penimbangans`
--
ALTER TABLE `penimbangans`
  ADD CONSTRAINT `penimbangans_kader_id_foreign` FOREIGN KEY (`kader_id`) REFERENCES `kaders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `penimbangans_warga_id_foreign` FOREIGN KEY (`warga_id`) REFERENCES `wargas` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `wargas`
--
ALTER TABLE `wargas`
  ADD CONSTRAINT `wargas_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
