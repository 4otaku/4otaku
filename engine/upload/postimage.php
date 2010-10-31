<?	

	define('ROOT_DIR', '/var/www/nameless/data/www/4otaku.ru/');

	include_once ROOT_DIR.'engine/config.php';
	include_once ROOT_DIR.'engine/upload/common.php';

	$file = $_FILES['filedata']['name'];
	$type = $_FILES['filedata']['type'];
	$sizefile = $_FILES['filedata']['size'];
	$temp = $_FILES['filedata']['tmp_name'];
	$check = getImageSize($temp);		

	if ($sizefile<$def['post']['picturesize']) {
		if (is_array($check)) {	
			$time = str_replace('.','',microtime(true));
			$extension =  strtolower(pathinfo($file, PATHINFO_EXTENSION));
			$newfile = ROOT_DIR.'images/full/'.$time.'.'.$extension;
			$newthumb = ROOT_DIR.'images/thumbs/'.$time.'.'.$extension;
			chmod($temp, 0755);
			move_uploaded_file($temp, $newfile);
			$imagick =  new Imagick($path = $newfile);
			scale(array(0 => 200, 1 => 150),$newthumb);
			echo $time.'.'.$extension;
		}
		else {echo 'error-filetype';}
	}
	else {echo 'error-maxsize';}
