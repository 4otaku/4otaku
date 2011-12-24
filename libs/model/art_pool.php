<?php

class Model_Art_Pool extends Model_Abstract
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
	protected $table = 'art_pool';

	// Последний порядковый номер пикчи
	protected $last_order = null;
	// Отметка о том, брали ли мы его уже из базы
	protected $last_order_known = false;	
	
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
	
	public function add_art($art) {
		if (!$this->last_order_known) {
			$this->last_order = (int) Database::order('order')->
				get_field('art_in_pool', 'order', 'pool_id = ?', $this->get_id());
				
			$this->last_order_known = true;
		}

		Database::insert('art_in_pool', array(
			'art_id' => $art->get_id(),
			'pool_id' => $this->get_id(),
			'order' => ++$this->last_order
		));
	}
}
