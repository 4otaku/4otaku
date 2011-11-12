<?

include '../inc.common.php';

ob_end_clean();

$posts = Database::get_full_vector('post');

foreach ($posts as $id => $post) {
	$links = (array) unserialize($post['link']);
	$extras = (array) unserialize($post['info']);
	$files = (array) unserialize($post['file']);
	
	$i = 0;
	foreach ($links as $link) {
		if (empty($link['url'])) {
			continue;
		}
		
		switch ($link['sizetype']) {
			case 'гб': $type = 2; break;
			case 'мб': $type = 1; break;
			case 'кб': $type = 0; break;
			default: $type = 0;	break;
		}
		
		Database::insert('post_link', array(
			'post_id' => $id,
			'name' => $link['name'],
			'size' => (float) $link['size'],
			'sizetype' => $type,
			'order' => $i,
		));
		
		$link_id = Database::last_id();
		
		$i++;
		
		$j = 0;
		foreach ($link['url'] as $key => $url) {

			$alias = $link['alias'][$key];
			
			Database::insert('post_link_url', array(
				'link_id' => $link_id,
				'alias' => $alias,
				'url' => $url,
				'order' => $j
			));
			
			$j++;	
		}
	}
	
	$i = 0;
	foreach ($extras as $extra) {
		if (empty($extra['url'])) {
			continue;
		}
		
		Database::insert('post_extra', array(
			'post_id' => $id,
			'name' => $extra['name'],
			'alias' => $extra['alias'],
			'url' => $extra['url'],
			'order' => $i,
		));
		
		$i++;
	}
	
	$i = 0;
	foreach ($files as $file) {
		$size = explode(' ', $file['size']);
		
		switch ($size[1]) {
			case 'гб': $sizetype = 2; break;
			case 'мб': $sizetype = 1; break;
			case 'кб': $sizetype = 0; break;
			default: $sizetype = 0;	break;
		}				
		
		switch ($file['type']) {
			case "audio": $type = 2; break;
			case "image": $type = 1; break;
			case "plain": $type = 0; break;
			default: $type = 0;	break;
		}
		
		Database::insert('post_file', array(
			'post_id' => $id,
			'type' => $type,	
			'name' => $file['name'],
			'folder' => $file['folder'],
			'file' => $file['filename'],
			'size' => (float) $size[0],
			'sizetype' => $sizetype,	
			'order' => $i,
		));
		
		$i++;
	}	
}
