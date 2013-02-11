<?php

include '../inc.common.php';

$string = 'http://www.pixiv.net/member_illust.php?mode=medium&illust_id=';

$arts = Database::get_vector('art', array('id', 'comment_count'));
$counts = Database::group('post_id')->get_vector('comment', array('post_id', 'count(*)'),
	'area != "deleted" and place = "art"');

foreach ($arts as $id => $count) {
	if (isset($counts[$id]) && $count != $counts[$id]) {
		Database::update('art', array('comment_count' => $counts[$id]), $id);
		echo "<br />У арта №$id число комментариев изменено с $count на " . $counts[$id];
		continue;
	}

	if (!isset($counts[$id]) && $count > 0) {
		Database::update('art', array('comment_count' => 0), $id);
		echo "<br />У арта №$id число комментариев изменено с $count на 0";
		continue;
	}
}

$posts = Database::get_vector('post', array('id', 'comment_count'));
$counts = Database::group('post_id')->get_vector('comment', array('post_id', 'count(*)'),
	'area != "deleted" and place = "post"');

foreach ($posts as $id => $count) {
	if (isset($counts[$id]) && $count != $counts[$id]) {
		Database::update('post', array('comment_count' => $counts[$id]), $id);
		echo "<br />У записи №$id число комментариев изменено с $count на " . $counts[$id];
		continue;
	}

	if (!isset($counts[$id]) && $count > 0) {
		Database::update('post', array('comment_count' => 0), $id);
		echo "<br />У записи №$id число комментариев изменено с $count на 0";
		continue;
	}
}
