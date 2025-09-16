<?php

function prepare_catalog_data($data) {
	$catalogData = array();

	if (!empty($data['main']['threads'])) {
		foreach ($data['main']['threads'] as $id => $thread) {
			$catalogData['threads'][$id] = prepare_thread_data($thread, $id);
		}
	}

	return $catalogData;
}

function prepare_thread_data($thread, $threadId) {
	$threadData = array(
		'id' => $threadId,
		'url' => '/board/'.$thread['current_board'].'/thread/'.$threadId,
		'title' => generate_thread_title($thread),
		'content' => array()
	);

	// Определяем тип контента
	if (!empty($thread['content']['video'])) {
		$threadData['content_type'] = 'video';
		$threadData['content'] = reset($thread['content']['video']);
	} elseif (!empty($thread['content']['image'])) {
		$threadData['content_type'] = 'image';
		$threadData['content'] = reset($thread['content']['image']);
	} elseif (!empty($thread['content']['random'])) {
		$threadData['content_type'] = 'random';
		$threadData['content'] = reset($thread['content']['random']);
	} elseif (!empty($thread['content']['flash'])) {
		$threadData['content_type'] = 'flash';
		$threadData['content'] = reset($thread['content']['flash']);
	}

	return $threadData;
}

function generate_thread_title($thread) {
	$title = $thread['name'];

	if (!empty($thread['trip'])) {
		$title .= ' ' . $thread['trip'];
	}

	$title .= ' в разделе ';

	// Добавляем категории
	$categories = array();
	foreach ($thread['categories'] as $board) {
		$categories[] = $board['alias'];
	}
	$title .= implode(' / ', $categories);

	$title .= '; ' . $thread['pretty_date'] . "\n<br />";

	$title .= obj::transform('text')->cut_long_text(strip_tags($thread['text'], '<br><em><strong><s>'), 500);

	return $title;
}

$catalogData = prepare_catalog_data($data);
twig_load_template('board/catalog', $catalogData);
