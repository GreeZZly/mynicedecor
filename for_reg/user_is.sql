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
-- Структура таблицы `user_is`
--

CREATE TABLE IF NOT EXISTS `user_is` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `surname` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `second_name` varchar(50) NOT NULL,
  `email` varchar(30) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `login` varchar(25) NOT NULL,
  `password` varchar(80) NOT NULL,
  `salt` varchar(40) DEFAULT NULL,
  `activation_code` varchar(80) DEFAULT NULL,
  `forgotten_password_code` varchar(80) DEFAULT NULL,
  `forgotten_password_time` int(11) DEFAULT NULL,
  `remember_code` varchar(80) DEFAULT NULL,
  `created_on` int(11) NOT NULL,
  `last_login` int(11) DEFAULT NULL,
  `last_time` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `phone` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `user_is`
--

INSERT INTO `user_is` (`id`, `surname`, `name`, `second_name`, `email`, `gender`, `login`, `password`, `salt`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `last_time`, `active`, `phone`) VALUES
(3, 'Мизуров', 'Эдуард', '21312', '3d1k39e38@gmail.com', '', '3d1k3e', 'e033ad8a605e9817f2f5990a2bcd6915aa7df492', '064470fd3e', NULL, NULL, NULL, NULL, 1382478949, 1382478949, NULL, 0, 2147483647),
(4, 'Мизуров', 'Эдуард', '21312', '3d1k39wqe38@gmail.com', '', '3d1kw3e', 'eb793c006262d2c21580c060a0f6d55ea6c994a7', 'dabf0cf8d6', NULL, NULL, NULL, NULL, 1382479069, 1382479069, NULL, 0, 2147483647);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
