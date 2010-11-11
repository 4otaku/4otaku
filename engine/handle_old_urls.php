<?

$redirect = array('tag','category','author','language','mixed','updates','page');

if (is_numeric($url[1]) || in_array($url[1],$redirect)) {
	$link = '/post/'.implode('/',$url);
}
elseif ($url[1] == 'orders') {
	$url[1] = 'order';
	$link = '/'.implode('/',$url);
}
elseif (!file_exists('libs/output/'.$url[1].'.php') && $db->sql('select id from news where url="'.$url[1].'"',2)) {
	$link = '/news/'.implode('/',$url);
}

if ($url[1] == 'mixed') $link = str_replace(';','&',$link);

if ($url[1] == 'search' && (!$url[3] || $url[3] == 'page'))
{
	$url[1] = 'search/p/rel';
	$link = '/'.implode('/',$url);
} 

if ($link) {
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
	<?
	die;
}
