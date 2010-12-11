<?

	include_once 'common.php';
	
	if ($sizefile<$def['board']['filesize']) {
		if (is_array($check)) {
			$md5=md5_file($temp);
			$extension = strtolower(pathinfo($file,PATHINFO_EXTENSION));
			$newname = $md5.'.'.$extension;			
			$newfile = ROOT_DIR.'images'.SL.'board'.SL.'full'.SL.$newname;
			$thumb=md5(microtime(true));			
			$newthumb = ROOT_DIR.'images'.SL.'board'.SL.'thumbs'.SL.$thumb.'.jpg';
			chmod($temp, 0755);
			if (!file_exists($newfile)) move_uploaded_file($temp, $newfile);
			$imagick =  new $image_class($path = $newfile);
			scale(array($def['board']['thumbwidth'],$def['board']['thumbheight']),$newthumb);
			$sizes = $imagick->getImageWidth().'x'.$imagick->getImageHeight();
			echo '/images/board/thumbs/'.$thumb.'.jpg|#'.$newname.'#'.$thumb.'.jpg#'.$sizefile.'#'.$sizes;
		}
		else {echo 'error-filetype';}
	}
	else {echo 'error-maxsize';} 
