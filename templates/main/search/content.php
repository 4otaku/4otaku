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
		if ($item['template'] == 'post' || $item['template'] == 'video' || $item['template'] == 'news') {
			$item['in_batch'] = true;
			twig_load_template('main/item/' . $item['template'], array(
				$item['template'] => $item,
				'id' => $item['id'],
				'navi_base' => $item['navi'],
			));
		} else {
			include 'templates'.SL.'main'.SL.'single'.SL.$item['template'].'.php';
		}
	}
}
$data['main']['navi']['base'] = $tmp;
