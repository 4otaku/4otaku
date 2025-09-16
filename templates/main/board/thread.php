<?
$hasFlash = false;
foreach ($data['main']['posts'] as $key => $post) {
	if (!empty($post['content']['flash'])) {
		$hasFlash = true;
	}
	if ($post['type'] == 'thread') { 
		$thread = $post; $id = $post['id'];
		include TEMPLATE_DIR.SL.'main'.SL.'board'.SL.'thread_message.php';
	} else { 
		include TEMPLATE_DIR.SL.'main'.SL.'board'.SL.'message.php'; 
	}
}
if ($hasFlash) {
	twig_load_template('board/ruffle-flash', []);
}