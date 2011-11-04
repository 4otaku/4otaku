<?php

abstract class Abstract_Action
{
	protected $need_redirect = false;
	protected $redirect_address = '';
	
	protected $minimal_rights = 0;
	
	protected $field_rights = array();
	
	public function check_access($function, $data) {
		if (sets::user('rights') < $this->minimal_rights) {
			return false;
		}
		
		foreach ($data as $key => $item) {
			if (
				array_key_exists($key, $this->field_rights) &&
				sets::user('rights') < $this->field_rights[$key]
			) {
				return false;
			}
		}
		
		return true;
	}
	
	public function check_redirect() {
		if ($this->need_redirect) {
			
			$redirect = 'http://' . def::site('domain') . 
				(empty($this->redirect_address) ? 
					$_SERVER["REQUEST_URI"] : 
					$this->redirect_address);
				
			engine::redirect($redirect);
		}
	}
	
	protected function set_redirect($address = false) {
		$this->need_redirect = true;
		
		if (!empty($address)) {
			$this->redirect_address = $address;
		}
	}	
}
