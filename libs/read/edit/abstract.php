<?php

abstract class Read_Edit_Abstract extends Read_Abstract
{
	protected $error_template = 'error/edit';
	
	protected function get_function($url) {

		$this->template = 'edit/' . $this->get_type() . '/' . $url[1];
		
		return $url[1];
	}
	
	protected function get_type() {

		$class = explode('_', get_called_class());
		$type = array_pop($class);
		
		return strtolower($type);
	}
}
