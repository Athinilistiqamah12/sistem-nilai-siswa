-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2026 at 03:17 PM
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
-- Database: `sekolah`
--

-- --------------------------------------------------------

--
-- Table structure for table `logging`
--

CREATE TABLE `logging` (
  `ID_LOG` int(11) NOT NULL,
  `KUNJUNGAN` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logging`
--

INSERT INTO `logging` (`ID_LOG`, `KUNJUNGAN`) VALUES
(1, 'kunjung'),
(2, 'kunjung'),
(3, 'kunjung');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `ID_USER` int(11) NOT NULL,
  `USERNAME` varchar(50) NOT NULL,
  `PASSWORD` varchar(255) NOT NULL,
  `TIPE` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`ID_USER`, `USERNAME`, `PASSWORD`, `TIPE`) VALUES
(1, 'guru', '$2y$10$6vNhR/ob.3zFpnmlpk3uLeE8wjJnNCo7Sc/UI5BcRcqu3BXUn4RLK', 1),
(2, 'siswa', '$2y$10$Vsng0LJ.XDi22Cnkc7Clg.1Kz22CMCy5w9kjaIUBgo.SLim9obgB.', 2);

-- --------------------------------------------------------

--
-- Table structure for table `nilai`
--

CREATE TABLE `nilai` (
  `ID_NILAI` int(11) NOT NULL,
  `ID_SISWA` varchar(20) NOT NULL,
  `matematika` int(3) NOT NULL DEFAULT 0,
  `bahasa_indonesia` int(3) NOT NULL DEFAULT 0,
  `bahasa_inggris` int(3) NOT NULL DEFAULT 0,
  `teori_kejuruan` int(3) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nilai`
--

INSERT INTO `nilai` (`ID_NILAI`, `ID_SISWA`, `matematika`, `bahasa_indonesia`, `bahasa_inggris`, `teori_kejuruan`) VALUES
(1, '2024001', 85, 88, 80, 90),
(2, '2024002', 92, 90, 88, 95),
(3, '2024003', 70, 75, 68, 80),
(4, '2024004', 78, 82, 85, 84),
(5, '2024005', 88, 79, 90, 92),
(6, '2024006', 78, 88, 89, 90),
(7, '2024007', 65, 70, 72, 75),
(8, '2024008', 82, 86, 84, 88),
(9, '2024009', 69, 40, 90, 90),
(10, '2024010', 90, 87, 93, 89),
(11, '2024011', 68, 72, 70, 78),
(12, '2024012', 86, 91, 89, 94),
(16, '204527', 80, 87, 90, 70);

-- --------------------------------------------------------

--
-- Table structure for table `siswa`
--

CREATE TABLE `siswa` (
  `ID_SISWA` varchar(20) NOT NULL,
  `NAMA_SISWA` varchar(100) NOT NULL,
  `KELAS` varchar(20) NOT NULL,
  `FOTO` varchar(255) NOT NULL DEFAULT '',
  `EMAIL` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `siswa`
--

INSERT INTO `siswa` (`ID_SISWA`, `NAMA_SISWA`, `KELAS`, `FOTO`, `EMAIL`) VALUES
('2024001', 'Ahmad Fauzi', 'XII RPL 1', '', 'ahmad@gmail.com'),
('2024002', 'Siti Nurhaliza', 'XII RPL 1', '', ''),
('2024003', 'Budi Santoso', 'XII RPL 1', '', ''),
('2024004', 'Dewi Lestari', 'XII RPL 1', '', ''),
('2024005', 'Rizky Ramadhan', 'XII RPL 2', '', ''),
('2024006', 'Anisa Putri', 'XII RPL 2', '', ''),
('2024007', 'Dimas Prasetyo', 'XII RPL 2', '', ''),
('2024008', 'Putri Maharani', 'XII RPL 2', '', ''),
('2024009', 'Fajar Nugroho', 'XII RPL 3', '', ''),
('2024010', 'Lestari Wulandari', 'XII RPL 3', '', ''),
('2024011', 'Eko Wijaya', 'XII RPL 3', '', ''),
('2024012', 'Nadia Safitri', 'XII RPL 3', '', ''),
('204527', 'harisa', 'XII RPL 1', '', 'joko98864@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `logging`
--
ALTER TABLE `logging`
  ADD PRIMARY KEY (`ID_LOG`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`ID_USER`),
  ADD UNIQUE KEY `USERNAME` (`USERNAME`);

--
-- Indexes for table `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`ID_NILAI`),
  ADD UNIQUE KEY `ID_SISWA` (`ID_SISWA`);

--
-- Indexes for table `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`ID_SISWA`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `logging`
--
ALTER TABLE `logging`
  MODIFY `ID_LOG` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `ID_USER` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `nilai`
--
ALTER TABLE `nilai`
  MODIFY `ID_NILAI` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
