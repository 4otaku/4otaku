<?

class Transform_String
{
	
	public static function detect_language ($string) {
		
		$count_all = mb_strlen($string, 'UTF-8');
		
		$count = array();
		
		$count['rus'] = preg_match_all('/[а-яё]/ui', $string, $dev_null);
		$count['jap'] = preg_match_all('/\p{Hiragana}|\p{Katakana}|\p{Han}/u', $string, $dev_null);
		$count['eng'] = preg_match_all('/[a-z]/i', $string, $dev_null);
		
		foreach ($count as $language => $number) {
			if ($number * 1.5 > $count_all) {
				return $language;
			}
		}
		
		return '';
	}
	
	public static function rumonth($in) {
		$rumonth = array('','Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь');
		
		if (is_numeric($in)) {
			return $rumonth[ltrim($in,'0')];
		}
		
		return array_search($in,$rumonth);
	}

	public static function rudate($time = false, $minutes = false) {
		if (empty($time)) {
			$time = time();
		}
		
		$time = (int) $time;
		
		$date = self::rumonth(date('m'), $time).date(' j, Y', $time);
		
		if ($minutes) {
			$date .= date('; G:i', $time);
		}
		
		return $date;
	}
	
	public static function parse_time ($string, $add_current = true) {
		$parts = preg_split('/([^\d]+)/', $string, null, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
		
		$time = 0;
		
		for ($i = 1; $i < count($parts); $i += 2) {
			$parts[$i] = trim($parts[$i]);
			switch ($parts[$i]) {
				case 'm': $multiplier = MINUTE; break;
				case 'h': $multiplier = HOUR; break;
				case 'd': $multiplier = DAY; break;
				case 'w': $multiplier = WEEK; break;
				case 'M': $multiplier = MONTH; break;
				default: $multiplier = 0; break;
			}
			
			$time = $time + $parts[$i - 1] * $multiplier;
		}
		
		return $time + (int) $add_current * time();
	}
	
	public static function round_bytes ($bytes, $precision = 1) {
		if ($bytes > GIGABYTE) {
			return array(round($bytes/GIGABYTE, $precision), 'гб');
		} elseif ($bytes > MEGABYTE) {
			return array(round($bytes/MEGABYTE, $precision), 'мб');
		} elseif ($bytes > KILOBYTE) {
			return array(round($bytes/KILOBYTE, $precision), 'кб');
		} else {
			return array($bytes, 'б');
		}		
	}
	
}
