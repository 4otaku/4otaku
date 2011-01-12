<?
$request = preg_replace('/^\/go\/?(\?|\/index\.php\?)?/i', '', urldecode($_SERVER['REQUEST_URI']));

//if(!$_GET) die;

if (preg_match_all('/(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?(.*)?$/iD', $request, $matches) !== 1
|| preg_match('/[^a-zа-яё\d_\-\+%:&\.,=\/]/iu', $request))	die;

$link = str_replace('"','\\"',$matches[0][0] ? $matches[0][0] : $request);

header("Location: ".$link, true, 302);

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
