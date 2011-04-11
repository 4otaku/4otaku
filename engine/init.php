<?
	
	// Начало работы скрипта, константы, подгрузка конфига, autoload
	
	mb_internal_encoding('UTF-8');
	define('SL', DIRECTORY_SEPARATOR);
	define('ENGINE', __DIR__);
	define('ROOT', dirname(__DIR__));
	define('CACHE', ROOT.SL.'cache');
	define('FILES', ROOT.SL.'files');
	define('IMAGES', ROOT.SL.'images');
	
	define('MINUTE', 60);
	define('HOUR', MINUTE * 60);
	define('DAY', HOUR * 24);
	define('WEEK', DAY * 7);
	define('MONTH', DAY * 30);
	
	define('KILOBYTE', 1024);
	define('MEGABYTE', KILOBYTE * 1024);
	define('GIGABYTE', MEGABYTE * 1024);

	include_once 'autoloader.php';
	
	// Подгружаем конфиг, если не нашли - бросаем ошибку,
	// т.к. сайт без конфига нежизнеспособен.
	
	$config_files = glob(ROOT.SL.'config'.SL.'*');
	
	if (!empty($config_files)) {
		foreach ($config_files as $config_file) {
			Config::load($config_file);
		}
	} else {
		Error::fatal('Конфиг не найден.');
	}
	
	define('SITE_DIR', Config::main('website', 'Directory'));
