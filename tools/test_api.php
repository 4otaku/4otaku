<?php
header("Content-type: application/xml");
$ch = curl_init();

$data = array(
	'link' => array(array(
		'link' => 'http://www.youtube.com/watch?v=xWtOT6dZhc4&feature=related', 
		'name' => 'nya')),
	'category' => array('Фото', 'nsfw'),
	'torrent' => array('http://4otaku.ru/files/post/132636775782/radiantrecords.torrent'),
	'title' => 'Василий',
	'text' => 'тохо',
	'tag' => '東方アレンジ',
	'type' => 'author',
	'source' => '4otaku.ru',
//	'format' => 'xml'
);

curl_setopt($ch, CURLOPT_URL, "http://4otaku.local/api/create/post");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);

curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
$result = curl_exec($ch);

echo $result;
