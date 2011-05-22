<?

class Transform_Text
{
	const url_regex = '/(https?|ftp|irc):\/\/[:_\p{L}\d\-\.]+\.[:\p{L}\d]+(\/.+?(?=\.\s|"\s|\)\s|,\s|\s|$|&nbsp;|\[\/?[a-z]{1,8}|　))?/s';
	const bbcode_regex = '/\[([a-zA-Z]*)=?([^\n]*?)\](.*?)\[\/\1\]/is';
	
	function strtolower_ru ($text) {
		$alfavitlover = array('ё','й','ц','у','к','е','н','г', 'ш','щ','з','х','ъ','ф','ы','в', 'а','п','р','о','л','д','ж','э', 'я','ч','с','м','и','т','ь','б','ю');
		$alfavitupper = array('Ё','Й','Ц','У','К','Е','Н','Г', 'Ш','Щ','З','Х','Ъ','Ф','Ы','В', 'А','П','Р','О','Л','Д','Ж','Э', 'Я','Ч','С','М','И','Т','Ь','Б','Ю');
		return str_replace($alfavitupper,$alfavitlover,strtolower($text));
	}

	function strtoupper_ru ($text) {
		$alfavitlover = array('ё','й','ц','у','к','е','н','г', 'ш','щ','з','х','ъ','ф','ы','в', 'а','п','р','о','л','д','ж','э', 'я','ч','с','м','и','т','ь','б','ю');
		$alfavitupper = array('Ё','Й','Ц','У','К','Е','Н','Г', 'Ш','Щ','З','Х','Ъ','Ф','Ы','В', 'А','П','Р','О','Л','Д','Ж','Э', 'Я','Ч','С','М','И','Т','Ь','Б','Ю');
		return str_replace($alfavitlover,$alfavitupper,strtoupper($text));
	}

	function punto_switcher ($text) {
		$lat = array('`','q','w','e','r','t','y','u','i', 'o','p','[',']','a','s','d','f', 'g','h','j','k','l',';','\'', 'z','x','c','v','b','n','m',',','.',
			'~','Q','W','E','R','T','Y','U','I', 'O','P','{','}','A','S','D','F', 'G','H','J','K','L',':','"', 'Z','X','C','V','B','N','M','<','>');
		$ru = array('ё','й','ц','у','к','е','н','г', 'ш','щ','з','х','ъ','ф','ы','в', 'а','п','р','о','л','д','ж','э', 'я','ч','с','м','и','т','ь','б','ю',
			'Ё','Й','Ц','У','К','Е','Н','Г', 'Ш','Щ','З','Х','Ъ','Ф','Ы','В', 'А','П','Р','О','Л','Д','Ж','Э', 'Я','Ч','С','М','И','Т','Ь','Б','Ю');
		$chars = preg_split('//u', $text, -1, PREG_SPLIT_NO_EMPTY);

		foreach ($chars as &$char) if (in_array($char,$lat)) $char = str_replace($lat,$ru,$char);
						else $char = str_replace($ru,$lat,$char);
		return implode('',$chars);
	}
	
	public static function headline ($text, $minimum_return = 40) {
        $text = self::cutout_hidden($text);
        
        $text = strip_tags($text, '<br>');
        
        $text = preg_split('/\s*(<br[^>]*>\s*)+/', $text, null, PREG_SPLIT_NO_EMPTY);
        
        $return = '';
        
        while (!empty($text) && mb_strlen($return, 'UTF-8') < $minimum_return) {
			
			if (!empty($return)) {
				$return .= "\n";
			}
			
			$return .= array_shift($text);			
		}
		
		if (!empty($text)) {
			$return .= ' ...';
		}
        
        return $return;		
	}
	
	public static function cutout_hidden ($text) {
		return preg_replace('/<([a-zA-Z]+)[^>]+class="[^"]*(?<=\s|")hidden(?=\s|")[^"]*"[^>]*>.*?<\/\1>/is', '', $text);
	}

	function format($text) {
		$text = str_replace("\r","",$text);
		$text = $this->bb2html($text);
		$text = preg_replace(self::URL_REGEX, '<a href="$0">$0</a>', $text);
		$text = str_replace('⟯','http',nl2br($text));
		return $text;
	}

	function wakaba ($text) {
		$text = str_replace("\r","",$text);
		$i = 0; $links = array();
		if (preg_match_all(self::URL_REGEX, $text, $matches)) {
			foreach ($matches[0] as $match) {
				$text = preg_replace('/'.preg_quote($match,'/').'/','{⟯link'.++$i.'}',$text,1);
				$links[$i] = '<a href="'.$match.'">'.$match.'</a>';
			}
		}
		$text = explode("\n", $text."\n");
		$state= '';
		foreach ($text as &$string) $this->wakaba_mark($string,$state);
		$text = str_replace("</li>\n",'</li>',implode("\n", $text));

		foreach($links as $key => $link) {
			$text = str_replace('{⟯link'.$key.'}',$links[$key],$text);
		}

		$text = nl2br(trim($text));
		
		return $text;
	}

	function wakaba_mark (& $string, & $state) {
		$string = $this->wakaba_strike($string);

		if (preg_match('/^(?:\-|\+|\*)\s+(.*)$/',$string,$match)) {
			$new_state = 'ul';
		} elseif (preg_match('/^\d+\.\s+(.*)$/',$string,$match)) {
			$new_state = 'ol';
		} else {
			$new_state = '';
		}

		if ($state == $new_state) {
			$string = empty($new_state) ? $string : '<li>'.$match[1].'</li>';
		} else {
			$tmp_string = '';
			$tmp_string .= !empty($state) ? '</'.$state.'>' : '';
			$tmp_string .= !empty($new_state) ? '<'.$new_state.'><li>'.$match[1].'</li>' : $string;
			$string = $tmp_string;
		}
		$state = $new_state;

		$string = preg_replace('/(\*{2}|_{2})(.+?)\1/', '<b>$2</b>', $string);
		$string = preg_replace('/(\*|_)(.+?)\1/', '<i>$2</i>', $string);
		$string = preg_replace('/`(.+?)`|^ {4}(.+)$/', '<code>$1$2</code>', $string);
		$string = preg_replace('/%{2}(.+?)%{2}/', '<span class="board_spoiler">$1</span>', $string);
		$string = preg_replace('/^&gt;(?!&gt;\d+(\s|$))(.+)$/', '<span class="board_quote">$0</span>', $string);
		$string = preg_replace('/\s{2,}/e','str_replace(array(" ","\t"),"&nbsp;","$0")',$string);
	}

	function wakaba_strike ($string) {
		$parts = preg_split('/((?:\^H)+|\{⟯link\d+\})/',$string,null,PREG_SPLIT_DELIM_CAPTURE);
		foreach ($parts as $key => $part) {
			if ($key && $part{0}.$part{1} == '^H' && $parts[$key-1]{1} != '⟯') {
				unset($parts[$key]);
				$parts[$key-1] = undo_safety($parts[$key-1]);

				$parts[$key-1] =
					redo_safety(mb_substr($parts[$key-1], 0, -1/2 * strlen($part))) . '<s>' .
					redo_safety(mb_substr($parts[$key-1], -1/2 * strlen($part))) . '</s>';
			}
		}

		return implode('',$parts);
	}

	function wcase ($count, $case1, $case2, $case3) {
		if ($count > 9) {
			if ($count % 10 == 0 || $count % 10 > 4 || $count[strlen($count)-2] == 1) return $case3;
			if ($count % 10 == 1) return $case1;
			return $case2;
		}
		if ($count == 0 || $count > 4) return $case3;
		if ($count == 1) return $case1;
		return $case2;
	}

	public static function cut_long_words ($string, $length = false, $break = '<wbr />') {

		if (empty($length)) $length = Config::template('word_cut_length');

		$parts = preg_split('/(<[^>]*>|\s)/',$string,null,PREG_SPLIT_DELIM_CAPTURE);

		foreach ($parts as $key => $part) {
			if (
				!empty($part) &&
				!in_array($part{0},array(' ',"\t","\r","\n",'<')) &&
				strlen($part) > $length &&
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

		return implode($break,$parts);
	}
	
	public static function insert_linebreaks ($text, $symbol) {
		$symbol = (array) $symbol;
		$symbol = preg_quote(join($symbol), '/');
		
		return preg_replace('/['.$symbol.']/us', '$0<wbr />', $text);
	}
	
	public static function cut_long_text ($text, $length, $prepend = ' ...', $cut_words = false) {
		if (
			strlen($text) < $length ||
			!preg_match('/((?:<.+?>)*(?:&[\w#\d]{1,8};|.)){'.$length.'}/ius', $text, $match)
		) {
			return empty($cut_words) ? $text : self::cut_long_words($text,$cut_words);
		}
	
		preg_match_all('/<([^\s>\/]+)(?![^>]*\/>)[^>]*>/is', $match[0], $opening_tags);
		preg_match_all('/<\/([^\s>]+)[^>]*>/is', $match[0], $ending_tags);
		
		$tags = array();
		
		foreach ($opening_tags[1] as $tag_name) {
			$tags[$tag_name]++;			
		}
		
		foreach ($ending_tags[1] as $tag_name) {
			$tags[$tag_name]--;			
		}
		
		$return = empty($cut_words) ? $match[0] : self::cut_long_words($match[0],$cut_words);

		foreach ($tags as $tag_name => $count) {
			if ($count > 0) {
				$return .= str_repeat('</'.$tag_name.'>',$count);
			} elseif ($count < 0) {
				$return = str_repeat('<'.$tag_name.'>',abs($count)).$return;
			}
		}
		
		if (strlen($match[0]) < strlen($text)) $return .= $prepend;
		
		return $return;
	}

	function bb2html ($string) {
        while (preg_match_all(self::bbcode_regex, $string, $matches)) {
			foreach ($matches[0] as $key => $match) {
				list($tag, $param, $innertext) = array($matches[1][$key], $matches[2][$key], $matches[3][$key]);
				switch ($tag) {
					case 'b': $replacement = "<strong>$innertext</strong>"; break;
					case 'i': $replacement = "<em>$innertext</em>"; break;
					case 's': $replacement = "<s>$innertext</s>"; break;
					case 'size': 
						if ($param{0} != '+' && $param{0} != '-') $param = '+'.$param;
						$replacement = "<font size=\"$param;\">$innertext</span>"; 
						break;
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
