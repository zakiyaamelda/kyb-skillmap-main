-- phpMyAdmin SQL Dump
-- version 4.4.15.10
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 31, 2023 at 03:16 PM
-- Server version: 5.5.25a
-- PHP Version: 5.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mp_dashboard`
--

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE IF NOT EXISTS `department` (
  `id` int(11) NOT NULL,
  `dept_name` varchar(32) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE IF NOT EXISTS `karyawan` (
  `npk` varchar(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `role` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `karyawan_workstation`
--

CREATE TABLE IF NOT EXISTS `karyawan_workstation` (
  `npk` varchar(11) NOT NULL,
  `workstation_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mp_category`
--

CREATE TABLE IF NOT EXISTS `mp_category` (
  `codename` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `mp_file_proof`
--

CREATE TABLE IF NOT EXISTS `mp_file_proof` (
  `id` int(11) NOT NULL,
  `npk` varchar(11) NOT NULL,
  `mp_category` varchar(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `filename` varchar(32) NOT NULL,
  `description` varchar(64) DEFAULT NULL,
  `posted_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;
-- --------------------------------------------------------

--
-- Table structure for table `mp_scores`
--

CREATE TABLE IF NOT EXISTS `mp_scores` (
  `npk` varchar(11) CHARACTER SET utf8 NOT NULL,
  `mp_category` varchar(11) NOT NULL,
  `score` int(11) NOT NULL,
  `msk` int(11) NOT NULL,
  `kt` int(11) NOT NULL,
  `png` int(11) NOT NULL,
  `pssp` int(11) NOT NULL,
  `fivejq` int(11) NOT NULL,
  `kao` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `process`
--

CREATE TABLE IF NOT EXISTS `process` (
  `id` int(11) NOT NULL,
  `workstation_id` int(11) NOT NULL,
  `name` varchar(48) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=726 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `qualifications`
--

CREATE TABLE IF NOT EXISTS `qualifications` (
  `process_id` int(11) NOT NULL,
  `npk` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(16) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sub_workstations`
--

CREATE TABLE IF NOT EXISTS `sub_workstations` (
  `id` int(11) NOT NULL,
  `name` varchar(48) NOT NULL,
  `workstation_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(12) NOT NULL,
  `password` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `workstations`
--

CREATE TABLE IF NOT EXISTS `workstations` (
  `id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `name` varchar(48) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;
