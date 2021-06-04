-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2021 at 05:58 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kyd`
--

-- --------------------------------------------------------

--
-- Table structure for table `datafile`
--

CREATE TABLE `datafile` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(25) NOT NULL,
  `ext` varchar(6) DEFAULT NULL,
  `size` int(10) UNSIGNED DEFAULT NULL,
  `ofVideo` int(10) UNSIGNED NOT NULL,
  `ofUser` int(10) UNSIGNED NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `device`
--

CREATE TABLE `device` (
  `id` int(6) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `focallength` double NOT NULL,
  `ofUser` int(6) UNSIGNED DEFAULT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(6) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(30) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `videofile`
--

CREATE TABLE `videofile` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(25) NOT NULL,
  `ext` varchar(6) DEFAULT NULL,
  `size` int(10) UNSIGNED DEFAULT NULL,
  `fromDevice` int(10) UNSIGNED DEFAULT NULL,
  `ofUser` int(10) UNSIGNED NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `datafile`
--
ALTER TABLE `datafile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_DataVideo` (`ofVideo`),
  ADD KEY `FK_DataUser` (`ofUser`);

--
-- Indexes for table `device`
--
ALTER TABLE `device`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_UserDevice` (`ofUser`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `videofile`
--
ALTER TABLE `videofile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_VideoDevice` (`fromDevice`),
  ADD KEY `FK_VideoUser` (`ofUser`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `datafile`
--
ALTER TABLE `datafile`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `device`
--
ALTER TABLE `device`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `videofile`
--
ALTER TABLE `videofile`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `datafile`
--
ALTER TABLE `datafile`
  ADD CONSTRAINT `FK_DataUser` FOREIGN KEY (`ofUser`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_DataVideo` FOREIGN KEY (`ofVideo`) REFERENCES `videofile` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `device`
--
ALTER TABLE `device`
  ADD CONSTRAINT `FK_UserDevice` FOREIGN KEY (`ofUser`) REFERENCES `users` (`id`);

--
-- Constraints for table `videofile`
--
ALTER TABLE `videofile`
  ADD CONSTRAINT `FK_VideoDevice` FOREIGN KEY (`fromDevice`) REFERENCES `device` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `FK_VideoUser` FOREIGN KEY (`ofUser`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
