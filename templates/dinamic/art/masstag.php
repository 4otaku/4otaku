<?
	$lang['transfer'] = array(
		$def['area'][0] => 'на главную',
		$def['area'][1] => 'на премодерацию',
		$def['area'][2] => 'в барахолку',
		'deleted' => 'в печь'
	);
if ($add_res['meta']) { ?>
	Уехало <?=$lang['transfer'][$add_res['meta']];?>
<? } else { ?>
	<a href="<?=SITE_DIR.'/art'?>/<?=$data['id'];?>" rel="<?=$data['id'];?>" class="with_help3" title="
			<?
				if (count($data['meta']['tag']) > 1) {
					?>
						Теги: 
					<?
				}
				else {
					?>
						Тег: 
					<?
				}
				foreach ($data['meta']['tag'] as &$tag) $tag = $tag['name'];
				echo implode(', ',$data['meta']['tag']);
			?>
			  | 
			<?	
				if (count($data['meta']['author']) > 1) {
					?>
						Опубликовали: 
					<?
				}
				else {
					?>
						Опубликовал: 
					<?
				}
				echo implode(', ',$data['meta']['author']);
			?>
			  | 
			<?
				if (count($data['meta']['category']) > 1) {
					?>
						Категории: 
					<?
				}
				else {
					?>
						Категория: 
					<?
				}
				echo implode(', ',$data['meta']['category']);
			?>
	">
		<img src="<?=SITE_DIR.'/images'?>/booru/thumbs/<?=($sets['art']['largethumbs'] ? 'large_' : '');?><?=$data['thumb'];?>.jpg">
	</a>
<? } ?>
