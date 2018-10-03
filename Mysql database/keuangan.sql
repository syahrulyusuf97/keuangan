-- phpMyAdmin SQL Dump
-- version 4.8.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 03 Okt 2018 pada 10.37
-- Versi server: 10.1.31-MariaDB
-- Versi PHP: 7.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `keuangan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `credits`
--

CREATE TABLE `credits` (
  `credit_id` int(10) UNSIGNED NOT NULL,
  `keperluan` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` double NOT NULL,
  `tanggal` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `credits`
--

INSERT INTO `credits` (`credit_id`, `keperluan`, `jumlah`, `tanggal`, `created_at`, `updated_at`) VALUES
(1, 'Beli sabun mandi', 1700, '2018-09-24', '2018-09-24 02:21:18', '2018-09-24 02:21:18'),
(2, 'Makan Siang', 12500, '2018-09-24', '2018-09-24 12:00:10', '2018-09-24 12:00:10'),
(3, 'Makan Siang', 15000, '2018-09-25', '2018-09-25 13:38:51', '2018-09-25 13:38:51'),
(4, 'Makan siang', 12500, '2018-09-27', '2018-09-27 05:46:34', '2018-09-27 05:46:34'),
(5, 'Gojek', 17000, '2018-09-29', '2018-09-28 23:54:56', '2018-09-28 23:54:56'),
(6, 'Bis', 30000, '2018-09-29', '2018-09-28 23:55:09', '2018-09-28 23:55:09'),
(7, 'Beli susu bernard brand', 8500, '2018-09-29', '2018-09-29 11:46:45', '2018-09-29 11:46:45'),
(8, 'Beli snack', 6000, '2018-09-30', '2018-09-30 21:30:19', '2018-09-30 21:30:19'),
(9, 'Beli parfume & soffel', 27500, '2018-09-30', '2018-09-30 21:32:13', '2018-09-30 21:32:13'),
(10, 'Beli paket data Indosat', 18000, '2018-09-30', '2018-09-30 21:32:49', '2018-09-30 21:32:49'),
(11, 'Beli pulsa', 12000, '2018-09-30', '2018-09-30 21:33:23', '2018-09-30 21:33:23'),
(12, 'Beli minuman dingin', 3000, '2018-09-30', '2018-09-30 21:33:45', '2018-09-30 21:33:45'),
(13, 'Nafkah', 700000, '2018-09-29', '2018-09-30 21:39:03', '2018-09-30 21:39:03'),
(14, 'Beli bensin', 15000, '2018-10-01', '2018-10-02 02:05:12', '2018-10-02 02:05:12'),
(15, 'Beli minuman dingin', 3000, '2018-10-01', '2018-10-02 02:06:46', '2018-10-02 02:07:04'),
(16, 'Beli keperluan pribadi', 39800, '2018-10-01', '2018-10-02 02:08:11', '2018-10-02 02:08:11'),
(17, 'Bayar bis', 30000, '2018-10-02', '2018-10-02 02:09:37', '2018-10-02 02:09:37'),
(18, 'Bayar gojek', 17000, '2018-10-02', '2018-10-02 02:10:50', '2018-10-02 02:10:50'),
(19, 'Beli makan siang', 13500, '2018-10-02', '2018-10-02 06:37:45', '2018-10-02 06:37:45'),
(20, 'Beli makan siang', 13500, '2018-10-03', '2018-10-03 06:00:40', '2018-10-03 06:00:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `depositos`
--

CREATE TABLE `depositos` (
  `deposito_id` int(10) UNSIGNED NOT NULL,
  `keterangan` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` double NOT NULL,
  `tanggal` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `depositos`
--

INSERT INTO `depositos` (`deposito_id`, `keterangan`, `jumlah`, `tanggal`, `created_at`, `updated_at`) VALUES
(1, 'Uang saku', 150000, '2018-09-23', '2018-09-23 12:58:26', '2018-09-23 12:58:26'),
(2, 'Cicilan gaji bulan agustus - september 2018', 1000000, '2018-09-25', '2018-09-25 13:39:56', '2018-09-29 23:51:50'),
(3, 'Pelunasan cicilan gaji bulan agustus - september 2018', 500000, '2018-09-28', '2018-09-28 10:00:00', '2018-09-28 10:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(3, '2014_10_12_000000_create_users_table', 1),
(4, '2014_10_12_100000_create_password_resets_table', 1),
(5, '2018_09_16_225631_create_deposito_table', 1),
(6, '2018_09_16_230005_create_credit_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `password_resets`
--

INSERT INTO `password_resets` (`email`, `token`, `created_at`) VALUES
('syahrulyusuf52@gmail.com', '$2y$10$zYfocyXtlOZYA0dmGqqi1OOkGtAgWvrheJqpIS1rHFcOX7h8Y6ihu', '2018-09-16 17:31:49');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Syahrul', 'admin@gmail.com', '$2y$10$gwZI8vxxH0cMz.7sMtK8ku4b6setYobiLcO6AJMu6ks/jIDgxIZxi', 'aegxVzmAYlWFzX4REtVXr5dfxeHspwLswYp73Pvs5Vj0BxdjeZUiUCbT8UC1', '2018-09-16 16:40:49', '2018-09-16 16:40:49');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `credits`
--
ALTER TABLE `credits`
  ADD PRIMARY KEY (`credit_id`);

--
-- Indeks untuk tabel `depositos`
--
ALTER TABLE `depositos`
  ADD PRIMARY KEY (`deposito_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `credits`
--
ALTER TABLE `credits`
  MODIFY `credit_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `depositos`
--
ALTER TABLE `depositos`
  MODIFY `deposito_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
