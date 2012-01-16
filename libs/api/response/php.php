<?php

class Api_Response_Php extends Api_Response_Abstract {

	public function encode(Array $data) {
		return serialize($data);
	}
}
