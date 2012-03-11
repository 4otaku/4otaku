<?php

abstract class Api_Create_Abstract extends Api_Abstract
{

	const MAX_URL_LENGTH = 2000;

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
/*		$model = new Model_Api_Log(array(
			'uid' => $this->uid,
			'ip' => $this->ip,
			'type' => $type,
			'data' => base64_encode(gzdeflate($data, 9))
		));

		$model->insert(); */
	}

	protected function add_error($code, $error = '') {
		if ($code == Error_Api::ALREADY_EXISTS) {
			$this->add_answer('id', $error);
			$error = '';
		}

		parent::add_error($code, $error);
	}

	protected function transform_category() {
		$data = (array) $this->get('category');

		if (empty($data)) {
			return array('none');
		}

		$result = (array) Database::get_vector('category', array('alias', 'name'),
			Database::array_in('name', $data), $data);

		foreach ($data as &$one) {
			if (in_array($one, $result)) {
				$one = array_search($one, $result);
			}
		}

		return $data;
	}

	protected function transform_language() {
		$data = (array) $this->get('language');

		if (empty($data)) {
			return array('none');
		}

		$result = (array) Database::get_vector('language', array('alias', 'name'),
			Database::array_in('name', $data), $data);

		foreach ($data as &$one) {
			if (in_array($one, $result)) {
				$one = array_search($one, $result);
			}
		}

		return $data;
	}

	protected function get_file($field, $error_empty = '', $error_unreachable = '') {

		if (empty($field)) {
			throw new Error_Api($error_empty, Error_Api::MISSING_INPUT);
		}

		if (strlen($field) < self::MAX_URL_LENGTH && parse_url($field)) {
			$data = Http::download($field);
			$error = $error_unreachable;
		} else {
			$data = base64_decode($field);
			$error = 'Некорректный формат передачи бинарных данных. Они были закодированы в base64?';
		}

		if (empty($data)) {
			throw new Error_Api($error, Error_Api::INCORRECT_INPUT);
		}

		return $data;
	}

	protected function get_extension($data) {
		$finfo = new finfo(FILEINFO_MIME);
		$info = $finfo->buffer($data);

		return preg_replace(array('/^.*?\//', '/;.*/'), '', $info);
	}
}
