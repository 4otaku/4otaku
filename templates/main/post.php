<?
	$lang['transfer'] = array(
		$def['area'][0] => 'на главную',
		$def['area'][1] => 'в мастерскую',
		$def['area'][2] => 'в барахолку'
	);
	
foreach ($data['main']['post'] as $item) {
	include SITE_FDIR._SL.'templates'.SL.'main'.SL.'single'.SL.'post.php';
}
