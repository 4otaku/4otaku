<?php

class Model_Video extends Model_Abstract_Main
{
	// Поля таблицы
	protected $fields = array(
		'id',
		'title',
		'link',
		'object',
		'text',
		'pretty_text',
		'author',
		'category',
		'tag',
		'comment_count',
		'last_comment',
		'pretty_date',
		'sortdate',
		'area',
	);

	// Название таблицы
	protected $table = 'video';

	protected $meta_fields= array('tag', 'category', 'author');

	public function insert() {

		$this->set('pretty_date', Transform_Text::rudate());
		$this->set('sortdate', ceil(microtime(true)*1000));
		$this->set('area', def::area(1));

		parent::insert();

		return $this;
	}

	public function load() {
		parent::load();

		$object = $this->get('object');
		if (!empty($object)) {
			$object = str_replace(array('%video_width%','%video_height%'),
				explode('x', sets::video('full')), $object);
			$this->set('display_object', $object);
		}

		return $this;
	}
}
