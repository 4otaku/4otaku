<?
die;
include '../inc.common.php';

$pools = Database::get_vector('art_pool', 'id, art');

foreach ($pools as $id => $arts) {

	$arts = explode('|', $arts);
	$arts = array_filter($arts);
	$arts = array_reverse($arts);

	$insert = array();
	$order = 0;
	foreach ($arts as $art) {
		$insert[] = array(
			'art_id' => $art,
			'pool_id' => $id,
			'order' => $order++,
		);
	}

	Database::bulk_insert('art_in_pool', $insert, true);
}

