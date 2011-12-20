<?php

class Model_Post_File extends Model_Abstract
{
	// Поля таблицы
	protected $fields = array(
		'id',
		'post_id',
		'name',
		'hash',
		'file',
		'size',
		'sizetype',
		'order'
	);

	// Название таблицы
	protected $table = 'post_torrent';

	protected $sizetypes = array('кб', 'мб', 'гб');

	public function __construct($data = array()) {

		parent::__construct($data);
		
		$this->set('display_size', round($this->get('size'), 2));
		$this->set('display_sizetype', $this->sizetypes[$this->get('sizetype')]);
	}	
	
	public function insert() {

		parent::insert();

		return $this;
	}
}
