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
		$transformations = get_class_methods('Parse_Web_Url');

		$query = array();		

		$class = $this->call->get_output_class($url);

		foreach ($transformations as $transformation) {
			$query = array_merge($query, (array) Parse_Web_Url::$transformation($url));
		}

		$query = array_replace_recursive($url, array(
			'type' => 'output',
			'class' => $class,
			'function' => 'listing',
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
	
	public function get_output_class ($url) {
		if (empty($url)) {
			$url = array('index');
		}
		
		$modules = Config::modules();
		
		$string = implode('/', $url);
		
		foreach ($modules as $name => $aliases) {
			foreach ($aliases as $alias) {
				if (strpos($string, trim($alias, '/')) === 0) {
					return $name;
				}
			}
		}
		
		return $url[0];
	}
}
