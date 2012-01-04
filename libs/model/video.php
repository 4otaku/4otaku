<?php

class Model_Video extends Model_Abstract_Meta
{
	// Поля таблицы
	protected $fields = array(
		'id',
		'title',
		'link',
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

	public function set_display_object($sizes_type) {

		try {
			$link = $this->get('link');
			if (!empty($link)) {
				$video = new Transform_Video($link);
				$object = $video->enable_nico()->set_sizes($sizes_type)->get_html();
				$this->set('display_object', $object);
			}
		} catch (Error $e) {
			$this->set('display_error', true);
		}
	}
}
