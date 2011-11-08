<script type="text/javascript">
	$(document).ready(function(){
/*
		$('form#edittag').submit(function () {
			$.post("/ajax.php?m=admin&f=edittag&id="+$(this).attr('rel').split('|')[0]+"&old_alias="+$(this).attr('rel').split('|')[1],
				function(data){
					alert("Data Loaded: " + data);
				}
			);

			return false;
		});
*/
	});
</script>

<table width="500px" cellspacing="5px">
	<tr class="settings_header">
		<td>
			Изменить тег "<?=$data['name'];?>"
		</td>
	</tr>
</table>
<form id="edittag" enctype="multipart/form-data" method="post">
	<input type="hidden" name="do" value="admin.edittag">
	<input type="hidden" name="old_alias" value="<?=$data['alias'];?>">
	<input type="hidden" name="id" value="<?=$data['id'];?>">
	Алиас:
	<input type="text" value="<?=$data['alias'];?>" name="alias" style="width: 95%">
	Имя:
	<input type="text" value="<?=$data['name'];?>" name="name" style="width: 95%">
	Варианты:
	<textarea name="variants" rows="10" style="width: 95%"><?=str_replace('|',', ',trim($data['variants'],'|'));?></textarea>
	<div>
		Цвет:
		<select name="color">
			<option value=""<?=(!$data['color'] ? 'selected="selected" class="selected"' : '');?>>Обычный</option>
			<option value="AA0000" <?=($data['color'] == 'AA0000' ? 'selected="selected" class="selected"' : '');?>>Автор</option>
			<option value="00AA00" <?=($data['color'] == '00AA00' ? 'selected="selected" class="selected"' : '');?>>Персонаж</option>
			<option value="AA00AA" <?=($data['color'] == 'AA00AA' ? 'selected="selected" class="selected"' : '');?>>Произведение</option>
			<option value="0000FF" <?=($data['color'] == '0000FF' ? 'selected="selected" class="selected"' : '');?>>Служебный</option>
		</select>
	</div>
	<div>
		Где используется:
		<nobr>
		<?	$area_types = array('post_main' => 'записи','post_flea_market' => 'записи, б.','video_main' => 'видео','video_flea_market' => 'видео, б','art_main' => 'арт','art_flea_market' => 'арт, б');
			foreach ($area_types as $key => $area_type) { ?>
			<? if (isset($data[$key]) && $data[$key]) { ?>
				<a href="<?=$def['site']['dir']?>/<?=substr($key,0,strpos($key,'_')).(substr($key,strpos($key,'_')+1) != 'main' ? '/'.substr($key,strpos($key,'_')+1) : '').'/tag/'.$data['alias'];?>/" target="_blank">
					<?=$area_type;?>
				</a>,
			<? } ?>
		<? } ?>
		</nobr>
	</div>
	<input type="submit" value="Сохранить" />
	<!--a href="<?=$def['site']['dir']?>/admin/tags/merge/<?=$data['alias'];?>" class="plaintext">
		<input type="button" value="Объединить" />
	</a-->
</form>
