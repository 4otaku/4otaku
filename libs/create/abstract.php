<?php

abstract class Create_Abstract extends Abstract_Action
{
	// @TODO: переписать
	protected function add_res($text, $error = false) {
		global $add_res;

		$add_res = array('text' => $text, 'error' => $error);

		$cookie = obj::get('dynamic__cookie');

		$cookie->inner_set('add_res.text', $text);
		$cookie->inner_set('add_res.error', $error);
	}	
}
