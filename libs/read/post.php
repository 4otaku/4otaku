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
	
	protected function get_item($id) {
		
	}
	
	protected function get_items() {
		$items = $this->load_batch('post');
		
		$this->data['items'] = $items;
	}
	
	protected function get_navigation() {
		
	}
	
	protected function index($url) {
		
		$this->get_items();
	}
}
