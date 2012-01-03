<?php

class Read_Video extends Read_Main
{
	protected $template = 'main/video';
	protected $error_template = 'error/video';

	protected $show_template = 'dynamic/video/show';

	protected $side_modules = array(
		'head' => array('js', 'css'),
		'header' => array('menu', 'personal'),
		'top' => array('add_bar'),
		'sidebar' => array('search', 'comments','quicklinks','orders','tags'),
		'footer' => array('year')
	);

	public function __construct() {
		parent::__construct();

		$cookie = new dynamic__cookie();
		$cookie->inner_set('visit.video', time(), false);

		$this->per_page = sets::pp('video');
	}

	// @TODO: public - хак для поиска и RSS, заменить на protected при возможности
	public function get_item($id) {
		$item = new Model_Video($id);
		$item->set_display_object('full');

		if ($item['area'] == 'workshop' || sets::user('rights')) {
			$item['is_editable'] = true;
		}
		$this->load_meta($item);

		query::$url['area'] = $this->area = $item['area'];

		$this->data['items'] = array($id => $item);
		$this->data['base'] = '/video/';
	}

	protected function get_items() {

		$items = $this->load_batch('video');

		foreach ($items as $id => &$item) {
			$item['id'] = $id;
			$item = new Model_Video($item);
			$item->set_display_object('thumb');

			if ($this->area == 'workshop' || sets::user('rights')) {
				$item['is_editable'] = true;
			}
		}

		$this->load_meta($items);

		$this->data['items'] = $items;
		if ($this->count > $this->per_page) {
			$this->data['navi'] = $this->get_bottom_navi('video');
		}
	}

	protected function get_navigation() {
		return array(
			'tag' => $this->get_navi_tag('video'),
			'category' => $this->get_navi_category('video'),
			'rss' => $this->get_navi_rss('video')
		);
	}

	protected function display_show($url) {

		parent::display_show($url);

		if ($url[3] == 'batch') {
			foreach ($this->data['items'] as $item) {
				$item->set_display_object('thumb');
			}
		}
	}
}
