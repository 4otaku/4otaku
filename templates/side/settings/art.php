Показывать материалы 18+: 
<input type="checkbox" class="settings" rel="show.nsfw" value="1"<?=($sets['show']['nsfw'] ? ' checked' : '');?>>
<br />
<?
	if ($sets['show']['nsfw']) {
		?>
			<div class="settings_child">
				Показывать яой: 
				<input type="checkbox" class="settings" rel="show.yaoi" value="1"<?=($sets['show']['yaoi'] ? ' checked' : '');?>>
				<br />
				Показывать гуро: 
				<input type="checkbox" class="settings" rel="show.guro" value="1"<?=($sets['show']['guro'] ? ' checked' : '');?>>
				<br />
				Показывать фурри: 
				<input type="checkbox" class="settings" rel="show.furry" value="1"<?=($sets['show']['furry'] ? ' checked' : '');?>>
			</div>
		<?
	}
?>
Размер тамбнейлов: 
<select class="settings" rel="art.largethumbs">
	<option value="0"<?=(!$sets['art']['largethumbs'] ? ' selected="yes"' : '');?>>обычный</option>
	<option value="1"<?=($sets['art']['largethumbs'] ? ' selected="yes"' : '');?>>крупный</option>
</select>
<br />
Показывать переводы: 
<input type="checkbox" class="settings" rel="show.translation" value="1"<?=($sets['show']['translation'] ? ' checked' : '');?>>
<br />
Уменьшать большие арты: 
<input type="checkbox" class="settings" rel="art.resized" value="1"<?=($sets['art']['resized'] ? ' checked' : '');?>>
<br />
Количество артов на страницу: 
<select class="settings" rel="pp.art">
	<option value="10"<?=($sets['pp']['art'] == 10 ? ' selected="yes"' : '');?>>10</option>
	<option value="15"<?=($sets['pp']['art'] == 15 ? ' selected="yes"' : '');?>>15</option>
	<option value="20"<?=($sets['pp']['art'] == 20 ? ' selected="yes"' : '');?>>20</option>
	<option value="25"<?=($sets['pp']['art'] == 25 ? ' selected="yes"' : '');?>>25</option>
	<option value="30"<?=($sets['pp']['art'] == 30 ? ' selected="yes"' : '');?>>30</option>
	<option value="40"<?=($sets['pp']['art'] == 40 ? ' selected="yes"' : '');?>>40</option>
	<option value="50"<?=($sets['pp']['art'] == 50 ? ' selected="yes"' : '');?>>50</option>
</select>
<br />
Количество комментариев на страницу: 
<select class="settings" rel="pp.comment_in_post">
	<option value="3"<?=($sets['pp']['comment_in_post'] == 3 ? ' selected="yes"' : '');?>>3</option>
	<option value="4"<?=($sets['pp']['comment_in_post'] == 4 ? ' selected="yes"' : '');?>>4</option>
	<option value="5"<?=($sets['pp']['comment_in_post'] == 5 ? ' selected="yes"' : '');?>>5</option>
	<option value="7"<?=($sets['pp']['comment_in_post'] == 7 ? ' selected="yes"' : '');?>>7</option>
	<option value="10"<?=($sets['pp']['comment_in_post'] == 10 ? ' selected="yes"' : '');?>>10</option>
	<option value="15"<?=($sets['pp']['comment_in_post'] == 15 ? ' selected="yes"' : '');?>>15</option>
	<option value="20"<?=($sets['pp']['comment_in_post'] == 20 ? ' selected="yes"' : '');?>>20</option>
</select>
<br />
Направление комментариев: 
<select class="settings" rel="dir.comments_tree">
	<option value="1"<?=($sets['dir']['comments_tree'] ? ' selected="yes"' : '');?>>инвертированное</option>
	<option value="0"<?=(!$sets['dir']['comments_tree'] ? ' selected="yes"' : '');?>>обычное</option>
</select>
<? if ($sets['user']['rights']) { ?>
	<br />
	Сортировка: 
	<select class="settings" rel="artsort.tag">
		<option value="0"<?=($sets['artsort']['tag'] == 0 ? ' selected="yes"' : '');?>>Обычная</option>
		<option value="1"<?=($sets['artsort']['tag'] == 1 ? ' selected="yes"' : '');?>>По тегам</option>
		<option value="-1"<?=($sets['artsort']['tag'] == -1 ? ' selected="yes"' : '');?>>По тегам, инвертированная</option>
	</select>
<? } ?>
