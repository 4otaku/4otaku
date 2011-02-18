SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

DROP TABLE IF EXISTS `data`;
CREATE TABLE `data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(32) NOT NULL,
  `item_id` int(10) unsigned NOT NULL,
  `item_type` varchar(16) NOT NULL,
  `area` varchar(16) DEFAULT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `direct` (`item_id`,`item_type`),
  KEY `search` (`type`,`area`,`data`(255)),
  KEY `pseudo_unique` (`item_id`,`item_type`,`type`,`data`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `helper`;
CREATE TABLE `helper` (
  `id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `helper` (`id`) VALUES (1);

DROP TABLE IF EXISTS `meta`;
CREATE TABLE `meta` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `alias` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(6) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Tags only',
  `item_type` varchar(16) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'Categories only',
  PRIMARY KEY (`id`),
  UNIQUE KEY `identity` (`type`,`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `tag_variants`;
CREATE TABLE `tag_variants` (
  `alias` varchar(255) NOT NULL,
  `variant` varchar(255) NOT NULL,
  PRIMARY KEY (`alias`),
  UNIQUE KEY `variant` (`variant`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
