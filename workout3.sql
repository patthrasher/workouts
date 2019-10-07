-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Sep 24, 2019 at 08:16 PM
-- Server version: 5.7.26
-- PHP Version: 7.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `workout3`
--

-- --------------------------------------------------------

--
-- Table structure for table `Cardio`
--

CREATE TABLE `Cardio` (
  `cardio_id` int(11) NOT NULL,
  `workout_id` int(11) NOT NULL,
  `cardio_movement` varchar(50) DEFAULT NULL,
  `minutes` int(11) DEFAULT NULL,
  `intensity` text,
  `rank` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Cardio`
--

INSERT INTO `Cardio` (`cardio_id`, `workout_id`, `cardio_movement`, `minutes`, `intensity`, `rank`) VALUES
(68, 141, 'lunge jumps', 1, 'medium', 1),
(69, 141, 'jump squats', 1, 'medium', 2),
(116, 145, 'onecadioedit', 8, 'a', 1),
(117, 145, 'two cardio no replace hopefully ', 2, 'asdf', 2),
(118, 145, 'threeeeeee', 4, 'd', 3);

-- --------------------------------------------------------

--
-- Table structure for table `Strength`
--

CREATE TABLE `Strength` (
  `strength_id` int(11) NOT NULL,
  `workout_id` int(11) NOT NULL,
  `strength_movement` varchar(250) DEFAULT NULL,
  `sets` int(11) DEFAULT NULL,
  `reps` int(11) DEFAULT NULL,
  `rank` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Strength`
--

INSERT INTO `Strength` (`strength_id`, `workout_id`, `strength_movement`, `sets`, `reps`, `rank`) VALUES
(98, 141, 'pull ups/negatives', 8, 32, 1),
(99, 141, 'dips', 3, 21, 2),
(169, 145, 'oneedit', 9, 9, 1),
(170, 145, 'twoedit no replace', 1, 2, 2),
(171, 146, 'hang with Jaspreet', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE `Users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`user_id`, `name`, `email`, `password`) VALUES
(8, 'Pat', 'pat@pat.com', '5bd0249c7b3fa239af74b7154107e321'),
(9, 'Bacon', 'malorie@bacon.com', '9876aac04a98d2c4471dcba1b003675c');

-- --------------------------------------------------------

--
-- Table structure for table `Workouts`
--

CREATE TABLE `Workouts` (
  `workout_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Workouts`
--

INSERT INTO `Workouts` (`workout_id`, `user_id`, `date`) VALUES
(141, 8, '9/12/19'),
(145, 9, '1/2/3'),
(146, 8, '9/19/2019');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Cardio`
--
ALTER TABLE `Cardio`
  ADD PRIMARY KEY (`cardio_id`),
  ADD KEY `Cardo_ibfk_1` (`workout_id`);

--
-- Indexes for table `Strength`
--
ALTER TABLE `Strength`
  ADD PRIMARY KEY (`strength_id`),
  ADD KEY `Strength_ibfk_1` (`workout_id`);

--
-- Indexes for table `Users`
--
ALTER TABLE `Users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `email` (`email`),
  ADD KEY `password` (`password`);

--
-- Indexes for table `Workouts`
--
ALTER TABLE `Workouts`
  ADD PRIMARY KEY (`workout_id`),
  ADD KEY `Workouts_ibfk_1` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Cardio`
--
ALTER TABLE `Cardio`
  MODIFY `cardio_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `Strength`
--
ALTER TABLE `Strength`
  MODIFY `strength_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=172;

--
-- AUTO_INCREMENT for table `Users`
--
ALTER TABLE `Users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `Workouts`
--
ALTER TABLE `Workouts`
  MODIFY `workout_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Cardio`
--
ALTER TABLE `Cardio`
  ADD CONSTRAINT `Cardo_ibfk_1` FOREIGN KEY (`workout_id`) REFERENCES `Workouts` (`workout_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Strength`
--
ALTER TABLE `Strength`
  ADD CONSTRAINT `Strength_ibfk_1` FOREIGN KEY (`workout_id`) REFERENCES `Workouts` (`workout_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Workouts`
--
ALTER TABLE `Workouts`
  ADD CONSTRAINT `Workouts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
