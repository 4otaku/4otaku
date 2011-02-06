<?

ini_set('memory_limit', '128M');

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
		return true;
	}

	$class = ROOT_DIR.SL.'engine'.SL.str_replace('__',SL,$class_name).'.php';
	if (file_exists($class)) {
		include_once $class;
		return true;
	}

	if (_INDEX_) {
		include_once TEMPLATE_DIR.SL.'404'.SL.'fatal.php';
		ob_end_flush();
	}
	exit();
}

function myoutput($buffer) {
	$known = array('msie', 'firefox', 'opera');
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	$pattern = '#(?<browser>'.implode('|', $known).')[/ ]+(?<version>[0-9]+(?:\.[0-9]+)?)#';
	preg_match_all($pattern, $agent, $matches);
	
	if (end($matches['browser']) == 'opera' && end($matches['version']) < 11) {
		$buffer = str_replace('<br /><wbr />','<br />',$buffer);
		$buffer = str_replace('<wbr /><br />','<br />',$buffer);
		$buffer = str_replace('<wbr />','&shy;',$buffer);
	}
	
	if (strpos($buffer,'<textarea') && (_AJAX_)) return $buffer;
    return str_replace(array("\t","  ","\n","\r"),array(""," ","",""),$buffer);
}

include_once ROOT_DIR.SL.'engine'.SL.'backwards_compatibility.php';

mb_internal_encoding('UTF-8');

if (
	strpos($_SERVER["REQUEST_URI"], 'art/download') === false &&
	!strpos($_SERVER["REQUEST_URI"], '/rss/') &&
	!(_CRON_) &&
	!((_AJAX_) && $_GET['f'] == 'download')
) {
	ob_start('myoutput');
}

include_once ROOT_DIR.SL.'engine'.SL.'config.php';

def::import($def);
if(!def::site('domain')) {
	def::set('site','domain',$_SERVER['SERVER_NAME']);
}

define('SITE_DIR',str_replace(array('/','\\'),SL,rtrim(def::site('dir'),'/')));

if(def::site('domain') != $_SERVER['SERVER_NAME'] && !(_CRON_)) {
	engine::redirect('http://'.$def['site']['domain'].$_SERVER["REQUEST_URI"], true);
}

if (!(_CRON_)) {
	$check = new check_values();
	list($get, $post) = query::get_globals($_GET, $_POST);
	include_once ROOT_DIR.SL.'engine'.SL.'metafunctions.php';
}


// Тут мы работаем с сессиями
if (!(_CRON_)) {
	// Логично, что у крона сессии нет.
	
	// Удалим все левые куки, нечего захламлять пространство
	foreach ($_COOKIE as $key => $cook) if ($key != 'settings') setcookie ($key, "", time() - 3600);

	$cookie_domain = (def::site('domain') != 'localhost' ? def::site('domain') : '').SITE_DIR;

	// Хэш. Берем либо из cookie, если валиден, либо генерим новый
	query::$cookie = (!empty($_COOKIE['settings']) && $check->hash($_COOKIE['settings'])) ? $_COOKIE['settings'] : md5(microtime(true));

	// Пробуем прочитать настройки для хэша
	$sess = obj::db()->sql('SELECT data, lastchange FROM settings WHERE cookie = "'.query::$cookie.'"', 1);

        // Проверяем полученные настройки
	if (isset($sess['data']) && isset($sess['lastchange'])) {
		// Настройки есть

		// Обновляем cookie еще на 2 мес у клиента, если она поставлена больше месяца назад
		if(intval($sess['lastchange']) < (time()-3600*24*30)) {
			setcookie("settings", query::$cookie, time()+3600*24*60, '/' , $cookie_domain);
			// Фиксируем факт обновления в БД
			obj::db()->sql('UPDATE settings SET lastchange = "'.time().'" WHERE cookie = "'.query::$cookie.'"',0);
		}

		// Проверяем валидность настроек и исправляем, если что-то не так
		if ((base64_decode($sess['data']) !== false) && is_array(unserialize(base64_decode($sess['data'])))) {
			// Все ок, применяем сохраненные настройки
			$sets = array_replace_recursive($sets, unserialize(base64_decode($sess['data'])));
			sets::import($sets);
		} else {
			// Заполняем поле настройками 'по-умолчанию' (YTowOnt9 разворачивается в пустой массив)
			obj::db()->sql('UPDATE settings SET data = "YTowOnt9" WHERE cookie = "'.query::$cookie.'"',0);
		}
	} else {
		// Настроек нет, создаем их

		setcookie("settings", query::$cookie, time()+3600*24*60, '/' , $cookie_domain);
		// Вносим в БД сессию с дефолтными настройками
		obj::db()->sql('INSERT INTO settings (cookie, data, lastchange) VALUES ("'.query::$cookie.'", "YTowOnt9", "'.time().'")',0);
	}
}
