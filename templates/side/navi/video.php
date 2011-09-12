<?
	$lang['link'] = array(
		$def['area'][0] => 'Видео, главный раздел',
		$def['area'][1] => 'Видео, очередь премодерации',
		$def['area'][2] => 'Видео, барахолка'
	);
	
	foreach ($lang['link'] as $key => $one) {
		?>
			<a href="<?=$def['site']['dir']?>/video/<?=($key == $def['area'][0] ? "" : $key."/");?>"<?=($url['area'] == $key ? ' class="plaintext"' : '');?>>
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
		<a href="#" rel="/video/<?=($url['area'] != $def['area'][0] ? $url['area'].'/' : '');?>">
			Перейти к выбранному
		</a>
		<br />
	</span>
<?
	if ($data['main']['rss']) {
		?>
			<br /><br />
			<a href="<?=$def['site']['dir']?>/rss/<?=_base64_encode(implode('|',array_slice($data['main']['rss'],2)));?>" target="_blank">
				Подписаться на rss <?=$data['main']['rss']['type-name'];?> <?=$data['main']['rss']['meta-name'];?>
			</a>
		<?
	}
