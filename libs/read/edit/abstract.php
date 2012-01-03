<?php

abstract class Read_Edit_Abstract extends Read_Abstract
{
	protected $error_template = 'error/edit';

	protected function get_function($url) {
		if (empty($url[2]) || !Check::id($url[2])) {
			throw new Error_Read_Edit();
		}

		$this->data['id'] = $url[2];
		$this->data['type'] = $this->get_type();

		$this->template = 'edit/' . $this->data['type'] . '/' . $url[1];

		return $url[1];
	}

	protected function get_type() {

		$class = get_called_class();
		$class = strtolower($class);

		return substr($class, 10);
	}

	protected function do_output($template, $data = array()) {

		twig_load_template($template, $data);
		exit();
	}
}
