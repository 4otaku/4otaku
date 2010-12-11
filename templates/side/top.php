<?
	$lang['add'] = array(
		$def['type'][0] => 'Добавить материал',
		$def['type'][1] => 'Добавить видео',
		$def['type'][2] => 'Загрузить картинки',
		'order' => 'Оставить заказ (не забудьте прочитать правила)',
		'pool' => 'Добавить новую группу',
		'board' => 'Открыть новый тред',
		'thread' => 'Ответить в тред',
	);

	if (is_array($data['top']['board_list'])) {
		?> 
			<div class="center" width="100%">
				Доски: [
		<?
		$first = 0;
		foreach ($data['top']['board_list'] as $alias => $name) {
			?>
				<?=($first++ ? ' / ' : '');?>
				<a href="/art/board/<?=$alias;?>/">
					<?=$name;?>
				</a>
			<?
		}
		?>
				]
			</div>
		<?		
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
						<?
							if (isset($data['top']['add_bar']['pass'])) {
								?>
									<span class="right">
										Пароль: <input name="password" class="password" type="text" rel="<?=$url[3];?>">
									</span>
								<?
							}
						?>
					</div>
					<div id="add_loader">
						<img src="/images/ajax-loader.gif">
					</div>
					<div id="add_form">
						&nbsp;
					</div>
				</div>
			</div>
			<br />
		<?
	}
	if (isset($add_res)) {
		?>
			<div class="mini-shell addres">
				<span class="span-<?=($add_res['error'] ? 'red' : 'green');?>">
					<?=$add_res['text'];?>
				</span>
			</div>
		<?
	}