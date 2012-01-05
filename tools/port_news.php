<?php

include '../inc.common.php';

ob_end_clean();

$news = Database::get_full_vector('news');

foreach ($news as $id => $item) {
	rename(IMAGES.SL.'full'.SL.$item['image'],
		IMAGES.SL.'news'.SL.'full'.SL.$item['image']);
	rename(IMAGES.SL.'thumbs'.SL.$item['image'],
		IMAGES.SL.'news'.SL.'thumb'.SL.preg_replace('/\.[a-z]+$/ui', '.jpg', $item['image']));
}

Database::sql('ALTER TABLE `news` DROP `url`;');
Database::sql('ALTER TABLE  `news` ADD  `author` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `image` ,
ADD  `category` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `author`;');
Database::sql('update news set category = \'|website|\' where 1;');
Database::sql('update news set author = \'|nameless|\' where 1;');

Database::sql('INSERT INTO  `category` (`id` ,`alias` ,`name` ,`area`)VALUES (NULL ,  \'website\',  \'Новости сайта\',  \'|news|\');');
