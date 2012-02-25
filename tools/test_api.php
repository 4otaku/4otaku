<?php
die;
header("Content-type: application/xml");
$ch = curl_init();

$data = array(
	'link' => array(array(
		'link' => 'http://www.youtube.com/watch?v=xWtOT6dZhc4&feature=related', 
		'name' => 'nya',
		'size' => '123.12 gigabyte sfgb')),
	'bonus_link' => array(array(
		'link' => 'http://www.youtube.com/watch?v=xWtOT6dZhc4&feature=related', 
		'name' => 'nya',
		'size' => '123.12 gigabyte sfgb')),		
	'category' => array('Фото', 'nsfw'),
	'image' => array('http://4otaku.ru/images/booru/full/ed2bc81e27e3117ffec410808c374da5.jpg', 'http://4otaku.ru/images/board/full/719cef4d0aa4ddf5a139ccb1c12e7a0f.jpg'),
	'torrent' => array(array('file' => 'http://4otaku.ru/files/post/132636775782/radiantrecords.torrent')),
	'file' => array(array('file' => 'http://4otaku.ru/files/post/132636775782/radiantrecords.torrent'),
	array('file' => 'http://4otaku.ru/images/booru/full/0254ae9f8367bf23451549c0aa3400b3.jpg'),
	array('file' => 'http://4otaku.ru/files/post/13253438304/mbaa_mk2.rar'),
	array('file' => 'http://4otaku.ru/files/post/132532030717/mbaacc107releaserev11.exe'),
	array('file' => 'http://4otaku.ru/files/post/132500418983/audiotrack.mp3'),
	),
	'title' => '23ffRaraonyanya',
	'text' => 'тохо',
	'tag' => '東方アレンジ',
	'type' => 'author',
	'source' => '4otaku.ru',
	'format' => 'xml'
);

curl_setopt($ch, CURLOPT_URL, "http://4otaku.local/api/create/post");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);

curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
$result = curl_exec($ch);

echo $result;
