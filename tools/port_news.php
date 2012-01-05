<?php

include '../inc.common.php';

ob_end_clean();

$news = Database::get_full_vector('news');

Database::sql('ALTER TABLE  `news` ADD  `extension` VARCHAR( 16 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `image`');

foreach ($news as $id => $item) {
	rename(IMAGES.SL.'full'.SL.$item['image'],
		IMAGES.SL.'news'.SL.'full'.SL.$item['image']);
	rename(IMAGES.SL.'thumbs'.SL.$item['image'],
		IMAGES.SL.'news'.SL.'thumb'.SL.preg_replace('/\.[a-z]+$/ui', '.jpg', $item['image']));

	Database::update('news', array('image' => preg_replace('/\.[a-z]+$/ui', '', $item['image']),
		'extension' => preg_replace('/^.*\./ui', '', $item['image'])), $id);
	Database::update('comment', array('post_id' => $id), 'post_id = ?', $item['url']);
}

Database::sql('ALTER TABLE `news` DROP `url`;');
Database::sql('ALTER TABLE  `news` ADD  `author` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `extension` ,
ADD  `category` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `author`;');
Database::sql('update news set category = \'|website|\' where 1;');
Database::sql('update news set author = \'|nameless|\' where 1;');
Database::sql('update news set pretty_text = \'\' where 1;');

Database::sql('INSERT INTO  `category` (`id` ,`alias` ,`name` ,`area`)VALUES (NULL ,  \'website\',  \'Новости сайта\',  \'|news|\');');
