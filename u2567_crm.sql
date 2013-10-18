-- phpMyAdmin SQL Dump
-- version 3.5.8.1deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Окт 18 2013 г., 11:25
-- Версия сервера: 5.5.32-0ubuntu0.13.04.1
-- Версия PHP: 5.4.9-4ubuntu2.3

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
-- Структура таблицы `address`
--

CREATE TABLE IF NOT EXISTS `address` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `country` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL COMMENT 'регион типа чувашия',
  `subregion` varchar(100) DEFAULT NULL COMMENT 'сюда запишется город',
  `post_code` varchar(100) DEFAULT NULL,
  `ppp` varchar(100) DEFAULT NULL COMMENT 'сюда нас.пункт',
  `street` varchar(150) DEFAULT NULL,
  `house` varchar(100) DEFAULT NULL,
  `housing` varchar(100) DEFAULT NULL COMMENT 'корпус',
  `flat` varchar(100) DEFAULT NULL COMMENT 'Квартира или офис',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=253 ;

-- --------------------------------------------------------

--
-- Структура таблицы `attach`
--

CREATE TABLE IF NOT EXISTS `attach` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `id_plans` int(20) DEFAULT NULL,
  `file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_plans` (`id_plans`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `banking_details`
--

CREATE TABLE IF NOT EXISTS `banking_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) DEFAULT NULL,
  `legal_address` varchar(100) DEFAULT NULL,
  `head` varchar(100) DEFAULT NULL,
  `under` varchar(100) DEFAULT NULL,
  `accountant` varchar(100) DEFAULT NULL,
  `INN` varchar(100) DEFAULT NULL,
  `KPP` varchar(100) DEFAULT NULL,
  `bank` varchar(100) DEFAULT NULL,
  `BIK` varchar(100) DEFAULT NULL,
  `payment_account` varchar(50) DEFAULT NULL,
  `corr_account` varchar(50) DEFAULT NULL,
  `OGRN` varchar(50) DEFAULT NULL,
  `OKPO` varchar(50) DEFAULT NULL,
  `OKVED` varchar(50) DEFAULT NULL,
  `OKFS` varchar(50) DEFAULT NULL,
  `OKOPF` varchar(50) DEFAULT NULL,
  `OKATO` varchar(50) DEFAULT NULL,
  `personal_account` varchar(50) DEFAULT NULL,
  `card_number` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=122 ;

-- --------------------------------------------------------

--
-- Структура таблицы `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_registred_company` int(15) NOT NULL,
  `cat_left` int(11) NOT NULL DEFAULT '0',
  `cat_right` int(11) NOT NULL DEFAULT '0',
  `cat_level` int(11) NOT NULL DEFAULT '0',
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Структура таблицы `contact`
--

CREATE TABLE IF NOT EXISTS `contact` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `id_customer` int(20) DEFAULT NULL,
  `surname` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `second_name` varchar(50) DEFAULT NULL,
  `responsobility` varchar(100) DEFAULT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `birthday` varchar(20) DEFAULT NULL,
  `email` varchar(30) DEFAULT NULL,
  `address_id` int(20) DEFAULT NULL,
  `id_passport` int(11) DEFAULT NULL,
  `id_bank_details` int(11) DEFAULT NULL,
  `id_contact_info` int(11) DEFAULT NULL,
  `id_work_place` int(11) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  KEY `address_id` (`address_id`),
  KEY `id_customer` (`id_customer`),
  KEY `id_passport` (`id_passport`),
  KEY `id_bank_details` (`id_bank_details`),
  KEY `id_contact_info` (`id_contact_info`),
  KEY `id_work_place` (`id_work_place`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=67 ;

-- --------------------------------------------------------

--
-- Структура таблицы `contact_info`
--

CREATE TABLE IF NOT EXISTS `contact_info` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `phone` varchar(100) DEFAULT NULL,
  `phone_home` varchar(100) DEFAULT NULL,
  `phone_work` varchar(100) DEFAULT NULL,
  `phone_for_sms` varchar(100) DEFAULT NULL,
  `send_sms` enum('yes','no') DEFAULT 'no',
  `send_email` enum('yes','no') DEFAULT 'no',
  `IM` varchar(100) DEFAULT NULL,
  `fax` varchar(50) DEFAULT NULL,
  `email_home` varchar(50) DEFAULT NULL,
  `email_work` varchar(50) DEFAULT NULL,
  `email_reserv` varchar(50) DEFAULT NULL,
  `site1` varchar(100) DEFAULT NULL,
  `site2` varchar(100) DEFAULT NULL,
  `site3` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=148 ;

-- --------------------------------------------------------

--
-- Структура таблицы `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `type` enum('legal','individual') DEFAULT 'legal',
  `name` varchar(100) NOT NULL COMMENT 'название компании или имя физическуого лица',
  `surname` varchar(70) DEFAULT NULL,
  `second_name` varchar(70) DEFAULT NULL,
  `gender` varchar(30) DEFAULT NULL,
  `date_registration` varchar(30) DEFAULT NULL,
  `birthday` varchar(30) DEFAULT NULL,
  `photo` varchar(250) DEFAULT NULL,
  `work_mode_c` varchar(50) DEFAULT NULL,
  `dinner_time_c` varchar(20) DEFAULT '12001300',
  `status` int(10) DEFAULT '0',
  `responsibility` varchar(100) DEFAULT 'free',
  `SNILS` varchar(25) DEFAULT NULL,
  `INN_c` varchar(20) DEFAULT NULL,
  `id_contact_info` int(15) DEFAULT NULL,
  `id_address` int(15) DEFAULT NULL,
  `id_bank_details` int(15) DEFAULT NULL,
  `id_work_place` int(11) DEFAULT NULL,
  `id_passport` int(15) DEFAULT NULL,
  `ownership` varchar(100) DEFAULT 'ООО' COMMENT 'форма собственности',
  `description` varchar(255) DEFAULT NULL,
  `label` tinyint(1) NOT NULL DEFAULT '1',
  `captured` tinyint(1) NOT NULL DEFAULT '0',
  `id_registred_company` int(15) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_contact_info` (`id_contact_info`),
  KEY `id_address` (`id_address`),
  KEY `id_work_place` (`id_work_place`),
  KEY `id_passport` (`id_passport`),
  KEY `id_bank_details` (`id_bank_details`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=111 ;

-- --------------------------------------------------------

--
-- Структура таблицы `documents`
--

CREATE TABLE IF NOT EXISTS `documents` (
  `id` int(11) NOT NULL,
  `contract` varchar(50) NOT NULL,
  `account` varchar(50) NOT NULL,
  `act_works` varchar(50) NOT NULL,
  `customer_request` varchar(50) NOT NULL,
  `request_store` varchar(50) NOT NULL,
  `request_supply` varchar(50) NOT NULL,
  `order_supply` varchar(50) NOT NULL,
  `unconfirmed_bid` varchar(50) NOT NULL,
  `invoice` varchar(50) NOT NULL,
  `retail_sale` varchar(50) NOT NULL,
  `sales_other` varchar(50) NOT NULL,
  `account_texture` varchar(50) NOT NULL,
  `account_texture_get` varchar(50) NOT NULL,
  `account_texture_set` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `geo_cities`
--

CREATE TABLE IF NOT EXISTS `geo_cities` (
  `cid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `socr` varchar(10) NOT NULL,
  `code` varchar(13) NOT NULL,
  `region_id` tinyint(2) unsigned NOT NULL,
  `subregion_id` smallint(3) unsigned NOT NULL,
  `city_id` smallint(3) unsigned NOT NULL,
  `ppp_id` smallint(3) unsigned NOT NULL,
  `aa_status` tinyint(2) unsigned NOT NULL,
  `index` mediumint(6) unsigned zerofill NOT NULL DEFAULT '000000',
  `gninmb` smallint(4) unsigned zerofill NOT NULL DEFAULT '0000',
  `uno` smallint(4) unsigned zerofill NOT NULL DEFAULT '0000',
  `ocatd` varchar(11) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`),
  KEY `region_id` (`region_id`,`subregion_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=191641 ;

-- --------------------------------------------------------

--
-- Структура таблицы `geo_houses`
--

CREATE TABLE IF NOT EXISTS `geo_houses` (
  `hid` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `korp` varchar(10) NOT NULL,
  `socr` varchar(10) NOT NULL,
  `code` varchar(19) NOT NULL,
  `region_id` tinyint(2) unsigned NOT NULL,
  `subregion_id` smallint(3) unsigned NOT NULL,
  `city_id` smallint(3) unsigned NOT NULL,
  `ppp_id` smallint(3) unsigned NOT NULL,
  `street_id` smallint(4) unsigned NOT NULL,
  `house_id` smallint(4) unsigned NOT NULL,
  `aa_status` tinyint(2) unsigned NOT NULL,
  `index` mediumint(6) unsigned zerofill NOT NULL DEFAULT '000000',
  `gninmb` smallint(4) unsigned zerofill NOT NULL DEFAULT '0000',
  `uno` smallint(4) unsigned zerofill NOT NULL DEFAULT '0000',
  `ocatd` varchar(11) NOT NULL,
  PRIMARY KEY (`hid`),
  KEY `region_id` (`region_id`,`subregion_id`,`city_id`,`ppp_id`,`street_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=81473 ;

-- --------------------------------------------------------

--
-- Структура таблицы `geo_regions`
--

CREATE TABLE IF NOT EXISTS `geo_regions` (
  `rid` tinyint(2) unsigned zerofill NOT NULL,
  `name` varchar(44) NOT NULL,
  PRIMARY KEY (`rid`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `geo_streets`
--

CREATE TABLE IF NOT EXISTS `geo_streets` (
  `sid` mediumint(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(40) NOT NULL,
  `socr` varchar(10) NOT NULL,
  `code` varchar(17) NOT NULL,
  `region_id` tinyint(2) unsigned NOT NULL,
  `subregion_id` smallint(3) unsigned NOT NULL,
  `city_id` smallint(3) unsigned NOT NULL,
  `ppp_id` smallint(3) unsigned NOT NULL,
  `street_id` smallint(4) unsigned NOT NULL,
  `aa_status` tinyint(2) unsigned NOT NULL,
  `post_code` mediumint(6) NOT NULL DEFAULT '0',
  `gninmb` smallint(4) unsigned zerofill NOT NULL DEFAULT '0000',
  `uno` smallint(4) unsigned zerofill NOT NULL DEFAULT '0000',
  `ocatd` varchar(11) NOT NULL,
  PRIMARY KEY (`sid`),
  KEY `region_id` (`region_id`,`subregion_id`,`city_id`,`ppp_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=821648 ;

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `author` int(20) NOT NULL,
  `receiver` int(20) NOT NULL,
  `text` text NOT NULL,
  `read_msg` tinyint(1) NOT NULL DEFAULT '0',
  `new` tinyint(1) NOT NULL DEFAULT '1',
  `delete_author` tinyint(1) NOT NULL DEFAULT '0',
  `delete_receiver` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `price` varchar(100) DEFAULT NULL,
  `id_sale` int(11) NOT NULL,
  `time` varchar(5) DEFAULT NULL,
  `description` text,
  `date` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_sale` (`id_sale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `passport`
--

CREATE TABLE IF NOT EXISTS `passport` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_p` varchar(50) DEFAULT NULL,
  `surname_p` varchar(50) DEFAULT NULL,
  `second_name_p` varchar(50) DEFAULT NULL,
  `gender_p` varchar(35) DEFAULT NULL,
  `number` int(20) DEFAULT NULL,
  `series` int(20) DEFAULT NULL,
  `scan_passport` varchar(150) DEFAULT NULL,
  `passport_issued` varchar(250) DEFAULT NULL COMMENT 'пасспорт выдан',
  `date` varchar(30) DEFAULT NULL COMMENT 'дата выдачи',
  `passport_db` varchar(20) DEFAULT NULL,
  `kod` varchar(100) DEFAULT NULL COMMENT 'код подразделения',
  `place_birth` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=70 ;

-- --------------------------------------------------------

--
-- Структура таблицы `payment`
--

CREATE TABLE IF NOT EXISTS `payment` (
  `payment_time` varchar(10) DEFAULT NULL,
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `id_sale` int(15) NOT NULL,
  `payment` varchar(100) NOT NULL,
  `payment_date` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_sale` (`id_sale`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

-- --------------------------------------------------------

--
-- Структура таблицы `phase`
--

CREATE TABLE IF NOT EXISTS `phase` (
  `id_phase` int(20) NOT NULL AUTO_INCREMENT,
  `id_sale` int(20) DEFAULT NULL,
  `phase` varchar(100) DEFAULT NULL,
  `date` varchar(30) NOT NULL,
  PRIMARY KEY (`id_phase`),
  KEY `id_sale` (`id_sale`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=76 ;

-- --------------------------------------------------------

--
-- Структура таблицы `plans`
--

CREATE TABLE IF NOT EXISTS `plans` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `date` varchar(20) DEFAULT NULL,
  `time` varchar(30) DEFAULT NULL,
  `plan` enum('yes','no') DEFAULT 'no',
  `fact` enum('yes','no') NOT NULL DEFAULT 'no',
  `action` varchar(30) DEFAULT NULL COMMENT 'Мероприятие',
  `responsibility` varchar(100) DEFAULT NULL,
  `performer` varchar(30) DEFAULT NULL,
  `sale_name` int(20) DEFAULT NULL COMMENT 'сылается на magic_crm.sale.id',
  `priority` enum('Важно и срочно','Важно, не срочно','Неважно, но срочно','Неважно и не срочно') NOT NULL DEFAULT 'Важно и срочно',
  `task` varchar(150) DEFAULT NULL,
  `result` varchar(150) DEFAULT NULL,
  `contact_name` varchar(100) DEFAULT NULL,
  `id_customer` int(20) DEFAULT NULL COMMENT 'сылается на id клиента, через id клиента можно узнать список контаков этого клиента',
  `id_contact` int(20) DEFAULT NULL,
  `id_address` int(15) DEFAULT NULL,
  `id_registred_company` int(15) NOT NULL,
  `phase` varchar(202) DEFAULT NULL,
  `alert` int(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sale_name` (`sale_name`),
  KEY `id_customer` (`id_customer`),
  KEY `id_registred_company` (`id_registred_company`),
  KEY `id_contact` (`id_contact`),
  KEY `id_phase` (`phase`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

-- --------------------------------------------------------

--
-- Структура таблицы `plan_of_payment`
--

CREATE TABLE IF NOT EXISTS `plan_of_payment` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `id_user` int(15) NOT NULL,
  `date` varchar(10) NOT NULL COMMENT 'дата вида 09-2013',
  `plan` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_user` (`id_user`,`date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=46 ;

-- --------------------------------------------------------

--
-- Структура таблицы `plan_of_phase`
--

CREATE TABLE IF NOT EXISTS `plan_of_phase` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `id_pos` int(15) NOT NULL,
  `phase` varchar(100) NOT NULL,
  `count` int(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pos` (`id_pos`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='таблица для обьеденения с plan_of_sale' AUTO_INCREMENT=43 ;

-- --------------------------------------------------------

--
-- Структура таблицы `plan_of_sale`
--

CREATE TABLE IF NOT EXISTS `plan_of_sale` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `id_user` int(15) NOT NULL,
  `process` varchar(50) NOT NULL,
  `date` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_user` (`id_user`,`process`,`date`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_registred_company` int(15) NOT NULL,
  `category_id` int(20) DEFAULT NULL,
  `product` varchar(100) DEFAULT NULL,
  `cost` varchar(100) DEFAULT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'руб.',
  `code` varchar(100) DEFAULT NULL,
  `service` tinyint(1) DEFAULT '0',
  `storage` int(50) NOT NULL,
  `stored` int(50) NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `description` text,
  `popularity` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

-- --------------------------------------------------------

--
-- Структура таблицы `product_images`
--

CREATE TABLE IF NOT EXISTS `product_images` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `id_product` int(15) NOT NULL,
  `path` text NOT NULL,
  `alt` text,
  PRIMARY KEY (`id`),
  KEY `id_product` (`id_product`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `prognosis`
--

CREATE TABLE IF NOT EXISTS `prognosis` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `id_sale` int(15) NOT NULL,
  `prognosis` int(100) NOT NULL,
  `prognosis_date` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_sale` (`id_sale`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

-- --------------------------------------------------------

--
-- Структура таблицы `records`
--

CREATE TABLE IF NOT EXISTS `records` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `company` varchar(255) NOT NULL DEFAULT 'ИП Довакин Д.Р.',
  `status` enum('0','1','2','3','4','5') NOT NULL DEFAULT '4',
  `trainer` varchar(255) NOT NULL DEFAULT 'Милославский Е.Н.',
  `phase` varchar(255) NOT NULL DEFAULT 'Презентация',
  `prediction` int(20) NOT NULL DEFAULT '30000',
  `date` date NOT NULL DEFAULT '2013-02-13',
  `label` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

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

-- --------------------------------------------------------

--
-- Структура таблицы `rim`
--

CREATE TABLE IF NOT EXISTS `rim` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `recipient` varchar(100) DEFAULT NULL,
  `id_company` int(20) DEFAULT NULL,
  `theme` varchar(250) NOT NULL,
  `date` varchar(30) NOT NULL,
  `time` varchar(30) NOT NULL,
  `performer` varchar(100) NOT NULL,
  `status` varchar(70) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_company` (`id_company`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sale`
--

CREATE TABLE IF NOT EXISTS `sale` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `customer_id` int(20) DEFAULT NULL COMMENT 'необходимо для привязвания продажи, и поиска контактов этого клиентов',
  `name_sale` varchar(100) DEFAULT NULL,
  `number` varchar(50) DEFAULT NULL,
  `responsibility` varchar(100) DEFAULT NULL,
  `performer` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT '',
  `start_deal` varchar(20) DEFAULT '',
  `time_start` varchar(20) DEFAULT NULL,
  `time_end` varchar(20) DEFAULT NULL,
  `end_deal` varchar(20) DEFAULT '',
  `type_sale` varchar(100) DEFAULT '',
  `open_project` varchar(100) DEFAULT NULL,
  `start_project` varchar(30) DEFAULT NULL,
  `end_project` varchar(30) DEFAULT NULL,
  `plan` varchar(100) DEFAULT NULL,
  `debt` varchar(200) DEFAULT '',
  `cost` varchar(200) DEFAULT '',
  `date_shipment` varchar(20) DEFAULT '',
  `contract_1c` varchar(200) DEFAULT '',
  `account_1c` varchar(200) DEFAULT '',
  `comment` text,
  `id_document` int(15) DEFAULT NULL,
  `failure` tinyint(1) DEFAULT NULL,
  `failure_cause` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `id_document` (`id_document`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=102 ;

-- --------------------------------------------------------

--
-- Структура таблицы `sector`
--

CREATE TABLE IF NOT EXISTS `sector` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `id_registred_company` int(20) NOT NULL,
  `sector` varchar(100) NOT NULL,
  `cat_right` int(20) NOT NULL,
  `cat_left` int(20) NOT NULL,
  `cat_level` int(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=60 ;

-- --------------------------------------------------------

--
-- Структура таблицы `segment`
--

CREATE TABLE IF NOT EXISTS `segment` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `id_customer` int(20) NOT NULL,
  `segment` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_sector` (`id_customer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(15) NOT NULL AUTO_INCREMENT,
  `surname` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `second_name` varchar(50) DEFAULT NULL,
  `email` varchar(30) NOT NULL,
  `gender` enum('male','female') DEFAULT 'male',
  `login` varchar(25) NOT NULL,
  `password` varchar(80) NOT NULL,
  `salt` varchar(40) DEFAULT NULL,
  `activation_code` varchar(80) DEFAULT NULL,
  `forgotten_password_code` varchar(40) DEFAULT NULL,
  `forgotten_password_time` int(11) DEFAULT NULL,
  `remember_code` varchar(40) DEFAULT NULL,
  `created_on` int(11) NOT NULL,
  `last_login` int(11) DEFAULT NULL,
  `last_time` int(11) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `birthday` varchar(20) DEFAULT '',
  `show_birthday` enum('yes','no') DEFAULT 'no',
  `id_contact_info` int(11) DEFAULT NULL,
  `id_work_place` int(15) DEFAULT NULL,
  `id_passport` int(15) DEFAULT NULL,
  `address_id` int(20) DEFAULT NULL,
  `INN` varchar(30) DEFAULT NULL,
  `SNILS` varchar(30) DEFAULT NULL,
  `id_bank_details` int(15) DEFAULT NULL,
  `description` text,
  `interface_language` varchar(100) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL DEFAULT 'default.png',
  `id_registred_company` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `address_id` (`address_id`),
  KEY `id_registred_company` (`id_registred_company`),
  KEY `id_contact_info` (`id_contact_info`),
  KEY `id_bank_details` (`id_bank_details`),
  KEY `id_work_place` (`id_work_place`,`id_passport`),
  KEY `id_passport` (`id_passport`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Структура таблицы `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `user_id` int(8) NOT NULL,
  `group_id` int(8) NOT NULL,
  `work` tinyint(1) NOT NULL DEFAULT '1',
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `head` tinyint(1) NOT NULL DEFAULT '0',
  `append` tinyint(1) NOT NULL DEFAULT '1',
  `edit` tinyint(1) NOT NULL DEFAULT '1',
  `delete` tinyint(1) NOT NULL DEFAULT '1',
  `see_all` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Структура таблицы `work_place`
--

CREATE TABLE IF NOT EXISTS `work_place` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `route` varchar(100) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL,
  `dinner_time` varchar(15) DEFAULT NULL,
  `work_mode` varchar(30) DEFAULT NULL,
  `reception_day` varchar(30) DEFAULT NULL,
  `fired_day` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=71 ;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `attach`
--
ALTER TABLE `attach`
  ADD CONSTRAINT `attach_ibfk_1` FOREIGN KEY (`id_plans`) REFERENCES `plans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact_ibfk_10` FOREIGN KEY (`id_work_place`) REFERENCES `work_place` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contact_ibfk_11` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contact_ibfk_6` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `contact_ibfk_7` FOREIGN KEY (`id_passport`) REFERENCES `passport` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contact_ibfk_8` FOREIGN KEY (`id_bank_details`) REFERENCES `banking_details` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contact_ibfk_9` FOREIGN KEY (`id_contact_info`) REFERENCES `contact_info` (`id`) ON DELETE SET NULL;

--
-- Ограничения внешнего ключа таблицы `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_10` FOREIGN KEY (`id_bank_details`) REFERENCES `banking_details` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `customer_ibfk_5` FOREIGN KEY (`id_contact_info`) REFERENCES `contact_info` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `customer_ibfk_6` FOREIGN KEY (`id_address`) REFERENCES `address` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `customer_ibfk_8` FOREIGN KEY (`id_passport`) REFERENCES `passport` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `customer_ibfk_9` FOREIGN KEY (`id_work_place`) REFERENCES `work_place` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`id_sale`) REFERENCES `sale` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`id_sale`) REFERENCES `sale` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `phase`
--
ALTER TABLE `phase`
  ADD CONSTRAINT `phase_ibfk_1` FOREIGN KEY (`id_sale`) REFERENCES `sale` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `plans`
--
ALTER TABLE `plans`
  ADD CONSTRAINT `plans_ibfk_1` FOREIGN KEY (`sale_name`) REFERENCES `sale` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `plans_ibfk_2` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `plans_ibfk_3` FOREIGN KEY (`id_contact`) REFERENCES `contact` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Ограничения внешнего ключа таблицы `plan_of_payment`
--
ALTER TABLE `plan_of_payment`
  ADD CONSTRAINT `plan_of_payment_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `plan_of_phase`
--
ALTER TABLE `plan_of_phase`
  ADD CONSTRAINT `plan_of_phase_ibfk_1` FOREIGN KEY (`id_pos`) REFERENCES `plan_of_sale` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `plan_of_sale`
--
ALTER TABLE `plan_of_sale`
  ADD CONSTRAINT `plan_of_sale_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`id_product`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `prognosis`
--
ALTER TABLE `prognosis`
  ADD CONSTRAINT `prognosis_ibfk_1` FOREIGN KEY (`id_sale`) REFERENCES `sale` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `rim`
--
ALTER TABLE `rim`
  ADD CONSTRAINT `rim_ibfk_1` FOREIGN KEY (`id_company`) REFERENCES `customer` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Ограничения внешнего ключа таблицы `sale`
--
ALTER TABLE `sale`
  ADD CONSTRAINT `sale_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `sale_ibfk_6` FOREIGN KEY (`id_document`) REFERENCES `documents` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `user_ibfk_2` FOREIGN KEY (`id_registred_company`) REFERENCES `registred_company` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_ibfk_6` FOREIGN KEY (`id_bank_details`) REFERENCES `banking_details` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `user_ibfk_7` FOREIGN KEY (`id_contact_info`) REFERENCES `contact_info` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `user_ibfk_8` FOREIGN KEY (`id_work_place`) REFERENCES `work_place` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `user_ibfk_9` FOREIGN KEY (`id_passport`) REFERENCES `passport` (`id`) ON DELETE SET NULL;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
