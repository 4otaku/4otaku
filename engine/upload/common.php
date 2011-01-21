<?

define('SL', DIRECTORY_SEPARATOR);

define('ROOT_DIR', dirname(dirname(dirname(__FILE__))));

function __autoload($class_name) {
	$class = ROOT_DIR.SL.'libs'.SL.str_replace('__',SL,$class_name).'.php';
	if (file_exists($class)) {
		include_once $class;
	} else {
		$class = ROOT_DIR.SL.'engine'.SL.str_replace('__',SL,$class_name).'.php';
		if (file_exists($class)) {
			include_once $class;
		}
	}	
}

include_once ROOT_DIR.SL.'engine/config.php';

define('SITE_DIR',str_replace(array('/','\\'),SL,rtrim($def['site']['dir'],'/')));

$file = $_FILES['filedata']['name'];
$type = $_FILES['filedata']['type'];
$sizefile = $_FILES['filedata']['size'];
$temp = $_FILES['filedata']['tmp_name'];
$check = getImageSize($temp);	

if (!class_exists('Imagick')) {
	include_once 'imagick_substitute.php';
	$image_class = 'imagick_substitute';
	$composite['over'] = imagick_substitute::COMPOSITE_OVER;
	$composite['jpeg'] = imagick_substitute::COMPRESSION_JPEG;	
} else {
	$image_class = 'Imagick';
	$composite['over'] = Imagick::COMPOSITE_OVER;
	$composite['jpeg'] = Imagick::COMPRESSION_JPEG;
}

function undo_safety($str) {
	return str_replace(array('&amp;','&quot;','&lt;','&gt;','&092;','&apos;'),array('&','"','<','>','\\',"'"),$str);
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
	global $imagick; global $path; global $image_class; global $composite;
	$old_x = $imagick->getImageWidth(); $old_y = $imagick->getImageHeight();
	if (!is_array($new_size)) $new_size = array('0' => $new_size, '1' => $new_size);	
	if ($thumbnail) {
		$aspect = min ($new_size[0]/$old_x,$new_size[1]/$old_y);
		$x = round($old_x*$aspect); $y = round($old_y*$aspect);
		$func = 'thumbnailImage';
	}
	else {
		$aspect = $new_size[0]/$old_x;
		$x = round($old_x*$aspect); $y = round($old_y*$aspect);
		$func = 'scaleImage';
	}	

	$format = $imagick->getImageFormat();
	
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
		$imagick = $imagick->coalesceImages();
		if (is_array($imagick) && count($imagick > 1)) {
			$imagick = array_shift($imagick);
			$imagick = array_shift($imagick); // да, это такая особая магия, потому что coalesceImages работает со 2-го кадра :3
		} else {
			$imagick = $imagick;
		}
		$imagick->setImageCompressionQuality($compression);
		$imagick->$func($x,$y);
		$imagick->setImagePage($x,$y,0,0); // и это очень важно для gif
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
