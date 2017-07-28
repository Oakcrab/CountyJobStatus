
-- phpMyAdmin SQL Dump
-- version 2.11.11.3
-- http://www.phpmyadmin.net
--
-- Host: 68.178.217.3
-- Generation Time: Nov 27, 2015 at 06:54 PM
-- Server version: 5.5.43
-- PHP Version: 5.1.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `jcso`
--

-- --------------------------------------------------------

--
-- Table structure for table `corner_checks`
--

CREATE TABLE `corner_checks` (
  `check_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `survey` varchar(10) NOT NULL,
  `surveyor` varchar(100) NOT NULL,
  `section` varchar(2) NOT NULL,
  `twp` varchar(2) NOT NULL,
  `rng` varchar(2) NOT NULL,
  `corner` varchar(1) NOT NULL,
  `notes` text NOT NULL,
  `checked` set('no','yes') NOT NULL DEFAULT 'no',
  `checked_by` tinyint(2) NOT NULL,
  `reg_date` datetime NOT NULL,
  PRIMARY KEY (`check_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `job_id` mediumint(8) NOT NULL AUTO_INCREMENT,
  `job_num` mediumint(5) NOT NULL,
  `job_name` varchar(150) NOT NULL,
  `job_desc` varchar(250) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `notes` text,
  `section` tinyint(4) DEFAULT NULL,
  `township` tinyint(4) DEFAULT NULL,
  `sur_range` tinyint(4) DEFAULT NULL,
  `type_id` tinyint(4) NOT NULL,
  `user_id` tinyint(4) NOT NULL,
  `reg_date` datetime NOT NULL,
  `complete` datetime DEFAULT NULL,
  PRIMARY KEY (`job_id`),
  KEY `job_num` (`job_num`,`section`,`township`,`sur_range`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=55 ;

-- --------------------------------------------------------

--
-- Table structure for table `job_type`
--

CREATE TABLE `job_type` (
  `type_id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `type_name` varchar(60) NOT NULL,
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Table structure for table `reporting`
--

CREATE TABLE `reporting` (
  `report_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `report_no` tinyint(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `user_id` mediumint(8) unsigned NOT NULL,
  `report_title` varchar(100) NOT NULL,
  `report_desc` text NOT NULL,
  `reg_date` datetime NOT NULL,
  PRIMARY KEY (`report_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Table structure for table `splits`
--

CREATE TABLE `splits` (
  `record_id` mediumint(9) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(15) NOT NULL,
  `township` smallint(2) unsigned NOT NULL,
  `map_num` int(8) unsigned NOT NULL,
  `date` date NOT NULL,
  `owner` varchar(250) NOT NULL,
  `county` tinyint(2) unsigned NOT NULL,
  `area` tinyint(2) unsigned NOT NULL,
  `section` tinyint(2) unsigned NOT NULL,
  `block` smallint(3) unsigned NOT NULL,
  `parcel` smallint(3) unsigned NOT NULL,
  `split` smallint(3) unsigned NOT NULL,
  `taxid` smallint(3) unsigned NOT NULL,
  `acres` decimal(7,3) unsigned NOT NULL,
  `description` varchar(500) NOT NULL,
  `deed` mediumint(7) NOT NULL,
  `survey` varchar(10) NOT NULL,
  `notes` text NOT NULL,
  `submit` datetime NOT NULL,
  PRIMARY KEY (`record_id`),
  KEY `township` (`township`,`map_num`,`county`,`area`,`section`,`block`,`split`,`taxid`),
  KEY `parcel` (`parcel`),
  FULLTEXT KEY `owner` (`owner`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=337 ;

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `task_desc` varchar(250) NOT NULL,
  `notes` varchar(250) DEFAULT NULL,
  `user_id` tinyint(4) NOT NULL,
  `assigned_id` tinyint(5) unsigned NOT NULL,
  `due` datetime NOT NULL,
  `complete` datetime DEFAULT NULL,
  `reg_date` datetime NOT NULL,
  PRIMARY KEY (`task_id`),
  KEY `assigned_id` (`assigned_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(40) NOT NULL,
  `email` varchar(60) NOT NULL,
  `pass` char(40) NOT NULL,
  `phone` bigint(10) unsigned NOT NULL,
  `ext` tinyint(3) unsigned NOT NULL,
  `reg_date` datetime NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `phone` (`phone`,`ext`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;
