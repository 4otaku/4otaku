<?
	$lang['transfer'] = array(
		$def['area'][0] => 'на главную',
		$def['area'][1] => 'на премодерацию',
		$def['area'][2] => 'в барахолку'
	);
	
foreach ($data['main']['video'] as $item) {
	include 'templates'.SL.'main'.SL.'single'.SL.'video.php';
}
