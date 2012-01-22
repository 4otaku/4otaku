<?php

$ch = curl_init();

$data = array(
	'archive' => base64_encode(file_get_contents('/tmp/1.zip')),
//	'category' => array('Фото', 'nsfw'),
	'title' => 'Василий',
	'text' => '[b]Тест[/b]',
	'source' => '4otaku.ru',
//	'format' => 'xml'
);

curl_setopt($ch, CURLOPT_URL, "http://4otaku.local/api/create/art/pack");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);

curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
$result = curl_exec($ch);

echo $result;
