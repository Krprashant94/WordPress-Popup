-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 12, 2018 at 06:40 PM
-- Server version: 5.6.41-84.1-log
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mlendyx8_mlendz`
--

-- --------------------------------------------------------

--
-- Table structure for table `mlendz_spa_location`
--

CREATE TABLE `mlendz_spa_location` (
  `location` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mlendz_spa_question_answer`
--

CREATE TABLE `mlendz_spa_question_answer` (
  `tree` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_location` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `option_list` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `last` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `mlendz_spa_setting`
--

CREATE TABLE `mlendz_spa_setting` (
  `id` tinyint(4) NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_pages` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mlendz_spa_user_data`
--

CREATE TABLE `mlendz_spa_user_data` (
  `id` int(11) NOT NULL,
  `time_now` bigint(20) NOT NULL,
  `user_post` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mlendz_spa_location`
--
ALTER TABLE `mlendz_spa_location`
  ADD PRIMARY KEY (`location`);

--
-- Indexes for table `mlendz_spa_question_answer`
--
ALTER TABLE `mlendz_spa_question_answer`
  ADD PRIMARY KEY (`tree`);

--
-- Indexes for table `mlendz_spa_setting`
--
ALTER TABLE `mlendz_spa_setting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mlendz_spa_user_data`
--
ALTER TABLE `mlendz_spa_user_data`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mlendz_spa_user_data`
--
ALTER TABLE `mlendz_spa_user_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
