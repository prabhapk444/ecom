-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2024 at 03:47 PM
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
-- Database: `music`
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
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Table structure for table `liked_songs`
--

CREATE TABLE `liked_songs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `song_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `liked_songs`
--

INSERT INTO `liked_songs` (`id`, `user_id`, `song_id`) VALUES
(7, 9, 9),
(15, 8, 14),
(17, 8, 17),
(18, 8, 16),
(19, 8, 15),
(20, 8, 12);

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
(9, '2024-10-19 08:00:57', 'heat waves', 'songs/Heat-Waves(PagalWorld).mp3', 'images/heatwaves.png', 'Glass Animals', 'hollywood'),
(10, '2024-10-19 10:03:38', 'Bang Bang', 'songs/Bang-Bang-Bang.mp3', 'images/raju.png', 'Yuvan Shankar Raja', 'natpu'),
(11, '2024-10-19 10:13:43', 'Yaarumillaa', 'songs/Yaarumillaa-MassTamilan.org.mp3', 'images/yaarumilla.png', 'Sid Sriram', 'sad'),
(12, '2024-10-19 10:32:57', 'Adi Penne', 'songs/Adi Penne.mp3', 'images/adi penne.jpg', 'Stephen Zechariah', 'love'),
(13, '2024-10-19 10:34:42', 'Vilagathey', 'songs/Vilagathey.mp3', 'images/vilagathey.jpg', 'Stephen Zechariah', 'love'),
(14, '2024-10-19 10:44:37', 'Hayyoda', 'songs/Hayyoda-MassTamilan.dev.mp3', 'images/hayooda.png', 'Aniruth Ravichandar', 'love'),
(15, '2024-10-19 10:50:49', 'Hawa-Hawa', 'songs/Hawa-Hawa.mp3', 'images/hawa.jpeg', 'Kathick,Saindhavi', 'love'),
(16, '2024-10-19 10:54:00', 'Vinmeen-vithaiyil', 'songs/Vinmeen-Vithaiyil.mp3', 'images/vinmeen.jpg', 'Nivas k Prasana', 'love'),
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(8, 'prabha', 'viperprabhakaran@gmail.com', '$2y$10$gry6O2snMvU3LeAB6lAw0OpZ51HlfivvbKR6.Rr3HkjaL/v5wf5Ji', '2024-10-18 17:23:25', '2024-10-25 05:47:08'),
(9, 'pk', 'karanprabha22668@gmail.com', '$2y$10$xlxVcYXqz/aw0GRvLteusOQZSw0psbTHT5r1vyfrZwkPt7KSj9rfS', '2024-10-23 10:03:58', '2024-10-23 10:03:58');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `songs`
--
ALTER TABLE `songs`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
