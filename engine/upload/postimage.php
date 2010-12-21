<?	

	include_once 'common.php';

	if ($sizefile<$def['post']['picturesize']) {
		if (is_array($check)) {	
			$time = str_replace('.','',microtime(true));
			$extension =  strtolower(pathinfo($file, PATHINFO_EXTENSION));
			$newfile = ROOT_DIR.SL.'images'.SL.'full'.SL.$time.'.'.$extension;
			$newthumb = ROOT_DIR.SL.'images'.SL.'thumbs'.SL.$time.'.'.$extension;
			chmod($temp, 0755);
			move_uploaded_file($temp, $newfile);
			$imagick =  new $image_class($path = $newfile);
			scale(array(0 => 200, 1 => 150),$newthumb);
			echo $time.'.'.$extension;
		}
		else {echo 'error-filetype';}
	}
	else {echo 'error-maxsize';}
