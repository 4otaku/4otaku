<?php

include '../inc.common.php';

$arts = Database::get_full_table('art_variation');

foreach ($arts as $art) {
	if ($art['resized']) {
		$file = IMAGES.SL.'booru'.SL.'full'.SL.
			$art['md5'].'.'.$art['extension'];
			
		$sizes = getimagesize($file);
		$width = $sizes[0];
		$height = $sizes[1];
		$weight = filesize($file);

		if ($weight > 1024*1024) {
			$weight = round($weight/(1024*1024),1).' мб';
		} elseif ($weight > 1024) {
			$weight = round($weight/1024,1).' кб';
		} else {
			$weight = $weight.' б';
		}
		
		Database::update('art_variation', 
			array('resized' => $width.'x'.$height.'px; '.$weight), $art['id']);
	}
}
