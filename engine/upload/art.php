<?

	include_once 'common.php';
	
	if ($sizefile<$def['booru']['filesize']) {
		if (is_array($check)) {
			$md5=md5_file($temp); 
			$db = new mysql();
			if (!($db->sql('select id from art where md5="'.$md5.'"',2,'id'))) {
				$extension = strtolower(pathinfo($file,PATHINFO_EXTENSION));
				$thumb=md5(microtime(true));
				$newname = $md5.'.'.$extension;
				$newfile = ROOT_DIR.'images'.SL.'booru'.SL.'full'.SL.$newname;
				$newthumb = ROOT_DIR.'images'.SL.'booru'.SL.'thumbs'.SL.$thumb.'.jpg';
				$newlargethumb = ROOT_DIR.'images'.SL.'booru'.SL.'thumbs'.SL.'large_'.$thumb.'.jpg';
				chmod($temp, 0755);
				move_uploaded_file($temp, $newfile);			
				$a = microtime(true);
				$imagick =  new Imagick($path = $newfile);
				$sizes = $imagick->getImageWidth().'x'.$imagick->getImageHeight();
				if ($imagick->getImageWidth() > $def['booru']['resizewidth']*$def['booru']['resizestep'])
					if (scale($def['booru']['resizewidth'],ROOT_DIR.'images/booru/resized/'.$md5.'.jpg',95,false))
						$resized = $sizes;
				scale($def['booru']['largethumbsize'],$newlargethumb);
				scale($def['booru']['thumbsize'],$newthumb);
				echo '/images/booru/thumbs/'.$thumb.'.jpg|'.$md5.'#'.$thumb.'#'.$extension.'#'.$resized;
			}
			else {echo 'error-already-have';}
		}
		else {echo 'error-filetype';}
	}
	else {echo 'error-maxsize';} 
