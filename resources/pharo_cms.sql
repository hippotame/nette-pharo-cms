-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Počítač: localhost
-- Vygenerováno: Úte 27. zář 2016, 18:05
-- Verze serveru: 5.5.52-0ubuntu0.14.04.1
-- Verze PHP: 5.6.23-1+deprecated+dontuse+deb.sury.org~trusty+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databáze: `pharo_cms`
--
CREATE DATABASE IF NOT EXISTS `pharo_cms` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `pharo_cms`;

-- --------------------------------------------------------

--
-- Struktura tabulky `blog_category`
--

DROP TABLE IF EXISTS `blog_category`;
CREATE TABLE IF NOT EXISTS `blog_category` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Vypisuji data pro tabulku `blog_category`
--

INSERT INTO `blog_category` (`id`, `name`, `ordering`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `blog_category_txt`
--

DROP TABLE IF EXISTS `blog_category_txt`;
CREATE TABLE IF NOT EXISTS `blog_category_txt` (
  `id_blog_category` int(10) unsigned NOT NULL,
  `lang` int(5) unsigned DEFAULT '1',
  `context` text NOT NULL,
  `date_updated` datetime NOT NULL,
  KEY `id_blog_category` (`id_blog_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Text value for blog_category';

--
-- Vypisuji data pro tabulku `blog_category_txt`
--

INSERT INTO `blog_category_txt` (`id_blog_category`, `lang`, `context`, `date_updated`) VALUES
(1, 1, 'menu_test', '2016-09-21 21:10:57');

-- --------------------------------------------------------

--
-- Struktura tabulky `blog_post`
--

DROP TABLE IF EXISTS `blog_post`;
CREATE TABLE IF NOT EXISTS `blog_post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_blog_category` int(11) NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `date_release` datetime DEFAULT NULL,
  `date_deleted` datetime DEFAULT NULL,
  `post_perex` int(11) DEFAULT NULL,
  `post_content` int(11) DEFAULT NULL,
  `post_header` int(11) NOT NULL,
  `post_title` int(11) NOT NULL,
  `post_image` int(11) DEFAULT NULL,
  `post_can_comment` int(11) DEFAULT NULL,
  `post_status` varchar(12) NOT NULL DEFAULT '''publish''',
  `menu_order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktura tabulky `blog_post_txt`
--

DROP TABLE IF EXISTS `blog_post_txt`;
CREATE TABLE IF NOT EXISTS `blog_post_txt` (
  `id_blog_post` int(11) unsigned NOT NULL,
  `lang` int(5) unsigned DEFAULT '1',
  `context` text NOT NULL,
  `date_updated` datetime NOT NULL,
  KEY `id_blog_category` (`id_blog_post`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Text value for blog_category';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
