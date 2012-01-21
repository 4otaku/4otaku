<?php

abstract class Create_Abstract extends Action_Abstract
{
	public function __construct() {
		parent::__construct();

		$this->writer->set_redirect();
	}
}
