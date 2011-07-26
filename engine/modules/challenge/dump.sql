CREATE TABLE IF NOT EXISTS `challenge` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `owner` int(10) unsigned NOT NULL,
  `title` varchar(512) NOT NULL,
  `text` text NOT NULL,
  `pretty_text` text NOT NULL,
  `challenge_start` datetime NOT NULL,
  `challenge_stop` datetime NOT NULL,
  `adding_start` datetime NOT NULL,
  `adding_stop` datetime NOT NULL,
  `settings` text NOT NULL,
  `comments` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `challenge_attachment` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `challenge_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `data` text NOT NULL,
  `comments` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `challenge_result` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `challenge_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `place` int(10) unsigned NOT NULL,
  `status` varchar(512) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `challenge_update` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `challenge_id` int(10) unsigned NOT NULL,
  `title` varchar(512) NOT NULL,
  `text` text NOT NULL,
  `pretty_text` text NOT NULL,
  `comments` int(10) unsigned NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `challenge_user` (
  `challenge_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `data` text character set utf8 NOT NULL,
  PRIMARY KEY  (`challenge_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
