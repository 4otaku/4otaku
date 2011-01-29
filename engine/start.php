<?
	
	// Начальные определения, подгрузка конфига, autoload
	
	define('SL', DIRECTORY_SEPARATOR);

	// Задаем autoloader
	// Он ищет классы во всех под-директориях внтури engine/
	
	$autoload_directories = glob(__DIR__.SL.'*', GLOB_ONLYDIR);
	
	function autoload($name) {
		global $autoload_directories;
		
		$name = str_replace('_', '/', strtolower($name));
		$alt_name = preg_replace('/^(.+)\/(.+?)$/', '$1_$2', $name);

		foreach ($autoload_directories as $directory) {

			if (is_readable($directory.SL.$name.'.php')) {
				include_once($directory.SL.$name.'.php');
				return true;
			}

			if (is_readable($directory.SL.$alt_name.'.php')) {
				include_once($directory.SL.$alt_name.'.php');
				return true;
			}			
		}
		
		return false;
	}
	
	// Не __autoload, потому как в дальнейшем плагину может потребоваться добавить свой autoload
	spl_autoload_register('autoload', false);	
	
	// Подгружаем конфиг, если не нашли - бросаем ошибку,
	// т.к. сайт без конфига нежизнеспособен.
	
	$config_files = glob(dirname(__DIR__).SL.'config'.SL.'*');
	
	if (!empty($config_files)) {
		foreach ($config_files as $config_file) {
			Config::load($config_file);
		}
	} else {
		Error::fatal('Конфиг не найден.');
	}