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
	
	protected function index($url) {
		
		$this->data['items'] = $this->get_items();		
		$this->get_navigation();
	}
}
