-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 20, 2009 at 07:07 PM
-- Server version: 5.0.67
-- PHP Version: 5.2.6-2ubuntu4.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `db017`
--

-- --------------------------------------------------------

--
-- Table structure for table `Categories`
--

DROP TABLE IF EXISTS `Categories`;
CREATE TABLE IF NOT EXISTS `Categories` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `parent` int(10) unsigned NOT NULL,
  `name` varchar(60) collate utf8_unicode_ci NOT NULL,
  `fields` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `Categories`
--

INSERT INTO `Categories` (`id`, `parent`, `name`, `fields`) VALUES
(1, 0, 'X', 'UDE=::UDI=::UDM=');

-- --------------------------------------------------------

--
-- Table structure for table `Items`
--

DROP TABLE IF EXISTS `Items`;
CREATE TABLE IF NOT EXISTS `Items` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `parent` int(10) unsigned NOT NULL,
  `name` varchar(60) collate utf8_unicode_ci NOT NULL,
  `fields` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `Items`
--

INSERT INTO `Items` (`id`, `parent`, `name`, `fields`) VALUES
(1, 1, 'Ia', 'VmEx::::VmEz'),
(5, 1, 'Ib', '::VmIy::VmIz'),
(6, 1, 'Ic', '::::'),
(7, 1, 'Id', '::::');

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
CREATE TABLE IF NOT EXISTS `Users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(60) collate utf8_unicode_ci NOT NULL,
  `login` varchar(60) collate utf8_unicode_ci NOT NULL,
  `pass` varchar(40) collate utf8_unicode_ci NOT NULL,
  `rights` int(2) unsigned NOT NULL default '0',
  `cred` varchar(40) collate utf8_unicode_ci NOT NULL COMMENT 'User special check code',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`id`, `name`, `login`, `pass`, `rights`, `cred`) VALUES
(1, 'Serg', 'admin', '90b9aa7e25f80cf4f64e990b78a9fc5ebd6cecad', 1, '815855eb3ff73d8c2a4c932a590ad22ed60b8ecc');

