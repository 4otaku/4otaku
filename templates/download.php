<?
	if (!$data['main']['type']) header("Content-type: application/zip");
	else header("Content-type: image/".$data['main']['type']);
	header("Content-Disposition: attachment; filename=".$data['main']['name']);
	header("Pragma: no-cache");
	header("Content-Transfer-Encoding: binary");
	header("Expires: 0");
	header("Content-Length: ".filesize($data['main']['file']));
	ob_clean();
	readfile($data['main']['file']);
	flush();
