-- phpMyAdmin SQL Dump
-- version 4.4.15.9
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 03, 2018 at 08:35 PM
-- Server version: 5.6.37
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wtrak`
--

-- --------------------------------------------------------

--
-- Table structure for table `wdata`
--

CREATE TABLE IF NOT EXISTS `wdata` (
  `dataid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `wdate` date NOT NULL,
  `wgt` float NOT NULL,
  `wnote` varchar(40) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=133 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `wdata`
--

INSERT INTO `wdata` (`dataid`, `userid`, `wdate`, `wgt`, `wnote`) VALUES
(1, 3, '2018-01-01', 238.6, 'Starting'),
(2, 3, '2018-01-02', 236, ''),
(3, 3, '2018-01-03', 234, ''),
(4, 3, '2018-01-04', 232, ''),
(5, 3, '2018-01-05', 232, ''),
(6, 3, '2018-01-06', 232, ''),
(7, 3, '2018-01-07', 232, ''),
(8, 3, '2018-01-08', 232, ''),
(9, 3, '2018-01-09', 231.6, ''),
(10, 3, '2018-01-10', 231.6, ''),
(11, 3, '2018-01-11', 232, ''),
(12, 3, '2018-01-12', 234, ''),
(13, 3, '2018-01-13', 234, ''),
(14, 3, '2018-01-14', 233, ''),
(15, 3, '2018-01-15', 232, ''),
(16, 3, '2018-01-16', 232, ''),
(17, 3, '2018-01-17', 232, ''),
(18, 3, '2018-01-18', 232, ''),
(19, 3, '2018-01-19', 232, ''),
(20, 3, '2018-01-20', 232, 'Hagstrums'),
(21, 3, '2018-01-21', 233, 'Hagstrums'),
(22, 3, '2018-01-22', 233, 'Hagstrums'),
(23, 3, '2018-01-23', 233, 'Hagstrums'),
(24, 3, '2018-01-24', 233, 'Hagstrums'),
(25, 3, '2018-01-25', 234, 'Hagstrums'),
(26, 3, '2018-01-26', 234, 'Hagstrums'),
(27, 3, '2018-01-27', 234, 'Hagstrums'),
(28, 3, '2018-01-28', 234, ''),
(29, 3, '2018-01-29', 234, ''),
(30, 3, '2018-01-30', 235, ''),
(31, 3, '2018-01-31', 235, ''),
(32, 3, '2018-02-01', 235, ''),
(33, 3, '2018-02-02', 235, ''),
(34, 3, '2018-02-03', 235, ''),
(35, 3, '2018-02-04', 235, 'Sick'),
(36, 3, '2018-02-05', 236, 'Sick'),
(37, 3, '2018-02-06', 237, 'Sick'),
(38, 3, '2018-02-07', 238, 'Sick'),
(39, 3, '2018-02-08', 239, 'Sick'),
(40, 3, '2018-02-09', 240, 'Sick'),
(41, 3, '2018-02-10', 241, 'Sick'),
(42, 3, '2018-02-11', 242, 'Sick'),
(43, 3, '2018-02-12', 243, 'Sick'),
(44, 3, '2018-02-13', 243, 'Sick'),
(45, 3, '2018-02-14', 241, 'Sick'),
(46, 3, '2018-02-15', 239, 'Rosie visit'),
(47, 3, '2018-02-16', 238, 'Rosie visit'),
(48, 3, '2018-02-17', 238, 'Rosie visit'),
(49, 3, '2018-02-18', 239, 'Rosie visit'),
(50, 3, '2018-02-19', 241, 'Joe visit'),
(51, 3, '2018-02-20', 241, 'Joe visit'),
(52, 3, '2018-02-21', 241, 'Joe visit'),
(53, 3, '2018-02-22', 242, 'Joe visit'),
(54, 3, '2018-02-23', 242, 'Joe visit'),
(55, 3, '2018-02-24', 242.6, 'Joe visit'),
(56, 3, '2018-02-25', 243, 'Joe visit'),
(57, 3, '2018-02-26', 243, 'Joe visit'),
(58, 3, '2018-02-27', 238, 'Jimmy visit'),
(59, 3, '2018-02-28', 238, 'Jimmy visit'),
(60, 3, '2018-03-01', 236.8, 'Jimmy visit'),
(61, 3, '2018-03-02', 234.6, 'Jimmy visit'),
(62, 3, '2018-03-03', 234.6, 'Jimmy visit'),
(63, 3, '2018-03-04', 234.8, 'Jimmy visit'),
(64, 3, '2018-03-05', 238.6, 'Jimmy visit'),
(65, 3, '2018-03-06', 237, 'Jimmy visit'),
(66, 3, '2018-03-07', 236.4, ''),
(67, 3, '2018-03-08', 234.4, ''),
(68, 3, '2018-03-09', 234.4, ''),
(69, 3, '2018-03-10', 234.4, ''),
(70, 3, '2018-03-11', 236.4, ''),
(71, 3, '2018-03-12', 236.5, ''),
(72, 3, '2018-03-13', 236.8, ''),
(73, 3, '2018-03-14', 236.8, ''),
(74, 3, '2018-03-15', 232.4, ''),
(75, 3, '2018-03-16', 230.4, ''),
(76, 3, '2018-03-17', 230.4, ''),
(77, 3, '2018-03-18', 230.4, ''),
(78, 3, '2018-03-19', 232.4, ''),
(79, 3, '2018-03-20', 232.6, ''),
(80, 3, '2018-03-21', 230.2, ''),
(81, 3, '2018-03-22', 228.4, ''),
(82, 3, '2018-03-23', 228.4, ''),
(83, 3, '2018-03-24', 228.4, ''),
(84, 3, '2018-03-25', 228.4, ''),
(85, 3, '2018-03-26', 228.4, ''),
(86, 3, '2018-03-27', 229.6, ''),
(87, 3, '2018-03-28', 231, ''),
(88, 3, '2018-03-29', 229, ''),
(89, 3, '2018-03-30', 228.2, ''),
(90, 3, '2018-03-31', 228.2, 'Contest Segment 1'),
(91, 3, '2018-04-01', 231, 'C Bday week'),
(92, 3, '2018-04-02', 232, 'C Bday week'),
(93, 3, '2018-04-03', 234, 'C Bday week'),
(94, 3, '2018-04-04', 236, 'C Bday week'),
(95, 3, '2018-04-05', 239, 'C Bday week'),
(96, 3, '2018-04-06', 242, 'C Bday week'),
(97, 3, '2018-04-07', 236, ''),
(98, 3, '2018-04-08', 235, ''),
(99, 3, '2018-04-09', 234, ''),
(100, 3, '2018-04-10', 233, ''),
(101, 3, '2018-04-11', 232, 'Fast'),
(102, 3, '2018-04-12', 230.6, 'Fast'),
(103, 3, '2018-04-13', 230.6, 'Fast'),
(104, 3, '2018-04-14', 229, ''),
(105, 3, '2018-04-15', 232, ''),
(106, 3, '2018-04-16', 230, ''),
(107, 3, '2018-04-17', 232, ''),
(108, 3, '2018-04-18', 232, 'Fast'),
(109, 3, '2018-04-19', 229.6, 'Fast'),
(110, 3, '2018-04-20', 228, ''),
(111, 3, '2018-04-21', 228, ''),
(112, 3, '2018-04-22', 228.2, ''),
(113, 3, '2018-04-23', 229.8, ''),
(114, 3, '2018-04-24', 229.8, ''),
(115, 3, '2018-04-25', 227.8, 'Fast'),
(116, 3, '2018-04-26', 227.8, 'Fast'),
(117, 3, '2018-04-27', 227.8, 'Fast'),
(118, 3, '2018-04-28', 230, ''),
(119, 3, '2018-04-29', 231, ''),
(120, 3, '2018-04-30', 227.6, ''),
(121, 3, '2018-05-01', 227.6, ''),
(122, 3, '2018-05-02', 226.5, 'Fast'),
(123, 3, '2018-05-03', 224.2, 'Fast'),
(124, 3, '2018-05-04', 224.2, 'C to doctor'),
(125, 3, '2018-05-05', 225, ''),
(126, 3, '2018-05-06', 227, ''),
(127, 3, '2018-05-07', 230, ''),
(128, 3, '2018-05-08', 230, ''),
(129, 3, '2018-05-09', 229, ''),
(130, 3, '2018-05-10', 228.4, ''),
(131, 3, '2018-05-11', 228.4, ''),
(132, 3, '2018-05-12', 230, '');

-- --------------------------------------------------------

--
-- Table structure for table `wuser`
--

CREATE TABLE IF NOT EXISTS `wuser` (
  `userid` int(11) NOT NULL,
  `email` varchar(40) NOT NULL,
  `username` varchar(10) NOT NULL,
  `password` tinyblob NOT NULL,
  `wgoal` float DEFAULT NULL,
  `wgoaldate` date DEFAULT NULL,
  `wactive` tinyint(1) NOT NULL DEFAULT '0',
  `lastlogin` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `wuser`
--

INSERT INTO `wuser` (`userid`, `email`, `username`, `password`, `wgoal`, `wgoaldate`, `wactive`, `lastlogin`) VALUES
(3, 'barlowcr@gmail.com', 'chris', 0xd869c665c7c600a85cfd399580fbf2e215f7c42095be0ba9898376607dbd9b65, 210, '2018-09-23', 1, '2018-07-04 12:39:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wdata`
--
ALTER TABLE `wdata`
  ADD PRIMARY KEY (`dataid`);

--
-- Indexes for table `wuser`
--
ALTER TABLE `wuser`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wdata`
--
ALTER TABLE `wdata`
  MODIFY `dataid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=133;
--
-- AUTO_INCREMENT for table `wuser`
--
ALTER TABLE `wuser`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
