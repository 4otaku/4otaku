<?
	$lang['add'] = array(
		$def['type'][0] => 'Добавить материал',
		$def['type'][1] => 'Добавить видео',
		$def['type'][2] => 'Загрузить картинки',
		'order' => 'Оставить заказ (не забудьте прочитать правила)',
		'pool' => 'Добавить новую группу'		
	);
?>
<?
	if ($data['top']['add_bar']) {
		?>
			<div class="addborder">
				<div id="downscroller" rel="<?=$data['top']['add_bar']['type'];?>"> 
					<div>
						<a href="#bugfix" class="disabled">
							<?=$lang['add'][$data['top']['add_bar']['type']];?>
							<?=($data['top']['add_bar']['pool'] ? " в группу ".$data['top']['add_bar']['pool'] : "");?>
						</a>
						<span class="arrow"> ↓</span> 
						<? 
							if ($data['top']['add_bar']['pass']) {
								?>
									<span class="right">
										Пароль: <input name="password" class="password" type="text" rel="<?=$url[3];?>">
									</span>
								<?
							}
						?>
					</div>
					<div id="add_loader">
						<img src="<?=SITE_DIR.'/images'?>/ajax-loader.gif">
					</div>
					<div id="add_form">
						&nbsp;
					</div>
				</div>
			</div>
			<br />
		<?
	}	
	if ($add_res) {
		?>
			<div class="mini-shell addres">
				<span class="span-<?=($add_res['error'] ? 'red' : 'green');?>">
					<?=$add_res['text'];?>
				</span>
			</div>
		<?
	}
?>
