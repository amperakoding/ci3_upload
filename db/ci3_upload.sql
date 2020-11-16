-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 16, 2020 at 08:14 AM
-- Server version: 8.0.22-0ubuntu0.20.04.2
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ci3_upload`
--

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id_berita` int NOT NULL,
  `judul_berita` varchar(250) DEFAULT NULL,
  `deskripsi_berita` text,
  `file_foto` text,
  `file_foto_thumb` text,
  `file_foto_size` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `berita`
--

INSERT INTO `berita` (`id_berita`, `judul_berita`, `deskripsi_berita`, `file_foto`, `file_foto_thumb`, `file_foto_size`) VALUES
(3, 'thumb lagi', 'thumb lagithumb lagi', 'thumb-lagi20201116080931.jpg', 'thumb-lagi20201116080931_thumb.jpg', 121),
(4, 'thumb lagithumb lagi', 'thumb lagithumb lagithumb lagithumb lagi', 'thumb-lagithumb-lagi20201116080942.jpg', 'thumb-lagithumb-lagi20201116080942_thumb.jpg', 232),
(5, 'thumb lagi11', 'thumb lagi1111', 'thumb-lagi1120201116080607.jpg', 'thumb-lagi1120201116080607_thumb.jpg', 123);

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` int NOT NULL,
  `judul_buku` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `penulis_buku` varchar(100) DEFAULT NULL,
  `file_foto` text,
  `file_foto_ext` char(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `file_foto_size` char(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `judul_buku`, `penulis_buku`, `file_foto`, `file_foto_ext`, `file_foto_size`) VALUES
(2, 'What is Lorem Ipsum?', 'Si Ipsum', 'what-is-lorem-ipsum20201116070009.jpg', '.jpg', '27.85'),
(3, 'Meneketehe', 'Hektem', 'meneketehe20201116070022.jpg', '.jpg', '25.59'),
(4, 'Why do we use it?', 'Si Use It', 'why-do-we-use-it20201116070054.jpg', '.jpg', '21.85');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id_berita`);

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id_berita` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
