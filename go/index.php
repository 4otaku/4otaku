<?

if (!$_GET) die;

$start = explode('?',$_SERVER['REQUEST_URI']);
$link = urldecode(implode('?',array_slice($start,1)));

$array = explode ("/",$link);

foreach ($array as $num => &$part) if ($num == 2) $part = str_replace('_','.',$part);

$link = implode('/',$array);
header("Location: ".$link,TRUE,302);

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


