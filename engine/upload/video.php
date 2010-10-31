<?

	define('ROOT_DIR', '/var/www/nameless/data/www/4otaku.ru/');
	
	include_once ROOT_DIR.'engine/config.php';
	include_once ROOT_DIR.'engine/upload/common.php';	
	include_once ROOT_DIR.'libs/mysql.php';

	$file = $_FILES['filedata']['name'];
	$type = $_FILES['filedata']['type'];
	$sizefile = $_FILES['filedata']['size'];
	$temp = $_FILES['filedata']['tmp_name'];

	if ($sizefile < $def['video']['filesize']) {
		$md5=md5_file($temp); 
		$db = new mysql();
		if (!($db->sql('select id from video where link="'.$md5.'"',2,'id'))) {
			$newfile = ROOT_DIR.'files/video/'.$md5.'.mp4';
			move_uploaded_file($temp, $newfile);
			echo $md5.'.mp4';
		}
		else {echo 'error-already-have';}
	}
	else {echo 'error-maxsize';} 
