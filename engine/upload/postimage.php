<?	

	include_once 'common.php';

	if ($sizefile<$def['post']['picturesize']) {
		if (is_array($check)) {	
			$time = str_replace('.','',microtime(true));
			$extension =  strtolower(pathinfo($file, PATHINFO_EXTENSION));
			$newfile = ROOT_DIR.SL.'images'.SL.'full'.SL.$time.'.'.$extension;
			$newthumb = ROOT_DIR.SL.'images'.SL.'thumbs'.SL.$time.'.jpg';
			chmod($temp, 0755);
			if (!move_uploaded_file($temp, $newfile)) file_put_contents($newfile, file_get_contents($temp));
			$imagick =  new $image_class($path = $newfile);
			scale(array(0 => 200, 1 => 150),$newthumb);
			
			$result = array(
				'success' => true, 
				'image' => SITE_DIR.'/images/thumbs/'.$time.'.jpg',
				'data' => $time.'.'.$extension, 
			);
		}
		else {$result = array('error' => 'filetype');}
	}
	else {$result = array('error' => 'maxsize');}

echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
