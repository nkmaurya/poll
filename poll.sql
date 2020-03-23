-- Adminer 4.7.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `poll`;
CREATE TABLE `poll` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `expiration_date` date NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `poll` (`id`, `question`, `expiration_date`, `created`, `updated`) VALUES
(22,	'Do you think Tokyo Olympics should be postponed in the wake of coronavirus outbreak?',	'2020-03-29',	'2020-03-23 18:01:57',	'2020-03-23 18:01:57'),
(23,	'Should there be a complete lockdown in India to contain the spread of coronavirus?',	'2020-03-22',	'2020-03-23 18:05:26',	'2020-03-23 18:05:26'),
(24,	'Should the government accept the demand for complete lockdown to contain coronavirus?',	'2020-03-28',	'2020-03-23 21:04:02',	'2020-03-23 21:04:02');

DROP TABLE IF EXISTS `poll_option`;
CREATE TABLE `poll_option` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `answer` text NOT NULL,
  `color_code` varchar(8) NOT NULL,
  `vote_counter` int(10) NOT NULL DEFAULT '0',
  `display_order` int(2) NOT NULL DEFAULT '0',
  `poll_id` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `poll_id` (`poll_id`),
  CONSTRAINT `poll_option_ibfk_3` FOREIGN KEY (`poll_id`) REFERENCES `poll` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `poll_option` (`id`, `answer`, `color_code`, `vote_counter`, `display_order`, `poll_id`, `created`, `updated`) VALUES
(20,	' A',	'#902626',	2,	1,	23,	'2020-03-23 18:05:26',	'2020-03-23 18:05:26'),
(21,	' B',	'#1c650b',	2,	3,	23,	'2020-03-23 18:05:26',	'2020-03-23 18:05:26'),
(24,	'Yes',	'#ad2e2e',	20,	1,	22,	'2020-03-23 21:03:11',	'2020-03-23 21:03:11'),
(25,	'No',	'#ad2e2e',	20,	2,	22,	'2020-03-23 21:03:11',	'2020-03-23 21:03:11'),
(27,	'Yes',	'#000000',	9,	2,	24,	'2020-03-23 21:41:32',	'2020-03-23 21:41:32'),
(28,	'No',	'#b32a2a',	9,	2,	24,	'2020-03-23 21:41:32',	'2020-03-23 21:41:32');

DROP TABLE IF EXISTS `poll_user`;
CREATE TABLE `poll_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_cookie` varchar(50) NOT NULL,
  `poll_id` int(10) unsigned NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `poll_id` (`poll_id`),
  CONSTRAINT `poll_user_ibfk_3` FOREIGN KEY (`poll_id`) REFERENCES `poll` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


-- 2020-03-23 16:29:39
