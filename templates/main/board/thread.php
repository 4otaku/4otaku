<? 
foreach ($data['main']['posts'] as $key => query::$post) { 
	if (empty($key)) { 
		$thread = query::$post; $id = query::$post['id'];
		include TEMPLATE_DIR . '/main/board/thread_message.php'; 
	} else { 
		include TEMPLATE_DIR . '/main/board/message.php'; 
	}
}
