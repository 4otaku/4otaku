<?php

class Update_Video extends Update_Abstract
{
	protected $field_rights = array(
		'author' => 1,
		'link' => 1,
		'area' => 1
	);

	protected $model;

	public function __construct($reader, $writer) {
		parent::__construct($reader, $writer);

		$data = $this->reader->get_data();

		if (empty($data['id']) || !Check::id($data['id'])) {
			throw new Error_Update('Incorrect Id');
		}

		$model = new Model_Video($data['id']);
		$model->load();

		if ($model->is_phantom()) {
			throw new Error_Update('Incorrect Id');
		}

		if ($model['area'] != 'workshop' && !sets::user('rights')) {
			throw new Error_Update('Not enough rights');
		}

		$this->model = $model;
	}

	protected function save_changes() {
		$this->model->commit();
	}

	protected function title($data) {
		$this->model['title'] = $data['title'];
	}

	protected function text($data) {

		$text = Transform_Text::format($data['text']);

		$this->model['text'] = $text;
		$this->model['pretty_text'] = $data['text'];
	}

	protected function category($data) {

		$worker = new Transform_Meta();

		$category = $worker->category($data['category']);
		$this->model['category'] = $category;
	}

	protected function tag($data) {

		$worker = new Transform_Meta();

		if ($this->model['area'] == 'flea_market' ||
			$this->model['area'] == 'main') {

			$area = 'post_'.$this->model['area'];
			$worker->erase_tags(array_keys($this->model['meta']['tag']), $area);
		} else {
			$area = false;
		}

		$tag = $worker->parse_array($data['tag']);
		$tag = $worker->add_tags($tag, $area);
		$this->model['tag'] = $tag;
	}

	protected function author($data) {

		$worker = new Transform_Meta();

		$author = $worker->parse($data['author'], def::user('author'));
		$author = $worker->author($author);

		$this->model['author'] = $author;
	}

	protected function link($data) {

		$this->model['link'] = $data['link'];
	}

	protected function area($data) {

		$this->writer->set_redirect();

		if (empty($data['sure'])) {
			$this->writer->set_message('Галочку ставить не забываем, она тут для защиты от случайных кликов.');
			return;
		}

		// TODO: перемести input__common::transfer в Model_Common
		input__common::transfer(array(
			'sure' => 1,
			'do' => array('video', 'transfer'),
			'where' => $data['area'],
			'id' => $this->model->get_id()
		));
	}
}
