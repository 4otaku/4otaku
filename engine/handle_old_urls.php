<?

$redirect = array('tag','category','author','language','mixed','updates','page');

if (is_numeric($url[1]) || in_array($url[1],$redirect)) {
	$link = SITE_DIR.'/post'.'/'.implode('/',$url);
}
elseif ($url[1] == 'orders') {
	$url[1] = 'order';
	$link = SITE_DIR.'/'.implode('/',$url);
}
elseif (!file_exists('libs/output/'.$url[1].'.php') && $db->sql('select id from news where url="'.$url[1].'"',2)) {
	$link = SITE_DIR.'/news'.'/'.implode('/',$url);
}

if ($url[1] == 'mixed') $link = str_replace(';','&',$link);

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
