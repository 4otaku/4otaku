<?php

class Model_Art_Pool extends class Model_Abstract
{
	// Поля таблицы
	protected $fields = array(
		'id',
		'name',
		'text',
		'pretty_text',
		'password',
		'email',
		'sortdate'
	);

	// Название таблицы
	private $table = 'art_pool';
	
	public function set_password($data) {
		$data = $this->encode_password($data);
		
		$this->set('password', $data);
	}
	
	protected function encode_password($string) {
		return md5($string);
	}
	
	public function correct_password($string) {
		$correct_hash = $this->get('password');
		
		if (!Check::is_hash($string)) {
			$string = $this->encode_password($string);
		}
		
		return empty($correct_hash) || $string == $correct_hash;
	}	
}
