<?php

class Update_Post extends Update_Abstract
{
	protected $field_rights = array(
		'author' => 1,
		'area' => 1
	);

	protected $model;

	public function __construct($reader, $writer) {
		parent::__construct($reader, $writer);

		$data = $this->reader->get_data();

		if (empty($data['id']) || !Check::id($data['id'])) {
			throw new Error_Update('Incorrect Id');
		}

		$model = new Model_Post($data['id']);
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

	protected function language($data) {

		$worker = new Transform_Meta();

		$language = $worker->language($data['language']);
		$this->model['language'] = $language;
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

	protected function image($data) {

		Database::delete('post_image', 'post_id = ?', $this->model->get_id());

		foreach($data['image'] as $image) {
			$image = explode('.', $image);
			$image = new Model_Post_Image(array(
				'file' => $image[0],
				'extension' => $image[1]
			));
			$this->model->add_image($image);
		}
	}

	protected function link($data) {

		$link_ids = Database::get_table('post_link', 'id', 'post_id = ?',
			$this->model->get_id());
		Database::delete('post_link_url',
			Database::array_in('link_id', $link_ids), $link_ids);

		Database::delete('post_link', 'post_id = ?', $this->model->get_id());

		$links = Check::link_array($data['link']);
		$links = Transform_Link::parse($links);

		foreach($links as $link) {
			$link = new Model_Post_Link($link);
			$this->model->add_link($link);
		}
	}

	protected function torrent($data) {

		Database::delete('post_torrent', 'post_id = ?', $this->model->get_id());

		foreach($data['torrent'] as $torrent) {
			$torrent = new Model_Post_Torrent($torrent);
			$this->model->add_torrent($torrent);
		}
	}

	protected function file($data) {

		Database::delete('post_file', 'post_id = ?', $this->model->get_id());

		foreach($data['file'] as $file) {
			$file = new Model_Post_File($file);
			$this->model->add_file($file);
		}
	}

	protected function extra($data) {

		Database::delete('post_extra', 'post_id = ?', $this->model->get_id());

		$extras = Check::link_array($data['extra']);
		$extras = Transform_Link::parse($extras);

		foreach($extras as $extra) {
			$extra = new Model_Post_Extra($extra);
			$this->model->add_extra($extra);
		}
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
			'do' => array('post', 'transfer'),
			'where' => $data['area'],
			'id' => $this->model->get_id()
		));
	}
}
