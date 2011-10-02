<?php

class Http
{
	const CONNECTIONS_LIMIT = 10;

	protected $default_headers = array(
		"Accept" => "text/xml,application/xml,application/xhtml+xml,text/html,q=0.9,text/plain,q=0.8,image/png,*/*,q=0.5",
		"Cache-Control" => "no-cache",
		"Connection" => "Keep-Alive",
		"Keep-Alive" => "300",
		"Accept-Charset" => "UTF-8,Windows-1251,ISO-8859-1,q=0.7,*,q=0.7",
		"Accept-Language" => "ru,en-us,en,q=0.5",
		"Pragma" => "",
	);

	protected $default_options = array(
		CURLOPT_USERAGENT => "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12",
		CURLOPT_FOLLOWLOCATION => false,
	);

	protected $response_header = array();
	protected $response_data = array();

	protected $worker;

	public function __construct ($options = array(), $headers = array()) {

		$worker = new MultiRequest_Handler();
		$worker->setConnectionsLimit(self::CONNECTIONS_LIMIT);
		$worker->onRequestComplete(array($this, "save_headers"));
		$worker->onRequestComplete(array($this, "save_data"));

		$headers = array_replace($this->default_headers, $headers);
		foreach ($headers as $key => $header) {
			$headers[$key] = "$key: $header";
		}
		$headers = array_values($headers);
		$worker->requestsDefaults()->addHeaders($headers);

		$options = array_replace($this->default_options, $options);
		$worker->requestsDefaults()->addCurlOptions($options);

		$this->worker = $worker;
	}

	public function add ($urls) {
		$urls = (array) $urls;

		foreach($urls as $url) {
			$request = new MultiRequest_Request($url);
			$this->worker->pushRequestToQueue($request);
		}

		return $this;
	}

	public function exec () {
		$this->worker->start();

		return $this;
	}

	public function save_headers ($request) {
		$this->response_header[$request->getRawUrl()] = $request->getResponseHeaders(true);
	}

	public function save_data ($request) {
		$this->response_data[$request->getRawUrl()] = $request->getContent();
	}

	public function get ($url) {
		return $this->response_data[$url];
	}

	public function get_headers ($url) {
		return $this->response_header[$url];
	}

	public static function download ($url) {
		$worker = new Http();
		$worker->add($url)->exec();

		return $worker->get($url);
	}

	public static function redirect($url, $permanent = false) {
		if ((bool) $permanent) {
			header("HTTP/1.x 301 Moved Permanently");
		} else {
			header("HTTP/1.x 302 Moved Temporarily");
		}

		if (empty($url)) {
			$url = $_SERVER["REQUEST_URI"];
		}

		header("Location: $url");
		exit();
	}
}
