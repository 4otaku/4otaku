<?

if (!class_exists('Imagick', false)) {
	include_once ROOT_DIR.SL.'engine'.SL.'upload'.SL.'imagick_substitute.php';
	$image_class = 'imagick_substitute';
	$composite['over'] = imagick_substitute::COMPOSITE_OVER;
	$composite['jpeg'] = imagick_substitute::COMPRESSION_JPEG;
} else {
	$image_class = 'Imagick';
	$composite['over'] = Imagick::COMPOSITE_OVER;
	$composite['jpeg'] = Imagick::COMPRESSION_JPEG;
}

if (!function_exists('undo_safety')) {
	function undo_safety($str) {
		return str_replace(array('&amp;','&quot;','&lt;','&gt;','&092;','&apos;'),array('&','"','<','>','\\',"'"),$str);
	}
}

function convert_to($source,$target_encoding) {
    $encoding = mb_detect_encoding($source, "auto");
	if ($encoding) {
		$target = str_replace( "?", "[question_mark]", $source);
		$target = mb_convert_encoding($target, $target_encoding, $encoding);
		$target = str_replace( "?", "", $target);
		$target = str_replace( "[question_mark]", "?", $target);
		return $target;
	}
	else return '';
}

function scale($new_size,$target,$compression = 80,$thumbnail = true) {
	global $imagick; global $path; global $image_class; global $composite; global $animated;

	if ($new_size === false) {
		$aspect = 1/2;
	} elseif (!is_array($new_size)) {
		$new_size = array('0' => $new_size, '1' => $new_size);
	}

	$format = $imagick->getImageFormat();
	if (strtolower($format) == 'gif') {
		$imagick = $imagick->coalesceImages();

		$animated = 1;
		if (is_animated($path) or $imagick->hasNextImage()) {
			if (!$thumbnail && ($imagick instanceOf Imagick)) {
				return scale_animated($new_size,$target);
			}
		}
	}

	$old_x = $imagick->getImageWidth(); $old_y = $imagick->getImageHeight();

	if ($thumbnail) {
		$aspect = empty($aspect) ? min ($new_size[0]/$old_x,$new_size[1]/$old_y) : $aspect;
		$x = round($old_x*$aspect); $y = round($old_y*$aspect);
		$func = 'thumbnailImage';
	} else {
		$aspect = empty($aspect) ? $new_size[0]/$old_x : $aspect;
		$x = round($old_x*$aspect); $y = round($old_y*$aspect);
		$func = 'scaleImage';
	}

	if (strtolower($format) == 'png') {
		$imagick->setImageCompressionQuality($compression);
		$imagick->$func($x,$y);
		$bg = $imagick->clone();
		$bg->colorFloodFillImage('#ffffff',100,'#777777',0,0);
		$bg->compositeImage($imagick,$composite['over'],0,0);
		$bg->setImageCompression($composite['jpeg']);
		$bg->setImageFormat('jpeg');
		$bg->writeImage($target);
	} elseif (strtolower($format) == 'gif') {
		$imagick->setImageCompressionQuality($compression);
		$imagick->$func($x,$y);
		$imagick->setImagePage($x,$y,0,0);
		$bg = $imagick->clone();
		$bg->colorFloodFillImage('#ffffff',100,'#777777',0,0);
		$bg->compositeImage($imagick,$composite['over'],0,0);
		$bg->setImageCompression($composite['jpeg']);
		$imagick->setImageFormat('jpeg');
		$bg->writeImage($target);
	} else {
		$imagick->setImageCompressionQuality($compression);
		$imagick->$func($x,$y);
		$imagick->setImageCompression($composite['jpeg']);
		$imagick->setImageFormat('jpeg');
		$imagick->writeImage($target);
	}
	$imagick->clear();
	$imagick = new $image_class($path = $target);
	return true;
}

function is_animated ($filename) {
	$filecontents = file_get_contents($filename);

	$str_loc = 0;
	$count = 0;
	while ($count < 2) {
		$where1 = strpos($filecontents, "\x00\x21\xF9\x04", $str_loc);
		if ($where1 === FALSE) {
			break;
		} else {
			$str_loc = $where1 + 1;
			$where2 = strpos($filecontents, "\x00\x2C", $str_loc);
			if ($where2 === FALSE) {
				continue;
			} else {
				if ($where1 + 8 == $where2) {
					$count++;
				}
				$str_loc = $where2 + 1;
			}
		}
	}

	return ($count > 1);
}

function scale_animated ($new_size, $target) {
	global $imagick; global $path; global $image_class; global $composite; global $sizes;

	$old_x = $imagick->getImageWidth();
	$old_y = $imagick->getImageHeight();
	$sizes = $old_x.'x'.$old_y;

	$aspect = !empty($new_size) ? $new_size[0]/$old_x : 1/2;
	$x = round($old_x*$aspect);
	$y = round($old_y*$aspect);

	do {
		$imagick->scaleImage($x, $y, 1);
	} while ($imagick->nextImage());

	$imagick = $imagick->deconstructImages();

	$target = preg_replace('/\.jpe?g$/i', '.gif', $target);

	$imagick->writeImages($target, true);

	$imagick->clear();
	$imagick = new $image_class($path = $target);
	return true;
}
