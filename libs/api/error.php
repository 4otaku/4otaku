<?php

class Api_Error extends Api_Abstract {

	const INCORRECT_URL = 10;

	public function process() {
		$this->add_error(Api_Error::INCORRECT_URL);
	}
}
