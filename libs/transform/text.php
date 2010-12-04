<?

class transform__text
{
	function strtolower_ru($text) {
		$alfavitlover = array('ё','й','ц','у','к','е','н','г', 'ш','щ','з','х','ъ','ф','ы','в', 'а','п','р','о','л','д','ж','э', 'я','ч','с','м','и','т','ь','б','ю');
		$alfavitupper = array('Ё','Й','Ц','У','К','Е','Н','Г', 'Ш','Щ','З','Х','Ъ','Ф','Ы','В', 'А','П','Р','О','Л','Д','Ж','Э', 'Я','Ч','С','М','И','Т','Ь','Б','Ю');
		return str_replace($alfavitupper,$alfavitlover,strtolower($text));
	} 
	
	function strtoupper_ru($text) {
		$alfavitlover = array('ё','й','ц','у','к','е','н','г', 'ш','щ','з','х','ъ','ф','ы','в', 'а','п','р','о','л','д','ж','э', 'я','ч','с','м','и','т','ь','б','ю');
		$alfavitupper = array('Ё','Й','Ц','У','К','Е','Н','Г', 'Ш','Щ','З','Х','Ъ','Ф','Ы','В', 'А','П','Р','О','Л','Д','Ж','Э', 'Я','Ч','С','М','И','Т','Ь','Б','Ю');
		return str_replace($alfavitlover,$alfavitupper,strtoupper($text));
	} 
	
	function punto_switcher($text) {
		$lat = array('`','q','w','e','r','t','y','u','i', 'o','p','[',']','a','s','d','f', 'g','h','j','k','l',';','\'', 'z','x','c','v','b','n','m',',','.',
			'~','Q','W','E','R','T','Y','U','I', 'O','P','{','}','A','S','D','F', 'G','H','J','K','L',':','"', 'Z','X','C','V','B','N','M','<','>');
		$ru = array('ё','й','ц','у','к','е','н','г', 'ш','щ','з','х','ъ','ф','ы','в', 'а','п','р','о','л','д','ж','э', 'я','ч','с','м','и','т','ь','б','ю',
			'Ё','Й','Ц','У','К','Е','Н','Г', 'Ш','Щ','З','Х','Ъ','Ф','Ы','В', 'А','П','Р','О','Л','Д','Ж','Э', 'Я','Ч','С','М','И','Т','Ь','Б','Ю');
		$chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);
	
		foreach ($chars as &$char) if (in_array($char,$lat)) $char = str_replace($lat,$ru,$char);
						else $char = str_replace($ru,$lat,$char);
		return implode('',$chars);
	}

	function format($text) {
		$text = str_replace("\r","",$text);
		$text = $this->bb2html($text);
		$text = preg_replace('/(https?|ftp|file):\/\/[\-A-Z0-9\+&@#\/%\?\=~_|\!\:,\.;]*[\-A-Z0-9\+&@#\/%\=~_|]/ui', '<a href="$0">$0</a>', $text);
		$text = str_replace('⟯','http',nl2br($text));
		return $text;
	}
	
	function mb_wordwrap($str, $maxLength, $break) {
		if (!preg_match('#(\S{'.$maxLength.',})#e',$str)) return $str;
		$wordEndChars = array(" ", "\n", "\r", "\f", "\v", "\0", '\x20', '\xc2' ,' \xa0');
		$count = 0;
		$newStr = "";
		$openTag = false;
		for($i=0; $i < mb_strlen($str); $i++) {
			$char = mb_substr($str,$i,1);
		    $newStr .= $char; 		   
		    if($char == "<") $openTag = true;
		    if(($openTag) && ($char == ">")) $openTag = false;
		    if(!$openTag){
		        if(!in_array($char, $wordEndChars)) {
		            $count++;
		            if($count==$maxLength) {
		                $newStr .= $break;
		                $count = 0;
		            }
		        }
		        else $count = 0;
		    }
		   
		}  
		return $newStr;
	} 	
	
	function wcase($count, $case1, $case2, $case3) {
		if ($count > 9) {
			if ($count % 10 == 0 || $count % 10 > 4 || $count[strlen($count)-2] == 1) return $case3;
			if ($count % 10 == 1) return $case1;
			return $case2;
		}
		if ($count == 0 || $count > 4) return $case3;
		if ($count == 1) return $case1;
		return $case2;
	}	
	
	function rumonth($in) {
		$rumonth = array('','Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь');	
		if (is_numeric($in))
			return $rumonth[ltrim($in,'0')];
		else
			return array_search($in,$rumonth);
	}
	
	function rudate($minutes = false) {
		$date = $this->rumonth(date('m')).date(' j, Y');
		if ($minutes) $date .= date('; G:i');
		return $date;
	}
	
	function cut_long_words($string, $length = 30, $break = '<wbr />') {
		$parts = preg_split('/\s/',$string);
		
		foreach ($parts as $key => $part) {
			if (
				strlen($part,'UTF-8') > $length && 
				preg_match_all('/(&[a-z]{1,8};|.){'.($length+1).'}/iu', $part, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER)
			) {
				$parts[$key] = ''; $last_position = 0;
				foreach ($matches as $match) {
					$parts[$key] .= substr($part, $last_position, $match[1][1] - $last_position);
					$parts[$key] .= $break;
					$last_position = $match[1][1];
				}
				$parts[$key] .= substr($part, $last_position, strlen($part) - $last_position);
			}
		}
		
		return implode(' ',$parts);
	}
	
	function bb2html($string) {
        while (preg_match_all('/\[([a-zA-Z]*)=?([^\n]*?)\](.*?)\[\/\1\]/is', $string, $matches)) {
			foreach ($matches[0] as $key => $match) {
				list($tag, $param, $innertext) = array($matches[1][$key], $matches[2][$key], $matches[3][$key]);
				switch ($tag) {
					case 'b': $replacement = "<strong>$innertext</strong>"; break;
					case 'i': $replacement = "<em>$innertext</em>"; break;
					case 's': $replacement = "<s>$innertext</s>"; break;
					case 'size': $replacement = "<span style=\"font-size: $param;\">$innertext</span>"; break;
					case 'color': $replacement = "<span style=\"color: $param;\">$innertext</span>"; break;
					case 'url': $replacement = '<a href="/go?' . str_replace('http','⟯',($param? $param : $innertext)) . "\">".str_replace('http','⟯',$innertext)."</a>"; break;
					case 'img':
						$param = explode('x', strtolower($param));
						$replacement = "<img src=\"".str_replace('http','⟯',$innertext)."\" " . (is_numeric($param[0])? "width=\"".$param[0]."\" " : '') . (is_numeric($param[1])? "height=\"".$param[1]."\" " : '') . '/><br />';
					break;
			        case 'spoiler':
						$replacement = "<div class=\"mini-shell\"><div class=\"handler\" width=\"100%\">".
							"<span class=\"sign\">↓</span> <a href=\"#\" class=\"disabled\">".str_replace(array('[',']'),array('',''),$param)."</a></div>".
							"<div class=\"text hidden\">".ltrim($innertext)."</div></div>";
					break; 
				}
				$string = str_replace($match, $replacement, $string);
			}
		}
        return $string;
    } 
}
