<?
	
	// Задаем autoloader
	// Он ищет сперва в cache/extended с проверкой, нет ли расширенного плагинами
	// Потом ищет в engine/framework, engine/module, engine/libs
	
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
		$name = str_replace('_', SL, strtolower($name)).'.php';

		if ($library = search_lib($name)) {
			include_once($library);
			return true;
		}
		
		$alt_name = preg_replace('/^(.+)\\'.SL.'(.+?)$/', '$1_$2', $name);
		
		if ($library = search_lib($alt_name)) {
			include_once($library);
			return true;			
		}		
		
		return false;
	}	
	
	function autoload_factory ($name) {
		$name = str_replace('_', '/', strtolower($name)).SL.'_factory.php';

		if ($library = search_lib($name)) {
			include_once($library);
			return true;
		}
		
		return false;
	}
	
	function search_lib ($name) {
		$directories = array(
			ENGINE.SL.'framework'.SL,
			ENGINE.SL.'modules'.SL,
			ENGINE.SL.'libs'.SL,
		);

		foreach ($directories as $directory) {

			if (is_readable($directory.SL.$name)) {
				return $directory.SL.$name;
			}
		}
		
		return false;
	}
	
	// Не __autoload, потому как в дальнейшем плагину или шаблонизатору 
	// может потребоваться добавить свой autoload
	spl_autoload_register('autoload_extended', false);
	spl_autoload_register('autoload_normal', false);
	spl_autoload_register('autoload_factory', false);
