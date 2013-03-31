<? if ($url['area'] == def::get('area',3) && $url[1] == def::get('type',2)) { ?>
	<div class="mini-shell margin10">
		Графические ресурсы для создания любительских ВН. 
		<a href="http://wiki.4otaku.org/%D0%A1%D0%BE%D0%B7%D0%B4%D0%B0%D0%BD%D0%B8%D0%B5_%D0%BB%D1%8E%D0%B1%D0%B8%D1%82%D0%B5%D0%BB%D1%8C%D1%81%D0%BA%D0%BE%D0%B9_%D0%92%D0%9D">
			Статья про создание
		</a> 
		(в процессе написания).
	</div>
<? } ?>
<?
	$lang['add'] = array(
		$def['type'][0] => 'Добавить материал',
		$def['type'][1] => 'Добавить видео',
		$def['type'][2] => 'Загрузить картинки',
		'order' => 'Оставить заказ (не забудьте прочитать правила)',
		'pool' => 'Добавить новую группу',
		'board' => 'Открыть новый тред',
		'thread' => 'Ответить в тред',
		'soku' => 'Загрузить реплей',
	);
	
	if (is_array($data['top']['board_list'])) {
		$board_list = $data['top']['board_list'];
		include_once(TEMPLATE_DIR.SL.'main'.SL.'board'.SL.'menu.php');
	}
	if ($data['top']['add_bar']) {
		if (!$data['top']['add_bar']['name']) $data['top']['add_bar']['name'] = $data['top']['add_bar']['type'];
		?>
			<div class="addborder">
				<div id="downscroller" rel="<?=$data['top']['add_bar']['type'];?>#<?=$data['top']['add_bar']['info'];?>">
					<div>
						<a href="#scroll" class="disabled">
							<?=$lang['add'][$data['top']['add_bar']['name']];?>
							<?=($data['top']['add_bar']['pool'] ? " в группу ".$data['top']['add_bar']['pool'] : "");?>
						</a>
						<span class="arrow"> ↓</span> 
						<? if (isset($data['top']['add_bar']['pass'])) { ?>
									<span class="right">
										Пароль: <input name="password" class="password" type="text" rel="<?=$url[3];?>">
									</span>
						<? } /* elseif (
							$data['top']['add_bar']['name'] == 'board' || 
							$data['top']['add_bar']['name'] == 'thread'
						) { ?>
							<a href="#scroll-draw" class="disabled right">
								Рисовать
							</a> 
							<span class="arrow right">↓ </span> 
						<? } */ ?>
					</div>
					<div id="add_loader">
						<img src="<?=$def['site']['dir']?>/images/ajax-loader.gif">
					</div>
					<div id="add_form">
						&nbsp;
					</div>
				</div>
			</div>
			<br />
		<?
	}
?>
<div class="mini-shell addres<?=(isset($add_res) ? '' : ' hidden');?>">
<?
	if (isset($add_res)) {
		?>			
			<span class="span-<?=($add_res['error'] ? 'red' : 'green');?>">
				<?=$add_res['text'];?>
			</span>			
		<?
	}
?>
</div>
