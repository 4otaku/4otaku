<?php

abstract class Action_Writer_Abstract
{
	protected $success = false;
	protected $error = 0;
	protected $message = '';
	protected $data = array();
	protected $actions = array();

	public function set_success($success = true) {
		$this->success = $success;

		return $this;
	}

	public function set_message($message) {
		$this->message = $message;

		return $this;
	}

	public function set_data($data) {
		foreach ($data as $key => $param) {
			$this->set_param($key, $param);
		}

		return $this;
	}

	public function set_error($error) {
		$this->error = $error;

		return $this;
	}

	public function set_param($key, $param) {
		$this->data[$key] = $param;

		return $this;
	}

	public function set_action($type, $action) {
		if (empty($this->action[$type])) {
			$this->action[$type] = array();
		}
		$this->action[$type][] = $action;

		return $this;
	}

	public function set_redirect($where = false) {
		$this->set_action('redirect', $where);
	}

	abstract public function process_actions();
	abstract public function return_data();
}
