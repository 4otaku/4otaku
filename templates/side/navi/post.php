<?
	$lang['link'] = array(
		$def['area'][0] => 'Записи, главный раздел',
		$def['area'][1] => 'Записи, мастерская',
		$def['area'][2] => 'Записи, барахолка'
	);
	
	foreach ($lang['link'] as $key => $one) {
		?>
			<a href="<?=SITE_DIR.'/post'?>/<?=($key == $def['area'][0] ? "" : $key."/");?>"<?=($url['area'] == $key ? ' class="plaintext"' : '');?>>
				<?=$one;?>
			</a>
			<br />				
		<?
	}
?>
	<br />
	<div class="selector">
		<span>	
			<hr width="100%" />
			Категория: 
			<input type="submit" class="disabled right add_navi" value="+" />
			<select rel="category" class="right">
				<option value="empty" selected="selected">Не выбрано</option>
				<? 
					foreach($data['main']['navigation']['category'] as $alias => $name) {
						?>
							<option value="<?=$alias;?>"><?=$name;?></option>
						<?
					}
				?>
			</select> 
		</span>
	</div>
	<div class="selector">
		<span>	
			<hr width="100%" />
			Язык: 
			<input type="submit" class="disabled right add_navi" value="+" />
			<select rel="language" class="right">
				<option value="empty" selected="selected">Не выбрано</option>		
				<? 
					foreach($data['main']['navigation']['language'] as $alias => $name) {
						?>
							<option value="<?=$alias;?>"><?=$name;?></option>
						<?
					}
				?>
			</select> 
		</span>
	</div>
	<div class="selector">
		<span>
			<hr width="100%" />		
			Тег: 
			<input type="submit" class="disabled right add_navi" value="+" />
			<select rel="tag" class="right">
				<option value="empty" selected="selected">Не выбрано</option>		
				<? 
					foreach($data['main']['navigation']['tag'] as $alias => $name) {
						?>
							<option value="<?=$alias;?>"><?=$name;?></option>
						<?
					}
				?>
			</select> 
		</span>
	</div>
	<hr width="100%" />	
	<span class="hidden navigation_link">
		<a href="#" rel="<?=SITE_DIR.'/post'?>/<?=($url['area'] != $def['area'][0] ? $url['area'].'/' : '');?>">
			<input type="submit" value="Перейти к выбранному">
		</a>
		<br />
	</span>
<?	
	if ($data['main']['rss']) {
		?>
			<br />
			<a href="<?=SITE_DIR?>/rss/<?=_base64_encode(implode('|',array_slice($data['main']['rss'],2)));?>" target="_blank">
				Подписаться на rss <?=$data['main']['rss']['type-name'];?> <?=$data['main']['rss']['meta-name'];?>
			</a>
		<?
	}

