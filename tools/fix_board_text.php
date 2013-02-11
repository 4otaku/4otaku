<?php
die;
include '../inc.common.php';

$posts = Database::get_full_vector('board');

foreach ($posts as $id => $post) {
	$text = obj::transform('text')->wakaba(redo_safety($post['pretty_text']));
	Database::update('board', array('text' => $text), $id);
}
