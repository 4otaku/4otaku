<?php

header("Content-type: application/xml");
$ch = curl_init();

$data = array(
	'image' => 'http://img.0chan.ru/s/src/13306222677674.jpg',
	'text' => 'тохо',
	'tag' => array('nura_(oaaaaaa)'),
	'type' => 'author',
	'source' => '4otaku.ru',
	'format' => 'xml'
);

curl_setopt($ch, CURLOPT_URL, "http://4otaku.local/api/create/art");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);

curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
$result = curl_exec($ch);

echo $result;
