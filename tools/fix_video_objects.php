<?
die;
include '../inc.common.php';

$videos = Database::get_full_vector('video');

$worker = new Transform_Video();

foreach ($videos as $id => $video) {
	$object = $worker->html($video['link'], false);

	if (!empty($object)) {
		Database::update('video', array('object' => $object), $id);
	}
}
