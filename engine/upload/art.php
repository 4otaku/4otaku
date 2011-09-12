<?

	include_once 'common.php';

	if ($sizefile < def::art('filesize')) {
		if (is_array($check)) {
			$md5 = md5_file($temp);
			if (
				!Database::get_count('art', 'md5 = ?', $md5) &&
				!Database::get_count('art_variation', 'md5 = ?', $md5)
			) {

				$extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
				$thumb=md5(microtime(true));
				$newname = $md5.'.'.$extension;
				$newfile = IMAGES.SL.'booru'.SL.'full'.SL.$newname;
				$newresized = IMAGES.SL.'booru'.SL.'resized'.SL.$md5.'.jpg';
				$newthumb = IMAGES.SL.'booru'.SL.'thumbs'.SL.$thumb.'.jpg';
				$newlargethumb = IMAGES.SL.'booru'.SL.'thumbs'.SL.'large_'.$thumb.'.jpg';
				chmod($temp, 0755);

				if (!move_uploaded_file($temp, $newfile)) {
					file_put_contents($newfile, file_get_contents($temp));
				}

				$imagick =  new $image_class($path = $newfile);
				$animated = 0;

				$sizes = $imagick->getImageWidth().'x'.$imagick->getImageHeight();
				if ($imagick->getImageWidth() > def::art('resizewidth')*def::art('resizestep')) {
					if (scale(def::art('resizewidth'), $newresized, 95, false)) {
						$resized = $sizes;
					}
				} elseif ($sizefile > def::art('resizeweight')) {
					if (scale(false, $newresized, 95, false)) {
						$resized = $sizes;
					}
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

				scale(def::art('largethumbsize'), $newlargethumb);
				scale(def::art('thumbsize'), $newthumb);

				$result = array(
					'success' => true,
					'image' => '/images/booru/thumbs/'.$thumb.'.jpg',
					'md5' => $md5,
					'data' => $md5.'#'.$thumb.'#'.$extension.'#'.$resized.'#'.$animated,
				);
			} else {
				$result = array('error' => 'already-have');
			}
		} else {
			$result = array('error' => 'filetype');
		}
	} else {
		$result = array('error' => 'maxsize');
	}

echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
