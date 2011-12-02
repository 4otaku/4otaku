<?

class Check
{
	public static function num($num, $max = false, $default = 0, $min = false) {
		if (!is_numeric($num)) return $default;
		if ($max !== false && $num > $max) return $default;
		if ($min !== false && $num < $min) return $default;
		return $num;
	}
	
	public static function id(&$num) {
		$num = (int) $num;
		
		return $num > 0;
	}

	public static function link($str, $default = '') {
		return (!preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $str)) ? $default : $str;
	}

	public static function link_array($array) {
		$return = array();
		if (is_array($array)) {
			foreach ($array as $one) {
				$link = explode('&gt;',$one['link']);
				if (count($link) < 3 && self::link(undo_safety(end($link)))) {
					$return[] = $one;
				}
			}
		}
		return $return;
	}

	public static function hash($str, $default = '') {
		return (preg_match('/[^0-9a-f]/i', $str)) ? $default : $str;
	}

	public static function is_hash($str) {
		return (bool) preg_match('/^[0-9a-f]{32}$/ui', $str);
	}

	public static function email($str, $default = true) {
		global $def;
		if ($default === true) $default = $def['user']['mail'];
		return (!preg_match('/[-a-zA-Z0-9_.+]+@[a-zA-Z0-9-]+.[a-zA-Z]+/', $str)) ? $default : $str;
	}

	public static function text($str, $max = false, $default = '', $min = false) {
		if ($max !== false && mb_strlen($str) > $max) return $default;
		if ($min !== false && mb_strlen($str) < $min) return $default;
		return $str;
	}

	public static function lat($str, $default = '') {
		return (!ctype_alnum(str_replace(array('-', '_'), '', $str))) ? $default : $str;
	}

	public static function rights($soft = false) {
		if (sets::user('rights')) {
			return true;
		}

		if ($soft) {
			return false;
		}

		die;
	}
}
