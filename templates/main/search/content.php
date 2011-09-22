<?
	$lang['transfer'] = array(
		$def['area'][0] => 'на главную',
		$def['area'][1] => 'в мастерскую',
		$def['area'][2] => 'в барахолку'
	);

$tmp = $data['main']['navi']['base'];
if (!empty($data['main']['data']) && is_array($data['main']['data'])) {
	foreach ($data['main']['data'] as $item) {
		$data['main']['navi']['base'] = $item['navi'];
		include 'templates'.SL.'main'.SL.'single'.SL.$item['template'].'.php';
	}
}
$data['main']['navi']['base'] = $tmp;
