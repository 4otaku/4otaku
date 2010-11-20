<script type="text/javascript">
	$(document).ready(function(){ 
		$(".settings").unbind('change').change(function(){
			if ($(this).is(':checkbox')) val = '{'+$(this).is(':checked')+'}';
			else val = $(this).val();
			$.post("<?=SITE_DIR?>/ajax.php?m=cookie&f=set&field="+$(this).attr('rel')+"&val="+val);
			window.changed = true;
		});	
		$("#TB_closeWindowButton").unbind('click').click(function(e){
			e.preventDefault();		
			if (window.changed == true) document.location.reload();	
			else tb_remove();
		});
		$(document).unbind('keyup').keyup(function(e) {
			if (e.keyCode == 27) {
				if (window.changed == true) document.location.reload();	
				else tb_remove(); 
			}
		});
	});			
</script>
Настройки привязываются к cookies, поэтому если вы смените браузер, компьютер или очистите cookies, ваши настройки сбросятся.
<table width="500px" cellspacing="5px">
	<tr class="settings_header">
		<td>
			Общие настройки	
		</td>
	</tr>
	<tr>
		<td>
			Показывать материалы 18+: 
			<input type="checkbox" class="settings" rel="show.nsfw" value="1"<?=($sets['show']['nsfw'] ? ' checked' : '');?>>
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
			<br />
			Количество новостей на страницу: 
			<select class="settings" rel="pp.news">
				<option value="3"<?=($sets['pp']['news'] == 3 ? ' selected="yes"' : '');?>>3</option>
				<option value="4"<?=($sets['pp']['news'] == 4 ? ' selected="yes"' : '');?>>4</option>
				<option value="5"<?=($sets['pp']['news'] == 5 ? ' selected="yes"' : '');?>>5</option>
				<option value="7"<?=($sets['pp']['news'] == 7 ? ' selected="yes"' : '');?>>7</option>
				<option value="10"<?=($sets['pp']['news'] == 10 ? ' selected="yes"' : '');?>>10</option>
				<option value="15"<?=($sets['pp']['news'] == 15 ? ' selected="yes"' : '');?>>15</option>
				<option value="20"<?=($sets['pp']['news'] == 20 ? ' selected="yes"' : '');?>>20</option>
			</select>
			<br />
			Количество постов на страницу в ленте комментариев: 
			<select class="settings" rel="pp.comment_in_line">
				<option value="3"<?=($sets['pp']['comment_in_line'] == 3 ? ' selected="yes"' : '');?>>3</option>
				<option value="4"<?=($sets['pp']['comment_in_line'] == 4 ? ' selected="yes"' : '');?>>4</option>
				<option value="5"<?=($sets['pp']['comment_in_line'] == 5 ? ' selected="yes"' : '');?>>5</option>
				<option value="7"<?=($sets['pp']['comment_in_line'] == 7 ? ' selected="yes"' : '');?>>7</option>
				<option value="10"<?=($sets['pp']['comment_in_line'] == 10 ? ' selected="yes"' : '');?>>10</option>
				<option value="15"<?=($sets['pp']['comment_in_line'] == 15 ? ' selected="yes"' : '');?>>15</option>
				<option value="20"<?=($sets['pp']['comment_in_line'] == 20 ? ' selected="yes"' : '');?>>20</option>
			</select>
			<br />
			Количество тегов в облаке: 
			<select class="settings" rel="pp.tags">
				<option value="20"<?=($sets['pp']['tags'] == 20 ? ' selected="yes"' : '');?>>20</option>
				<option value="30"<?=($sets['pp']['tags'] == 30 ? ' selected="yes"' : '');?>>30</option>
				<option value="40"<?=($sets['pp']['tags'] == 40 ? ' selected="yes"' : '');?>>40</option>
				<option value="50"<?=($sets['pp']['tags'] == 50 ? ' selected="yes"' : '');?>>50</option>
				<option value="70"<?=($sets['pp']['tags'] == 70 ? ' selected="yes"' : '');?>>70</option>
				<option value="100"<?=($sets['pp']['tags'] == 100 ? ' selected="yes"' : '');?>>100</option>
				<option value="150"<?=($sets['pp']['tags'] == 150 ? ' selected="yes"' : '');?>>150</option>
			</select>				 
		</td>
	</tr>
	<tr>
		<td class="settings_header">
			Настройки записей
		</td>		
	</tr>
	<tr valign="top">
		<td>
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
		</td>
	<tr>
		<td class="settings_header">
			Настройки видео
		</td>
	</tr>
	<tr>
		<td>
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
		</td>
	</tr>
	<tr>	
		<td class="settings_header">
			Настройки артов
		</td>								
	</tr>
	<tr>			
		<td>
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
			Показывать яой: 
			<input type="checkbox" class="settings" rel="show.yaoi" value="1"<?=($sets['show']['yaoi'] ? ' checked' : '');?>>
			<br />
			Показывать гуро: 
			<input type="checkbox" class="settings" rel="show.guro" value="1"<?=($sets['show']['guro'] ? ' checked' : '');?>>
			<br />
			Показывать фурри: 
			<input type="checkbox" class="settings" rel="show.furry" value="1"<?=($sets['show']['furry'] ? ' checked' : '');?>>
			<br />
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
		</td>
	</tr>	
</table>
