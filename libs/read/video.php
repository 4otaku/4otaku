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
		$this->load_meta($item);

		if ($item['area'] == 'workshop' || sets::user('rights')) {
			$item['is_editable'] = true;
		}
		$this->load_meta($item);

		query::$url['area'] = $this->area = $item['area'];

		$this->data['items'] = array($id => $item);
		$this->data['base'] = '/video/';
	}

	protected function get_items() {

	}

	protected function get_navigation() {

	}
}
