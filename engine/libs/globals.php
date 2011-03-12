<?

final class Globals extends Objects
{
	// Для загруженных данных
	public static $vars = array();
	
	// Для адреса запроса
	public static $url = array();	
	
	// Для информации о пользователе
	public static $user_data = array();
	
	// Настройки пользователя
	public static $preferences = false;
	
	// Для самого запроса
	public static $query = array();	
	
	// Для полученных из ядра данных
	public static $data = array();		
	
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
		
		self::$url = array_filter($url);
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
			self::$preferences = Cookie::get_preferences(self::$user_data['cookie']);
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
	}	
}
