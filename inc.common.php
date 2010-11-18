<?

switch (basename($_SERVER['SCRIPT_FILENAME'], '.php')) {
	case 'index': define('_INDEX_', true);  define('_AJAX_', false); define('_CRON_', false); break;
	case 'ajax':  define('_INDEX_', false); define('_AJAX_', true);  define('_CRON_', false); break;
	case 'cron':  define('_INDEX_', false); define('_AJAX_', false); define('_CRON_', true);  break;
	default: die;
}

define('SL', DIRECTORY_SEPARATOR);

define('ROOT_DIR', dirname(__FILE__));

function __autoload($class_name) {
	$class = ROOT_DIR . SL . 'libs'.SL.str_replace('__',SL,$class_name) . '.php';
	if (file_exists($class)) {
		include_once $class;
	} else {
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

// Moved from matafunctions cause now is used on startup
function merge_settings(&$array1,&$array2) {
	$merged = $array1;
	if(is_array($array2))
	foreach ($array2 as $key => &$value)
		if (is_array($value) && isset($merged[$key]) && is_array($merged[$key]))
			$merged[$key] = merge_settings($merged[$key], $value);
		else
			$merged[$key] = $value;
	return $merged;
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

$check = new check_values();

if ($check->hash($_COOKIE['settings'])) $settings = $db->sql('select data from settings where cookie = "'.$_COOKIE['settings'].'"',2);
if (isset($settings) && !(_CRON_)) {
    $cookie_domain = $_SERVER['SERVER_NAME'] == 'localhost' ? false : '.'.$_SERVER['SERVER_NAME'];	
    setcookie("settings", $_COOKIE['settings'], time()+3600*24*60, '/' , $cookie_domain);
    $sets = merge_settings($sets,$hide_sape = unserialize(base64_decode($settings)));
} else if(!isset($settings) && _INDEX_) {
    $hash = md5(microtime(true));
    $cookie_domain = $_SERVER['SERVER_NAME'] == 'localhost' ? false : '.'.$_SERVER['SERVER_NAME'];
    setcookie("settings", $hash, time()+3600*24*60, '/' , $cookie_domain);
    $db->sql('insert into settings (cookie,lastchange) values ("'.$hash.'","'.time().'")',0);
    $_COOKIE['settings'] = $hash;
}

