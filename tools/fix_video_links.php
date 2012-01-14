<?php
die;
include '../inc.common.php';

$videos = Database::get_full_vector('video', 'link = ?', '***');

foreach ($videos as $id => $video) {
	if (preg_match('/src="[^"]+youtube.*v\/([^"&]+)/ui', $video['object'], $link)) {
		$link = 'http://www.youtube.com/watch?v='.$link[1];
	} elseif (preg_match('/src="[^"]+nicovideo.*\/([^"\/]+)"/ui', $video['object'], $link)) {
		$link = 'http://www.nicovideo.jp/watch/'.$link[1];
	} elseif (preg_match('/id.3D([^&]+)&amp;searchbar=false/ui', $video['object'], $link)) {
		$link = 'http://amvnews.ru/index.php?go=Files&in=view&id='.$link[1];
	} elseif (preg_match('/mid=([^"]+)/ui', $video['object'], $link)) {
		$link = 'http://www.gametrailers.com/video/lost_url_part/'.$link[1];
	} else {
		$link = false;
	}

	if (!empty($link)) {
		Database::update('video', array('link' => $link), $id);
	}
}

