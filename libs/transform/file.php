<?

class Transform_File
{
	public static function weight($filesize) {
		$filesize = (int) $filesize;
		if ($filesize > 1024*1024) {
			return round($filesize/(1024*1024), 1).' мегабайт';
		} elseif ($filesize > 1024) {
			return round($filesize/1024, 1).' килобайт';
		} else {
			return $filesize.' байт';
		}
	}

	public static function weight_short($filesize) {
		$filesize = (int) $filesize;
		if ($filesize > 1024*1024*1024) {
			return round($filesize/(1024*1024), 1).' гб';
		} elseif ($filesize > 1024*1024) {
			return round($filesize/(1024*1024), 1).' мб';
		} elseif ($filesize > 1024) {
			return round($filesize/1024, 1).' кб';
		} else {
			return $filesize.' байт';
		}
	}

	public static function make_name($word) {
		$word = strtolower(self::jap2lat(self::ru2lat(undo_safety($word))));
		$word = preg_replace('/[^a-z\d\[\]\.\-]/ui', '_', $word);
		return $word;
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

class transform__file extends Transform_File {}
