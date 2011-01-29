<?
	
	// Начальные определения, подгрузка конфига, autoload
	
	define('SL', DIRECTORY_SEPARATOR);

	$autoload_directories = glob(__DIR__.SL.'*',GLOB_ONLYDIR);
	
	function autoload($name) {
		global $autoload_directories;
		
		$name = str_replace('_', '/', strtolower($name));
		$alt_name = preg_replace('/^(.+)\/(.+?)$/', '$1_$2', $name);

		foreach ($autoload_directories as $directory) {

			if (file_exists($directory.SL.$name.'.php')) {
				include_once($directory.SL.$name.'.php');
				return true;
			}

			if (file_exists($directory.SL.$alt_name.'.php')) {
				include_once($directory.SL.$alt_name.'.php');
				return true;
			}			
		}
		
		return false;
	}
	
	// Не __autoload, потому как в дальнейшем плагину может потребоваться добавить свой autoload
	spl_autoload_register('autoload', false);
	
	include('../test.php');