<?

class Transform_String
{
	
	function detect_language ($string) {
		
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
	
}
