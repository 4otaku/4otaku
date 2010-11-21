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
			include_once ROOT_DIR.'templates'.SL.'404'.SL.'fatal.php';
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

include_once ROOT_DIR.SL.'engine'.SL.'config.php';

if(!empty($def['site']['domain']) && $def['site']['domain'] != $_SERVER["SERVER_NAME"]) {
	header("HTTP/1.x 301 Moved Permanently");
	header("Location: http://".$def['site']['domain'].$_SERVER["REQUEST_URI"]);
	exit();
}

$db = new mysql();

$check = new check_values();

// Тут мы работаем с сессиями
if (!(_CRON_)) {
        // Логично, что у крона сессии нет.

        // Определяем домен для cookie. Если в настройках задан домен - берем его, иначе опираемся на окружение
        if (!empty($def['site']['domain']))
            $cookie_domain = $def['site']['domain'];
        else if ($_SERVER['SERVER_NAME'] == 'localhost')
            $cookie_domain = NULL;
        else
            $cookie_domain = '.'.$_SERVER['SERVER_NAME'];

        // Хэш. Берем либо из cookie, если валиден, либо генерим новый
        $hash = (!empty($_COOKIE['settings']) && $check->hash($_COOKIE['settings'])) ? $_COOKIE['settings'] : md5(microtime(true));

        // Пробуем прочитать настройки для хэша
        $sess = $db->sql('SELECT data, lastchange FROM settings WHERE cookie = "'.$hash.'"', 1);

        // Проверяем полученные настройки
	if (isset($sess['data']) && isset($sess['lastchange'])) {
		// Настройки есть

		// Обновляем cookie еще на 2 мес у клиента, если она поставлена больше месяца назад
                if(intval($sess['lastchange']) < (time()-3600*24*30)) {
                	setcookie("settings", $hash, time()+3600*24*60, '/' , $cookie_domain);
                        // Фиксируем факт обновления в БД
                        $db->sql('UPDATE settings SET lastchange = "'.time().'" WHERE cookie = "'.$hash.'"',0);
                }

                // Проверяем валидность настроек и исправляем, если что-то не так
		if ((base64_decode($sess['data']) !== false) && is_array(unserialize(base64_decode($sess['data'])))) {
                	// Все ок, применяем сохраненные настройки
			$sets = merge_settings($sets, unserialize(base64_decode($sess['data'])));
		} else {
                	// Заполняем поле настройками 'по-умолчанию' (YTowOnt9 разворачивается в пустой массив)
			$db->sql('UPDATE settings SET data = "YTowOnt9" WHERE cookie = "'.$hash.'"',0);
		}
	} else {
        	// Настроек нет, создаем их

                setcookie("settings", $hash, time()+3600*24*60, '/' , $cookie_domain);
                // Вносим в БД сессию с дефолтными настройками
                $db->sql('INSERT INTO settings (cookie, data, lastchange) VALUES ("'.$hash.'", "YTowOnt9", "'.time().'")',0);
                // @fixme Не самый удачный способ передать cookie в cookie.php
                $_COOKIE['settings'] = $hash;
	}
}
