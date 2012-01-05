<?php

class Model_News extends Model_Abstract_Meta
{
	// Поля таблицы
	protected $fields = array(
		'id',
		'title',
		'text',
		'pretty_text',
		'image',
		'extension',
		'author',
		'category',
		'comment_count',
		'last_comment',
		'pretty_date',
		'area',
	);

	// Название таблицы
	protected $table = 'news';

	protected $meta_fields= array('category', 'author');

	public function insert() {

		$this->set('pretty_date', Transform_Text::rudate());
		$this->set('sortdate', ceil(microtime(true)*1000));
		$this->set('area', def::area(1));

		parent::insert();

		return $this;
	}
}
