<?php

include '../inc.common.php';

$string = 'http://www.pixiv.net/member_illust.php?mode=medium&illust_id=';

$arts = Database::get_full_vector('art', 'source like "%img%pixiv%"');

foreach ($arts as $id => $art) {
	$source = (int) basename($art['source']);
	
	if (empty($source)) {
		continue;
	}
	
	Database::update('art', array('source' => $string . $source), $id);
}
