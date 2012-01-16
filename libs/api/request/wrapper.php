<?php

class Api_Request
{
	protected $converters = array(
		'Api_Request_Json', 'Api_Request_Xml'
	);

	protected $default_response_format = 'php';

	protected $data = array();

	public function __construct($data = null) {
		if (empty($data)) {
			$data = array_replace_recursive(query::$get, query::$post);

			if (empty($data)) {
				$input = trim(file_get_contents("php://input"));
				if (!empty($input)) {
					$data = $this->convert_input($input);
				}
			}
		}

		$this->data = $data;
	}

	protected function convert_input($input) {
		$converters = $this->converters;
		$data = array();

		while (!$data && $converters) {
			$converter = array_shift($converters);

			try {
				$converter = new $converter();
				$data = $converter->process($input);
			} catch (Error_Api_Request $e) {
				$data = array();
			}
		}

		return $data;
	}

	public function get_response_class() {
		if (empty($this->data['format']) ||
			!class_exists('Api_Response_' . ucfirst($this->data['format']))) {

			$format = $this->default_response_format;
		} else {
			$format = $this->data['format'];
		}

		return 'Api_Response_' . ucfirst($format);
	}

	public function get($field = false) {
		if (empty($field)) {
			return $this->data;
		}

		if (!empty($this->data[$field])) {
			return $this->data[$field];
		}

		return null;
	}
}
