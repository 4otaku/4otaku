<?

class Transform_String
{
	
	public function detect_language ($string) {
		
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
	
	public function parse_time ($string, $add_current = true) {
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
	
}
