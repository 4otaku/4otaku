<?php

class Api_Response_Serialize extends Api_Response_Abstract {

	public function encode(Array $data) {
		return serialize($data);
	}
}
