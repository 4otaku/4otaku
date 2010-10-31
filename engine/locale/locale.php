<?	

$dir = 'engine/locale/'.$sets['locale'].'/';
$files = scandir($dir);
foreach ($files as $file) 
	if (pathinfo($dir.$file,PATHINFO_EXTENSION) == 'inc') {
		$part = pathinfo($dir.$file,PATHINFO_FILENAME);
		$temp_language = file($dir.$file,FILE_SKIP_EMPTY_LINES);
		foreach ($temp_language as $string) {
			$string = explode('=',strip_tags($string));
			$key = trim($string[0]);
			$value = trim(implode('=',array_slice($string,1)));
			if ($key && $value) $language[$part][$key] = $value;
		}
	}

function lang($string) {
	global $language; 	
	$part = explode('.',$string);
	if ($language[$part[0]][$part[1]]) return $language[$part[0]][$part[1]];
	return $part[1];
}
