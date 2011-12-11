<?php

class Read_Post_Gouf extends Read_Abstract
{
	protected $template = 'main/gouf';	
	protected $error_template = 'error/post';
	
	protected $side_modules = array(
		'head' => array('title', 'js', 'css'),
		'header' => array('menu', 'personal'),
		'top' => array(),
		'sidebar' => array('comments','update','orders','tags'),
		'footer' => array('year')
	);
	
	public function __construct() {
		parent::__construct();		
		
		$this->per_page = sets::pp('post');
	}
	
	protected function get_items() {
		

	}
	
	protected function display_index($url) {
		
		$this->get_items();
	}
	
	protected function display_page($url) {
		
		$this->set_page($url, 2);
		$this->get_items();
	}
	
	protected function display_sort($url) {
		
		$this->set_sort($url, 2);
		$this->set_page($url, 4);
		$this->get_items();
	}
	
	protected function display_update($url) {
		
		array_shift($url);
		$worker = new Read_Post_Gouf_Update();

		$worker->process($url);		
	}
}
