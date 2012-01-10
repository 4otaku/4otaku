<?

class Transform_Meta
{
	private $tag_types = array(
		'none' => '',
		'character' => '00AA00',
		'персонаж' => '00AA00',
		'герой' => '00AA00',
		'hero' => '00AA00',
		'actor' => '00AA00',
		'series' => 'AA00AA',
		'аниме' => 'AA00AA',
		'copyright' => 'AA00AA',
		'произведение' => 'AA00AA',
		'game' => 'AA00AA',
		'игра' => 'AA00AA',
		'художник' => 'AA0000',
		'autor' => 'AA0000',
		'author' => 'AA0000',
		'artist' => 'AA0000',
		'автор' => 'AA0000',
		'мангака' => 'AA0000',
		'mangaka' => 'AA0000',
		'special' => '0000FF',
		'служебный'	=> '0000FF',
	);

	function parse($items, $default = 'Проставьте_теги', $separators = ', ') {
		global $sets;
		if (!$items = trim($items,$separators)) return array($default);
		$tags = array_unique(array_filter(explode(',',str_replace(str_split($separators),',',$items))));
		foreach ($tags as $key => $tag)
			if (preg_match('/(^(:?&lt;|<)\p{L}+(?:&gt;|>)|(?:&lt;|<)\p{L}+(?:&gt;|>)$)/u',$tag,$type)) {
				$tags[$key] = str_replace($type[0],'',$tag);

				$color_key = mb_strtolower(substr($type[0],4,-4),'UTF-8');
				if (isset($this->tag_types[$color_key])) {
					$this->colors[$tags[$key]] = $this->tag_types[$color_key];
				}
			}
		return $tags;
	}

	function parse_array ($items, $default = 'Проставьте_теги') {

		if (empty($items)) {
			return array($default);
		}

		if (!is_array($items)) {
			return $this->parse($items);
		}

		$tags = array_unique(array_filter($items));

		foreach ($tags as $key => $tag) {
			$tag = str_replace(array('&amp;'), array('&'), $tag);

			if (preg_match('/(^(:?&lt;|<)\p{L}+(?:&gt;|>)|(?:&lt;|<)\p{L}+(?:&gt;|>)$)/u',$tag,$type)) {
				$tags[$key] = str_replace($type[0],'',$tag);

				$color_key = mb_strtolower(substr($type[0],4,-4),'UTF-8');
				if (isset($this->tag_types[$color_key])) {
					$this->colors[$tags[$key]] = $this->tag_types[$color_key];
				}
			}
		}
		return $tags;
	}

	function erase_tags($erase, $erasearea) {
		foreach ($erase as $one) {
			obj::db()->sql('update tag set '.$erasearea.' = '.$erasearea.' - 1 where alias="'.$one.'"',0);
		}
	}

	function add_tags($tags, $update = false){
		foreach ($tags as $key => $tag) {

			$tag = undo_safety($tag);

			if ($check = obj::db()->sql('select alias from tag where name = "'.$tag.'" or locate("|'.$tag.'|",variants) or alias="'.$tag.'"',2)) {
				if ($update) obj::db()->sql('update tag set '.$update.' = '.$update.' + 1 where alias="'.$check.'"',0);
				if (isset($this->colors[$tag])) {
					obj::db()->update('tag','color',$this->colors[$tag],$check,'alias');
				}
				$tags[$key] = $check;
			} else {
				$alias = $this->make_alias($tag);
				obj::db()->insert('tag',array($alias,$tag,'|',$this->colors[$tag],0,0,0,0,0,0,0,0));
				if ($update) obj::db()->update('tag',$update,1,$alias,'alias');
				$tags[$key] = $alias;
			}
		}
		return '|'.implode('|',$tags).'|';
	}

	function category($categories) {
		foreach ($categories as &$category)
			$category = obj::db()->sql('select alias from category where name = "'.$category.'" or alias="'.$category.'"',2);
		return '|'.implode('|',array_filter(array_unique($categories))).'|';
	}

	function language($languages) {
		foreach ($languages as &$language)
			$language = obj::db()->sql('select alias from language where name = "'.$language.'" or alias="'.$language.'"',2);
		return '|'.implode('|',array_filter(array_unique($languages))).'|';
	}

	function author($authors){
		foreach ($authors as &$author) {
			$author = preg_replace('/#.*$/','',$author);
			if ($check = obj::db()->sql('select alias from author where name = "'.$author.'" or alias="'.$author.'"',2))
				$author = $check;
			else {
				$alias = $this->make_alias($author);
				obj::db()->insert('author',array($alias,$author));
				$author = $alias;
			}
		}
		return '|'.implode('|',$authors).'|';
	}

	public static function make_alias($word) {
		$word = strtolower(self::jap2lat(self::ru2lat(undo_safety($word))));
		$word = str_replace(' ','_',$word);
		$word = preg_replace('/[^a-z_\d]/eui','urlencode("$0")',$word);
		return str_replace('%5C%27', '%27', $word);
	}

	/* Не трогаем - тут какая-то аццкая хрень с пробелами, работает только так */

	public static function jap2lat($st) {
		$k2r = array('/きゃ/' => 'kya', '/きゅ/' => 'kyu', '/きょ/' => 'kyo', '/
しゃ/' => 'sha', '/しゅ/' => 'shu', '/しょ/' => 'sho', '/ちゃ/' =>
'cha', '/ちゅ/' => 'chu', '/ちょ/' => 'cho', '/にゃ/' => 'nya', '/にゅ/'
=> 'nyu', '/にょ/' => 'nyo', '/ひゃ/' => 'hya', '/ひゅ/' => 'hyu', '/
ひょ/' => 'hyo', '/みゃ/' => 'mya', '/みゅ/' => 'myu', '/みょ/' =>
'myo', '/りゃ/' => 'rya', '/りゅ/' => 'ryu', '/りょ/' => 'ryo', '/ぎゃ/'
=> 'gya', '/ぎゅ/' => 'gyu', '/ぎょ/' => 'gyo', '/じゃ/' => 'ja', '/じゅ
/' => 'ju', '/じょ/' => 'jo', '/ぢゃ/' => 'ja', '/ぢゅ/' => 'ju', '/ぢょ
/' => 'jo', '/びゃ/' => 'bya', '/びゅ/' => 'byu', '/びょ/' => 'byo', '/
ぴゃ/' => 'pya', '/ぴゅ/' => 'pyu', '/ぴょ/' => 'pyo', '/あ/' => 'a', '/
い/' => 'i', '/う/' => 'u', '/え/' => 'e', '/お/' => 'o', '/か/' =>
'ka', '/き/' => 'ki', '/く/' => 'ku', '/け/' => 'ke', '/こ/' => 'ko', '/
さ/' => 'sa', '/し/' => 'shi', '/す/' => 'su', '/せ/' => 'se', '/そ/' =>
'so', '/た/' => 'ta', '/ち/' => 'chi', '/つ/' => 'tsu', '/て/' => 'te',
'/と/' => 'to', '/な/' => 'na', '/に/' => 'ni', '/ぬ/' => 'nu', '/ね/'
=> 'ne', '/の/' => 'no', '/は/' => 'ha', '/ひ/' => 'hi', '/ふ/' => 'fu',
'/へ/' => 'he', '/ほ/' => 'ho', '/ま/' => 'ma', '/み/' => 'mi', '/む/'
=> 'mu', '/め/' => 'me', '/も/' => 'mo', '/や/' => 'ya', '/ゆ/' => 'yu',
'/よ/' => 'yo', '/ら/' => 'ra', '/り/' => 'ri', '/る/' => 'ru', '/れ/'
=> 're', '/ろ/' => 'ro', '/わ/' => 'wa', '/ゐ/' => 'wi', '/ゑ/' => 'we',
'/を/' => 'wo', '/ん/' => 'n', '/が/' => 'ga', '/ぎ/' => 'gi', '/ぐ/' =>
'gu', '/げ/' => 'ge', '/ご/' => 'go', '/ざ/' => 'za', '/じ/' => 'ji', '/
ず/' => 'zu', '/ぜ/' => 'ze', '/ぞ/' => 'zo', '/だ/' => 'da', '/ぢ/' =>
'ji', '/づ/' => 'zu', '/で/' => 'de', '/ど/' => 'do', '/ば/' => 'ba', '/
び/' => 'bi', '/ぶ/' => 'bu', '/べ/' => 'be', '/ぼ/' => 'bo', '/ぱ/' =>
'pa', '/ぴ/' => 'pi', '/ぷ/' => 'pu', '/ぺ/' => 'pe', '/ぽ/' => 'po',
'/　/'=>' ', '/っ(.)/' => '$1$1');
		return preg_replace(array_keys($k2r), array_values($k2r), $st);
	}

	public static function ru2lat($st) {
		$tbl= array('а'=>'a', 'б'=>'b', 'в'=>'v', 'г'=>'g', 'д'=>'d', 'е'=>'e',
			'ж'=>'g', 'з'=>'z', 'и'=>'i', 'й'=>'y', 'к'=>'k', 'л'=>'l', 'м'=>'m', 'н'=>'n',
			'о'=>'o', 'п'=>'p', 'р'=>'r', 'с'=>'s', 'т'=>'t', 'у'=>'u', 'ф'=>'f', 'ы'=>'i',
			'э'=>'e', 'А'=>'A', 'Б'=>'B', 'В'=>'V', 'Г'=>'G', 'Д'=>'D', 'Е'=>'E', 'Ж'=>'G',
			'З'=>'Z', 'И'=>'I', 'Й'=>'Y', 'К'=>'K', 'Л'=>'L', 'М'=>'M', 'Н'=>'N', 'О'=>'O',
			'П'=>'P', 'Р'=>'R', 'С'=>'S', 'Т'=>'T', 'У'=>'U', 'Ф'=>'F', 'Ы'=>'I', 'Э'=>'E',
			'ё'=>"yo", 'х'=>"h", 'ц'=>"ts", 'ч'=>"ch", 'ш'=>"sh", 'щ'=>"shch", 'ъ'=>"", 'ь'=>"",
			'ю'=>"yu", 'я'=>"ya", 'Ё'=>"YO", 'Х'=>"H", 'Ц'=>"TS", 'Ч'=>"CH", 'Ш'=>"SH", 'Щ'=>"SHCH",
			'Ъ'=>"", 'Ь'=>"", 'Ю'=>"YU", 'Я'=>"YA");
		return strtr($st, $tbl);
	}
}

class transform__meta extends Transform_Meta {}
