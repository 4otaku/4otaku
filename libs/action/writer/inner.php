<?php

class Action_Writer_Inner extends Action_Writer_Abstract
{
	// Пока не нужно
	public function process_actions() {}

	public function return_data() {
		return $this;
	}

	public function get_success() {
		return $this->success;
	}

	public function get_message() {
		return $this->message;
	}

	public function get_error() {
		return $this->error;
	}

	public function get_data($key = false) {
		if (!empty($key)) {
			return isset($this->data[$key]) ? $this->data[$key] : null;
		}
		return $this->data;
	}
}
