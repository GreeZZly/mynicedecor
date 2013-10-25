-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb2
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Окт 23 2013 г., 02:05
-- Версия сервера: 5.5.31
-- Версия PHP: 5.4.4-14+deb7u4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `u2567_crm`
--

-- --------------------------------------------------------

--
-- Структура таблицы `registred_company`
--

CREATE TABLE IF NOT EXISTS `registred_company` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `address` varchar(200) DEFAULT 'Good CRM',
  `time` varchar(100) DEFAULT '(GMT+04:00)+Europe/Moscow',
  `valuta` varchar(20) DEFAULT 'руб',
  `lang` varchar(30) DEFAULT 'Русский',
  `link` varchar(200) NOT NULL DEFAULT '""',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `registred_company`
--

INSERT INTO `registred_company` (`id`, `name`, `address`, `time`, `valuta`, `lang`, `link`) VALUES
(1, 'goodpractice', 'goodpractice', NULL, NULL, NULL, ''),
(2, 'nicedecor', 'nicedecor', '(GMT+04:00)+Europe/Moscow', 'руб', 'Русский', '""');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
