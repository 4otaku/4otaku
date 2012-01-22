<?php

$ch = curl_init();

$data = array(
	'link' => 'http://www.youtube.com/watch?v=xWtOT6dZhc4&feature=related',
	'category' => array('Фото', 'nsfw'),
	'title' => 'Василий',
	'text' => '[b]Тест[/b]',
	'source' => '4otaku.ru',
//	'format' => 'xml'
);

curl_setopt($ch, CURLOPT_URL, "http://4otaku.local/api/create/video");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);

curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
$result = curl_exec($ch);

echo $result;
