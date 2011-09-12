<?
	$lang['transfer'] = array(
		$def['area'][0] => 'на главную',
		$def['area'][1] => 'на премодерацию',
		$def['area'][2] => 'в барахолку',
		'sprites' => 'в спрайты',
		'deleted' => 'в печь'
	);
	
	$lang['no_transfer'] = array(
		'same_target' => 'Вы пытаетесь отправить арт в тот же самый раздел, где он уже находится.',
		'not_enough_tags' => 'Слишком мало тегов чтобы отправить арт №'.$data['id'].' на главную.',
	);

if (!empty($add_res['meta'])) { ?>
	Уехало <?=$lang['transfer'][$add_res['meta']];?>
<? } else { ?>
	<? if (!empty($add_res['meta_error'])) { ?>
		<script type="text/javascript">
			var new_warning = $("<span/>").addClass("span-red").
				html('<?=$lang['no_transfer'][$add_res['meta_error']];?>');
			
			$(".addres").html('').append(new_warning).fadeIn(1000).delay(5000).fadeOut(1000);
		</script>
	<? } ?>
	<a href="<?=$def['site']['dir']?>/art/<?=$data['id'];?>" rel="<?=$data['id'];?>" class="with_help3" title="
			<?
				if (!empty($data['meta']['tag'])) {
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
			?> | <?	} ?>			
			<?	
				if (!empty($data['meta']['author'])) {
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
			?> | <?	} ?>
			<?
				if (!empty($data['meta']['category'])) {
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
				}
			?>
	">
		<img src="<?=$def['site']['dir']?>/images/booru/thumbs/<?=($sets['art']['largethumbs'] ? 'large_' : '');?><?=$data['thumb'];?>.jpg">
	</a>
<? } ?>
