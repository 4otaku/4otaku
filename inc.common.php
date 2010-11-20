<?

switch (basename($_SERVER['SCRIPT_FILENAME'], '.php')) {
	case 'index': define('_INDEX_', true);  define('_AJAX_', false); define('_CRON_', false); break;
	case 'ajax':  define('_INDEX_', false); define('_AJAX_', true);  define('_CRON_', false); break;
	case 'cron':  define('_INDEX_', false); define('_AJAX_', false); define('_CRON_', true);  break;
	default: die;
}

define('SL', DIRECTORY_SEPARATOR);

define('ROOT_DIR', dirname(__FILE__));
if (file_exists(ROOT_DIR . SL . 'custom_templates')) {
	define('TEMPLATE_DIR', ROOT_DIR . SL . 'custom_templates');
} else {
	define('TEMPLATE_DIR', ROOT_DIR . SL . 'templates');
}

function __autoload($class_name) {
	$class = ROOT_DIR.SL.'libs'.SL.str_replace('__',SL,$class_name).'.php';
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
	if (strpos($buffer,'<textarea') && (_AJAX_)) return $buffer;
    return str_replace(array("\t","  ","\n","\r"),array(""," ","",""),$buffer);
}

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
	!(_CRON_)
) { 
	ob_start('myoutput');
}

include_once 'engine'.SL.'config.php';

$db = new mysql();

$check = new check_values();

if (!(_CRON_)) {
        if (isset($def['site']['domain']) && ($def['site']['domain'] != ''))
            $cookie_domain = $def['site']['domain'];
        else if ($_SERVER['SERVER_NAME'] == 'localhost')
            $cookie_domain = NULL;
        else
            $cookie_domain = '.'.$_SERVER['SERVER_NAME'];
        
        $hash = (isset($_COOKIE['settings']) && $check->hash($_COOKIE['settings'])) ? $_COOKIE['settings'] : md5(microtime(true));
                
        $settings = $db->sql('SELECT data FROM settings WHERE cookie = "'.$hash.'"',2);
       
	if (isset($settings)) {
		setcookie("settings", $hash, time()+3600*24*60, '/' , $cookie_domain);
                if ((base64_decode($settings) !== false) && is_array(unserialize(base64_decode($settings))))
                	$sets = merge_settings($sets, unserialize(base64_decode($settings)));
                else
                	$db->sql('UPDATE settings SET data = "YTowOnt9" WHERE cookie = "'.$hash.'")',0);
	} else {
                if(_INDEX_)  setcookie("settings", $hash, time()+3600*24*60, '/' , $cookie_domain);
		$db->sql('INSERT INTO settings (cookie,lastchange) VALUES ("'.$hash.'","'.time().'")',0);
		$_COOKIE['settings'] = $hash;
	}
}
