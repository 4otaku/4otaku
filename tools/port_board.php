<?
die;
include '../inc.common.php';

$data = obj::db()->sql('select * from board');
$category_ids = obj::db()->sql('select id,alias from category','alias');

foreach ($data as $post) {
	$content = unserialize(base64_decode($post['content']));
	
	if (!empty($content)) {
		$type = key($content);
		$content = ($type == 'video') ? current($content) : current(current($content));
	
		$data = obj::db()->insert('board_attachment',array(
			$post['id'],
			$type,
			base64_encode(serialize($content)),
			0
		));
	}
	
	if ($post['thread'] == 0) {
		$categories = array_unique(array_filter(explode('|', $post['boards'])));
		
		foreach ($categories as $category) {
			$data = obj::db()->insert('board_category',array(
				$post['id'],
				$category_ids[$category],
				(int) ($post['type'] != 'old')
			), false);		
		}
	}
}

obj::db()->sql('ALTER TABLE `board` DROP `content`',0);
obj::db()->sql('ALTER TABLE `board` DROP `links`',0);
obj::db()->sql('ALTER TABLE `board` DROP `boards`',0);
