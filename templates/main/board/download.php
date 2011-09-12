<? 
foreach ($data['main']['posts'] as $key => $post) { 
	if ($post['type'] == 'thread') { 
		$thread = $post; $id = $post['id'];
		include TEMPLATE_DIR.SL.'main'.SL.'board'.SL.'download_thread_message.php'; 
	} else { 
		include TEMPLATE_DIR.SL.'main'.SL.'board'.SL.'download_message.php'; 
	}
}
