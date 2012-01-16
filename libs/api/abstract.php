<?php

abstract class Api_Abstract {

	protected $request;

	protected $success = false;

	protected $errors = array();
	protected $answer = array();

	public function __construct(Api_Request $request) {
		$this->request = $request;
	}

	protected function add_error($error) {
		$this->errors[] = (String) $error;
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
