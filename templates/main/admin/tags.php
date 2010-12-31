<div id="admin_tags">
	Искать теги: <input type="text" value="<?=($url[3] == 'search' ? urldecode($url[4]) : '');?>" name="searchtags" class="searchtags" size="17"> <input type="submit" value="Искать" class="disabled search_tags">
	 <a href="/admin/tags/problem">Проблемные теги</a>.
	<div class="right"> 
	 <input type="submit" value="Сохранить все" class="disabled save_all"><br />	 
	Тегов: 
	<select class="settings" rel="pp.tags_admin">
		<option value="10"<?=($sets['pp']['tags_admin'] == 10 ? ' selected="yes"' : '');?>>10</option>
		<option value="20"<?=($sets['pp']['tags_admin'] == 20 ? ' selected="yes"' : '');?>>20</option>
		<option value="30"<?=($sets['pp']['tags_admin'] == 30 ? ' selected="yes"' : '');?>>30</option>
		<option value="40"<?=($sets['pp']['tags_admin'] == 40 ? ' selected="yes"' : '');?>>40</option>
		<option value="50"<?=($sets['pp']['tags_admin'] == 50 ? ' selected="yes"' : '');?>>50</option>
		<option value="75"<?=($sets['pp']['tags_admin'] == 75 ? ' selected="yes"' : '');?>>75</option>
		<option value="100"<?=($sets['pp']['tags_admin'] == 100 ? ' selected="yes"' : '');?>>100</option>
	</select>
	</div>
	<table>
		<tr>
			<th>
				Алиас:
			</th>
			<th>
				Имя:
			</th>
			<th>
				Варианты:
			</th>
			<th>
				Цвет:
			</th>
			<th>
				Перейти
			</th>
		</tr>	
	<?	$area_types = array('post_main' => 'записи','post_flea_market' => 'записи, б.','video_main' => 'видео','video_flea_market' => 'видео, б','art_main' => 'арт','art_flea_market' => 'арт, б');
		if (is_array($data['main']['tags'])) foreach ($data['main']['tags'] as $id => $item) {
			?>
				<tr>
					<form enctype="multipart/form-data" method="post">
						<input type="hidden" name="do" value="admin.edittag">
						<input type="hidden" name="old_alias" value="<?=$item['alias'];?>">
						<input type="hidden" name="id" value="<?=$id;?>">
						<td>
							<input type="text" value="<?=$item['alias'];?>" name="alias" size="17">
						</td>
						<td>
							<input type="text" value="<?=$item['name'];?>" name="name" size="17">
						</td>
						<td>
							<input type="text" value="<?=str_replace('|',', ',trim($item['variants'],'|'));?>" name="variants" size="17">
						</td>
						<td>
							<select name="color">
								<option value=""<?=(!$item['color'] ? 'selected="selected" class="selected"' : '');?>>Обычный</option>
								<option value="AA0000" <?=($item['color'] == 'AA0000' ? 'selected="selected" class="selected"' : '');?>>Автор</option>
								<option value="00AA00" <?=($item['color'] == '00AA00' ? 'selected="selected" class="selected"' : '');?>>Персонаж</option>
								<option value="AA00AA" <?=($item['color'] == 'AA00AA' ? 'selected="selected" class="selected"' : '');?>>Произведение</option>
							</select>
						</td>	
						<td>
							<? foreach ($area_types as $key => $area_type) { ?>
								<? if ($item[$key]) { ?>
									<a href="/<?=substr($key,0,strpos($key,'_')).(substr($key,strpos($key,'_')+1) != 'main' ? '/'.substr($key,strpos($key,'_')+1) : '').'/tag/'.$item['alias'];?>/" target="_blank">
										<?=$area_type;?>
									</a> 
								<? } ?>
							<? } ?>
						</td>		
						<td>
							<input type="submit" value="Сохранить">
							<a href="/admin/tags/merge/<?=$item['alias'];?>" class="plaintext"> 
								<input type="button" value="Объединить">
							</a>
							<input type="submit" value="Удалить" class="delete_tag disabled" rel="<?=$id;?>|<?=$item['alias'];?>">
						</td>
					</form>
				</tr>
			<?
		}
	?>
	</table>
</div>
