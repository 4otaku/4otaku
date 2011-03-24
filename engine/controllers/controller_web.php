<?

class Controller_Web extends Controller_Abstract implements Plugins
{
	public function build () {
		if (array_key_exists('do', Globals::$vars)) {
			return $thisbuild_input(Globals::$vars, Globals::$preferences);
		}

		return $this->build_output(Globals::$url, Globals::$preferences);
	}

	protected function build_input ($vars, $preferences) {
		$do = explode('.', $vars['do']);

		if (count($do) != 2) {
			Error::fatal("Неверный формат аргумента 'do'");
		}

		$query = array(
			'type' => 'input',
			'class' => $do[0],
			'function' => $do[1],
		);

		return array_merge($vars, $query);
	}

	protected function build_output ($url, $preferences) {
		$url = $this->check_alias($url);

		$module = array_shift($url);

		$worker = $module . '_web';
		if (!class_exists($worker)) {
			$module = 'error';
			$worker = 'error_web';
		}
		
		$worker = new $worker();

		$query = $worker->make_query($url);

		$query = array_replace_recursive(array(
			'type' => 'output',
			'module' => $module,
			'function' => 'main'
		), $query);

		if (
			array_key_exists('mixed', $query) &&
			is_array($preferences) &&
			array_key_exists('mixed', $preferences)
		) {
			$query['mixed'] = array_replace_recursive(
				$preferences['mixed'],
				$query['mixed']
			);
		}

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
