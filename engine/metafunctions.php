<?

function undo_safety($str) {
	return str_replace(array('&amp;','&quot;','&lt;','&gt;','&092;','&apos;'),array('&','"','<','>','\\',"'"),$str);
}

function redo_safety($str) {
	return str_replace(array('&','"','<','>','\\',"'"),array('&amp;','&quot;','&lt;','&gt;','&092;','&apos;'),$str);
}

function undo_quotes($str) {
	return str_replace(array('&amp;','&quot;','&apos;'),array('&','"',"'"),$str);
}

function redo_quotes($str) {
	return str_replace(array('&','"',"'"),array('&amp;','&quot;','&apos;'),$str);
}

function _crypt($input,$decrypt=false) {
	$o = $s1 = $s2 = array(); 

	$basea = array('?','(','$','',"]",'@',';',"&",'*'); 
	$basea = array_merge($basea, range('a','z'), range('A','Z'), range(0,9) );
	$basea = array_merge($basea, array('!','/','[','|','.',')','_','+','%',' ') );
	$dimension=9; 
	for($i=0;$i<$dimension;$i++) { 
		for($j=0;$j<$dimension;$j++) {
			$s1[$i][$j] = $basea[$i*$dimension+$j];
			$s2[$i][$j] = str_rot13($basea[($dimension*$dimension-1) - ($i*$dimension+$j)]);
		}
	}
	unset($basea);
	$m = floor(strlen($input)/2)*2; 
	$symbl = $m==strlen($input) ? '':$input[strlen($input)-1]; 
	$al = array();

	for ($ii=0; $ii<$m; $ii+=2) {
		$symb1 = $symbn1 = strval($input[$ii]);
		$symb2 = $symbn2 = strval($input[$ii+1]);
		$a1 = $a2 = array();
		for($i=0;$i<$dimension;$i++) { 
			for($j=0;$j<$dimension;$j++) {
				if ($decrypt) {
					if ($symb1===strval($s2[$i][$j]) ) $a1=array($i,$j);
					if ($symb2===strval($s1[$i][$j]) ) $a2=array($i,$j);
					if (!empty($symbl) && $symbl===strval($s2[$i][$j])) $al=array($i,$j);
				}
				else {
					if ($symb1===strval($s1[$i][$j]) ) $a1=array($i,$j);
					if ($symb2===strval($s2[$i][$j]) ) $a2=array($i,$j);
					if (!empty($symbl) && $symbl===strval($s1[$i][$j])) $al=array($i,$j);
				}
			}
		}
		if (sizeof($a1) && sizeof($a2)) {
			$symbn1 = $decrypt ? $s1[$a1[0]][$a2[1]] : $s2[$a1[0]][$a2[1]];
			$symbn2 = $decrypt ? $s2[$a2[0]][$a1[1]] : $s1[$a2[0]][$a1[1]];
		}
		$o[] = $symbn1.$symbn2;
	}
	if (!empty($symbl) && sizeof($al))
		$o[] = $decrypt ? $s1[$al[1]][$al[0]] : $s2[$al[1]][$al[0]];
	return implode('',$o);
}

function _base64_encode($input, $nodotes = false) {
	return str_replace(array('+','/','='),array('-','_',($nodotes ? '' : '.')),base64_encode($input));
}

function _base64_decode($input) {
	return base64_decode(str_replace(array('-','_','.'),array('+','/','='),$input));
}

function encrypt($input) {
	return _base64_encode(_crypt($input));
}

function decrypt($input) {	
	return _crypt(_base64_decode($input),true);
}

function hex2bin($md5) {
    for ($i = 0; $i < 32; $i += 2)
        $return .= chr(hexdec($md5{$i + 1}) + hexdec($md5{$i})*16);

    return $return;
}

function get_include_contents($filename) {
    if (is_file($filename)) {
        ob_start();
        include $filename;
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
    return false;
}

function get_protocol() {
	return strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https'? 'https://' : 'http://';
} 
