Искать теги: <input type="text" value="<?=($url[3] == 'search' ? urldecode($url[4]) : '');?>" name="searchtags" class="searchtags" size="17"> <input type="submit" value="Искать" class="disabled search_tags">
<br /> 
<a href="/admin/tags/problem/alias/">Проблемные теги - алиас</a>. <a href="/admin/tags/problem/match/">Проблемные теги - совпадения</a>. <a href="/admin/tags/problem/empty/">Проблемные теги - пустые</a>. 
 <br /><br />
 <? if ($url[4] != 'match') { ?>
	<? if ($url[4] != 'empty') { ?>
		<h2><span class="href">Возможно корявый алиас:</span></h2>
	<? } else { ?>
		<h2><span class="href">Нигде не использующиеся теги:</span></h2>
	<? } ?>
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
						<input type="hidden" name="id" value="<?=$id;?>">
						<input type="hidden" name="old_alias" value="<?=$item['alias'];?>">						
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
								<option value="">Обычный</option>
								<option value="AA0000">Автор</option>
								<option value="00AA00">Персонаж</option>
								<option value="AA00AA">Произведение</option>
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
						<input type="submit" value="Удалить" class="delete_tag disabled" rel="<?=$id;?>|<?=$item['alias'];?>">
						</td>
					</form>
				</tr>
			<?
		}
	?>
	</table>
<? } else { ?>
	<h2><span class="href">Пары пересекающихся тегов:</span></h2>
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
		if (is_array($data['main']['tags'])) foreach ($data['main']['tags'] as $_item) {
			foreach ($_item as $id => $item) { ?>
				<tr>
					<form enctype="multipart/form-data" method="post">
						<input type="hidden" name="do" value="admin.edittag">
						<input type="hidden" name="id" value="<?=$id;?>">
						<input type="hidden" name="old_alias" value="<?=$item['alias'];?>">						
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
								<option value="">Обычный</option>
								<option value="AA0000">Автор</option>
								<option value="00AA00">Персонаж</option>
								<option value="AA00AA">Произведение</option>
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
						<input type="submit" value="Удалить" class="delete_tag disabled" rel="<?=$id;?>|<?=$item['alias'];?>">
						</td>
					</form>
				</tr>
			<? }
			?>
				<tr>
					<td colspan="6">
						<hr />
					</td>
				</tr>
			<?
		}
	?>
	</table>	
<? } ?>
