<?
define('DEBUG', $_SERVER['REMOTE_ADDR'] == '80.252.16.11');

$request = preg_replace('/^\/go\/?(\?|\/index\.php\?)?/i', '', urldecode($_SERVER['REQUEST_URI']));

$test = preg_match_all('/(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?(.*)?$/iD', $request, $matches);
if (!($test == 1 || !(preg_match('/[^a-zа-яё\d_\-\+%&\.,=\/]/iu', $request) && $test == 0))) die;

$link = str_replace('"','\\"',$matches[0][0] ? $matches[0][0] : $request);

$warning = true;
$valid = array('narod.ru', 'megaupload.com', 'mediafire.com', '4shared.com');
foreach ($valid as $domain) {
	if (stripos($link, $domain)) {
		$warning = false;
		break;
	}
}

if ($warning) {
	include_once '../engine/config.php';

	if (stripos($_SERVER["HTTP_REFERER"], $def['site']['domain'])) {
		$warning = false;
	}
}

if (!preg_match('/^[a-z]{2,7}:\/\//i', $link)) {
	$link = 'http://'.$link;
}

if (!$warning) {

	header("Location: $link", true, 302);

	?>
	<html>
		<head>
			<meta name="robots" content="noindex" />
			<meta http-equiv="REFRESH" content="0;url=<?=$link;?>">
		</head>
		<body>
			Выполняется перенаправление на адрес
			<a href="<?=$link;?>">
				<?=$link;?>
			</a>
		</body>
	</html>
<? } else {
	header("HTTP/1.x 404 Not Found");
}
