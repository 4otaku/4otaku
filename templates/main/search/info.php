<?
	$lang['search'] = array(
		'p' => 'записям',
		'v' => 'видео',
		'a' => 'артам',
		'n' => 'новостям',
		'o' => 'заказам',
		'c' => 'комментариям'
	);
?>
<div class="shell">
	<div class="center margin10">
		<input type="text" size="50" class="search" value="<?=urldecode($url[4]);?>"> <input type="button" value="искать" class="searchb">						
	</div>
	<div id="search-tip" class="center search-main" rel="0"></div>
	<div class="right">
		Сортировать результаты поиска по: 
		<select class="search-switcher">
			<option value="rel"<?=($url[3] == 'rel' ? ' selected="selected"' : '');?>>
				релевантности.
			</option>
			<option value="date"<?=($url[3] == 'date' ? ' selected="selected"' : '');?>>
				дате от новых к старым.
			</option>
			<option value="rdate"<?=($url[3] == 'rdate' ? ' selected="selected"' : '');?>>
				дате от старых к новым.
			</option>
		</select>
	</div>
	Вы ищете по: 
	<?
		$search_area = str_split($url[2]);
		foreach ($search_area as &$one) $one = $lang['search'][$one];
		echo implode(', ',$search_area);
	?>
	. <br />
	<a href="#" class="disabled show_searchareas">
		Изменить область поиска.
	</a>
	<br />
	<div class="hidden secondary_searchareas">
		<br />
		Искать в:
		<br />
		<ul>
			<li>
				<input type="checkbox" <?=(strpos($url[2],'p') !== false ? 'checked="checked"': '');?>value="p" class="secondary_searcharea searcharea <?=(strpos($url[2],'p') !== false ? 'checked': 'not_checked');?>">
				 В записях
			</li>
			<li>
				<input type="checkbox" <?=(strpos($url[2],'v') !== false ? 'checked="checked"': '');?>value="v" class="secondary_searcharea searcharea <?=(strpos($url[2],'v') !== false ? 'checked': 'not_checked');?>">
				 В видео
			</li>
			<li>
				<input type="checkbox" <?=(strpos($url[2],'a') !== false ? 'checked="checked"': '');?>value="a" class="secondary_searcharea searcharea <?=(strpos($url[2],'a') !== false ? 'checked': 'not_checked');?>">
				 В артах
			</li>
			<li>
				<input type="checkbox" <?=(strpos($url[2],'n') !== false ? 'checked="checked"': '');?>value="n" class="secondary_searcharea searcharea <?=(strpos($url[2],'n') !== false ? 'checked': 'not_checked');?>">
				 В новостях
			</li>
			<li>
				<input type="checkbox" <?=(strpos($url[2],'o') !== false ? 'checked="checked"': '');?>value="o" class="secondary_searcharea searcharea <?=(strpos($url[2],'o') !== false ? 'checked': 'not_checked');?>">
				 В столе заказов
			</li>
			<li>
				<input type="checkbox" <?=(strpos($url[2],'c') !== false ? 'checked="checked"': '');?>value="c" class="secondary_searcharea searcharea <?=(strpos($url[2],'c') !== false ? 'checked': 'not_checked');?>">
				 В комментариях
			</li>
		</ul>
		<br />
		<a href="<?=SITE_DIR?>/<?=implode('/',$url);?>" class="secondary_search">
			<input type ="submit" value="Искать в указанном">
		</a>
	</div>
</div>
