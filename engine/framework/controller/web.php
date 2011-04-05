<?

class Controller_Web implements Controller_Interface, Plugins
{
	public function query () {
		if (array_key_exists('do', Globals::$vars)) {
			return $this->build_input(Globals::$vars);
		}

		return $this->build_output(Globals::$url);
	}

	protected function build_input ($vars) {
		$do = explode('.', $vars['do']);

		if (count($do) != 2) {
			Error::fatal("Неверный формат аргумента 'do'");
		}

		$query = array(
			'type' => 'input',
			'module' => $do[0],
			'function' => $do[1],
		);

		return array_merge($vars, $query);
	}

	protected function build_output ($url) {
		$url = $this->check_alias($url);

		$module = array_shift($url);

		$worker = $module . '_web';
		if (!class_exists($worker)) {
			$module = 'error';
			$worker = 'error_web';
		}
		
		Objects::$wrapper = new $worker();

		$query = Objects::$wrapper->make_query($url);

		$query = array_replace_recursive(array(
			'type' => 'output',
			'module' => $module,
			'function' => 'main',
		), $query);

		return $query;
	}
	
	public function subquery ($submodule, $area, $query) {
		if (
			$query['type'] != 'output' || 
			!Controller::test_sub_area($area, $query['function'])
		) {
			return null;
		}
		
		$worker = $submodule . '_web';
		if (!class_exists($worker)) {
			return null;
		}
		
		$wrapper = & Objects::$sub_wrapper[$submodule];
		
		$wrapper = new $worker();
		
		$query = $wrapper->make_subquery($query);
		
		$query = array_replace_recursive(array(
			'type' => 'output',
			'module' => $submodule,
			'function' => 'main',
		), $query);

		return $query;		
	}	
	
	public function check_alias ($url) {
		if (empty($url)) {
			$url = array('index');
		}

		$modules = Config::alias();

		$string = implode('/', $url);
		
		foreach ($modules as $name => $module) {
			foreach ($module as $alias) {
				$alias = trim($alias, '/');
				
				if (strpos($string, $alias) === 0) {
					$url = explode('/', substr($string, strlen($alias)));
			
					array_unshift($url, $name);
					
					$url = array_values(array_filter($url));
	
					return $url;
				}
			}
		}
		
		return $url;
	}
}
