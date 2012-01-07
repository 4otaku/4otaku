<?

class Transform_Array
{
	public static function meta_sort ($a, $b) {
		if (!is_array($a) || !is_array($b)) {
			return self::locale_natcmp($a, $b);
		}

		if (isset($a['name']) && isset($b['name'])) {
			return self::locale_natcmp($a['name'], $b['name']);
		}

		return 0;
	}

	public static function locale_natcmp ($a, $b) {
		preg_match('/(\p{Cyrillic})|(\p{Latin})|(\p{Hiragana}|\p{Katakana})/ui', $a, $a_index);
		preg_match('/(\p{Cyrillic})|(\p{Latin})|(\p{Hiragana}|\p{Katakana})/ui', $b, $b_index);

		if (count($a_index) != count($b_index)) {
			return count($a_index) - count($b_index);
		}

		return strnatcasecmp($a, $b);
	}
}

class transform__array extends Transform_Array {}
