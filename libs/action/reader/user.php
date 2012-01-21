<?php

class Action_Reader_User extends Action_Reader_Abstract
{
	public function __construct() {
		$this->data = query::$post;
	}
}
