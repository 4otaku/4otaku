<?
die;
$where = "";
$params = array();

if (empty($where)) die;

include '../inc.common.php';
include '../engine/upload/functions.php';

$arts = Database::get_vector('art', array('id','md5','extension','resized','animated'), $where, $params);

foreach ($arts as $id => $art) {
	$file = ROOT_DIR.SL.'images'.SL.'booru'.SL.'full'.SL.$art['md5'].'.'.$art['extension'];

	$is_animated = is_animated($file);

	if ($is_animated && empty($art['animated'])) {
		Database::update('art', array('animated' => 1), $id);
	} elseif (!$is_animated && !empty($art['animated'])) {
		Database::update('art', array('animated' => 0), $id);
	}

	if (!empty($art['resized'])) {
		$sizefile = filesize($file);
		$dimensions = getimagesize($file);

		if ($sizefile > 1024*1024) {
			$sizefile = round($sizefile/(1024*1024),1).' мб';
		} elseif ($sizefile > 1024) {
			$sizefile = round($sizefile/1024,1).' кб';
		} else {
			$sizefile = $sizefile.' б';
		}
		$resized = "$dimensions[0]x$dimensions[1]px; $sizefile";

		if ($resized != $art['resized']) {
			Database::update('art', array('resized' => $resized), $id);
		}
	}
}
