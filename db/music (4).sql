-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2024 at 01:58 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `melody hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(10) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `liked_songs`
--

CREATE TABLE `liked_songs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `song_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `songs`
--

CREATE TABLE `songs` (
  `id` int(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `title` varchar(255) NOT NULL,
  `song_path` varchar(255) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `songs`
--

INSERT INTO `songs` (`id`, `created_at`, `title`, `song_path`, `image_path`, `author`, `category`) VALUES
(9, '2024-10-19 08:00:57', 'heat waves', 'songs/Heat-Waves(PagalWorld).mp3', 'images/heatwaves.png', 'Glass Animals', 'English'),
(10, '2024-10-19 10:03:38', 'Bang Bang', 'songs/Bang-Bang-Bang.mp3', 'images/raju.png', 'Yuvan Shankar Raja', 'Friendship'),
(11, '2024-10-19 10:13:43', 'Yaarumillaa', 'songs/Yaarumillaa-MassTamilan.org.mp3', 'images/yaarumilla.png', 'Sid Sriram', 'Sad'),
(12, '2024-10-19 10:32:57', 'Adi Penne', 'songs/Adi Penne.mp3', 'images/adi penne.jpg', 'Stephen Zechariah', 'Love'),
(13, '2024-10-19 10:34:42', 'Vilagathey', 'songs/Vilagathey.mp3', 'images/vilagathey.jpg', 'Stephen Zechariah', 'Love'),
(14, '2024-10-19 10:44:37', 'Hayyoda', 'songs/Hayyoda-MassTamilan.dev.mp3', 'images/hayooda.png', 'Aniruth Ravichandar', 'Love'),
(15, '2024-10-19 10:50:49', 'Hawa-Hawa', 'songs/Hawa-Hawa.mp3', 'images/hawa.jpeg', 'Kathick,Saindhavi', 'Love'),
(16, '2024-10-19 10:54:00', 'Vinmeen-vithaiyil', 'songs/Vinmeen-Vithaiyil.mp3', 'images/vinmeen.jpg', 'Nivas k Prasana', 'Love'),
(17, '2024-10-26 07:58:23', 'Dynamite', 'songs/Dynamite(PagalNew.Com.Se).mp3', 'images/bts.png', 'BTS', 'POP');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `profile_image` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `created_at`, `updated_at`, `profile_image`) VALUES
(11, 'pk', 'viperprabhakaran@gmail.com', '$2y$10$XYbGgC2Mq8LguGIxl8ESZ.PRV/hGs4iteiQaUGFHotoH.mXk8Q1xG', '2024-10-28 07:59:26', '2024-10-28 12:08:44', 'uploads/671f7ecc0a4e0.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_unique` (`username`);

--
-- Indexes for table `liked_songs`
--
ALTER TABLE `liked_songs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `song_id` (`song_id`);

--
-- Indexes for table `songs`
--
ALTER TABLE `songs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category` (`category`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `liked_songs`
--
ALTER TABLE `liked_songs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `songs`
--
ALTER TABLE `songs`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `liked_songs`
--
ALTER TABLE `liked_songs`
  ADD CONSTRAINT `liked_songs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `liked_songs_ibfk_2` FOREIGN KEY (`song_id`) REFERENCES `songs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
