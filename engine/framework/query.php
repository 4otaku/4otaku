<?

class Query implements Plugins
{	
	protected static $url_parts = array();
	
	public static function make_query ($url, $vars) {
	
		$query_input = self::make_query_input($vars);
		$query_output = self::make_query_output($url, $vars);		
				
		return array($query_input, $query_output);
	}
	
	public static function make_query_output ($url, $vars = array()) {
		$query = array();
		
		$url_parts = (array) Config::settings('url_parts');		
		ksort($url_parts);
		
		if (empty($vars['ajax']) || !empty($vars['output'])) {
			$query['output'] = true;
		}
		
		// Первый элемент массива урла - указатель на рабочий модуль, 
		// он уже не нужен, убираем
		array_shift($url);

		if (is_array($url_parts)) {
			foreach ($url_parts as $function) {
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
	
	public static function get_module ($url, $vars = array()) {
		if (!empty($vars['module'])) {
			$module = $vars['module'];
		} else {
			$module = reset($url);
		}

		return class_exists(ucfirst($module).'_Output') ? $module : 'error';
	}
	
	public static function get_worker_name ($module, $query, $type) {
		if (!empty($query['submodule'])) {
			return ucfirst($module).'_Submodule_'.ucfirst($query['submodule']);
		}
		
		return ucfirst($module).'_'.ucfirst($type);
	}	
	
	public function valid_subquery ($area, $query) {
	
		$rule = array();		
		
		if (!empty($query['submodule'])) {
			$rule[] = $query['submodule'];
		}
		
		$function = empty($query['function']) ? 'main' : $query['function'];
		if ($function != 'main' || empty($rule)) {
			$rule[] = $function;
		}
		
		$rule = '{'.implode('.', $rule).'}';
		
		$area = explode(',', $area);

		foreach ($area as $test) {
			if ($test == '*') {
				return true;
			}
			
			if ($rule === $test) {
				return true;
			}
			
			if (preg_match('/^\[.+\]$/', $test)) {
				$test_function = 'is_'.trim($test, '[]');

				if (
					is_callable(array($this, $test_function)) && 
					$this->$test_function($query)
				) {		
					return true;
				}
			}
		}
		
		return false;
	}
	
	// Дефолтное, на случай если не переопределено в модуле
	public function make_subquery ($query, $module) {
		return array();
	}
}
