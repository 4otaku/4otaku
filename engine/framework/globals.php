<?

final class Globals implements Plugins
{
	// Для загруженных данных
	public static $vars = array();
	
	// Для адреса запроса
	public static $url = array();	
	
	// Для алиасов урла
	public static $url_aliases = array();
	
	// Для информации о пользователе
	public static $user_data = array();
	
	// Настройки пользователя
	public static $preferences = false;
	
	static private $safe_replacements = array(
		'&' => '&amp;',
		'"' => '&quot;',
		'<' => '&lt;',
		'>' => '&gt;',
		'?' => '&#63;',
		'\\' => '&#092;',
		"'" => '&apos;',
	);
	
	public static function get_vars($data) {
		self::clean_globals($data);
		$data= self::safety_globals($data, array());
		self::$vars = array_replace_recursive(self::$vars, $data);
	}
	
	public static function get_url($request) {

		$request = preg_replace('/^'.preg_quote(SITE_DIR,'/').'/', '', $request);
		$url = explode('/', preg_replace('/\?[^\/]+$/', '', $request)); 
		
		$url = array_values(array_filter($url));
		
		if (empty($url)) {
			$url = array('index');
		}
		
		$aliases = Config::alias();
		foreach ($aliases as $key => $alias) {
			if (empty($alias['from']) || empty($alias['to']) || !is_numeric($alias['position'])) {
				unset($aliases[$key]);
				continue;
			}
			
			$aliases[$key]['from'] = trim($alias['from'], '/');
			$aliases[$key]['to'] = explode('/', trim($alias['to'], '/'));
		}
		
		foreach ($url as $id => $section) {
			foreach ($aliases as $alias) {
				if ($alias['from'] == $section && $alias['position'] == $id + 1) {
					array_splice($url, $id, 1, $alias['to']);
				}
			}
		}
		
		self::$url = $url;
		self::$url_aliases = $aliases;
	}
	
	public static function get_user($user_data) {
		self::$user_data = $user_data;
	}
	
	public static function clean_globals(&$data, $iteration = 0) {
		if ($iteration > 10 || !is_array($data)) {
			return;
		}

		foreach ($data as $k => $v)	{
			if (is_array($v)) {
				self::clean_globals($data[$k], ++$iteration);
			} else {
				$v = str_replace(chr('0'),'',$v);
				$v = str_replace("\0",'',$v);
				$v = str_replace("\x00",'',$v);
				$v = str_replace('%00','',$v);
				$v = str_replace("../","&#46;&#46;/",$v);
				$data[$k] = stripslashes($v);
			}
		}
	}

	public static function safety_globals(&$data, $input, $iteration = 0) {
		if ($iteration > 10 || !is_array($data)) {
			return $input;
		}

		foreach($data as $k => $v) {
			if (is_array($v)) {
				$input[$k] = self::safety_globals($data[$k], array(), ++$iteration);
			} else {
				$k = str_replace(
					array_keys(self::$safe_replacements),
					array_values(self::$safe_replacements),
					$k);
				$v = str_replace(
					array_keys(self::$safe_replacements),
					array_values(self::$safe_replacements),
					$v);
				$input[$k] = $v;
			}
		}

		return $input;
	}
	
	public static function user() {
		
		if (self::$preferences === false) {			
			$user_data = Cookie::get_preferences(self::$user_data['cookie']);
			
			$module = Core::get_module(self::$url, self::$vars);
			if (!empty($user_data['module'])) {
				$user_data['settings'] = $user_data['module'];
			}
					
			self::$preferences = array_replace_recursive(Config::data(), $user_data);
		}
		
		$preferences = self::$preferences;		
		$arguments = func_get_args();

		while (!empty($arguments)) {
			$argument = array_shift($arguments);
			
			if (!isset($preferences[$argument])) {
				return null;
			}
			
			$preferences = $preferences[$argument];
		}
		
		return $preferences;
	}
	
	public static function user_info() {
		$arguments = func_get_args();
		
		array_unshift($arguments, 'info');
		
		return call_user_func_array(array('self', 'user'), $arguments);
	}
	
	public static function user_settings() {
		$arguments = func_get_args();
		
		array_unshift($arguments, 'settings');
		
		return call_user_func_array(array('self', 'user'), $arguments);
	}
}
