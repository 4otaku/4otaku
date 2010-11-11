<?

if(!$_GET || preg_match_all('/(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?(.*)?$/iD', urldecode($_SERVER['REQUEST_URI']), $matches) !== 1) die;

$link = htmlspecialchars($matches[0][0]);

header("Location: ".$link, TRUE, 302);

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
