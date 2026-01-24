-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2026 at 04:31 AM
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
-- Table structure for table `tbl_certification`
--

CREATE TABLE `tbl_certification` (
  `id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `printed_name` varchar(255) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `cert_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_certification`
--

INSERT INTO `tbl_certification` (`id`, `record_id`, `printed_name`, `signature`, `cert_date`) VALUES
(1, 1, '', '', '2026-01-24');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_dependents`
--

CREATE TABLE `tbl_dependents` (
  `id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `dep_name` varchar(255) DEFAULT NULL,
  `relationship` varchar(100) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_dependents`
--

INSERT INTO `tbl_dependents` (`id`, `record_id`, `dep_name`, `relationship`, `date_of_birth`) VALUES
(1, 1, 'JOHN DAVID PATENIO', 'BROTHER', '0006-08-26');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_employment_info`
--

CREATE TABLE `tbl_employment_info` (
  `id` int(11) NOT NULL,
  `record_id` int(11) NOT NULL,
  `profession` varchar(255) DEFAULT NULL,
  `year_started` varchar(10) DEFAULT NULL,
  `se_monthly_earnings` decimal(10,2) DEFAULT NULL,
  `foreign_address` varchar(255) DEFAULT NULL,
  `ofw_monthly_earnings` decimal(10,2) DEFAULT NULL,
  `spouse_ss_number` varchar(20) DEFAULT NULL,
  `spouse_income` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_employment_info`
--

INSERT INTO `tbl_employment_info` (`id`, `record_id`, `profession`, `year_started`, `se_monthly_earnings`, `foreign_address`, `ofw_monthly_earnings`, `spouse_ss_number`, `spouse_income`) VALUES
(1, 1, 'CANDLE BUSINESS', '2010', 15000.00, '0', 0.00, '', 0.00);

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `father_last_name` varchar(100) DEFAULT NULL,
  `father_first_name` varchar(100) DEFAULT NULL,
  `father_middle_name` varchar(100) DEFAULT NULL,
  `father_suffix` varchar(20) DEFAULT NULL,
  `mother_last_name` varchar(100) DEFAULT NULL,
  `mother_first_name` varchar(100) DEFAULT NULL,
  `mother_middle_name` varchar(100) DEFAULT NULL,
  `mother_suffix` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_record`
--

INSERT INTO `tbl_record` (`id`, `last_name`, `first_name`, `middle_name`, `suffix`, `gender`, `marital_status`, `birthdate`, `nationality`, `birthplace`, `home_address`, `phone_number`, `email`, `created_at`, `father_last_name`, `father_first_name`, `father_middle_name`, `father_suffix`, `mother_last_name`, `mother_first_name`, `mother_middle_name`, `mother_suffix`) VALUES
(1, 'PATENIO', 'RICA MAE', 'BADAYOS', '', 'Female', 'Single', '2004-11-15', 'FILIPINO', 'SITIO SANGI', 'SITIO SANGI', '09982060487', 'rcmpatenio@gmail.com', '2026-01-23 19:02:01', 'PATENIO', 'ENRIQUE', 'BASALE', '', 'BADAYOS', 'AMY', 'CANADA', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_certification`
--
ALTER TABLE `tbl_certification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `record_id` (`record_id`);

--
-- Indexes for table `tbl_dependents`
--
ALTER TABLE `tbl_dependents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `record_id` (`record_id`);

--
-- Indexes for table `tbl_employment_info`
--
ALTER TABLE `tbl_employment_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `record_id` (`record_id`);

--
-- Indexes for table `tbl_record`
--
ALTER TABLE `tbl_record`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_certification`
--
ALTER TABLE `tbl_certification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_dependents`
--
ALTER TABLE `tbl_dependents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_employment_info`
--
ALTER TABLE `tbl_employment_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_record`
--
ALTER TABLE `tbl_record`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_certification`
--
ALTER TABLE `tbl_certification`
  ADD CONSTRAINT `tbl_certification_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `tbl_record` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_dependents`
--
ALTER TABLE `tbl_dependents`
  ADD CONSTRAINT `tbl_dependents_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `tbl_record` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tbl_employment_info`
--
ALTER TABLE `tbl_employment_info`
  ADD CONSTRAINT `tbl_employment_info_ibfk_1` FOREIGN KEY (`record_id`) REFERENCES `tbl_record` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
