<?
die;
include '../inc.common.php';

$arts = obj::db()->sql('select * from art where variation != "|"');

foreach ($arts as $art) {
	$variations = array_filter(explode('|', $art['variation']));
	$i = 0;

	foreach ($variations as $variation) {
		$data = explode('.', $variation);
		$resized = (int) file_exists(ROOT_DIR.SL.'images'.SL.'booru'.SL.'resized'.SL.$data[1].'.jpg');
		
		$insert = array($art['id'], $data[1], $data[0], $data[2], $resized, $i++);
		
		obj::db()->insert('art_variation', $insert);		
	}
}
