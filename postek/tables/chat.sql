-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 01, 2023 at 08:57 AM
-- Server version: 10.5.20-MariaDB
-- PHP Version: 7.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id21603433_ktechy`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `userid` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `friend_request` varchar(3000) DEFAULT NULL,
  `friend_accept` varchar(3000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`userid`, `username`, `email`, `password`, `friend_request`, `friend_accept`) VALUES
(1, 'Kiran', 'kiran@gmail.com', 'Kiran123&', '', 'Luffy,Zoro,Naruto'),
(2, 'Luffy', 'luffy@gmail.com', 'Luffy123&', '', 'Kiran,Naruto,Hinata Shouyo'),
(3, 'Zoro', 'zoro@gmail.com', 'Zoro123&', '', 'Kiran,Naruto'),
(4, 'Naruto', 'naruto@gmail.com', 'Naruto123&', '', 'Kiran,Zoro,Luffy,Hinata Shouyo'),
(5, 'SasukeUchiha', 'sasuke@gmail.com', 'Sasuke123&', NULL, NULL),
(6, 'Gon', 'gon@gmail.com', 'Gon123&', '', 'Killua'),
(7, 'Killua', 'killua@gmail.com', 'Killua123&', NULL, 'Gon'),
(8, 'Hinata Shouyo', 'hinata@gmail.com', 'Hinata123&', 'Zoro,Gon,Killua', 'Luffy,Naruto');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
