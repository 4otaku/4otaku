<?php

class Update_Post_Update extends Update_Abstract
{
	protected $minimal_rights = 1;

	protected $model;

	public function __construct($reader, $writer) {
		parent::__construct($reader, $writer);

		$data = $this->reader->get_data();

		if (empty($data['id']) || !Check::id($data['id'])) {
			throw new Error_Update('Incorrect Id');
		}

		$model = new Model_Post_Update($data['id']);
		$model->load();

		if ($model->is_phantom()) {
			throw new Error_Update('Incorrect Id');
		}

		$this->model = $model;
	}

	protected function save_changes() {
		$this->model->commit();
	}

	protected function text($data) {

		$text = Transform_Text::format($data['text']);

		$this->model['text'] = $text;
		$this->model['pretty_text'] = $data['text'];
	}

	protected function author($data) {

		$this->model['username'] = $data['author'];
	}

	protected function link($data) {

		$link_ids = Database::get_table('post_update_link', 'id', 'update_id = ?',
			$this->model->get_id());
		Database::delete('post_update_link_url',
			Database::array_in('link_id', $link_ids), $link_ids);

		Database::delete('post_update_link', 'update_id = ?', $this->model->get_id());

		$links = Check::link_array($data['link']);
		$links = Transform_Link::parse($links);

		foreach($links as $link) {
			$link = new Model_Post_Update_Link($link);
			$this->model->add_link($link);
		}
	}
}
