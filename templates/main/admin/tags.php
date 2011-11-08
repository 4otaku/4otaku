<div id="admin_tags" class="overview">
	Искать теги: <input type="text" value="<?=($url[3] == 'search' ? urldecode($url[4]) : '');?>" name="searchtags" class="searchtags" size="17"> <input type="submit" value="Искать" class="disabled search_tags">
	 <a href="<?=$def['site']['dir']?>/admin/tags/problem">Проблемные теги</a>.
	<div class="right">
	 <input type="submit" value="Сохранить все" class="disabled save_all" /><br />
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
	<? if (is_array($data['main']['tags']))
			include_once('templates'.SL.'main'.SL.'navi.php');
	?>
	<table width="100%" cellspacing="0">
		<tr>
			<th width="10%">
				Алиас:
			</th>
			<th width="10%">
				Имя:
			</th>
			<th>
				Варианты:
			</th>
			<th width="10%">
				Цвет:
			</th>
			<th width="30px">
				Перейти:
			</th>
			<th width="300px">
				Опции:
			</th>
		</tr>
	<?	$area_types = array('post_main' => 'записи','post_flea_market' => 'записи, б.','video_main' => 'видео','video_flea_market' => 'видео, б','art_main' => 'арт','art_flea_market' => 'арт, б');
		if (is_array($data['main']['tags'])) foreach ($data['main']['tags'] as $id => $item) {
			?>
				<tr>
					<td>
						<?=$item['alias'];?>
					</td>
					<td class="name">
						<?=$item['name'];?>
					</td>
					<td>
						<?=str_replace('|',', ',trim($item['variants'],'|'));?>
					</td>
					<td>
						<? $color = array("" => 'Обычный', "AA0000" => 'Автор', "00AA00" => 'Персонаж', "AA00AA" => 'Произведение', "0000FF" => 'Служебный'); ?>
						<span style="color: #<?=$item['color']?>;"><?=(isset($color[$item['color']])) ? $color[$item['color']] : '-'; ?></span>
					</td>
					<td>
						<!--nobr-->
						<? foreach ($area_types as $key => $area_type) { ?>
							<? if ($item[$key]) { ?>
								<nobr>
								<a href="<?=$def['site']['dir']?>/<?=substr($key,0,strpos($key,'_')).(substr($key,strpos($key,'_')+1) != 'main' ? '/'.substr($key,strpos($key,'_')+1) : '').'/tag/'.$item['alias'];?>/" target="_blank">
									<?=$area_type;?>
								</a>,
								</nobr>
							<? } ?>
						<? } ?>
						<!--/nobr-->
					</td>
					<td>
						<a href="/ajax.php?m=admin&f=tag_form&width=500&height=585&id=<?=$id?>" class="thickbox" title="" rel="edit-tag-form">
							<input type="button" value="Редактировать" />
						</a>
						<a href="<?=$def['site']['dir']?>/admin/tags/merge/<?=$item['alias'];?>" class="plaintext">
							<input type="button" value="Объединить" />
						</a>
						<input type="submit" value="Удалить" class="delete_tag disabled" rel="<?=$id;?>|<?=$item['alias'];?>">
					</td>
				</tr>

				<!--tr>
					<form enctype="multipart/form-data" method="post">
						<input type="hidden" name="do" value="admin.edittag">
						<input type="hidden" name="old_alias" value="<?=$item['alias'];?>">
						<input type="hidden" name="id" value="<?=$id;?>">
						<td>
							<input type="text" value="<?=$item['alias'];?>" name="alias" style="width: 95%">
						</td>
						<td>
							<input type="text" value="<?=$item['name'];?>" name="name" style="width: 95%">
						</td>
						<td>
							<textarea name="variants" style="width: 95%"><?=str_replace('|',', ',trim($item['variants'],'|'));?></textarea>
						</td>
						<td>
							<select name="color">
								<option value=""<?=(!$item['color'] ? 'selected="selected" class="selected"' : '');?>>Обычный</option>
								<option value="AA0000" <?=($item['color'] == 'AA0000' ? 'selected="selected" class="selected"' : '');?>>Автор</option>
								<option value="00AA00" <?=($item['color'] == '00AA00' ? 'selected="selected" class="selected"' : '');?>>Персонаж</option>
								<option value="AA00AA" <?=($item['color'] == 'AA00AA' ? 'selected="selected" class="selected"' : '');?>>Произведение</option>
								<option value="0000FF" <?=($item['color'] == '0000FF' ? 'selected="selected" class="selected"' : '');?>>Служебный</option>
							</select>
						</td>
						<td>
							<nobr>
							<? foreach ($area_types as $key => $area_type) { ?>
								<? if ($item[$key]) { ?>
									<a href="<?=$def['site']['dir']?>/<?=substr($key,0,strpos($key,'_')).(substr($key,strpos($key,'_')+1) != 'main' ? '/'.substr($key,strpos($key,'_')+1) : '').'/tag/'.$item['alias'];?>/" target="_blank">
										<?=$area_type;?>
									</a>,
								<? } ?>
							<? } ?>
							</nobr>
						</td>
						<td>
							<input type="submit" value="Сохранить">
							<a href="<?=$def['site']['dir']?>/admin/tags/merge/<?=$item['alias'];?>" class="plaintext">
								<input type="button" value="Объединить">
							</a>
							<input type="submit" value="Удалить" class="delete_tag disabled" rel="<?=$id;?>|<?=$item['alias'];?>">
						</td>
					</form>
				</tr-->
			<?
		}
	?>
	</table>
</div>
