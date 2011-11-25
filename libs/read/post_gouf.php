<?php

class Read_Post_Update extends Read_Abstract
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
}

/*
SELECT * 
FROM  `post_url` 
INNER JOIN  `post_link_url` ON post_link_url.url_id = post_url.id
INNER JOIN  `post_link` ON post_link.id = post_link_url.link_id
INNER JOIN  `post` ON post.id = post_link.post_id
GROUP BY post.id
*/
