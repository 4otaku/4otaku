<?

class query
{	
	static public $url = array();
	static public $class;
	static public $type;
	static public $vars = array();
	static public $post = array();
	static public $get = array();
	
	static private $safe_replacements = array(
		'&' => '&amp;',
		'"' => '&quot;',
		'<' => '&lt;',
		'>' => '&gt;',
		'\\' => '&#092;',
		"'" => '&apos;',
		'⟯' => '',
	);
	
	static function get_globals($get, $post) {
		self::clean_globals($get);
		self::clean_globals($post);
		self::$get = self::safety_globals($get, array());
		self::$post = self::safety_globals($post, array());

		if (isset($post['remember'])) {
			$md5 = md5(serialize($post));
			if (obj::db()->sql('select id from input_filter where md5 = "'.$md5.'"',2)) unset($post);
			else obj::db()->insert('input_filter',array($md5,time()));
		}		
		
		unset ($_GET, $_POST);
		return array(self::$get, self::$post);
	}

	static function clean_globals(&$data,$iteration = 0) {
		if ($iteration > 10) {
			return;
		}

		foreach ($data as $k => $v)	{
			if (is_array($v)) {
				self::clean_globals($data[$k],$iteration + 1);
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

	static function safety_globals(&$data,$input,$iteration = 0) {
		if ($iteration > 10) {
			return $input;
		}

		foreach($data as $k => $v) {
			if (is_array($v)) {
				$input[$k] = self::safety_globals($data[$k], array(), $iteration + 1);
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
