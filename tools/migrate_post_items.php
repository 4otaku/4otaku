<?php
die;
include '../inc.common.php';

ob_end_clean();

$posts = Database::get_full_vector('post');

foreach ($posts as $id => $post) {
	$links = (array) unserialize($post['link']);
	$extras = (array) unserialize($post['info']);
	$files = (array) unserialize($post['file']);
	$images = array_filter(explode('|', $post['image']));

	$i = 0;
	foreach ($links as $link) {
		if (empty($link['url'])) {
			continue;
		}

		switch ($link['sizetype']) {
			case 'гб': $type = 2; break;
			case 'мб': $type = 1; break;
			case 'кб': $type = 0; break;
			default: $type = 0;	break;
		}

		Database::insert('post_link', array(
			'post_id' => $id,
			'name' => $link['name'],
			'size' => str_replace(',', '.', $link['size']),
			'sizetype' => $type,
			'order' => $i,
		));

		$link_id = Database::last_id();

		$i++;

		$j = 0;
		foreach ($link['url'] as $key => $url) {

			$alias = $link['alias'][$key];

			$exists = Database::get_full_row('post_url', 'url = ?', $url);
			if ($exists) {
				$url_id = $exists['id'];
			} else {

				Database::insert('post_url', array(
					'url' => $url,
				));

				$url_id = Database::last_id();
			}

			if ($url_id == 0) {
				var_dump($post);
				var_dump($link);
			}

			Database::insert('post_link_url', array(
				'link_id' => $link_id,
				'url_id' => $url_id,
				'alias' => $alias,
				'order' => $j
			));

			$j++;
		}
	}

	$i = 0;
	foreach ($extras as $extra) {
		if (empty($extra['url'])) {
			continue;
		}

		Database::insert('post_extra', array(
			'post_id' => $id,
			'name' => $extra['name'],
			'alias' => $extra['alias'],
			'url' => $extra['url'],
			'order' => $i,
		));

		$i++;
	}

	$i = 0;
	foreach ($files as $file) {
		$size = explode(' ', $file['size']);

		switch ($size[1]) {
			case 'гб': $sizetype = 3; break;
			case 'мб': $sizetype = 2; break;
			case 'кб': $sizetype = 1; break;
			case 'байт': $sizetype = 0; break;
			default: $sizetype = 0;	break;
		}

		switch ($file['type']) {
			case "audio": $type = 2; break;
			case "image": $type = 1; break;
			case "plain": $type = 0; break;
			default: $type = 0;	break;
		}

		Database::insert('post_file', array(
			'post_id' => $id,
			'type' => $type,
			'name' => $file['name'],
			'folder' => $file['folder'],
			'file' => $file['filename'],
			'size' => str_replace(',', '.', $size[0]),
			'sizetype' => $sizetype,
			'order' => $i,
		));

		$i++;
	}

	$i = 0;
	foreach ($images as $image) {
		$file = IMAGES.SL.'full'.SL.$image;
		$sizes = getimagesize($file);
		$weight = filesize($file);

		$name = explode('.', $image);

		Database::insert('post_image', array(
			'post_id' => $id,
			'file' => $name[0],
			'extension' => $name[1],
			'width' => $sizes[0],
			'height' => $sizes[1],
			'weight' => $weight,
			'order' => $i,
		));

		rename($file, IMAGES.SL.'post'.SL.'full'.SL.$image);
		rename(IMAGES.SL.'thumbs'.SL.$name[0].'.jpg', IMAGES.SL.'post'.SL.'thumb'.SL.$name[0].'.jpg');

		$i++;
	}
}

$updates = Database::get_full_vector('updates');

foreach ($updates as $id => $update) {
	Database::insert('post_update', array(
		'id' => $id,
		'post_id' => $update['post_id'],
		'username' => $update['username'],
		'text' => $update['text'],
		'pretty_text' => $update['pretty_text'],
		'pretty_date' => $update['pretty_date'],
		'sortdate' => $update['sortdate'],
		'area' => $update['area'],
	));

	$links = (array) unserialize($update['link']);

	$i = 0;
	foreach ($links as $link) {
		if (empty($link['url'])) {
			continue;
		}

		switch ($link['sizetype']) {
			case 'гб': $type = 2; break;
			case 'мб': $type = 1; break;
			case 'кб': $type = 0; break;
			default: $type = 0;	break;
		}

		Database::insert('post_update_link', array(
			'update_id' => $id,
			'name' => $link['name'],
			'size' => str_replace(',', '.', $link['size']),
			'sizetype' => $type,
			'order' => $i,
		));

		$link_id = Database::last_id();

		$i++;

		$j = 0;
		foreach ($link['url'] as $key => $url) {

			$alias = $link['alias'][$key];

			$exists = Database::get_full_row('post_url', 'url = ?', $url);
			if ($exists) {
				$url_id = $exists['id'];
			} else {

				Database::insert('post_url', array(
					'url' => $url,
				));

				$url_id = Database::last_id();
			}

			if ($url_id == 0) {
				var_dump($update);
				var_dump($link);
			}

			Database::insert('post_update_link_url', array(
				'link_id' => $link_id,
				'url_id' => $url_id,
				'alias' => $alias,
				'order' => $j
			));

			$j++;
		}
	}
}

Database::delete('cron', 'function = ?', 'gouf_check');
Database::delete('cron', 'function = ?', 'gouf_refresh_links');
Database::insert('cron', array('function' => 'Post_Gouf::check', 'period' => '1m'));
Database::insert('cron', array('function' => 'Post::delete_unused_urls', 'period' => '1h'));
