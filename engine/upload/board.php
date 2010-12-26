<?

	include_once 'common.php';
	
	$md5=md5_file($temp);
	$extension = strtolower(pathinfo($file,PATHINFO_EXTENSION));
	$newname = $md5.'.'.$extension;			
	$newfile = ROOT_DIR.SL.'images'.SL.'board'.SL.'full'.SL.$newname;
	chmod($temp, 0755);

	if (class_exists('finfo') && file_exists('/usr/share/misc/magic')) {
		$finfo = new finfo(FILEINFO_MIME, '/usr/share/misc/magic');
		$mime_type = $finfo->file($temp);
		if (preg_match('/shockwave.*flash/', $mime_type)) {
			if ($sizefile<$def['board']['flashsize']) {
				if (!file_exists($newfile)) move_uploaded_file($temp, $newfile);
				echo '/images/flash.png|'.$newname.'#flash#'.$sizefile;
			} else {
				echo 'error-flashmaxsize';
			}
			exit;
		}
	}
	
	if ($sizefile<$def['board']['filesize']) {
		if (is_array($check)) {
			if (!file_exists($newfile)) move_uploaded_file($temp, $newfile);
			$thumb=md5(microtime(true));			
			$newthumb = ROOT_DIR.SL.'images'.SL.'board'.SL.'thumbs'.SL.$thumb.'.jpg';
			$imagick =  new $image_class($path = $newfile);
			$sizes = $imagick->getImageWidth().'x'.$imagick->getImageHeight();
			scale(array($def['board']['thumbwidth'],$def['board']['thumbheight']),$newthumb);			
			echo '/images/board/thumbs/'.$thumb.'.jpg|'.$newname.'#'.$thumb.'.jpg#'.$sizefile.'#'.$sizes;
		} else {
			echo 'error-filetype';
		}
	} else {
		echo 'error-maxsize';
	} 
