<?php

abstract class Api_Request_Abstract {

	abstract public function convert($input);

	public function process($input) {
		$data = $this->convert($input);

		return (array) $data;
	}
}
