<?

class Controller_Web extends Controller_Abstract
{
	public function build () {
		if (array_key_exists('do', Globals::$vars)) {
			return $this->call->build_input(Globals::$vars, Globals::$preferences);
		}

		return $this->call->build_output(Globals::$url, Globals::$preferences);
	}

	public function build_input ($vars, $preferences) {
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

	public function build_output ($url, $preferences) {
		$module = $this->call->get_module($url);
var_dump($url);
		$worker = $module . '_web';
		$worker = new $worker();

		$query = $worker->call->make_query($url);

		$query = array_replace_recursive(array(
			'type' => 'output',
			'module' => $module,
			'function' => 'listing'
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
	
	public function get_module (& $url) {
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
					var_dump($url);
					return $name;
				}
			}
		}
		
		return $url[0];
	}
}
