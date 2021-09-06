-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 150.254.5.30:3306
-- Generation Time: Sep 06, 2021 at 09:11 PM
-- Server version: 10.5.9-MariaDB-log
-- PHP Version: 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_146939`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `city_id` int(11) NOT NULL,
  `city_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `days_week`
--

CREATE TABLE `days_week` (
  `day_id` int(11) NOT NULL,
  `day_name` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `free_time`
--

CREATE TABLE `free_time` (
  `free_time_id` int(11) NOT NULL,
  `date_time_start` datetime NOT NULL,
  `date_time_end` datetime NOT NULL,
  `all_day` tinyint(1) NOT NULL,
  `service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `providers`
--

CREATE TABLE `providers` (
  `provider_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` mediumtext NOT NULL,
  `email` varchar(100) NOT NULL,
  `provider_firstname` varchar(100) NOT NULL,
  `provider_lastname` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `rating_id` int(11) NOT NULL,
  `value` tinyint(4) NOT NULL,
  `reserved_service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `reserved_services`
--

CREATE TABLE `reserved_services` (
  `reserved_service_id` int(11) NOT NULL,
  `client_firstname` varchar(50) NOT NULL,
  `client_lastname` varchar(50) NOT NULL,
  `client_email` varchar(50) NOT NULL,
  `client_phone_number` int(9) NOT NULL,
  `date_time` datetime NOT NULL,
  `realised` tinyint(1) NOT NULL DEFAULT 0,
  `canceled` tinyint(1) NOT NULL DEFAULT 0,
  `date_time_canceled` datetime DEFAULT NULL,
  `sub_service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `service_name` varchar(50) NOT NULL,
  `service_description` varchar(500) NOT NULL,
  `address` varchar(50) NOT NULL,
  `city_id` int(11) NOT NULL,
  `service_email` varchar(50) NOT NULL,
  `phone_number` int(9) NOT NULL,
  `category_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `subservices`
--

CREATE TABLE `subservices` (
  `sub_service_id` int(11) NOT NULL,
  `sub_service_name` varchar(50) NOT NULL,
  `sub_service_description` varchar(500) NOT NULL,
  `price` float NOT NULL,
  `duration` tinyint(4) NOT NULL,
  `service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `work_time`
--

CREATE TABLE `work_time` (
  `work_time_id` int(11) NOT NULL,
  `day_id` int(11) NOT NULL,
  `time_start` time NOT NULL,
  `time_end` time NOT NULL,
  `service_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`city_id`);

--
-- Indexes for table `days_week`
--
ALTER TABLE `days_week`
  ADD PRIMARY KEY (`day_id`);

--
-- Indexes for table `free_time`
--
ALTER TABLE `free_time`
  ADD PRIMARY KEY (`free_time_id`);

--
-- Indexes for table `providers`
--
ALTER TABLE `providers`
  ADD PRIMARY KEY (`provider_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`rating_id`);

--
-- Indexes for table `reserved_services`
--
ALTER TABLE `reserved_services`
  ADD PRIMARY KEY (`reserved_service_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`service_id`);

--
-- Indexes for table `subservices`
--
ALTER TABLE `subservices`
  ADD PRIMARY KEY (`sub_service_id`);

--
-- Indexes for table `work_time`
--
ALTER TABLE `work_time`
  ADD PRIMARY KEY (`work_time_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `days_week`
--
ALTER TABLE `days_week`
  MODIFY `day_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `free_time`
--
ALTER TABLE `free_time`
  MODIFY `free_time_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `providers`
--
ALTER TABLE `providers`
  MODIFY `provider_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reserved_services`
--
ALTER TABLE `reserved_services`
  MODIFY `reserved_service_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `service_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subservices`
--
ALTER TABLE `subservices`
  MODIFY `sub_service_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `work_time`
--
ALTER TABLE `work_time`
  MODIFY `work_time_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
