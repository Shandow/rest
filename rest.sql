-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Авг 22 2016 г., 22:30
-- Версия сервера: 5.6.15-log
-- Версия PHP: 5.5.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `rest`
--

-- --------------------------------------------------------

--
-- Структура таблицы `coupons`
--

CREATE TABLE IF NOT EXISTS `coupons` (
  `coupon_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `code` varchar(32) DEFAULT NULL,
  `merchant_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`coupon_id`),
  KEY `merchant_id` (`merchant_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `coupons`
--

INSERT INTO `coupons` (`coupon_id`, `title`, `code`, `merchant_id`, `user_id`) VALUES
(1, 'Coupon1', '12345', 1, NULL),
(2, 'Coupon2', '123456', 1, NULL),
(3, 'Coupon3', '54321', 1, 1),
(4, 'Coupon4', '547657', 2, 2);

-- --------------------------------------------------------

--
-- Структура таблицы `merchants`
--

CREATE TABLE IF NOT EXISTS `merchants` (
  `merchant_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`merchant_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `merchants`
--

INSERT INTO `merchants` (`merchant_id`, `name`, `description`) VALUES
(1, 'Merchant 1', 'Description'),
(2, 'Merchant 2', 'Description');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(32) DEFAULT NULL,
  `token` varchar(32) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `token`, `created`) VALUES
(1, 'User 1', 'user1@email.com', '1234', 'token1', '2016-08-22 20:14:53'),
(2, 'User2', 'user2@email.com', '1234', 'token2', '2016-08-22 20:14:53');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `coupons`
--
ALTER TABLE `coupons`
  ADD CONSTRAINT `coupons_ibfk_1` FOREIGN KEY (`merchant_id`) REFERENCES `merchants` (`merchant_id`),
  ADD CONSTRAINT `coupons_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
