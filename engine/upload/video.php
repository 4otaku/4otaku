<?

	include_once 'common.php';

/*	Under construction	*/

/*
 	if ($sizefile < $def['video']['filesize']) {
		$md5=md5_file($temp); 
		if (!(obj::db()->sql('select id from video where link="'.$md5.'"',2,'id'))) {
			$newfile = ROOT_DIR.'files/video/'.$md5.'.mp4';
			move_uploaded_file($temp, $newfile);
			echo $md5.'.mp4';
		}
		else {echo 'error-already-have';}
	}
	else {echo 'error-maxsize';} 
*/
