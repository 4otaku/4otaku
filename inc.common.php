<?

switch (basename($_SERVER['SCRIPT_FILENAME'], '.php')) {
	case 'index': define('_INDEX_', true); break;
	case 'ajax': define('_AJAX_', true); break;
	case 'cron': define('_CRON_', true); break;
	default: die;
}

_INDEX_ === true ? null : define('_INDEX_', false);
_AJAX_ === true ? null : define('_AJAX_', false);
_CRON_ === true ? null : define('_CRON_', false);


define('SL', DIRECTORY_SEPARATOR);

define('ROOT_DIR', dirname(__FILE__));

function __autoload($class_name) {
	$class = 'libs'.SL.str_replace('__',SL,$class_name) . '.php';
	if (file_exists($class)) include_once $class;
	else {
		if (_INDEX_) {
			include_once 'templates'.SL.'404'.SL.'fatal.php';
			ob_end_flush();
		}
		die;
	}
}

function myoutput($buffer) {
	if (strpos($buffer,'<textarea') && _AJAX_) return $buffer;
    return str_replace(array("\t","  ","\n","\r"),array(""," ","",""),$buffer);
}

mb_internal_encoding('UTF-8');

if (
	strpos($_SERVER["REQUEST_URI"], 'art/download') === false && 
	!strpos($_SERVER["REQUEST_URI"], '/rss/') && 
	!_CRON_
) { 
	ob_start('myoutput');
}

include_once 'engine'.SL.'config.php';

$db = new mysql();
