<?
	$lang['link'] = array(
		$def['area'][0] => 'Арт, главный раздел',
		$def['area'][1] => 'Арт, очередь премодерации',
		$def['area'][2] => 'Арт, барахолка',
		$def['area'][3] => 'Арт, спрайты',
		'pool' => 'Арт, группы',
		'cg_packs' => 'Арт, CG паки',
	);

	foreach ($lang['link'] as $key => $one) {
		?>
			<a href="<?=$def['site']['dir']?>/art/<?=($key == $def['area'][0] ? "" : $key."/");?>"<?=($key == $url[2] || ($key == $url['area'] && $url[2] != 'pool' && $url['area'] != 'cg' && $url[2] != 'cg_packs') || ($url['area'] == 'cg' && $key == 'cg_packs') ? ' class="plaintext"' : '');?>>
				<?=$one;?>
			</a>
			<br />				
		<?
	}	
		
	if (is_array($data['main']['display']) && in_array('booru_page',$data['main']['display']) && $url[2] != 'page' && $url[3] && $url['area'] == 'main' && !$error) {
		?>
			<br />
			<a href="<?=$def['site']['dir']?>/art/slideshow/<?=$url[2];?>/<?=$url[3];?>#<?=(1 + ($data['main']['navi']['curr'] - 1)*$sets['pp']['art']);?>" target="_blank">
				Запустить слайдшоу
			</a>
		<?
	}
	if ($data['main']['rss']) {
		?>
			<br /><br />
			<a href="<?=$def['site']['dir']?>/rss/<?=_base64_encode(implode('|',array_slice($data['main']['rss'],2)));?>" target="_blank">
				Подписаться на rss <?=$data['main']['rss']['type-name'];?> <?=$data['main']['rss']['meta-name'];?>
			</a>
		<?
	}

