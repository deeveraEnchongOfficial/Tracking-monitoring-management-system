-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 06, 2020 at 10:03 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.2.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tracking_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `establishments`
--

CREATE TABLE `establishments` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `establishments`
--

INSERT INTO `establishments` (`id`, `name`, `address`) VALUES
(1, 'Sample Mall', 'Sample Only'),
(2, 'Sample Mall 2', 'Sample Address 2');

-- --------------------------------------------------------

--
-- Table structure for table `persons`
--

CREATE TABLE `persons` (
  `id` int(30) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `tracking_id` text NOT NULL,
  `address` text NOT NULL,
  `street` text NOT NULL,
  `baranggay` text NOT NULL,
  `city` text NOT NULL,
  `state` text NOT NULL,
  `zip_code` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `persons`
--

INSERT INTO `persons` (`id`, `firstname`, `middlename`, `lastname`, `tracking_id`, `address`, `street`, `baranggay`, `city`, `state`, `zip_code`) VALUES
(1, 'John', 'C', 'Smith', '2101911383', 'My Home', 'Here St.', 'Barangay', 'Manila', 'Metro Manila', '7100'),
(2, 'George', 'D', 'Wilson', '0315270880', 'G/F Campbridge Building1370 ', 'Jose Abad Santos Avenue 1012', '', 'Manila', 'Manila', '7100');

-- --------------------------------------------------------

--
-- Table structure for table `person_tracks`
--

CREATE TABLE `person_tracks` (
  `id` int(30) NOT NULL,
  `person_id` int(30) NOT NULL,
  `establishment_id` int(30) NOT NULL,
  `temperature` varchar(10) NOT NULL,
  `transaction` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `person_tracks`
--

INSERT INTO `person_tracks` (`id`, `person_id`, `establishment_id`, `temperature`, `transaction`, `date_created`) VALUES
(1, 1, 1, '36.7', '', '2020-10-06 14:59:22'),
(2, 1, 2, '38.6', '', '2020-10-06 15:46:21'),
(3, 2, 1, '36.6', '', '2020-10-06 15:46:41'),
(4, 2, 2, '36.6', '', '2020-10-06 16:00:32');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT 2 COMMENT '1 = Admin, 2= establishment_staff',
  `username` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `establishment_id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `type`, `username`, `password`, `establishment_id`) VALUES
(1, 'Administrator', 1, 'admin', '0192023a7bbd73250516f069df18b500', 0),
(2, 'Staff User', 2, 'staff', '1253208465b1efa876f982d8a9e73eef', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `establishments`
--
ALTER TABLE `establishments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `persons`
--
ALTER TABLE `persons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `person_tracks`
--
ALTER TABLE `person_tracks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `establishments`
--
ALTER TABLE `establishments`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `persons`
--
ALTER TABLE `persons`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `person_tracks`
--
ALTER TABLE `person_tracks`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
