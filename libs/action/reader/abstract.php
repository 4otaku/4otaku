<?php

abstract class Action_Reader_Abstract
{
	protected $data = array();

	public function get_data($key = false) {
		if (!empty($key)) {
			return isset($this->data[$key]) ? $this->data[$key] : null;
		}
		return $this->data;
	}
}
