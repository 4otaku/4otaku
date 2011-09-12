Показывать материалы 18+: 
<input type="checkbox" class="settings" rel="show.nsfw" value="1"<?=($sets['show']['nsfw'] ? ' checked' : '');?>>
<br />
Количество записей на страницу: 
<select class="settings" rel="pp.post">
	<option value="3"<?=($sets['pp']['post'] == 3 ? ' selected="yes"' : '');?>>3</option>
	<option value="4"<?=($sets['pp']['post'] == 4 ? ' selected="yes"' : '');?>>4</option>
	<option value="5"<?=($sets['pp']['post'] == 5 ? ' selected="yes"' : '');?>>5</option>
	<option value="7"<?=($sets['pp']['post'] == 7 ? ' selected="yes"' : '');?>>7</option>
	<option value="10"<?=($sets['pp']['post'] == 10 ? ' selected="yes"' : '');?>>10</option>
	<option value="15"<?=($sets['pp']['post'] == 15 ? ' selected="yes"' : '');?>>15</option>
	<option value="20"<?=($sets['pp']['post'] == 20 ? ' selected="yes"' : '');?>>20</option>
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
Количество обновлений на страницу: 
<select class="settings" rel="pp.updates_in_line">
	<option value="3"<?=($sets['pp']['updates_in_line'] == 3 ? ' selected="yes"' : '');?>>3</option>
	<option value="4"<?=($sets['pp']['updates_in_line'] == 4 ? ' selected="yes"' : '');?>>4</option>
	<option value="5"<?=($sets['pp']['updates_in_line'] == 5 ? ' selected="yes"' : '');?>>5</option>
	<option value="7"<?=($sets['pp']['updates_in_line'] == 7 ? ' selected="yes"' : '');?>>7</option>
	<option value="10"<?=($sets['pp']['updates_in_line'] == 10 ? ' selected="yes"' : '');?>>10</option>
	<option value="15"<?=($sets['pp']['updates_in_line'] == 15 ? ' selected="yes"' : '');?>>15</option>
	<option value="20"<?=($sets['pp']['updates_in_line'] == 20 ? ' selected="yes"' : '');?>>20</option>
</select>
<br />
Направление комментариев: 
<select class="settings" rel="dir.comments_tree">
	<option value="1"<?=($sets['dir']['comments_tree'] ? ' selected="yes"' : '');?>>инвертированное</option>
	<option value="0"<?=(!$sets['dir']['comments_tree'] ? ' selected="yes"' : '');?>>обычное</option>
</select>
