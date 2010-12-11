<? 
foreach ($data['main']['posts'] as $key => $post) { 
	if (empty($key)) { 
		$thread = $post; $id = $post['id'];
		include TEMPLATE_DIR . '/main/board/thread_message.php'; 
	} else { 
		include TEMPLATE_DIR . '/main/board/message.php'; 
	}
}
