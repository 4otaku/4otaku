<?php

abstract class Api_Create_Abstract extends Api_Abstract {

	protected $uid = '';
	protected $ip = '';

	public function __construct(Api_Request $request) {
		parent::__construct($request);

		$data = serialize($this->get());

		$this->uid = $this->generate_uid($data);
		$this->ip = $_SERVER['REMOTE_ADDR'];
		$this->log('input', $data);
	}

	public function get_response() {

		$response = parent::get_response();
		$this->log('output', $response);

		return $response;
	}

	protected function generate_uid($data) {
		return md5($data);
	}

	protected function log($type, $data) {
		$model = new Model_Api_Log(array(
			'uid' => $this->uid,
			'ip' => $this->ip,
			'type' => $type,
			'data' => $data,
		));

		$model->insert();
	}
}
