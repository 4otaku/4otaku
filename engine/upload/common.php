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

include_once ROOT_DIR.SL.'engine'.SL.'upload'.SL.'functions.php';
