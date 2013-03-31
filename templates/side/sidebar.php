<? if ($url[1] != 'search' || $url[2] == 'a') { ?>
	<?
		$area = array('p' => 'post', 'v' => 'video', 'a' => 'art', 'n' => 'news', 'c' => 'comments', 'o' => 'order');
		if ($key = array_search($url[1],$area)) $checked = $key;
		elseif ($url[1] == 'search') $checked = $url[2];
		else $checked = 'pov';
	?>
	<table width="100%">
		<tr>
			<td>
				<input value="" class="search" type="text" rel="<?=$checked;?>">
			</td>
			<td>
				<input value="Поиск" class="searchb" type="button">
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<a href="#" class="disabled search-options">
					Показать опции поиска.
				</a>
				<div class="hidden">
					<ul>
						<li>
							<input type="checkbox" <?=(strpos($checked,'p') !== false ? 'checked="checked"': '');?>value="p" class="searcharea <?=(strpos($checked,'p') !== false ? 'checked': 'not_checked');?>">
							 В записях
						</li>
						<li>
							<input type="checkbox" <?=(strpos($checked,'v') !== false ? 'checked="checked"': '');?>value="v" class="searcharea <?=(strpos($checked,'v') !== false ? 'checked': 'not_checked');?>">
							 В видео
						</li>					
						<li>
							<input type="checkbox" <?=(strpos($checked,'a') !== false ? 'checked="checked"': '');?>value="a" class="searcharea <?=(strpos($checked,'a') !== false ? 'checked': 'not_checked');?>">
							 В артах
						</li>					
						<li>
							<input type="checkbox" <?=(strpos($checked,'n') !== false ? 'checked="checked"': '');?>value="n" class="searcharea <?=(strpos($checked,'n') !== false ? 'checked': 'not_checked');?>">
							 В новостях
						</li>					
						<li>
							<input type="checkbox" <?=(strpos($checked,'o') !== false ? 'checked="checked"': '');?>value="o" class="searcharea <?=(strpos($checked,'o') !== false ? 'checked': 'not_checked');?>">
							 В столе заказов
						</li>					
						<li>
							<input type="checkbox" <?=(strpos($checked,'c') !== false ? 'checked="checked"': '');?>value="c" class="searcharea <?=(strpos($checked,'c') !== false ? 'checked': 'not_checked');?>">
							 В комментариях
						</li>					
					</ul>				
				</div>
			</td>		
		</tr>
		<tr>
			<td colspan="2">
				<div id="search-tip" rel="0"></div>
			</td>
		</tr>
	</table>
<? } ?>
<?
	if ($url[1] == 'search' && $url[2] == 'a') $side_url = 'art';
	else $side_url = $url[1];
	if (file_exists('templates'.SL.'side'.SL.'navi'.SL.$side_url.'.php')) {
		?>
			<div class="cats">	
				<h2>
					<span class="href">
						Навигация
					</span>
					 <a href="#" class="bar_arrow" rel="navi">
						<?
							if ($sets['dir']['navi']) {
								?>
									<img src="<?=$def['site']['dir']?>/images/text2391.png">
								<?
							}
							else {
								?>
									<img src="<?=$def['site']['dir']?>/images/text2387.png">
								<?				
							}
						?>
					</a>
				</h2>
				<div id="navi_bar"<?=($sets['dir']['navi'] ? '' : ' style="display:none;"');?>>
					<?
						include ('templates'.SL.'side'.SL.'navi'.SL.$side_url.'.php');
					?>
				</div>
			</div>		
		<?
	}
	if (file_exists('templates'.SL.'side'.SL.'settings'.SL.$side_url.'.php')) {
		?>
			<div class="cats">	
				<h2>
					<a href="#" class="bar_arrow" rel="settings">
						Настройки
					</a> 
					<a href="#" class="bar_arrow" rel="settings">
						<?
							if (!empty($sets['dir']['settings'])) {
								?>
									<img src="<?=$def['site']['dir']?>/images/text2391.png">
								<?
							}
							else {
								?>
									<img src="<?=$def['site']['dir']?>/images/text2387.png">
								<?				
							}
						?>
					</a>
				</h2>
				<div id="settings_bar"<?=($sets['dir']['settings'] ? '' : ' style="display:none;"');?>>
					<?
						include ('templates'.SL.'side'.SL.'settings'.SL.$side_url.'.php');
					?>
				</div>
			</div>		
		<?
	}
?>
<?
	if (is_array($data['sidebar'])) foreach ($data['sidebar'] as $key => $part)	include ('parts'.SL.$key.'.php');
?>
<div class="cats">
	&nbsp;
</div>
