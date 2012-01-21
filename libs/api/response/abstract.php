<?php

abstract class Api_Response_Abstract {

	protected $headers = array();

	protected $success = false;

	protected $errors = array();
	protected $answer = array();

	protected $error_messages = array(
		Error_Api::INCORRECT_URL => 'Incorrect url specified',
	);

	public function __construct($success = false, $errors = array(), $answer = array()) {
		$this->success = $success;
		$this->errors = $errors;
		$this->answer = $answer;
	}

	public function get_headers() {
		return $this->headers;
	}

	protected function set_header($name, $value) {
		$this->headers[$name] = $value;
	}

	public function get() {
		$data = $this->answer;

		$data['success'] = $this->success;

		$data['errors'] = array();
		foreach ($this->errors as $error) {
			if (!empty($error[1])) {
				$message = (String) $error[1];
			} elseif (array_key_exists($error[0], $this->error_messages)) {
				$message = $this->error_messages[$error[0]];
			} else {
				$message = '';
			}

			$data['errors'][] = array('code' => $error[0], 'message' => $message);
		}

		return $this->encode($data);
	}

	abstract public function encode(Array $data);
}
