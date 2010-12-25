<?

if (!function_exists('array_replace_recursive')) {
	function array_replace_recursive(&$array1,&$array2) {
		$merged = $array1;
		if(is_array($array2))
		foreach ($array2 as $key => &$value)
			if (is_array($value) && isset($merged[$key]) && is_array($merged[$key]))
				$merged[$key] = array_replace_recursive($merged[$key], $value);
			else
				$merged[$key] = $value;
		return $merged;
	}
}