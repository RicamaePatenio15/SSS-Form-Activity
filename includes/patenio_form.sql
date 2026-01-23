-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 23, 2026 at 01:38 PM
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
-- Database: `patenio_form`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_record`
--

CREATE TABLE `tbl_record` (
  `id` int(11) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `suffix` varchar(20) DEFAULT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `marital_status` enum('Single','Married','Widowed','Divorced') NOT NULL,
  `birthdate` date NOT NULL,
  `nationality` varchar(50) NOT NULL,
  `birthplace` varchar(255) NOT NULL,
  `home_address` text NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_record`
--

INSERT INTO `tbl_record` (`id`, `last_name`, `first_name`, `middle_name`, `suffix`, `gender`, `marital_status`, `birthdate`, `nationality`, `birthplace`, `home_address`, `phone_number`, `email`, `created_at`) VALUES
(1, 'BAS', 'EMMAN', 'ABENDAN', 'JR', 'Male', 'Single', '2025-12-03', 'PINOY', 'MINGLA', '', '09318475523', 'emman@gmail.com', '2026-01-23 12:19:01'),
(2, 'RICA', 'PATENIO', 'BAS', 'DSAD', 'Male', 'Married', '2026-01-10', 'PINAY', '', '', '09993118452', 'rica@gmail.com', '2026-01-23 12:28:39'),
(3, 'Q', 'Q', 'Q', '', 'Female', 'Single', '2026-01-07', 'DASDASD', '', '', '09121231234', 'oh@gmail.com', '2026-01-23 12:30:56'),
(4, 'ACE', 'CAB', 'RE', '', 'Male', 'Married', '2025-01-08', 'RRWER', 'REWRWR', '', '09318475523', 'AXCE@GMAIL.COM', '2026-01-23 12:37:30');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_record`
--
ALTER TABLE `tbl_record`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_record`
--
ALTER TABLE `tbl_record`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
