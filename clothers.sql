-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 18 Jun 2025 pada 16.24
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clothers`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `uid_admin` varchar(20) NOT NULL,
  `nama_admin` varchar(100) NOT NULL,
  `usrname_admin` varchar(100) NOT NULL,
  `password_admin` varchar(255) NOT NULL,
  `email_admin` varchar(100) NOT NULL,
  `notlp_admin` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`uid_admin`, `nama_admin`, `usrname_admin`, `password_admin`, `email_admin`, `notlp_admin`) VALUES
('AID684b99e26a990', 'Super Admin', 'admin123', '$2y$10$wnhp4shIHGe9oLxPjQ4DkO1rqOuFvjO65wv5PuJl5cB3jHXwmrw9m', 'admin@example.com', '082249809038');

-- --------------------------------------------------------

--
-- Struktur dari tabel `brand`
--

CREATE TABLE `brand` (
  `id_brand` int(11) NOT NULL,
  `nama_brand` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `brand`
--

INSERT INTO `brand` (`id_brand`, `nama_brand`) VALUES
(10001, 'NIKE'),
(10002, 'PUMA'),
(10003, 'ADIDAS'),
(10004, 'NEW BALANCE'),
(10005, 'MIZUNO'),
(10006, 'ASICS'),
(10007, 'SALOMON'),
(10008, 'HOKA');

-- --------------------------------------------------------

--
-- Struktur dari tabel `cart`
--

CREATE TABLE `cart` (
  `id_cart` int(11) NOT NULL,
  `uid` varchar(15) DEFAULT NULL,
  `id_prod` int(11) DEFAULT NULL,
  `id_ukuran` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT 1,
  `tanggal_ditambahkan` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_pesanan`
--

CREATE TABLE `detail_pesanan` (
  `id_detail` int(11) NOT NULL,
  `id_pesanan` varchar(20) DEFAULT NULL,
  `id_prod` int(11) DEFAULT NULL,
  `id_ukuran` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `harga_satuan` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `detail_pesanan`
--

INSERT INTO `detail_pesanan` (`id_detail`, `id_pesanan`, `id_prod`, `id_ukuran`, `jumlah`, `harga_satuan`) VALUES
(1, 'ORD6851917c8c9ee', 45683, 27, 1, 2399000),
(2, 'ORD68529e28b7f12', 45684, 27, 1, 1099000),
(3, 'ORD6852a899cc73f', 45685, 23, 1, 1899000),
(4, 'ORD6852a899cc73f', 45685, 22, 1, 1899000),
(5, 'ORD6852a899cc73f', 45690, 28, 1, 4300000),
(6, 'ORD6852c84b670df', 45690, 28, 1, 4300000);

-- --------------------------------------------------------

--
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`) VALUES
(10010, 'Sneakers'),
(10011, 'Sports'),
(10012, 'Apparel'),
(10013, 'Lifestyle');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kode_registrasi`
--

CREATE TABLE `kode_registrasi` (
  `id` int(11) NOT NULL,
  `kode` varchar(255) NOT NULL,
  `status` enum('aktif','terpakai') DEFAULT 'aktif',
  `digunakan_oleh` varchar(255) DEFAULT NULL,
  `digunakan_pada` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengiriman`
--

CREATE TABLE `pengiriman` (
  `id_pengiriman` int(11) NOT NULL,
  `metode` varchar(100) NOT NULL,
  `biaya` int(11) NOT NULL,
  `estimasi` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengiriman`
--

INSERT INTO `pengiriman` (`id_pengiriman`, `metode`, `biaya`, `estimasi`) VALUES
(1, 'JNE Reguler', 18000, '2-3 hari'),
(2, 'J&T Express', 20000, '1-2 hari'),
(3, 'GoSend Same Day', 25000, 'Hari ini'),
(4, 'Ambil di Toko', 0, 'Langsung');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pesanan`
--

CREATE TABLE `pesanan` (
  `id_pesanan` varchar(20) NOT NULL,
  `uid` varchar(15) DEFAULT NULL,
  `tanggal_pesan` datetime DEFAULT current_timestamp(),
  `status_pesanan` enum('pending','dibayar','dikirim','selesai','dibatalkan') DEFAULT 'pending',
  `metode_pengiriman` varchar(100) DEFAULT NULL,
  `alamat_pengiriman` text DEFAULT NULL,
  `metode_pembayaran` varchar(100) DEFAULT 'COD',
  `status_pengambilan` enum('belum diambil','sudah diambil') DEFAULT NULL,
  `id_pengiriman` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pesanan`
--

INSERT INTO `pesanan` (`id_pesanan`, `uid`, `tanggal_pesan`, `status_pesanan`, `metode_pengiriman`, `alamat_pengiriman`, `metode_pembayaran`, `status_pengambilan`, `id_pengiriman`) VALUES
('ORD6851917c8c9ee', 'UID927130', '2025-06-17 23:02:04', 'selesai', 'Ambil di Toko', '', 'COD', NULL, 4),
('ORD68529e28b7f12', 'UID927130', '2025-06-18 18:08:24', 'selesai', 'Ambil di Toko', '', 'COD', NULL, 4),
('ORD6852a899cc73f', 'UID927130', '2025-06-18 18:52:57', 'selesai', 'Ambil di Toko', '', 'COD', NULL, 4),
('ORD6852c84b670df', 'UID927130', '2025-06-18 21:08:11', 'selesai', 'Ambil di Toko', '', 'COD', 'sudah diambil', 4);

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk`
--

CREATE TABLE `produk` (
  `id_prod` int(11) NOT NULL,
  `nama_prod` varchar(255) NOT NULL,
  `thumbnail_prod` varchar(255) NOT NULL,
  `img1_prod` varchar(255) DEFAULT NULL,
  `img2_prod` varchar(255) DEFAULT NULL,
  `img3_prod` varchar(255) DEFAULT NULL,
  `kondisi_prod` enum('BRAND NEW','PRELOVED','DEFLECT','DEAD STOCK') NOT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `id_brand` int(11) DEFAULT NULL,
  `harga_prod` bigint(20) NOT NULL,
  `deskripsi_prod` text DEFAULT NULL,
  `target_kategori` enum('MEN','WOMEN','KIDS','UNISEX') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk`
--

INSERT INTO `produk` (`id_prod`, `nama_prod`, `thumbnail_prod`, `img1_prod`, `img2_prod`, `img3_prod`, `kondisi_prod`, `id_kategori`, `id_brand`, `harga_prod`, `deskripsi_prod`, `target_kategori`) VALUES
(45683, 'PUMA Deviate Nitro 3 Running Shoes Men', '684e1cc843ae2_PUMA Deviate Nitro™ 3 Running Shoes Men, Yellow Alert_Black, size 7_5.jpeg', 'img1_684e2ac1814b8_Deviate-NITRO™-3-Running-Shoes-Women.avif', 'img2_684e2ac181952_Deviate-NITRO™-3-Running-Shoes-Women (1).avif', 'img3_684e2ac181efe_Deviate-NITRO™-3-Running-Shoes-Women (2).avif', 'BRAND NEW', 10011, 10002, 2399000, 'Puma Deviate Nitro 3 – Lari Lebih Jauh, Lebih Cepat\r\n\r\nRasakan sensasi lari yang responsif dan stabil dengan Puma Deviate Nitro 3, sepatu performa tinggi yang dirancang untuk pelari jarak jauh maupun harian. Versi ketiga ini hadir dengan peningkatan signifikan pada bantalan dan efisiensi lari.\r\n\r\n🔹 Midsole NITRO™ Elite – memberikan kombinasi sempurna antara ringan dan responsivitas tinggi, membuat setiap langkah terasa lebih ringan dan cepat.\r\n🔹 Plate Carbon PWRPLATE – struktur pelat karbon inovatif untuk dorongan maksimal di setiap langkah.\r\n🔹 Upper Mesh Teknikal – breathable dan ringan, mendukung kenyamanan sekaligus menjaga kestabilan.\r\n🔹 Outsole PUMAGRIP – daya cengkeram optimal di berbagai permukaan, baik jalan kering maupun basah.\r\n\r\nCocok untuk: Pelari netral yang menginginkan kecepatan, kenyamanan, dan efisiensi dalam satu paket.\r\n\r\nBobot: Sekitar 260g (pria) / 220g (wanita)\r\nDrop: 8mm', 'MEN'),
(45684, 'Puma Men\'s Club Era ll Lace Up Sneaker - Black', '684e25940716e_Puma Men\'s Club Era ll Lace Up Sneaker - Blac.jpeg', NULL, NULL, NULL, 'DEAD STOCK', 10010, 10002, 1099000, 'Puma Men\'s Club Era II – Gaya Kasual, Sentuhan Klasik\r\n\r\nTampil sporty dan stylish setiap hari dengan Puma Club Era II. Sepatu ini memadukan desain klasik khas Puma dengan sentuhan modern yang simpel dan clean, cocok dipakai hangout, ke kampus, atau jalan santai.\r\n\r\n🔹 Desain minimalis elegan – mudah dipadukan dengan berbagai outfit kasual\r\n🔹 Material atas sintetis & suede – nyaman, tahan lama, dan mudah dibersihkan\r\n🔹 Outsole karet solid – memberikan cengkeraman yang stabil di berbagai permukaan\r\n🔹 Logo Puma khas – sentuhan branding yang ikonik\r\n\r\nCocok untuk: Aktivitas sehari-hari, gaya santai, dan pecinta streetwear\r\n\r\nTersedia dalam beberapa warna netral yang gampang di-mix & match. Pilihan pas buat kamu yang ingin tampil keren tanpa usaha berlebihan!', NULL),
(45685, 'Puma Speedcat OG Bleu Taille', '684e2e469ed1b_Baskets Puma Speedcat OG Bleu Taille 40_5.jpeg', NULL, NULL, NULL, 'BRAND NEW', 10010, 10002, 1899000, 'Puma Speedcat OG Bleu – Ikonik, Sporty, Penuh Gaya\r\nTerinspirasi dari dunia balap dan warisan motorsport, Puma Speedcat OG Bleu menghadirkan tampilan klasik dengan nuansa premium. Sepatu ini jadi pilihan sempurna bagi kamu yang ingin menonjolkan gaya retro sporty yang tak lekang oleh waktu.\r\n\r\n🔹 Desain Low-Profile – khas sepatu pembalap, ramping dan pas di kaki\r\n🔹 Upper Suede Premium – lembut, nyaman, dan memberi kesan mewah\r\n🔹 Outsole Karet Flat – traksi maksimal di berbagai permukaan\r\n🔹 Logo Cat di ujung depan & side Puma klasik – sentuhan otentik motorsport heritage\r\n\r\nWarna: Bleu (Biru Navy dengan aksen putih klasik)\r\nCocok untuk: Gaya kasual, streetwear, hingga kolektor sepatu Puma klasik', NULL),
(45686, 'Air Zoom Spiridon Caged 2 Stussy Fossil', '684edee5b1de0_Air Zoom Spiridon Caged 2 Stussy Fossil 40_5.jpeg', NULL, NULL, NULL, 'BRAND NEW', 10010, 10001, 2279000, 'rawr', NULL),
(45687, 'Jordan One Take 5 PF', '684edf90b3a63_jordanonetake5.jpg', NULL, NULL, NULL, 'PRELOVED', 10011, 10001, 1238000, 'jrdan onetake', NULL),
(45688, 'NB 550 Triple White', '684ee9900b5b0_Check out this listing I just found on Poshmark….jpeg', NULL, NULL, NULL, 'BRAND NEW', 10010, 10004, 999000, 'NB 550 White', NULL),
(45689, 'ASICS Gel-Kayano 14 White Fjord Grey', '684ff7f0603cb_ASICS Gel-Kayano 14 White Fjord Grey - EU 43_5.jpeg', NULL, NULL, NULL, 'BRAND NEW', 10010, 10004, 2600000, 'asics', NULL),
(45690, 'Adidas Mens Adizero Evo ', '684ffabacf6ab_Adidas Mens Adizero Evo SL Shoes.jpeg', NULL, NULL, NULL, 'BRAND NEW', 10011, 10001, 4300000, 'Adidas Mens Adizero Evo SL Shoes', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `produk_stok`
--

CREATE TABLE `produk_stok` (
  `id_stok` int(11) NOT NULL,
  `id_prod` int(11) NOT NULL,
  `id_ukuran` int(11) NOT NULL,
  `stok` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `produk_stok`
--

INSERT INTO `produk_stok` (`id_stok`, `id_prod`, `id_ukuran`, `stok`) VALUES
(30, 45683, 26, 3),
(31, 45683, 27, 7),
(32, 45683, 28, 1),
(33, 45683, 31, 4),
(34, 45684, 21, 4),
(35, 45684, 23, 1),
(36, 45684, 25, 2),
(37, 45684, 27, 4),
(38, 45684, 29, 1),
(39, 45683, 12, 0),
(40, 45683, 13, 0),
(41, 45683, 14, 0),
(42, 45683, 15, 0),
(43, 45683, 16, 0),
(44, 45683, 17, 0),
(45, 45683, 18, 0),
(46, 45683, 19, 0),
(47, 45683, 20, 0),
(48, 45683, 21, 0),
(49, 45683, 22, 0),
(50, 45683, 23, 0),
(51, 45683, 24, 0),
(52, 45683, 25, 0),
(53, 45683, 29, 0),
(54, 45683, 30, 0),
(55, 45683, 32, 0),
(56, 45683, 33, 0),
(57, 45683, 34, 0),
(58, 45683, 35, 0),
(59, 45683, 36, 0),
(60, 45683, 37, 0),
(61, 45683, 38, 0),
(62, 45683, 43, 0),
(63, 45685, 21, 2),
(64, 45685, 22, 1),
(65, 45685, 23, 1),
(66, 45685, 25, 4),
(67, 45685, 26, 2),
(68, 45685, 27, 3),
(69, 45685, 29, 1),
(70, 45685, 31, 3),
(71, 45686, 24, 2),
(72, 45686, 25, 5),
(73, 45686, 29, 2),
(74, 45686, 31, 1),
(75, 45687, 25, 3),
(76, 45687, 27, 4),
(77, 45687, 28, 3),
(78, 45687, 29, 2),
(79, 45687, 31, 5),
(80, 45687, 33, 3),
(81, 45688, 17, 1),
(82, 45688, 19, 2),
(83, 45688, 21, 3),
(84, 45688, 23, 2),
(85, 45688, 25, 4),
(86, 45688, 27, 5),
(87, 45688, 28, 2),
(88, 45688, 29, 3),
(89, 45688, 30, 1),
(90, 45688, 31, 3),
(91, 45688, 12, 0),
(92, 45688, 13, 0),
(93, 45688, 14, 0),
(94, 45688, 15, 0),
(95, 45688, 16, 0),
(96, 45688, 18, 0),
(97, 45688, 20, 0),
(98, 45688, 22, 0),
(99, 45688, 24, 0),
(100, 45688, 26, 0),
(101, 45688, 32, 0),
(102, 45688, 33, 0),
(103, 45688, 34, 0),
(104, 45688, 35, 0),
(105, 45688, 36, 0),
(106, 45688, 37, 0),
(107, 45688, 38, 0),
(108, 45688, 43, 0),
(109, 45689, 21, 1),
(110, 45689, 23, 2),
(111, 45689, 25, 1),
(112, 45689, 27, 2),
(113, 45689, 31, 2),
(114, 45690, 21, 1),
(115, 45690, 25, 3),
(116, 45690, 26, 1),
(117, 45690, 28, 3),
(118, 45690, 31, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `riwayat_status`
--

CREATE TABLE `riwayat_status` (
  `id` int(11) NOT NULL,
  `id_pesanan` varchar(20) DEFAULT NULL,
  `status_baru` enum('pending','dibayar','dikirim','selesai','dibatalkan') DEFAULT NULL,
  `waktu_status` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ukuran`
--

CREATE TABLE `ukuran` (
  `id_ukuran` int(11) NOT NULL,
  `ukuran_label` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `ukuran`
--

INSERT INTO `ukuran` (`id_ukuran`, `ukuran_label`) VALUES
(12, '35'),
(13, '36'),
(14, '36.5'),
(15, '37'),
(16, '37.5'),
(17, '38'),
(18, '38.5'),
(19, '39'),
(20, '39.5'),
(21, '40'),
(22, '40.5'),
(23, '41'),
(24, '41.5'),
(25, '42'),
(26, '42.5'),
(27, '43'),
(28, '43.5'),
(29, '44'),
(30, '44.5'),
(31, '45'),
(32, '45.5'),
(33, '46'),
(34, 'XS'),
(35, 'S'),
(36, 'M'),
(37, 'L'),
(38, 'XL'),
(43, 'L');

-- --------------------------------------------------------

--
-- Struktur dari tabel `ukuran_produk`
--

CREATE TABLE `ukuran_produk` (
  `id` int(11) NOT NULL,
  `id_prod` int(11) NOT NULL,
  `id_ukuran` int(11) NOT NULL,
  `stok` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `uid` varchar(15) NOT NULL,
  `nama_depan` varchar(50) NOT NULL,
  `nama_belakang` varchar(50) NOT NULL,
  `usrname` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `status` enum('aktif','diblokir') DEFAULT 'aktif'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`uid`, `nama_depan`, `nama_belakang`, `usrname`, `email`, `password`, `foto_profil`, `status`) VALUES
('UID927130', 'Ihsan', 'Banuaji', 'Ihsan Banuaji', 'xeraaaja@gmail.com', '$2y$10$uLIVn0.MUqVX3eyXXbfnWuce2TOtnhKhb4M3yDo2qgB2QHIB2Z7Pi', 'user_UID927130_1749957133.jpg', 'aktif');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`uid_admin`),
  ADD UNIQUE KEY `usrname_admin` (`usrname_admin`),
  ADD UNIQUE KEY `email_admin` (`email_admin`);

--
-- Indeks untuk tabel `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id_brand`);

--
-- Indeks untuk tabel `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id_cart`),
  ADD KEY `uid` (`uid`),
  ADD KEY `id_ukuran` (`id_ukuran`),
  ADD KEY `cart_ibfk_2` (`id_prod`);

--
-- Indeks untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `id_pesanan` (`id_pesanan`),
  ADD KEY `id_ukuran` (`id_ukuran`),
  ADD KEY `detail_pesanan_ibfk_2` (`id_prod`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indeks untuk tabel `kode_registrasi`
--
ALTER TABLE `kode_registrasi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indeks untuk tabel `pengiriman`
--
ALTER TABLE `pengiriman`
  ADD PRIMARY KEY (`id_pengiriman`);

--
-- Indeks untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD PRIMARY KEY (`id_pesanan`),
  ADD KEY `uid` (`uid`),
  ADD KEY `pesanan_pengiriman_fk` (`id_pengiriman`);

--
-- Indeks untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD PRIMARY KEY (`id_prod`),
  ADD KEY `id_kategori` (`id_kategori`),
  ADD KEY `id_brand` (`id_brand`);

--
-- Indeks untuk tabel `produk_stok`
--
ALTER TABLE `produk_stok`
  ADD PRIMARY KEY (`id_stok`),
  ADD KEY `id_prod` (`id_prod`),
  ADD KEY `id_ukuran` (`id_ukuran`);

--
-- Indeks untuk tabel `riwayat_status`
--
ALTER TABLE `riwayat_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pesanan` (`id_pesanan`);

--
-- Indeks untuk tabel `ukuran`
--
ALTER TABLE `ukuran`
  ADD PRIMARY KEY (`id_ukuran`);

--
-- Indeks untuk tabel `ukuran_produk`
--
ALTER TABLE `ukuran_produk`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ukuran` (`id_ukuran`),
  ADD KEY `ukuran_produk_ibfk_1` (`id_prod`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `usrname` (`usrname`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `brand`
--
ALTER TABLE `brand`
  MODIFY `id_brand` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10009;

--
-- AUTO_INCREMENT untuk tabel `cart`
--
ALTER TABLE `cart`
  MODIFY `id_cart` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10014;

--
-- AUTO_INCREMENT untuk tabel `kode_registrasi`
--
ALTER TABLE `kode_registrasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pengiriman`
--
ALTER TABLE `pengiriman`
  MODIFY `id_pengiriman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `produk`
--
ALTER TABLE `produk`
  MODIFY `id_prod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45692;

--
-- AUTO_INCREMENT untuk tabel `produk_stok`
--
ALTER TABLE `produk_stok`
  MODIFY `id_stok` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT untuk tabel `riwayat_status`
--
ALTER TABLE `riwayat_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `ukuran`
--
ALTER TABLE `ukuran`
  MODIFY `id_ukuran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT untuk tabel `ukuran_produk`
--
ALTER TABLE `ukuran_produk`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`id_prod`) REFERENCES `produk` (`id_prod`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_3` FOREIGN KEY (`id_ukuran`) REFERENCES `ukuran` (`id_ukuran`);

--
-- Ketidakleluasaan untuk tabel `detail_pesanan`
--
ALTER TABLE `detail_pesanan`
  ADD CONSTRAINT `detail_pesanan_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`),
  ADD CONSTRAINT `detail_pesanan_ibfk_2` FOREIGN KEY (`id_prod`) REFERENCES `produk` (`id_prod`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_pesanan_ibfk_3` FOREIGN KEY (`id_ukuran`) REFERENCES `ukuran` (`id_ukuran`);

--
-- Ketidakleluasaan untuk tabel `pesanan`
--
ALTER TABLE `pesanan`
  ADD CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`),
  ADD CONSTRAINT `pesanan_pengiriman_fk` FOREIGN KEY (`id_pengiriman`) REFERENCES `pengiriman` (`id_pengiriman`);

--
-- Ketidakleluasaan untuk tabel `produk`
--
ALTER TABLE `produk`
  ADD CONSTRAINT `produk_ibfk_1` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`),
  ADD CONSTRAINT `produk_ibfk_2` FOREIGN KEY (`id_brand`) REFERENCES `brand` (`id_brand`);

--
-- Ketidakleluasaan untuk tabel `produk_stok`
--
ALTER TABLE `produk_stok`
  ADD CONSTRAINT `produk_stok_ibfk_1` FOREIGN KEY (`id_prod`) REFERENCES `produk` (`id_prod`) ON DELETE CASCADE,
  ADD CONSTRAINT `produk_stok_ibfk_2` FOREIGN KEY (`id_ukuran`) REFERENCES `ukuran` (`id_ukuran`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `riwayat_status`
--
ALTER TABLE `riwayat_status`
  ADD CONSTRAINT `riwayat_status_ibfk_1` FOREIGN KEY (`id_pesanan`) REFERENCES `pesanan` (`id_pesanan`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `ukuran_produk`
--
ALTER TABLE `ukuran_produk`
  ADD CONSTRAINT `ukuran_produk_ibfk_1` FOREIGN KEY (`id_prod`) REFERENCES `produk` (`id_prod`) ON DELETE CASCADE,
  ADD CONSTRAINT `ukuran_produk_ibfk_2` FOREIGN KEY (`id_ukuran`) REFERENCES `ukuran` (`id_ukuran`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
