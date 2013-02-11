<?

include '../inc.common.php';

$translations = Database::get_table('art_translation', array('data'));

foreach ($translations as $item) {
	$data = (array) unserialize(base64_decode($item['data']));
	foreach ($data as $one) {
		if (strpos($one['text'], '&') !== false) {
			echo $one['text'] . '<br /><br />';
		}
	}
}
