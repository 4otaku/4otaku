<?

final class Globals
{
	// Хранилище для загруженных данных
	public static $vars = array();
	
	// Хранилище для адреса запроса
	public static $url = array();	
	
	// Хранилище для информации о пользователе
	public static $user = array();		
	
	static private $safe_replacements = array(
		'&' => '&amp;',
		'"' => '&quot;',
		'<' => '&lt;',
		'>' => '&gt;',
		'\\' => '&#092;',
		"'" => '&apos;',
		'⟯' => '',
	);
	
	public static function get_vars($data) {
		self::clean_globals($data);
		$data= self::safety_globals($data, array());
		self::$vars = array_replace_recursive(self::$vars, $data);
	}
	
	public static function get_url($request) {
		
		$request = preg_replace('/^'.preg_quote(SITE_DIR,'/').'/', '', $request);
		$url = explode('/', preg_replace('/\?[^\/]+$/', '', $request)); 

		if (isset($url[0])) {
			unset($url[0]);
		}		
		if (empty($url[1])) {
			$url[1] = 'index';
		}
		
		self::$url = $url;
	}
	
	public static function get_user($user_data) {
		self::$user = $user_data;
	}
	
	static function clean_globals(&$data, $iteration = 0) {
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

	static function safety_globals(&$data, $input, $iteration = 0) {
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
}