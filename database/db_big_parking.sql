-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2023 at 03:37 AM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_big_parking`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_biaya_parkir`
--

CREATE TABLE `tb_biaya_parkir` (
  `id_biaya` varchar(10) NOT NULL,
  `jenis_kendaraan` varchar(50) NOT NULL,
  `biaya_perjam` int(20) NOT NULL,
  `biaya_maksimal` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_biaya_parkir`
--

INSERT INTO `tb_biaya_parkir` (`id_biaya`, `jenis_kendaraan`, `biaya_perjam`, `biaya_maksimal`) VALUES
('2814657631', 'Mobil Penumpang', 2000, 6000),
('7451783359', 'Sepeda Motor', 1000, 3000),
('8146573659', 'Mobil Barang', 3000, 9000);

-- --------------------------------------------------------

--
-- Table structure for table `tb_jenis_kendaraan`
--

CREATE TABLE `tb_jenis_kendaraan` (
  `id_jenis_kendaraan` varchar(10) NOT NULL,
  `jenis_kendaraan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_jenis_kendaraan`
--

INSERT INTO `tb_jenis_kendaraan` (`id_jenis_kendaraan`, `jenis_kendaraan`) VALUES
('2166274281', 'Mobil Penumpang'),
('5983548291', 'Sepeda Motor'),
('6321549597', 'Mobil Barang');

-- --------------------------------------------------------

--
-- Table structure for table `tb_parkir`
--

CREATE TABLE `tb_parkir` (
  `id_parkir` varchar(10) NOT NULL,
  `no_polisi` varchar(15) NOT NULL,
  `jenis_kendaraan` varchar(50) NOT NULL,
  `masuk` datetime NOT NULL,
  `keluar` datetime DEFAULT NULL,
  `durasi` varchar(20) DEFAULT NULL,
  `biaya` int(20) DEFAULT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_parkir`
--

INSERT INTO `tb_parkir` (`id_parkir`, `no_polisi`, `jenis_kendaraan`, `masuk`, `keluar`, `durasi`, `biaya`, `status`) VALUES
('286754', 'B 2415 VHX', 'Sepeda Motor', '2022-03-10 00:20:23', '2022-03-10 01:49:35', '01:29', 2000, 'Keluar'),
('295533', 'A 2412 VHX', 'Sepeda Motor', '2023-03-10 00:32:39', '2023-03-10 07:47:07', '07:14', 8000, 'Keluar'),
('346822', 'B 4587 IIS', 'Mobil Penumpang', '2023-03-10 08:41:24', '2023-03-10 09:19:57', '00:38', 2000, 'Keluar'),
('385442', 'A 0000 GJH', 'Sepeda Motor', '2023-03-05 07:59:00', '2023-03-10 07:52:31', '119:53', 36000, 'Keluar'),
('562243', 'A 2809 MH', 'Sepeda Motor', '2023-03-09 15:16:10', '2023-03-10 02:13:28', '10:57', 11000, 'Keluar'),
('566582', 'A 1111 BHG', 'Sepeda Motor', '2023-03-08 23:12:55', '2023-03-09 02:22:58', '03:10', 4000, 'Keluar'),
('577895', 'B 1665 GUS', 'Mobil Barang', '2023-03-09 23:32:56', '2023-03-10 07:47:21', '08:14', 27000, 'Keluar'),
('598252', 'D 3456 TYU', 'Sepeda Motor', '2023-03-10 08:28:29', NULL, NULL, NULL, 'Masuk'),
('665475', 'B 5447 JKL', 'Mobil Penumpang', '2023-03-10 02:27:09', '2023-03-10 02:28:15', '00:01', 2000, 'Keluar'),
('672828', 'A 5487 HHB', 'Sepeda Motor', '2023-03-08 22:46:15', '2023-03-10 02:08:15', '27:22', 7000, 'Keluar'),
('756569', 'A 2458 GHJ', 'Mobil Barang', '2023-03-10 02:25:51', '2023-03-10 02:30:19', '00:04', 3000, 'Keluar'),
('884545', 'B 7777 GUS', 'Sepeda Motor', '2023-03-08 22:50:27', '2023-03-10 02:10:53', '27:20', 7000, 'Keluar'),
('896798', 'B 5487 VHB', 'Sepeda Motor', '2023-03-10 00:42:30', '2023-03-10 01:54:43', '01:12', 2000, 'Keluar'),
('925746', 'Z 1234 FGH', 'Mobil Barang', '2023-03-09 23:23:07', '2023-03-10 02:24:04', '03:00', 9000, 'Keluar'),
('984326', 'A 6541 VFG', 'Sepeda Motor', '2023-03-08 22:48:42', '2023-03-10 02:09:52', '27:21', 7000, 'Keluar'),
('986364', 'A 2412 VHX', 'Sepeda Motor', '2023-03-10 01:40:06', '2023-03-10 02:25:10', '00:45', 1000, 'Keluar');

-- --------------------------------------------------------

--
-- Table structure for table `tb_petugas`
--

CREATE TABLE `tb_petugas` (
  `id_petugas` varchar(10) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(30) NOT NULL,
  `tgl_daftar` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_petugas`
--

INSERT INTO `tb_petugas` (`id_petugas`, `nama_lengkap`, `username`, `password`, `tgl_daftar`) VALUES
('1914738678', 'Robby Isnaini', 'robby', 'aWlzMTIz', '2023-03-08 14:14:11'),
('8891515823', 'Setia Budi', 'budi', 'YnVkaTEyMw==', '2023-03-08 14:14:52'),
('9391524513', 'Muhamad Hendra', 'admin', 'YWRtaW4xMjM=', '2023-03-08 14:13:46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_biaya_parkir`
--
ALTER TABLE `tb_biaya_parkir`
  ADD PRIMARY KEY (`id_biaya`);

--
-- Indexes for table `tb_jenis_kendaraan`
--
ALTER TABLE `tb_jenis_kendaraan`
  ADD PRIMARY KEY (`id_jenis_kendaraan`);

--
-- Indexes for table `tb_parkir`
--
ALTER TABLE `tb_parkir`
  ADD PRIMARY KEY (`id_parkir`);

--
-- Indexes for table `tb_petugas`
--
ALTER TABLE `tb_petugas`
  ADD PRIMARY KEY (`id_petugas`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
