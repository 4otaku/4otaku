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

if (!empty($_FILES)) {
	$file = current(($_FILES));
	
	$sizefile = $file['size'];
	$temp = $file['tmp_name'];
	$check = getImageSize($temp);
	$file = $file['name'];
} else {
	$temp = ROOT_DIR.SL.'files'.SL.'tmp'.SL.$_GET['qqfile'];

	$handle = fopen($temp, "w");
	fwrite($handle, file_get_contents('php://input'));
	fclose($handle);

	$check = getImageSize($temp);
	$sizefile = filesize($temp);
	$file = urldecode($_GET['qqfile']);
}

include_once ROOT_DIR.SL.'engine'.SL.'upload'.SL.'functions.php';
