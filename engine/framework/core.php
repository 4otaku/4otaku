<?

class Core implements Plugins
{	
	public static function get_module ($url, $vars) {
		if (!empty($vars['module'])) {
			$module = $vars['module'];
		} else {
			$module = reset($url);
		}

		return class_exists(ucfirst($module).'_Output') ? $module : 'error';
	}
	
	protected static $url_parts = array();
	
	public static function make_query ($url, $vars) {
	
		self::$url_parts = (array) Config::settings('url_parts');		
		ksort(self::$url_parts);

		$query_input = self::make_query_input($vars);
		$query_output = self::make_query_output($url, $vars);		
				
		return array($query_input, $query_output);
	}
	
	public static function make_query_output ($url, $vars) {
		$query = array();
		
		// Первый элемент массива урла - указатель на рабочий модуль, 
		// он уже не нужен, убираем
		array_shift($url);

		if (is_array(self::$url_parts)) {
			foreach (self::$url_parts as $function) {
				if (is_callable(array('Query_Library', $function))) {
					$query = array_merge($query, (array) Query_Library::$function($url));
				}
			}
		}
		
		return $query;
	}
	
	public static function make_query_input ($vars) {
		if (empty($vars['function'])) {
			return array();
		}
		
		return $vars;
	}
	
	public static function get_worker_name ($module, $query, $type) {
		if (!empty($query['submodule'])) {
			return ucfirst($module).'_Submodule_'.ucfirst($query['submodule']);
		}
		
		return ucfirst($module).'_'.ucfirst($type);
	}
	
	public static function valid_subquery ($area, $query) {
		$area = explode(',', $area);
		
		$function = empty($query['function']) ? 'main' : $query['function'];
		
		foreach ($area as $test) {
			if ($test == '*') {
				return true;
			}
			
			if (
				preg_match('/^\{([a-z_]+)\}$/i', $test, $match) &&
				$function == $match[1]
			) {
				return true;
			}
		}
		
		return false;
	}
	
	// Дефолтное, на случай если не переопределено в модуле
	public function make_subquery ($query, $module) {
		return array();
	}
}
