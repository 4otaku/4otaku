<?php

class Api_Error extends Api_Abstract
{
	public function process() {
		$this->add_error(Error_Api::INCORRECT_URL);
	}
}
