<?php

class Error_Upload extends Error
{
	const
		EMPTY_FILE = 5,
		FILE_TOO_LARGE = 10,
		ALREADY_EXISTS = 30,
		NOT_A_TORRENT = 200;

	public function __construct($code = 0, Exception $previous = null) {
		parent::__construct('', $code, $previous);
	}
}
