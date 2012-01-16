<?php

abstract class Api_Abstract {

	protected $request;
	protected $response;

	protected $success = false;

	protected $errors = array();
	protected $answer = array();

	public function __construct(Api_Request $request) {
		$this->request = $request;
	}

	abstract public function process();

	public function process_request() {
		$this->process();

		$response_class = $this->request->get_response_class();

		$this->response = new $response_class(
			$this->success,
			$this->errors,
			$this->answer
		);

		return $this;
	}

	public function send_headers() {

		$headers = $this->response->get_headers();
		ob_end_flush();

		foreach ($headers as $key => $header) {
			header("$key: $header");
		}

		return $this;
	}

	public function get_response() {

		return $this->response->encode();
	}

	protected function add_error($code, $error = '') {
		$this->errors[] = array($code, (String) $error);
	}

	protected function answer($data) {
		foreach ($data as $key => $item) {
			$this->add_answer($key, $item);
		}
	}

	protected function add_answer($key, $data) {
		$this->answer[$key] = $data;
	}

	protected function set_success($success) {
		$this->success = (bool) $success;
	}
}
