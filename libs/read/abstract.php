<?php

abstract class Read_Abstract
{
	protected $minimal_rights = 0;	
	protected $url_rights = array();
	
	protected $template;	
	protected $error_template;
	
	protected $side_modules = array();
	
	protected $data = array();
	
	public function process($url) {
		if (!$this->check_access($url)) {
			$this->do_output($this->error_template);
		}
		
		$function = $this->get_function($url);

		if (method_exists($this, $function)) {
			$this->$function($url);
		} else {
			$this->do_output($this->error_template);
		}
		
		$this->do_output($this->template, $this->data);
	}
	
	protected function check_access($url) {
		if (sets::user('rights') < $this->minimal_rights) {
			return false;
		}
		
		$test_url = $this->get_function($url);
		
		if (
			array_key_exists($test_url, $this->url_rights) &&
			sets::user('rights') < $this->function_rights[$test_url]
		) {
			return false;
		}
		
		return true;
	}
	
	protected function get_function($url) {
		if (empty($url[2])) {
			return 'index';
		}
		
		if (is_numeric($url[2])) {
			return 'single_item';
		}
		
		return $url[2];
	}
	
	protected function get_side_data ($input) {
		$return = array();

		foreach ($input as $part => $modules) {
			$class = 'Side_' . ucfirst($part);
			$worker = new $class();
			foreach ($modules as $module) {
				$return[$part][$module] = $worker->$module();
			}
		}
		
		return $return;
	}	
	
	protected function do_output($template, $data = array()) {
		
		$data['sub'] = $this->get_side_data($this->side_modules);		
		
		twig_load_template($template, $data);
	}
}
