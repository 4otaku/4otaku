<?

class Controller_Web extends Controller_Abstract
{
	public function build() {
		Globals::$preferences = Cookie::get_preferences(Globals::$user['cookie']);

		if (array_key_exists('do', Globals::$vars)) {
			return $this->call->build_input(Globals::$vars, Globals::$preferences);
		}
		
		return $this->call->build_output(Globals::$url, Globals::$preferences);
	}
	
	public function build_input($vars, $preferences) {
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
	
	public function build_output($url, $preferences) {
		$tests = get_class_methods('Test_Web_Output');
		
		foreach ($tests as $test) {
			$test = Test_Web_Output::$test($url);
			if (!empty($test)) {
				$url = (array) $test;
				break;
			}
		}
		
		$class = array_shift($url);
		
		$query = array(
			'type' => 'output',
			'class' => $class,
		);
		
		if (
			array_key_exists('mixed', $url) &&
			array_key_exists('mixed', $preferences)
		) {
			$url['mixed'] = array_replace_recursive(
				$preferences['mixed'], 
				$url['mixed']
			);
		}
		
		return array_merge($url, $query); 
	}
}
