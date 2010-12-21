<?

foreach ($_COOKIE as $key => $cook) if ($key != 'settings') setcookie ($key, "", time() - 3600);

cleanGlobals($_GET);
cleanGlobals($_POST);

$get = parseIncomingRecursively($_GET, array());
$post = parseIncomingRecursively($_POST, array());

if (isset($post['remember'])) {
	$md5 = md5(serialize($post));
	if (obj::db()->sql('select id from input_filter where md5 = "'.$md5.'"',2)) unset($post);
	else obj::db()->insert('input_filter',array($md5,time()));
}

function cleanGlobals(&$data,$iteration = 0) {

	if ($iteration > 10) {
		return;
	}

	foreach ($data as $k => $v)	{
		if (is_array($v)) {
			cleanGlobals($data[$k],$iteration + 1);
		}
		else {
			$v = str_replace( chr('0') , '', $v );
			$v = str_replace( "\0" , '', $v );
			$v = str_replace( "\x00" , '', $v );
			$v = str_replace( '%00' , '', $v );
			$v = str_replace( "../", "&#46;&#46;/", $v );
			$data[$k] = stripslashes($v);
		}
	}
}

function parseIncomingRecursively(&$data,$input,$iteration = 0) {
	if ($iteration > 10) {
		return $input;
	}

	foreach($data as $k => $v) {
		if (is_array($v)) {
			$input[$k] = parseIncomingRecursively($data[$k], array(), $iteration + 1);
		}
		else {
			$k = str_replace(array('&','"','<','>','\\',"'",'⟯'),array('&amp;','&quot;','&lt;','&gt;','&#092;','&apos;',''),$k);
			$v = str_replace(array('&','"','<','>','\\',"'",'⟯'),array('&amp;','&quot;','&lt;','&gt;','&#092;','&apos;',''),$v);
			$input[$k] = $v;
		}
	}

	return $input;
}
