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

		$this->data['items'] = array($id => $item);
	}

	protected function get_items() {

		$start = ($this->page - 1) * $this->per_page;

		$condition = 'area != ?';
		$params = 'deleted';

		$items = Database::set_counter()->order('sortdate')
			->limit($this->per_page, $start)
			->get_full_vector('news', $condition, $params);

		$this->count = Database::get_counter();

		foreach ($items as $id => &$item) {
			$item['in_batch'] = true;
			$item['id'] = $id;
			$item = new Model_News($item);

			if (sets::user('rights')) {
				$item['is_editable'] = true;
			}
		}

		$this->data['items'] = $items;
		if ($this->count > $this->per_page) {
			$this->data['navi'] = $this->get_bottom_navi('post');
		}
	}

	protected function get_navigation() {
		return array(
			'tag' => $this->get_navi_tag('post'),
			'category' => $this->get_navi_category('post'),
			'language' => $this->get_navi_language(),
			'rss' => $this->get_navi_rss('post')
		);
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

	protected function display_index($url) {

		$this->get_items();
	}

	protected function display_single_item($url) {

		$this->set_page($url, 4);

		$this->get_item($url[1]);

		$item = reset($this->data['items']);
		if ($item['area'] == 'deleted') {
			$this->do_output($this->error_template);
			return;
		}

		$this->data['comment'] = $this->get_comments($url[1]);
		if ($this->count > $this->per_page) {
			$this->data['navi'] = $this->get_comment_navi($url[1]);
		}

		$this->data['single'] = true;
	}

	protected function display_show($url) {

		$this->get_item($url[2]);

		if ($url[3] == 'batch') {
			foreach ($this->data['items'] as $item) {
				$item['in_batch'] = true;
			}
		}

		$this->template = $this->show_template;
	}

	protected function display_page($url) {

		$this->set_page($url, 2);
		$this->get_items();
	}
}
