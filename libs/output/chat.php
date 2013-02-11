<?php

class output__chat extends engine
{
	public $allowed_url = array(
		array(1 => '|chat|', 2 => 'end')
	);
	public $template = 'general';
	public $side_modules = array(
		'head' => array('title'),
		'header' => array('menu', 'personal'),
		'sidebar' => array('comments','quicklinks','orders'),
		'footer' => array()
	);

	function get_data() {
		return array('display' => array('chat'));
	}
}
