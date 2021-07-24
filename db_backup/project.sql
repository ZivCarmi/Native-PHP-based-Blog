-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2019 at 12:13 PM
-- Server version: 10.4.6-MariaDB
-- PHP Version: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `article` text NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `title`, `article`, `date`) VALUES
(2, 1, 'First post by Ziv!', 'This is a test, \r\nfirst post in the blog!\r\n\r\n---------------------\r\n\r\nEdited here...', '2019-11-09 13:00:04'),
(3, 2, 'Default User here!!', 'Hello everyone! \r\nsecond post!', '2019-11-09 13:05:58'),
(4, 1, 'HEYYYYY ', 'MY 2ND POST WOWW', '2019-11-09 13:12:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `email`, `password`) VALUES
(1, 'Ziv', 'Carmi', 'ziv@gmail.com', '$2y$10$hiC9eg4XPEuiVXntjb0WK.leiqiT7kY.jl1WVDiBS6yUHYVzhfjN.'),
(2, 'Default', 'User', 'default@gmail.com', '$2y$10$/Jb/0YdWzhr9WLky8SQvLeGuN9I6kH2BdTzEp7/beT9L2bGhwqGwu'),
(3, 'dor', 'dor', 'dor@gmail.com', '$2y$10$peGScegx6m4J7BSMjybfn./TEBqOugyq8sm/F.nAu2IhEOV9jI9Ue');

-- --------------------------------------------------------

--
-- Table structure for table `users_profile`
--

CREATE TABLE `users_profile` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `profile_image` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `birth_day` date NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `gender` varchar(1) NOT NULL,
  `country` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_profile`
--

INSERT INTO `users_profile` (`id`, `user_id`, `profile_image`, `date`, `birth_day`, `nickname`, `gender`, `country`) VALUES
(1, 1, '2019.11.09.11.58.41-zivo.png', '2019-11-09', '0000-00-00', 'Zivos', 'n', 'Not specified'),
(2, 2, 'default_profile.png', '2019-11-09', '0000-00-00', '', 'n', 'Not specified'),
(3, 3, 'default_profile.png', '2019-11-09', '0000-00-00', 'drory', 'n', 'Not specified');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users_profile`
--
ALTER TABLE `users_profile`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users_profile`
--
ALTER TABLE `users_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
