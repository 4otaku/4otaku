<?

class Controller_Web implements Controller_Interface, Plugins
{	
	protected $module_name = false;
	protected $aliased_url = array();
	
	public function get_module () {
		if (empty($this->module_name)) {
			if (!empty(Globals::$vars['module']) && !empty(Globals::$vars['function'])) {
				$this->module_name = Globals::$vars['module'];
			} else {
				$this->aliased_url = $this->check_alias(Globals::$url);
				$this->module_name = array_shift($this->aliased_url);
			}
		}

		return $this->module_name;
	}

	public function query () {
		if (empty(Globals::$vars['module']) || empty(Globals::$vars['function'])) {
			return $this->build_output(Globals::$url);
		}
	
		return $this->build_input(Globals::$vars);
	}

	protected function build_input ($vars) {
		$query = array('type' => 'input');

		return array_merge($vars, $query);
	}

	protected function build_output ($url) {
		$module = $this->get_module();		

		$worker = $module . '_web';
		if (!class_exists($worker)) {
			$module = 'error';
			$worker = 'error_web';
		}
		
		Objects::$wrapper = new $worker();

		$query = Objects::$wrapper->make_query($this->aliased_url);

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
