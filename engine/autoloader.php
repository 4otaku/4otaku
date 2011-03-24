<?
	
	// Задаем autoloader
	// Он ищет сперва в cache/extended с проверкой, нет ли расширенного плагинами
	// Потом ищет во всех под-директориях внтури engine/
	
	$library_directories = glob(ENGINE.SL.'*', GLOB_ONLYDIR);
	
	function autoload_extended ($name) {
		if ($name == 'index') {
			return false;
		}
		
		$extended = ROOT.SL.'cache'.SL.'extended'.SL.$name.'.php';
		
		if (is_readable($extended)) {
			include_once($extended);
			return true;
		}
		
		return false;
	}
	
	function autoload_normal ($name) {
		if ($library = search_lib($name)) {
			include_once($library);
			return true;			
		}
		
		return false;
	}
	
	function search_lib ($name) {
		global $library_directories;

		$name = str_replace('_', '/', strtolower($name));
		$alt_name = preg_replace('/^(.+)\/(.+?)$/', '$1_$2', $name);
		
		foreach ($library_directories as $directory) {

			if (is_readable($directory.SL.$name.'.php')) {
				return $directory.SL.$name.'.php';
			}

			if (is_readable($directory.SL.$alt_name.'.php')) {
				return $directory.SL.$alt_name.'.php';
			}			
		}
		
		return false;
	}
	
	// Не __autoload, потому как в дальнейшем плагину или шаблонизатору 
	// может потребоваться добавить свой autoload
	spl_autoload_register('autoload_extended', false);
	spl_autoload_register('autoload_normal', false);
