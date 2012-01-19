<?php

class Error extends Exception {
	const
		NOT_AN_IMAGE = 20;

	public function __construct($message = '', $code = 0, Exception $previous = null) {

		if (is_int($message) && (empty($code) || !is_int($code))) {
			parent::__construct('', $message, $code);
		} else {
			parent::__construct($message, $code, $previous);
		}
	}
}
