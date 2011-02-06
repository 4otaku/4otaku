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
			if (!file_exists($newfile)) 
				if (!move_uploaded_file($temp, $newfile)) 
					file_put_contents($newfile, file_get_contents($temp));
			
			$result = array(
				'success' => true, 
				'image' => SITE_DIR.'/images/flash.png', 
				'data' => $newname.'#flash#'.$sizefile, 
			);
		} else {
			$result = array('error' => 'flashmaxsize');
		}
		
		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
		exit;
	}
}

if ($sizefile<$def['board']['filesize']) {
	if (is_array($check)) {
		if (!file_exists($newfile)) 
			if (!move_uploaded_file($temp, $newfile)) 
				file_put_contents($newfile, file_get_contents($temp));
		$thumb=md5(microtime(true));			
		$newthumb = ROOT_DIR.SL.'images'.SL.'board'.SL.'thumbs'.SL.$thumb.'.jpg';
		$imagick =  new $image_class($path = $newfile);
		$sizes = $imagick->getImageWidth().'x'.$imagick->getImageHeight();
		scale(array($def['board']['thumbwidth'],$def['board']['thumbheight']),$newthumb);
		
		$result = array(
			'success' => true, 
			'image' => SITE_DIR.'/images/board/thumbs/'.$thumb.'.jpg', 
			'data' => $newname.'#'.$thumb.'.jpg#'.$sizefile.'#'.$sizes, 
		);
	} else {
		$result = array('error' => 'filetype');
	}
} else {
	$result = array('error' => 'maxsize');
}

echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
