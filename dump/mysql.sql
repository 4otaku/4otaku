SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

DROP TABLE IF EXISTS `{pr}helper`;
CREATE TABLE `{pr}helper` (
  `id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `helper` (`id`) VALUES (1);

DROP TABLE IF EXISTS `{pr}meta`;
CREATE TABLE `{pr}meta` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `variants` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Tags only',
  `item_type` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Categories only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `identity` (`type`,`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `{pr}tag_variants`;
CREATE TABLE `{pr}tag_variants` (
  `alias` varchar(255) NOT NULL,
  `variant` varchar(255) NOT NULL,
  PRIMARY KEY (`alias`),
  UNIQUE KEY `variant` (`variant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{pr}post`;
CREATE TABLE `{pr}post` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(510) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `pretty_text` text COLLATE utf8_unicode_ci NOT NULL,
  `meta` text COLLATE utf8_unicode_ci NOT NULL,
  `comments` int(10) unsigned NOT NULL,
  `updates` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `area` enum('workshop','main','flea','deleted') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `selector` (`area`,`date`),
  FULLTEXT KEY `index` (`meta`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `{pr}post_items`;
CREATE TABLE `{pr}post_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `item_id` int(10) unsigned NOT NULL,
  `type` enum('image','link','info','file') NOT NULL,
  `sort_number` int(10) unsigned NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `selector` (`item_id`,`type`,`sort_number`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `{pr}video`;
CREATE TABLE `{pr}video` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(510) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `object` text COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `pretty_text` text COLLATE utf8_unicode_ci NOT NULL,
  `meta` text COLLATE utf8_unicode_ci NOT NULL,
  `comments` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `area` enum('workshop','main','flea','deleted') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `link` (`link`),
  KEY `selector` (`area`,`date`),
  FULLTEXT KEY `index` (`meta`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `{pr}art`;
CREATE TABLE `{pr}art` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `md5` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `width` smallint(5) unsigned NOT NULL,
  `height` smallint(5) unsigned NOT NULL,
  `weight` mediumint(8) unsigned NOT NULL,
  `resized` float unsigned NOT NULL,
  `extension` varchar(6) COLLATE utf8_unicode_ci NOT NULL,
  `thumbnail` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `source` varchar(510) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `meta` text COLLATE utf8_unicode_ci NOT NULL,
  `comments` int(10) unsigned NOT NULL,
  `variations` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `area` enum('workshop','main','flea','cg','sprites','variation','deleted') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `md5` (`md5`),
  KEY `selector` (`area`,`date`),
  FULLTEXT KEY `index` (`meta`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `{pr}art_similar`;
CREATE TABLE IF NOT EXISTS `{pr}art_similar` (
  `id` int(10) unsigned NOT NULL,
  `vector` varchar(2040) NOT NULL,
  `checked` tinyint(1) unsigned NOT NULL,
  `similar` varchar(4080) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `{pr}art_pool`;
CREATE TABL `{pr}art_pool` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `pretty_text` text NOT NULL,
  `count` int(10) unsigned,
  `order` text NOT NULL,
  `password` varchar(32) NOT NULL,
  `email` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `title` (`title`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `{pr}art_cg_pack`;
CREATE TABLE `{pr}art_cg_pack` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `md5` varchar(32) NOT NULL,
  `filename` varchar(2040) NOT NULL,
  `filesize` int(10) unsigned NOT NULL,
  `cover` varchar(32) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `pretty_text` text NOT NULL,
  `count` int(10) unsigned,
  `order` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `md5` (`md5`),
  UNIQUE KEY `title` (`title`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `{pr}art_translation`;
CREATE TABLE `art_translation` (
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
