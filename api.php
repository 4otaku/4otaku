<?php

include_once 'inc.common.php';

$request = preg_replace('/^'.preg_quote(SITE_DIR,'/').'/', '', $_SERVER["REQUEST_URI"]);
$request = urldecode($request);

$url = explode('/', preg_replace('/\?[^\/]+$/', '', $request));

$url = array_slice($url, 2);
$class = 'Api_' . implode('_', array_map('ucfirst', $url));

if (!class_exists($class)) {
	$class = 'Api_Error';
}

$request = new Api_Request();
$worker = new $class($request);

if (!($worker instanceOf Api_Abstract)) {
	$worker = new Api_Error($request);
}

echo $worker->process_request()->send_headers()->get_response();
