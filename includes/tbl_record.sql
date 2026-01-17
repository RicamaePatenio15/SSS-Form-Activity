-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 16, 2026 at 07:22 PM
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
  `record_id` int(11) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `suffix` varchar(10) DEFAULT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `marital_status` enum('Single','Married','Widowed','Divorced') NOT NULL,
  `birthdate` date NOT NULL,
  `nationality` varchar(100) NOT NULL,
  `birthplace` varchar(250) NOT NULL,
  `home_address` text NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email` varchar(150) NOT NULL,
  `father_last_name` varchar(100) DEFAULT NULL,
  `father_first_name` varchar(100) DEFAULT NULL,
  `father_middle_name` varchar(100) DEFAULT NULL,
  `father_suffix` varchar(10) DEFAULT NULL,
  `mother_last_name` varchar(100) DEFAULT NULL,
  `mother_first_name` varchar(100) DEFAULT NULL,
  `mother_middle_name` varchar(100) DEFAULT NULL,
  `mother_suffix` varchar(10) DEFAULT NULL,
  `profession` varchar(200) DEFAULT NULL,
  `year_started` varchar(4) DEFAULT NULL,
  `monthly_earnings` decimal(12,2) DEFAULT NULL,
  `foreign_address` text DEFAULT NULL,
  `ofw_monthly_earnings` decimal(12,2) DEFAULT NULL,
  `flexi_fund` enum('Yes','No') DEFAULT NULL,
  `spouse_ss_number` varchar(20) DEFAULT NULL,
  `spouse_income` decimal(12,2) DEFAULT NULL,
  `printed_name` varchar(200) DEFAULT NULL,
  `signature` varchar(200) DEFAULT NULL,
  `cert_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_dependents`
--

CREATE TABLE `tbl_dependents` (
  `dependent_id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `dependent_name` varchar(200) NOT NULL,
  `relationship` varchar(100) NOT NULL,
  `dependent_dob` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_record`
--
ALTER TABLE `tbl_record`
  ADD PRIMARY KEY (`record_id`);

--
-- Indexes for table `tbl_dependents`
--
ALTER TABLE `tbl_dependents`
  ADD PRIMARY KEY (`dependent_id`),
  ADD KEY `record_id` (`record_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_record`
--
ALTER TABLE `tbl_record`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_dependents`
--
ALTER TABLE `tbl_dependents`
  MODIFY `dependent_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_dependents`
--
ALTER TABLE `tbl_dependents`
  ADD CONSTRAINT `tbl_dependents_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `tbl_record` (`record_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;