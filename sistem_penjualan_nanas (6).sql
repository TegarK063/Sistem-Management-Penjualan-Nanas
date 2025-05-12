-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 04, 2024 at 05:22 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sistem_penjualan_nanas`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_admin`
--

CREATE TABLE `tb_admin` (
  `id_user` int NOT NULL,
  `nama` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `username` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `level` int DEFAULT NULL,
  `nohp` varchar(15) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_admin`
--

INSERT INTO `tb_admin` (`id_user`, `nama`, `username`, `password`, `level`, `nohp`, `alamat`) VALUES
(1, 'Apalah', 'admin@gmail.com', 'admin', 1, '00009912312', 'mars Men'),
(2, 'Yantok kopling', 'kasir@gmail.com', 'kasir', 2, '123312444', 'Subang'),
(12, 'Tegar Kusumaa', 'tegark63@gmail.com', '12345', 3, '4324', 'apalah\r\n'),
(18, 'luthfi', 'luthfi@gmail.com', '12345', 3, '0897622', NULL),
(19, 'luthfi2', 'luthfi2@gmail.com', '123', 3, '0878672846', NULL),
(20, 'Molaritas', 'Molla@gmail.com', '12345678', 3, '082345637887', NULL),
(21, 'luthfi10', 'luthfi10@gmail.com', '12345', 3, '08967368974', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_cart`
--

CREATE TABLE `tb_cart` (
  `id_cart` int NOT NULL,
  `id_user` int NOT NULL,
  `id` int DEFAULT NULL,
  `nama_menu` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah` int NOT NULL,
  `harga` int DEFAULT NULL,
  `total_harga` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_daftar_menu`
--

CREATE TABLE `tb_daftar_menu` (
  `id` int NOT NULL,
  `foto` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_menu` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `keterangan` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kategori` int DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `stok` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_daftar_menu`
--

INSERT INTO `tb_daftar_menu` (`id`, `foto`, `nama_menu`, `keterangan`, `kategori`, `harga`, `stok`) VALUES
(18, '86811-Nanas.jpg', 'Nanas Muda', 'Muda Banget', 1, 20000, 8),
(19, '27832-brd-59261_nanas-muda_full01.jpg', 'Nanas Hitam Jawa', 'Manis Banget', 1, 20000, 2),
(20, '25944-Hama-Tanaman-Nanas-yang-Perlu-Diwaspadai.jpg', 'Nanas Hitam Legam', 'huiii Hiotam nyo', 1, 30000, 2),
(21, '12334-image-85.png', 'Selai Nanas', 'MAnis PUOL', 2, 30000, 1),
(22, '77433-keripik-nanas.jpg', 'Kripik Nanas', 'anajy kripik cuy', 4, 30000, 0),
(23, '13554-ilustrasi-jus-nanas_43.jpeg', 'Jas Jus Nanas', 'Manis Seger', 3, 10000, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_detail_pesanan`
--

CREATE TABLE `tb_detail_pesanan` (
  `id_detail_pesanan` int NOT NULL,
  `id_pesanan` int NOT NULL,
  `id_user` int NOT NULL,
  `id` int DEFAULT NULL,
  `nama_menu` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jumlah` int NOT NULL,
  `harga` int DEFAULT NULL,
  `total_harga` int DEFAULT NULL,
  `status_pembayaran` varchar(200) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Menunggu Pembayaran'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_kategori`
--

CREATE TABLE `tb_kategori` (
  `id_kategori` int NOT NULL,
  `jenis_menu` int DEFAULT NULL,
  `kategori_menu` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kategori`
--

INSERT INTO `tb_kategori` (`id_kategori`, `jenis_menu`, `kategori_menu`) VALUES
(1, 1, 'Buah'),
(2, 2, 'Selai'),
(3, 2, 'Jus'),
(4, 2, 'Kripik');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pesanan`
--

CREATE TABLE `tb_pesanan` (
  `id_pesanan` int NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `waktu_sampai` datetime DEFAULT NULL,
  `id_user` int NOT NULL,
  `email` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `nohp` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_penerima` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat_tujuan` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `metode_bayar` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `total` int NOT NULL,
  `status_pembayaran` varchar(200) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_transaksi`
--

CREATE TABLE `tb_transaksi` (
  `id_transaksi` int NOT NULL,
  `id_pesanan` int NOT NULL,
  `time_pesan` datetime NOT NULL,
  `time_sampai` datetime DEFAULT NULL,
  `id_user` int NOT NULL,
  `email` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `nama` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `nohp` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_penerima` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat_tujuan` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `metode_bayar` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `total` int NOT NULL,
  `status_pembayaran` varchar(200) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_admin`
--
ALTER TABLE `tb_admin`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `tb_cart`
--
ALTER TABLE `tb_cart`
  ADD PRIMARY KEY (`id_cart`);

--
-- Indexes for table `tb_daftar_menu`
--
ALTER TABLE `tb_daftar_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Fk1` (`kategori`);

--
-- Indexes for table `tb_detail_pesanan`
--
ALTER TABLE `tb_detail_pesanan`
  ADD PRIMARY KEY (`id_detail_pesanan`);

--
-- Indexes for table `tb_kategori`
--
ALTER TABLE `tb_kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `tb_pesanan`
--
ALTER TABLE `tb_pesanan`
  ADD PRIMARY KEY (`id_pesanan`);

--
-- Indexes for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  ADD PRIMARY KEY (`id_transaksi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_admin`
--
ALTER TABLE `tb_admin`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tb_cart`
--
ALTER TABLE `tb_cart`
  MODIFY `id_cart` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT for table `tb_daftar_menu`
--
ALTER TABLE `tb_daftar_menu`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `tb_detail_pesanan`
--
ALTER TABLE `tb_detail_pesanan`
  MODIFY `id_detail_pesanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- AUTO_INCREMENT for table `tb_kategori`
--
ALTER TABLE `tb_kategori`
  MODIFY `id_kategori` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tb_pesanan`
--
ALTER TABLE `tb_pesanan`
  MODIFY `id_pesanan` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `tb_transaksi`
--
ALTER TABLE `tb_transaksi`
  MODIFY `id_transaksi` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_daftar_menu`
--
ALTER TABLE `tb_daftar_menu`
  ADD CONSTRAINT `Fk1` FOREIGN KEY (`kategori`) REFERENCES `tb_kategori` (`id_kategori`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
