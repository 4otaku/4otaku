<?php

class Action_Writer_User extends Action_Writer_Abstract
{
	public function process_actions() {
		$redirect = null;

		foreach ($this->action as $type => $actions) {
			if ($type == 'redirect') {
				$redirect = array_pop($actions);
			}
		}

		if ($redirect !== null) {
			$redirect = 'http://' . def::site('domain') .
				(empty($redirect) ? $_SERVER["REQUEST_URI"] : $redirect);

			engine::redirect($redirect);
		}
	}

	// @TODO: переписать
	public function return_data() {
		global $add_res;

		if (!empty($this->message)) {

			$add_res = array('text' => $this->message, 'error' => !$this->success);

			$cookie = obj::get('dynamic__cookie');

			$cookie->inner_set('add_res.text', $this->message);
			$cookie->inner_set('add_res.error', !$this->success);
		}
	}
}
