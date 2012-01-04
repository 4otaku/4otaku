<?php

class Model_News extends Model_Abstract_Logged
{
	// Поля таблицы
	protected $fields = array(
		'id',
		'url',
		'title',
		'text',
		'pretty_text',
		'image',
		'comment_count',
		'last_comment',
		'pretty_date',
		'area',
	);

	// Название таблицы
	protected $table = 'news';

	public function insert() {

		$this->set('pretty_date', Transform_Text::rudate());
		$this->set('sortdate', ceil(microtime(true)*1000));
		$this->set('area', def::area(1));

		parent::insert();

		return $this;
	}
}
