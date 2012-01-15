<?php

ini_set('memory_limit', '128M');
define('DEBUG', $_SERVER['REMOTE_ADDR'] == '80.252.16.11' ||
	$_SERVER['REMOTE_ADDR'] == '127.0.0.1');
define('MAINTENANCE', 0);

define('_TYPE_', basename($_SERVER['SCRIPT_FILENAME'], '.php'));

define('SL', DIRECTORY_SEPARATOR);

define('ROOT_DIR', dirname(__FILE__));
if (MAINTENANCE && !empty($_SERVER['REMOTE_ADDR']) && !DEBUG) {
	include_once "maintenance.php";
	exit();
}
include_once "constants.php";
include_once "autoloader.php";
include_once "functions.php";

Cache::$base_prefix = 'otaku_';
Cache::$drivers_list = array(
	"Cache_Memcached",
	"Cache_Dummy"
);

function myoutput($buffer) {
	$known = array('msie', 'firefox', 'opera');
	$agent = strtolower($_SERVER['HTTP_USER_AGENT']);
	$pattern = '#(?<browser>'.implode('|', $known).')[/ ]+(?<version>[0-9]+(?:\.[0-9]+)?)#';
	preg_match_all($pattern, $agent, $matches);

	if (end($matches['browser']) == 'opera' && end($matches['version']) < 9.8) {
		$buffer = str_replace('<wbr /> ',' ',$buffer);
		$buffer = str_replace(' <wbr />',' ',$buffer);
		$buffer = str_replace('<br /><wbr />','<br />',$buffer);
		$buffer = str_replace('<wbr /><br />','<br />',$buffer);
		$buffer = str_replace('<wbr />','&shy;',$buffer);
	}

	if (strpos($buffer,'<textarea') && (_TYPE_ == 'ajax')) return $buffer;
    return str_replace(array("\t","  ","\n","\r","<!--br-->"),array(""," ","","","\n"),$buffer);
}

include_once ROOT_DIR.SL.'engine'.SL.'backwards_compatibility.php';

mb_internal_encoding('UTF-8');

if (
	strpos($_SERVER["REQUEST_URI"], 'art/download') === false &&
	!strpos($_SERVER["REQUEST_URI"], '/rss/') &&
	_TYPE_ != 'cron' && _TYPE_ != 'api'&&
	!(_TYPE_ == 'ajax' && $_GET['f'] == 'download')
) {
	ob_start('myoutput');
}

include_once ROOT_DIR.SL.'engine'.SL.'config.php';

def::import($def);
if(!def::site('domain')) {
	def::set('site','domain',$_SERVER['SERVER_NAME']);
}

define('SITE_DIR',str_replace(array('/','\\'),SL,rtrim(def::site('dir'),'/')));

if(def::site('domain') != $_SERVER['SERVER_NAME'] && _TYPE_ != 'cron' && !empty($_SERVER['REMOTE_ADDR'])) {
	engine::redirect('http://'.$def['site']['domain'].$_SERVER["REQUEST_URI"], true);
}

if (_TYPE_ != 'cron' && _TYPE_ != 'api') {
	$check = new check_values();
	include_once ROOT_DIR.SL.'engine'.SL.'twig_init.php';
}
if (_TYPE_ != 'cron') {
	list($get, $post) = query::get_globals($_GET, $_POST);
}
include_once ROOT_DIR.SL.'engine'.SL.'metafunctions.php';

// Тут мы работаем с сессиями
if (_TYPE_ != 'cron' && _TYPE_ != 'api') {
	// Логично, что у крона или апи сессии нет.

	// Удалим все левые куки, нечего захламлять пространство
	foreach ($_COOKIE as $key => $cook) if ($key != 'settings') setcookie ($key, "", time() - 3600);

	$cookie_domain = (def::site('domain') != 'localhost' ? def::site('domain') : '').SITE_DIR;

	// Хэш. Берем либо из cookie, если валиден, либо генерим новый
	query::$cookie = (!empty($_COOKIE['settings']) && $check->hash($_COOKIE['settings'])) ? $_COOKIE['settings'] : md5(microtime(true));

	// Пробуем прочитать настройки для хэша
	$sess = Database::get_row('settings',
		array('data', 'lastchange'),
		'cookie = ?',
		query::$cookie);

        // Проверяем полученные настройки
	if (isset($sess['data']) && isset($sess['lastchange'])) {
		// Настройки есть

		// Обновляем cookie еще на 2 мес у клиента, если она поставлена больше месяца назад
		if(intval($sess['lastchange']) < (time()-3600*24*30)) {
			setcookie('settings', query::$cookie, time()+3600*24*60, '/', $cookie_domain);
			// Фиксируем факт обновления в БД
			Database::update('settings',
				array('lastchange' => time()),
				'cookie = ?',
				query::$cookie);
		}

		// Проверяем валидность настроек и исправляем, если что-то не так
		if ((base64_decode($sess['data']) !== false) && is_array(unserialize(base64_decode($sess['data'])))) {
			// Все ок, применяем сохраненные настройки
			$sets = array_replace_recursive($sets,
				unserialize(base64_decode($sess['data'])));

			$user = Database::get_row('user', 'login, email, rights',
				'cookie = ?', query::$cookie);

			if (!empty($user)) {
				$sets['user'] = array_replace($sets['user'], $user);
			}

			sets::import($sets);
		} else {
			// Заполняем поле настройками 'по-умолчанию' (YTowOnt9 разворачивается в пустой массив)
			Database::update('settings',
				array('data' => 'YTowOnt9'),
				'cookie = ?',
				query::$cookie);
		}
	} else {
		// Настроек нет, создаем их

		setcookie('settings', query::$cookie, time()+3600*24*60, '/' , $cookie_domain);
		// Вносим в БД сессию с дефолтными настройками
		Database::insert('settings', array(
			'cookie' => query::$cookie,
			'data' => 'YTowOnt9',
			'lastchange' => time()
		));
	}
}
