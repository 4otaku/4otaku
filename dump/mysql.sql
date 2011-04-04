SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

DROP TABLE IF EXISTS `<pr>meta`;
CREATE TABLE `<pr>meta` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `color` varchar(6) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT 'Tags only',
  `area` varchar(16) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT 'Categories only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `identity` (`type`,`alias`,`area`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `<pr>meta_count` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(16) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `module` varchar(16) NOT NULL,
  `area` varchar(16) NOT NULL,
  `count` int(10) unsigned NOT NULL,
  `expires` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`type`,`alias`,`module`,`area`),
  KEY `list_active_tags` (`type`,`count`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `<pr>tag_variants`;
CREATE TABLE `<pr>tag_variants` (
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `variant` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `language` enum('','eng','jap','rus')  CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  UNIQUE KEY `variant` (`variant`),
  KEY `alias` (`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `<pr>post`;
CREATE TABLE `<pr>post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(510) COLLATE utf8_general_ci NOT NULL,
  `text` text COLLATE utf8_general_ci NOT NULL,
  `pretty_text` text COLLATE utf8_general_ci NOT NULL,
  `meta` text COLLATE utf8_general_ci NOT NULL,
  `comments` smallint(5) unsigned NOT NULL,
  `updates` smallint(5) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `area` enum('workshop','main','flea','deleted') COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `selector` (`area`,`date`),
  FULLTEXT KEY `index` (`meta`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `<pr>post_items`;
CREATE TABLE `<pr>post_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned NOT NULL,
  `type` enum('image','link','info','file') NOT NULL,
  `sort_number` int(10) unsigned NOT NULL,
  `data` text NOT NULL,
  `last_check` timestamp NULL default NULL,
  `status` enum('ok','broken','unclear') COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `selector` (`item_id`,`type`,`sort_number`),
  KEY `last_check` (`last_check`),
  KEY `status` (`status`,`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `<pr>video`;
CREATE TABLE `<pr>video` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(510) COLLATE utf8_general_ci NOT NULL,
  `link` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `object` text COLLATE utf8_general_ci NOT NULL,
  `text` text COLLATE utf8_general_ci NOT NULL,
  `pretty_text` text COLLATE utf8_general_ci NOT NULL,
  `meta` text COLLATE utf8_general_ci NOT NULL,
  `comments` smallint(5) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `area` enum('workshop','main','flea','deleted') COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `link` (`link`),
  KEY `selector` (`area`,`date`),
  FULLTEXT KEY `index` (`meta`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `<pr>art`;
CREATE TABLE `<pr>art` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `md5` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `width` smallint(5) unsigned NOT NULL,
  `height` smallint(5) unsigned NOT NULL,
  `weight` mediumint(8) unsigned NOT NULL,
  `resized` float unsigned NOT NULL,
  `extension` varchar(6) COLLATE utf8_general_ci NOT NULL,
  `thumbnail` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `source` varchar(510) COLLATE utf8_general_ci NOT NULL,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `meta` text COLLATE utf8_general_ci NOT NULL,
  `comments` smallint(5) unsigned NOT NULL,
  `variations` smallint(5) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `area` enum('workshop','main','flea','cg','sprites','variation','deleted') COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `md5` (`md5`),
  KEY `selector` (`area`,`date`),
  FULLTEXT KEY `index` (`meta`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `<pr>art_similar`;
CREATE TABLE IF NOT EXISTS `<pr>art_similar` (
  `id` int(10) unsigned NOT NULL,
  `vector` varchar(2040) NOT NULL,
  `checked` tinyint(1) unsigned NOT NULL,
  `similar` varchar(4080) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `<pr>art_pool`;
CREATE TABLE `<pr>art_pool` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `pretty_text` text NOT NULL,
  `count` int(10) unsigned,
  `order` text NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `comments` smallint(5) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `title` (`title`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `<pr>art_cg_pack`;
CREATE TABLE `<pr>art_cg_pack` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `md5` varchar(32) NOT NULL,
  `filename` varchar(2040) NOT NULL,
  `weight` int(10) unsigned NOT NULL,
  `cover` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `pretty_text` text NOT NULL,
  `count` int(10) unsigned,
  `order` text NOT NULL,
  `comments` smallint(5) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `md5` (`md5`),
  UNIQUE KEY `title` (`title`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `<pr>art_translation`;
CREATE TABLE `<pr>art_translation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned NOT NULL,
  `data` text NOT NULL,
  `translator` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `selector` (`item_id`, `active`),
  UNIQUE KEY `sort` (`item_id`, `date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `<pr>comment`;
CREATE TABLE `<pr>comment` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `root` int(10) unsigned NOT NULL,
  `parent` int(10) unsigned NOT NULL,
  `place` varchar(32) character set utf8 NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `area` enum('workshop','main','flea','cg','sprites','variation','open','completed','dropped','deleted') character set utf8 NOT NULL,
  `username` varchar(256) character set utf8 NOT NULL default 'Анонимно',
  `email` varchar(256) character set utf8 NOT NULL default 'default@avatar.mail',
  `ip` int(10) unsigned NOT NULL,
  `cookie` varchar(32) character set utf8 NOT NULL,
  `text` text character set utf8 NOT NULL,
  `pretty_text` text character set utf8 NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `selector` (`place`,`item_id`,`root`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE utf8_general_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `<pr>cron`;
CREATE TABLE `<pr>cron` (
  `name` varchar(64) NOT NULL,
  `last_call` timestamp NULL default NULL on update CURRENT_TIMESTAMP,
  `period` time NOT NULL default '00:01:00',
  `runtime` float unsigned NOT NULL,
  `memory` bigint(20) unsigned NOT NULL,
  `status` enum('idle','process') NOT NULL,
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `<pr>cron` (`name`, `last_call`, `period`, `runtime`, `memory`, `status`) 
VALUES 
('check_links', '', '00:01:00', '', '', 'idle'),
('do_tag_count_cache', '', '00:01:00', '', '', 'idle');

DROP TABLE IF EXISTS `<pr>logs`;
CREATE TABLE `<pr>logs` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `room` varchar(64) NOT NULL,
  `html` mediumtext NOT NULL,
  `text` text NOT NULL,
  `year` smallint(5) unsigned NOT NULL,
  `month` tinyint(3) unsigned NOT NULL,
  `day` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `identity` (`room`,`year`,`month`,`day`),
  FULLTEXT KEY `text` (`text`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `<pr>news`;
CREATE TABLE `<pr>news` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `url` varchar(255) character set utf8 collate utf8_general_ci NOT NULL,
  `title` varchar(510) character set utf8 collate utf8_general_ci NOT NULL,
  `text` text character set utf8 collate utf8_general_ci NOT NULL,
  `pretty_text` text character set utf8 collate utf8_general_ci NOT NULL,
  `image` varchar(255) character set utf8 collate utf8_general_ci NOT NULL,
  `comments` smallint(5) unsigned NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `area` enum('workshop','main') character set utf8 collate utf8_general_ci NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `url` (`url`),
  KEY `selector` (`area`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `<pr>order`;
CREATE TABLE `<pr>order` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(510) character set utf8 collate utf8_unicode_ci NOT NULL,
  `username` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default 'Анонимус',
  `email` varchar(255) character set utf8 collate utf8_unicode_ci NOT NULL default 'default@avatar.mail',
  `spam` tinyint(1) unsigned NOT NULL,
  `text` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `pretty_text` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `meta` text NOT NULL,
  `comments` smallint(5) unsigned NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `area` enum('open','completed','dropped','deleted') NOT NULL default 'open',
  PRIMARY KEY  (`id`),
  KEY `selector` (`area`,`date`),
  FULLTEXT KEY `index` (`meta`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `<pr>order_links`;
CREATE TABLE `<pr>order_links` (
  `order_id` int(10) unsigned NOT NULL,
  `sort` smallint(5) unsigned NOT NULL,
  `link` text NOT NULL,
  PRIMARY KEY  (`order_id`,`sort`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `<pr>order_links`;
CREATE TABLE IF NOT EXISTS `<pr>cache` (
  `key` varchar(255) NOT NULL,
  `value` longtext NOT NULL,
  `expires` datetime NOT NULL,
  PRIMARY KEY (`key`),
  KEY `selector` (`expires`,`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `<pr>cookie`;
CREATE TABLE IF NOT EXISTS `<pr>cookie` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cookie` varchar(32) NOT NULL,
  `section` varchar(16) NOT NULL,
  `data` text NOT NULL,
  `expires` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`cookie`,`section`),
  KEY `expires` (`expires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
