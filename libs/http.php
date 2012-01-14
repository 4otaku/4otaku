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
	);

	protected $default_options = array(
		CURLOPT_USERAGENT => "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12",
		CURLOPT_FOLLOWLOCATION => false
	);

	protected $range = false;

	protected $norange_domains = array(
		'4shared.com'
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

			if ($this->range) {
				$domain = parse_url($url, PHP_URL_HOST);

				if (!empty($domain)) {
					$domain = preg_replace('/^www\./i', '', $domain);
					if (!in_array($domain, $this->norange_domains)) {
						$request->setCurlOption(CURLOPT_RANGE, $this->range);
					}
				}
			}

			$this->worker->pushRequestToQueue($request);
		}

		return $this;
	}

	public function enable_limit($limit) {
		$this->range = '1-' . $limit;

		return $this;
	}

	public function disable_limit() {
		$this->range = false;

		return $this;
	}

	public function exec () {
		$this->worker->start();

		return $this;
	}

	public function set_debug() {
		$this->worker->onRequestComplete(array($this, "notify_error"));
	}

	public function notify_error($request) {
		$error = $request->getFailException();
		if ($error && $request->getCode() != 206) {
			ob_end_flush();
			var_dump($error);
		}
	}

	public function flush () {
		$this->response_header = array();
		$this->response_data = array();

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

	public function get_full ($url) {
		$headers = $this->get_headers($url);
		$html = $this->get($url);

		$string = '';
		foreach ($headers as $type => $header) {
			$string .= "$type: $header\n";
		}

		$string .= "\n$html";

		return $string;
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
