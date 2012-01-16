<?php

class Api_Response_Json extends Api_Response_Abstract {

	protected $headers = array('Content-type' => 'application/json');

	public function encode(Array $data) {
		return json_encode($data);
	}
}
