SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE TABLE IF NOT EXISTS `art` (
  `id` int(11) NOT NULL auto_increment,
  `md5` varchar(64) collate utf8_unicode_ci NOT NULL,
  `thumb` varchar(64) collate utf8_unicode_ci NOT NULL,
  `extension` varchar(16) collate utf8_unicode_ci NOT NULL,
  `resized` varchar(32) collate utf8_unicode_ci NOT NULL,
  `author` text collate utf8_unicode_ci NOT NULL,
  `category` text collate utf8_unicode_ci NOT NULL,
  `tag` text collate utf8_unicode_ci NOT NULL,
  `pool` text collate utf8_unicode_ci NOT NULL,
  `translator` varchar(256) collate utf8_unicode_ci NOT NULL,
  `source` varchar(4096) collate utf8_unicode_ci NOT NULL,
  `comment_count` int(11) NOT NULL,
  `last_comment` bigint(16) NOT NULL,
  `pretty_date` varchar(256) collate utf8_unicode_ci NOT NULL,
  `sortdate` bigint(16) NOT NULL,
  `area` varchar(32) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `md5` (`md5`),
  KEY `area` (`area`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `art_pool` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(256) collate utf8_unicode_ci NOT NULL,
  `text` text collate utf8_unicode_ci NOT NULL,
  `pretty_text` text collate utf8_unicode_ci NOT NULL,
  `count` int(11) NOT NULL,
  `art` text collate utf8_unicode_ci NOT NULL,
  `password` varchar(32) collate utf8_unicode_ci NOT NULL,
  `email` varchar(256) collate utf8_unicode_ci NOT NULL,
  `sortdate` bigint(16) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `sortdate` (`sortdate`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `art_translation` (
  `id` int(11) NOT NULL auto_increment,
  `art_id` int(11) NOT NULL,
  `data` text collate utf8_unicode_ci NOT NULL,
  `author` varchar(256) collate utf8_unicode_ci NOT NULL,
  `pretty_date` varchar(128) collate utf8_unicode_ci NOT NULL,
  `sortdate` bigint(16) NOT NULL,
  `active` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `art_id` (`art_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `author` (
  `id` int(11) NOT NULL auto_increment,
  `alias` varchar(64) collate utf8_unicode_ci NOT NULL,
  `name` varchar(64) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL auto_increment,
  `alias` varchar(32) collate utf8_unicode_ci NOT NULL,
  `name` varchar(32) collate utf8_unicode_ci NOT NULL,
  `area` varchar(128) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

INSERT INTO `category` (`id`, `alias`, `name`, `area`) VALUES
(2, 'art', 'Арт', '|post|'),
(3, 'video', 'Видео', '|post|'),
(4, 'photo', 'Фото', '|post|art|'),
(1, 'none', 'Прочее', '|post|video|art|'),
(5, 'games', 'Игры', '|post|video|'),
(6, 'literature', 'Литература', '|post|'),
(7, 'manga', 'Манга', '|post|art|'),
(8, 'music', 'Музыка', '|post|video|'),
(9, 'soft', 'Программы', '|post|'),
(11, 'nsfw', 'Для взрослых', '|post|video|art|'),
(10, 'game_cg', 'Из игры', '|art|'),
(13, '3d', '3D графика', '|video|art|');

CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL auto_increment,
  `rootparent` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `place` varchar(32) collate utf8_unicode_ci NOT NULL,
  `post_id` varchar(256) collate utf8_unicode_ci NOT NULL,
  `username` varchar(256) collate utf8_unicode_ci NOT NULL,
  `email` varchar(256) collate utf8_unicode_ci NOT NULL,
  `ip` varchar(16) collate utf8_unicode_ci NOT NULL,
  `text` text collate utf8_unicode_ci NOT NULL,
  `pretty_text` text collate utf8_unicode_ci NOT NULL,
  `pretty_date` varchar(256) collate utf8_unicode_ci NOT NULL,
  `sortdate` bigint(16) NOT NULL,
  `area` varchar(32) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `post_id` (`post_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `cron` (
  `id` int(11) NOT NULL auto_increment,
  `time` int(11) NOT NULL,
  `function` varchar(100) collate utf8_unicode_ci NOT NULL,
  `period` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

INSERT INTO `cron` (`id`, `time`, `function`, `period`) VALUES
(1, 0, 'gouf_check', 60),
(2, 0, 'gouf_refresh_links', 86400),
(3, 0, 'clean_tags', 86400),
(4, 0, 'send_mails', 3600),
(5, 0, 'close_orders', 86400),
(6, 0, 'clean_settings', 3600),
(7, 0, 'add_to_search', 3600),
(8, 0, 'update_search', 60),
(9, 0, 'check_dropout_search', 86400),
(10, 0, 'search_balance_weights', 864000);

CREATE TABLE IF NOT EXISTS `gouf_base` (
  `id` int(11) NOT NULL auto_increment,
  `alias` varchar(255) collate utf8_unicode_ci NOT NULL,
  `text` varchar(510) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

INSERT INTO `gouf_base` (`id`, `alias`, `text`) VALUES
(4, 'mediafire.com', 'Preparing download...'),
(2, 'narod.ru', '<input type="hidden" name="action" value="sendcapcha" />'),
(3, 'megaupload.com', 'but_dnld_file.gif|<center>The file you are trying to access is temporarily unavailable.</center>|<center>Файл, который Вы пытаетесь открыть, временно недоступен|id="downloadlink"'),
(1, 'mediafire.com/?sharekey=', ' '),
(7, '4shared.com', '<font>Скачать</font>');

CREATE TABLE IF NOT EXISTS `gouf_links` (
  `id` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL,
  `post_title` varchar(255) collate utf8_unicode_ci NOT NULL,
  `checkdate` int(11) NOT NULL,
  `status` varchar(255) collate utf8_unicode_ci NOT NULL,
  `link` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `input_filter` (
  `id` int(11) NOT NULL auto_increment,
  `md5` varchar(64) collate utf8_unicode_ci NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `md5` (`md5`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `language` (
  `id` int(11) NOT NULL auto_increment,
  `alias` varchar(32) collate utf8_unicode_ci NOT NULL,
  `name` varchar(32) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

INSERT INTO `language` (`id`, `alias`, `name`) VALUES
(1, 'none', 'Не указан'),
(2, 'japanese', 'Японский'),
(3, 'english', 'Английский'),
(4, 'russian', 'Русский'),
(5, 'korean', 'Корейский'),
(6, 'nolanguage', 'Не требуется');

CREATE TABLE IF NOT EXISTS `logs` (
  `id` int(11) NOT NULL auto_increment,
  `cache` mediumtext collate utf8_unicode_ci NOT NULL,
  `year` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `day` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `year` (`year`),
  KEY `month` (`month`),
  KEY `day` (`day`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `misc` (
  `id` int(11) NOT NULL auto_increment,
  `type` varchar(64) collate utf8_unicode_ci NOT NULL,
  `data1` text collate utf8_unicode_ci NOT NULL,
  `data2` text collate utf8_unicode_ci NOT NULL,
  `data3` text collate utf8_unicode_ci NOT NULL,
  `data4` text collate utf8_unicode_ci NOT NULL,
  `data5` text collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=22 ;

INSERT INTO `misc` (`id`, `type`, `data1`, `data2`, `data3`, `data4`, `data5`) VALUES
(1, 'logs_start', '2010', '5', '30', '', ''),
(20, 'site_alias', 'youtube.com', 'YouTube', '', '', ''),
(21, 'site_alias', 'imagehost.org', 'ImageHost', '', '', ''),
(22, 'site_alias', 'narod.ru', 'Яндекс.Диск', '', '', ''),
(23, 'site_alias', 'mediafire.com', 'MediaFire', '', '', ''),
(24, 'site_alias', 'megaupload.com', 'MegaUpload', '', '', ''),
(15, 'site_alias', 'rapidshare.com', 'Rapidshare', '', '', ''),
(16, 'site_alias', 'blame.ru', 'Blame!ru', '', '', ''),
(17, 'site_alias', 'depositfiles.com', 'DepositFiles', '', '', ''),
(18, 'site_alias', '4shared.com', '4shared', '', '', ''),
(19, 'site_alias', 'wikipedia.org', 'Wikipedia', '', '', '');

CREATE TABLE IF NOT EXISTS `morphy_cache` (
  `word` varchar(128) collate utf8_unicode_ci NOT NULL,
  `cache` varchar(128) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`word`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL auto_increment,
  `url` varchar(256) collate utf8_unicode_ci NOT NULL,
  `title` varchar(256) collate utf8_unicode_ci NOT NULL,
  `text` text collate utf8_unicode_ci NOT NULL,
  `pretty_text` text collate utf8_unicode_ci NOT NULL,
  `image` varchar(256) collate utf8_unicode_ci NOT NULL,
  `comment_count` int(11) NOT NULL,
  `last_comment` bigint(16) NOT NULL,
  `pretty_date` varchar(256) collate utf8_unicode_ci NOT NULL,
  `sortdate` bigint(16) NOT NULL,
  `area` varchar(32) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `url` (`url`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(256) collate utf8_unicode_ci NOT NULL,
  `username` varchar(256) collate utf8_unicode_ci NOT NULL,
  `email` varchar(256) collate utf8_unicode_ci NOT NULL,
  `spam` tinyint(4) NOT NULL,
  `text` text collate utf8_unicode_ci NOT NULL,
  `pretty_text` text collate utf8_unicode_ci NOT NULL,
  `link` varchar(4096) collate utf8_unicode_ci NOT NULL,
  `category` text collate utf8_unicode_ci NOT NULL,
  `comment_count` int(11) NOT NULL,
  `last_comment` bigint(16) NOT NULL,
  `pretty_date` varchar(256) collate utf8_unicode_ci NOT NULL,
  `sortdate` bigint(16) NOT NULL,
  `area` varchar(32) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(256) collate utf8_unicode_ci NOT NULL,
  `text` text collate utf8_unicode_ci NOT NULL,
  `pretty_text` text collate utf8_unicode_ci NOT NULL,
  `image` varchar(256) collate utf8_unicode_ci NOT NULL,
  `link` text collate utf8_unicode_ci NOT NULL,
  `info` text collate utf8_unicode_ci NOT NULL,
  `file` text collate utf8_unicode_ci NOT NULL,
  `author` text collate utf8_unicode_ci NOT NULL,
  `category` text collate utf8_unicode_ci NOT NULL,
  `language` text collate utf8_unicode_ci NOT NULL,
  `tag` text collate utf8_unicode_ci NOT NULL,
  `comment_count` int(11) NOT NULL,
  `last_comment` bigint(16) NOT NULL,
  `update_count` int(11) NOT NULL,
  `pretty_date` varchar(256) collate utf8_unicode_ci NOT NULL,
  `sortdate` bigint(16) NOT NULL,
  `area` varchar(32) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `area` (`area`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `search` (
  `id` int(11) NOT NULL auto_increment,
  `place` varchar(16) collate utf8_unicode_ci NOT NULL,
  `item_id` int(11) NOT NULL,
  `index` longtext collate utf8_unicode_ci NOT NULL,
  `area` varchar(16) collate utf8_unicode_ci NOT NULL,
  `sortdate` bigint(16) NOT NULL,
  `lastupdate` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `table` (`place`),
  KEY `sortdate` (`sortdate`),
  FULLTEXT KEY `index` (`index`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `search_queries` (
  `id` int(11) NOT NULL auto_increment,
  `query` varchar(255) collate utf8_unicode_ci NOT NULL,
  `length` int(11) NOT NULL,
  `post` int(11) NOT NULL default '0',
  `video` int(11) NOT NULL default '0',
  `art` int(11) NOT NULL default '0',
  `comment` int(11) NOT NULL default '0',
  `news` int(11) NOT NULL default '0',
  `orders` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `query` (`query`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE `4otaku`.`search_weights` (
`place` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL ,
`weight` FLOAT NOT NULL DEFAULT '1',
PRIMARY KEY ( `place` )
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL auto_increment,
  `cookie` varchar(64) collate utf8_unicode_ci NOT NULL,
  `data` text collate utf8_unicode_ci NOT NULL,
  `lastchange` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `cookie` (`cookie`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(11) NOT NULL auto_increment,
  `alias` varchar(64) collate utf8_unicode_ci NOT NULL,
  `name` varchar(64) collate utf8_unicode_ci NOT NULL,
  `variants` text collate utf8_unicode_ci NOT NULL,
  `color` varchar(6) collate utf8_unicode_ci NOT NULL,
  `post_main` int(11) NOT NULL,
  `post_flea_market` int(11) NOT NULL,
  `video_main` int(11) NOT NULL,
  `video_flea_market` int(11) NOT NULL,
  `art_main` int(11) NOT NULL,
  `art_flea_market` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `alias` (`alias`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `updates` (
  `id` int(11) NOT NULL auto_increment,
  `post_id` int(11) NOT NULL,
  `username` varchar(256) collate utf8_unicode_ci NOT NULL,
  `text` text collate utf8_unicode_ci NOT NULL,
  `pretty_text` text collate utf8_unicode_ci NOT NULL,
  `link` text collate utf8_unicode_ci NOT NULL,
  `pretty_date` varchar(256) collate utf8_unicode_ci NOT NULL,
  `sortdate` bigint(16) NOT NULL,
  `area` varchar(16) collate utf8_unicode_ci NOT NULL default 'main',
  PRIMARY KEY  (`id`),
  KEY `post_id` (`post_id`,`sortdate`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL auto_increment,
  `login` varchar(64) collate utf8_unicode_ci NOT NULL,
  `pass` varchar(32) collate utf8_unicode_ci NOT NULL,
  `rights` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

INSERT INTO `user` (`id`, `login`, `pass`, `rights`) VALUES
(1, 'admin', 'd8578edf8458ce06fbc5bb76a58c5ca4', 2),
(2, 'moderator', 'd8578edf8458ce06fbc5bb76a58c5ca4', 1);

CREATE TABLE IF NOT EXISTS `versions` (
  `id` int(11) NOT NULL auto_increment,
  `type` varchar(16) collate utf8_unicode_ci NOT NULL,
  `item_id` int(11) NOT NULL,
  `data` longtext collate utf8_unicode_ci NOT NULL,
  `time` bigint(16) NOT NULL,
  `author` varchar(256) collate utf8_unicode_ci NOT NULL,
  `ip` varchar(16) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `type` (`type`,`item_id`,`time`,`author`(255),`ip`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `video` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(256) collate utf8_unicode_ci NOT NULL,
  `link` varchar(4096) collate utf8_unicode_ci NOT NULL,
  `object` text collate utf8_unicode_ci NOT NULL,
  `text` text collate utf8_unicode_ci NOT NULL,
  `pretty_text` text collate utf8_unicode_ci NOT NULL,
  `author` text collate utf8_unicode_ci NOT NULL,
  `category` text collate utf8_unicode_ci NOT NULL,
  `tag` text collate utf8_unicode_ci NOT NULL,
  `comment_count` int(11) NOT NULL,
  `last_comment` bigint(16) NOT NULL,
  `pretty_date` varchar(256) collate utf8_unicode_ci NOT NULL,
  `sortdate` bigint(16) NOT NULL,
  `area` varchar(32) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `area` (`area`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
