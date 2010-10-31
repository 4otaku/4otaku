<?

	define('ROOT_DIR', '/var/www/nameless/data/www/4otaku.ru/');
	
	include_once ROOT_DIR.'engine/config.php';
	include_once ROOT_DIR.'engine/upload/common.php';	
	include_once ROOT_DIR.'libs/mysql.php';

	$file = $_FILES['filedata']['name'];
	$type = $_FILES['filedata']['type'];
	$sizefile = $_FILES['filedata']['size'];
	$temp = $_FILES['filedata']['tmp_name'];
	$check = getImageSize($temp);		

	if ($sizefile<$def['booru']['filesize']) {
		if (is_array($check)) {
			$md5=md5_file($temp); 
			$db = new mysql();
			if (!($db->sql('select id from art where md5="'.$md5.'"',2,'id'))) {
				$extension = strtolower(pathinfo($file,PATHINFO_EXTENSION));
				$thumb=md5(microtime(true));
				$newname = $md5.'.'.$extension;
				$newfile = ROOT_DIR.'images/booru/full/'.$newname;
				$newthumb = ROOT_DIR.'images/booru/thumbs/'.$thumb.'.jpg';
				$newlargethumb = ROOT_DIR.'images/booru/thumbs/large_'.$thumb.'.jpg';
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
