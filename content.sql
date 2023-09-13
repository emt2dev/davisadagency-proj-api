-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 13, 2023 at 05:24 AM
-- Server version: 8.0.34-0ubuntu0.22.04.1
-- PHP Version: 8.1.2-1ubuntu2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vanapi`
--

-- --------------------------------------------------------

--
-- Table structure for table `content`
--

CREATE TABLE `content` (
  `id` int NOT NULL,
  `title` varchar(50) NOT NULL,
  `body` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `keywords` varchar(50) NOT NULL,
  `author` varchar(50) NOT NULL,
  `filepath1` varchar(255) NOT NULL,
  `filepath2` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `content`
--

INSERT INTO `content` (`id`, `title`, `body`, `keywords`, `author`, `filepath1`, `filepath2`) VALUES
(7, 'Virginia Beach Becomes Tech Friendly', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris congue augue eget tristique efficitur. Maecenas ornare nisl sagittis massa posuere, ut commodo lacus scelerisque. Aliquam pellentesque in augue tincidunt mollis. Etiam vitae vehicula magna. Donec fringilla ligula at lectus fermentum, et venenatis lacus eleifend. Etiam mauris justo, consectetur a. ', 'virginia,tech,modern,', 'David Duron', '/vanapi/uploads/testfile.png', '../uploads/testfile,png'),
(8, 'Ocean Divers Find Friends', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris congue augue eget tristique efficitur. Maecenas ornare nisl sagittis massa posuere, ut commodo lacus scelerisque. Aliquam pellentesque in augue tincidunt mollis. Etiam vitae vehicula magna. Donec fringilla ligula at lectus fermentum, et venenatis lacus eleifend. Etiam mauris justo, consectetur a. ', 'virginia,diving,ocean,', 'David Duron', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `content`
--
ALTER TABLE `content`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
