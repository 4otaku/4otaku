<?php

class Read_Post extends Read_Main
{
	protected $template = 'main/post';	
	protected $error_template = 'error/post';
	
	protected $side_modules = array(
		'head' => array('title', 'js', 'css'),
		'header' => array('menu', 'personal'),
		'top' => array('add_bar'),
		'sidebar' => array('comments','update','orders','tags'),
		'footer' => array('year')
	);
	
	public function __construct() {
		parent::__construct();		
		
		$cookie = new dynamic__cookie();
		$cookie->inner_set('visit.post', time(), false);
		
		$this->per_page = sets::pp('post');
	}	
	
	protected function get_item($id) {
		
	}
	
	protected function get_items() {
		
		$items = $this->load_batch('post');
				
		$this->data['items'] = $items;	
		$this->data['navi'] = $this->get_bottom_navi();	
	}
	
	protected function get_navigation() {
		return array(
			'tag' => $this->get_navi_tag('post'),
			'category' => $this->get_navi_category('post'),
			'language' => $this->get_navi_language()
		);
	}
	
	protected function index($url) {
		
		$this->get_items();
	}
	
	protected function page($url) {
		$page = (int) $url[2];
		
		if (!empty($page)) {
			$this->page = $page;
		}
		
		$this->get_items();
	}
}
