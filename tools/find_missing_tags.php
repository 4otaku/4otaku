<?php
die;
include '../inc.common.php';

$db_arts = Database::get_vector('art', array('id', 'tag'));

$all_tags = Database::get_vector('tag', array('alias', 'id'));

foreach ($db_arts as $id => $tags) {	
	$tags = array_unique(array_filter(explode('|', $tags)));
	
	foreach ($tags as $tag) {
		if (empty($all_tags[$tag])) {
			@$all_tags[$tag][] = $id;
		}
	}
}

$arts = array();

foreach ($all_tags as $tag => $data) {
	if (!is_array($data)) {
		unset($all_tags[$tag]);
	} else {
		foreach ($data as $art) {
			@$arts[$art][] = $tag;
		}
	}
}

foreach ($arts as $art => $tags) {
	?>
		<a href="/art/<?=$art;?>">Арт №<?=$art;?></a>, теги
		 <?=implode(', ', $tags);?> <br />
	<?php
	
	if (query::$get['fix'] == 1) {
		query::$post['id'] = $art;
		query::$post['type'] = 'art';
		query::$post['tags'] = urldecode(str_replace('|', ' ', $db_arts[$art]));
		$check = new Check();
		$worker = new input__common();
		$worker->edit_tag();
	}
}
