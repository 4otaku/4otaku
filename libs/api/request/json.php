<?php

class Api_Request_Json extends Api_Request_Abstract
{
	public function convert($input) {
		return json_decode($input, true);
	}
}
