Показывать материалы 18+: 
<input type="checkbox" class="settings" rel="show.nsfw" value="1"<?=($sets['show']['nsfw'] ? ' checked' : '');?>>
<br />
Количество видео на страницу: 
<select class="settings" rel="pp.video">
	<option value="3"<?=($sets['pp']['video'] == 3 ? ' selected="yes"' : '');?>>3</option>
	<option value="4"<?=($sets['pp']['video'] == 4 ? ' selected="yes"' : '');?>>4</option>
	<option value="5"<?=($sets['pp']['video'] == 5 ? ' selected="yes"' : '');?>>5</option>
	<option value="7"<?=($sets['pp']['video'] == 7 ? ' selected="yes"' : '');?>>7</option>
	<option value="10"<?=($sets['pp']['video'] == 10 ? ' selected="yes"' : '');?>>10</option>
	<option value="15"<?=($sets['pp']['video'] == 15 ? ' selected="yes"' : '');?>>15</option>
	<option value="20"<?=($sets['pp']['video'] == 20 ? ' selected="yes"' : '');?>>20</option>
</select>
<br />
Размер видео в общей ленте: 
<select class="settings" rel="video.thumb">
	<option value="320x240"<?=($sets['video']['thumb'] == '320x240' ? ' selected="yes"' : '');?>>320x240</option>
	<option value="480x360"<?=($sets['video']['thumb'] == '480x360' ? ' selected="yes"' : '');?>>480x360</option>
	<option value="600x450"<?=($sets['video']['thumb'] == '600x450' ? ' selected="yes"' : '');?>>600x450</option>
	<option value="720x540"<?=($sets['video']['thumb'] == '720x540' ? ' selected="yes"' : '');?>>720x540</option>
	<option value="900x675"<?=($sets['video']['thumb'] == '900x675' ? ' selected="yes"' : '');?>>900x675</option>
	<option value="1200x900"<?=($sets['video']['thumb'] == '1200x900' ? ' selected="yes"' : '');?>>1200x900</option>		
</select>
<br />
Размер отдельного видео: 
<select class="settings" rel="video.full">
	<option value="320x240"<?=($sets['video']['full'] == '320x240' ? ' selected="yes"' : '');?>>320x240</option>
	<option value="480x360"<?=($sets['video']['full'] == '480x360' ? ' selected="yes"' : '');?>>480x360</option>
	<option value="600x450"<?=($sets['video']['full'] == '600x450' ? ' selected="yes"' : '');?>>600x450</option>
	<option value="720x540"<?=($sets['video']['full'] == '720x540' ? ' selected="yes"' : '');?>>720x540</option>
	<option value="900x675"<?=($sets['video']['full'] == '900x675' ? ' selected="yes"' : '');?>>900x675</option>
	<option value="1200x900"<?=($sets['video']['full'] == '1200x900' ? ' selected="yes"' : '');?>>1200x900</option>		
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
