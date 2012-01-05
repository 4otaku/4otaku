<?

$redirect = array('tag', 'category', 'author',
	'language', 'mixed', 'updates', 'page');

$news = array(
	'1' => 'tech_support', '2' => 'kikaki', '3' => 'torrent', '4' => 'enlist', '5' => 'board_open', '6' => 'yumiko',
	'7' => 'plans_and_problems', '8' => 'imageboard', '9' => 'new_4otaku', '10' => 'download', '11' => 'codecs',
	'12' => 'function_updates', '13' => 'boards', '14' => '1year', '15' => 'question_board', '16' => 'dreams_of_dead',
	'17' => 'jabber', '18' => 'newyear2010', '19' => 'moveout', '20' => 'engine-v1.1', '21' => 'gouf_mk1',
	'22' => 'dod:_3.0', '23' => 'stardust_contest', '24' => 'bunbunmaru_01', '25' => 'booru_jabber',
	'26' => 'new_year_old', '27' => 'badaboom', '28' => 'booru', '29' => 'extracted_cg', '30' => '4team',
	'31' => 'functionality', '32' => 'birthday2', '33' => 'starlight_typhoon', '34' => 'sunday_cleaning',
	'35' => 'donelist', '36' => 'some_updates', '37' => 'gensokyo', '38' => 'board_returns', '39' => 'manga_suzunari',
	'40' => 'new2011year', '41' => 'wanted', '42' => 'new_features', '43' => '4otaku_3.0', '44' => 'delegating',
	'45' => 'shoot_the_moon', '46' => '1april', '47' => 'in_progress', '48' => 'in_progress_da_ze',
	'51' => 'sorry', '50' => 'stop_trolling_upd', '52' => 'forsaken_land', '53' => 'day_of_gifts',
	'54' => 'working', '55' => 'drawing_contest', '56' => 'row_row_translate_da_doujin', '57' => 'technology',
	'58' => 'drawing_site_in_process', '59' => 'drawing_site_complete', '60' => 'vocabound', '61' => 'art_wiki',
	'62' => 'comiket_challenge', '63' => 'i_fail', '64' => 'voca', '65' => 'too_many_bugs', '66' => 'preparing_birthday',
	'67' => 'happy_3rd_birthday', '68' => 'dark_side', '69' => 'service_down', '70' => 'new_functions', '71' => 'new2012year',
);

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
} elseif (
	$url[1] == 'news' &&
	($id = array_search($url[2], $news))
) {
	$url[2] = $id;
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
