<?php

class Api_Error extends Api_Abstract {

	public function __construct(Api_Request $request) {
		parent::__construct($request);

		$this->add_error('worker not found');
	}
}
