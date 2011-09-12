<?

	include_once 'common.php';
	
	if ($sizefile<$def['booru']['filesize']) {
		if (is_array($check)) {
			$md5=md5_file($temp);
			if (
				!obj::db()->sql('select id from art where md5="'.$md5.'"',2,'id') && 
				!obj::db('sub')->sql('select id from w8m_art where md5="'.$md5.'"',2) && 
				!obj::db('sub')->sql('select id from art where variation like "%.'.$md5.'.%"',2)
			) {
				$extension = strtolower(pathinfo($file,PATHINFO_EXTENSION));
				$thumb=md5(microtime(true));
				$newname = $md5.'.'.$extension;
				$newfile = ROOT_DIR.SL.'images'.SL.'booru'.SL.'full'.SL.$newname;
				$newthumb = ROOT_DIR.SL.'images'.SL.'booru'.SL.'thumbs'.SL.$thumb.'.jpg';
				$newlargethumb = ROOT_DIR.SL.'images'.SL.'booru'.SL.'thumbs'.SL.'large_'.$thumb.'.jpg';
				chmod($temp, 0755);
				if (!move_uploaded_file($temp, $newfile)) file_put_contents($newfile, file_get_contents($temp));
				$a = microtime(true);
				$imagick =  new $image_class($path = $newfile);
				$sizes = $imagick->getImageWidth().'x'.$imagick->getImageHeight();
				if ($imagick->getImageWidth() > $def['booru']['resizewidth']*$def['booru']['resizestep']) {
					if (scale($def['booru']['resizewidth'],ROOT_DIR.SL.'images/booru/resized/'.$md5.'.jpg',95,false))
						$resized = $sizes;
				} elseif ($sizefile > $def['booru']['resizeweight']) {
					if (scale(false,ROOT_DIR.SL.'images/booru/resized/'.$md5.'.jpg',95,false))
						$resized = $sizes;
				}
						
				if (!empty($resized)) {					
					if ($sizefile > 1024*1024) {
						$sizefile = round($sizefile/(1024*1024),1).' мб';
					} elseif ($sizefile > 1024) {
						$sizefile = round($sizefile/1024,1).' кб';
					} else {
						$sizefile = $sizefile.' б';
					}
					$resized .= 'px; '.$sizefile;
				}
				
				$animated = 0;
				
				scale($def['booru']['largethumbsize'],$newlargethumb);
				scale($def['booru']['thumbsize'],$newthumb);
				
				$result = array(
					'success' => true, 
					'image' => SITE_DIR.'/images/booru/thumbs/'.$thumb.'.jpg', 
					'md5' => $md5, 
					'data' => $md5.'#'.$thumb.'#'.$extension.'#'.$resized.'#'.$animated, 
				);
			}
			else {$result = array('error' => 'already-have');}
		}
		else {$result = array('error' => 'filetype');}
	}
	else {$result = array('error' => 'maxsize');} 

echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
