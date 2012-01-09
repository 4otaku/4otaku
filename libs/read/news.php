<?php

class Read_News extends Read_Main
{
	protected $template = 'main/news';
	protected $error_template = 'error/news';

	protected $show_template = 'dynamic/news/show';

	protected $side_modules = array(
		'head' => array('js', 'css'),
		'header' => array('menu', 'personal'),
		'top' => array(),
		'sidebar' => array('search', 'comments','quicklinks','orders'),
		'footer' => array('year')
	);

	public function __construct() {
		parent::__construct();

		if (sets::user('rights')) {
			$this->side_modules['top'][] = 'add_bar';
		}

		$this->per_page = sets::pp('news');
	}

	// @TODO: public - хак для поиска и RSS, заменить на protected при возможности
	public function get_item($id) {
		$item = new Model_News($id);

		if (sets::user('rights')) {
			$item['is_editable'] = true;
		}
		$this->load_meta($item);

		$this->data['items'] = array($id => $item);
		$this->data['base'] = '/news/';
	}

	protected function get_items() {

		$items = $this->load_batch('news');

		foreach ($items as $id => &$item) {
			$item['id'] = $id;
			$item = new Model_News($item);

			if ($this->area == 'workshop' || sets::user('rights')) {
				$item['is_editable'] = true;
			}
		}

		$this->load_meta($items);

		$this->data['items'] = $items;
		if ($this->count > $this->per_page) {
			$this->data['navi'] = $this->get_bottom_navi('news');
		}
	}

	protected function get_navigation() {
		return array();
	}

	protected function get_title() {

		$short = def::site('short_name') . ' ';

		if (count($this->data['items']) == 1) {
			$item = reset($this->data['items']);
			if (empty($item['in_batch'])) {
				return $short . $item['title'];
			}
		}

		return def::site('name');
	}
}
