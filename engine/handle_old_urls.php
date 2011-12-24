<?

$redirect = array('tag', 'category', 'author',
	'language', 'mixed', 'updates', 'page');

if (is_numeric($url[1]) || in_array($url[1], $redirect)) {

	$link = '/post/'.implode('/', $url);
} elseif ($url[1] == 'orders') {

	$url[1] = 'order';
	$link = '/'.implode('/',$url);
} elseif (
	$url[1] == 'search' &&
	(empty($url[3]) || $url[3] == 'page')
) {
	$url[1] = 'search/p/rel';
	$link = '/'.implode('/', $url);
}

if (isset($link)) {

	if ($url[1] == 'mixed') {
		$link = str_replace(';', '&', $link);
	}

	engine::redirect(SITE_DIR.$link, true, false);
	$domain = $def['site']['domain'] ? $def['site']['domain'] : $_SERVER['SERVER_NAME'];

	?>
		<html>
			<head>
				<meta name="robots" content="noindex" />
				<meta http-equiv="REFRESH" content="0;url=<?=SITE_DIR.$link;?>">
			</head>
			<body>
				Выполняется перенаправление на адрес
				<a href="<?=SITE_DIR.$link;?>">
					<?=get_protocol().$domain.SITE_DIR.$link;?>
				</a>
			</body>
		</html>
	<?
	exit();
}
