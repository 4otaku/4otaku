<?

define('SL', DIRECTORY_SEPARATOR);

define('ROOT_DIR', dirname(dirname(dirname(__FILE__))));

include_once ROOT_DIR.SL.'engine/config.php';
include_once ROOT_DIR.SL.'constants.php';
include_once ROOT_DIR.SL.'autoloader.php';

define('SITE_DIR',str_replace(array('/','\\'),SL,rtrim($def['site']['dir'],'/')));

if (!empty($_FILES)) {
	$file = current(($_FILES));

	$sizefile = $file['size'];
	$temp = $file['tmp_name'];
	$check = getimagesize($temp);
	$file = $file['name'];
} else {
	$temp = ROOT_DIR.SL.'files'.SL.'tmp'.SL.microtime().'_'.$_GET['qqfile'];

	$handle = fopen($temp, "w");
	fwrite($handle, file_get_contents('php://input'));
	fclose($handle);

	$check = getimagesize($temp);
	$sizefile = filesize($temp);
	$file = urldecode($_GET['qqfile']);
}

include_once ROOT_DIR.SL.'engine'.SL.'upload'.SL.'functions.php';
