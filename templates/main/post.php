<?
	$lang['transfer'] = array(
		$def['area'][0] => 'на главную',
		$def['area'][1] => 'в мастерскую',
		$def['area'][2] => 'в барахолку'
	);
	
if (is_array($data['main']['post'])) {
	foreach ($data['main']['post'] as $item) {
		include 'templates'.SL.'main'.SL.'single'.SL.'post.php';
	}
}
