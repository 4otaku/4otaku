<div class="shell">
	<input type="submit" class="right invert_pack" value="Инвертировать порядок" />
	<h2>
		Сортировка галереи <?=$data['main']['gal']['title'];?>
	</h2>
	<p>
		Сортировка идет от меньшего к большему, т.е. картинки с меньшим числом пользователь увидит раньше. Число должно быть не меньше единицы. Если число дробное, оно округлится. Числа могут повторяться, в этом случае сортировка между картинками с одинаковым числом будут по порядку записи в БД.
	</p>
	<form enctype="multipart/form-data" method="post">
		<input type="hidden" name="do" value="admin.pack_sort">
		<input type="hidden" name="id" value="<?=$url[4];?>">
		<table width="100%">
			<? if (!empty($data['main']['art'])) {
				foreach ($data['main']['art'] as $art) { ?>
				<tr>
					<td width="250px" style="border: 2px solid rgb(231, 231, 231);">
						<center><?=$art['filename'];?></center><br />
						<img src="/images/booru/thumbs/<?=$art['thumb'];?>.jpg">
					</td>
					<td align="left">
						<input type="text" value="<?=$art['order'];?>" name="order[<?=$art['id'];?>]" class="pack_order" /><br />
						Обложка? <input type="radio" name="chosen" value="<?=$art['thumb'];?>"<?=($art['thumb'] == $data['main']['gal']['cover'] ? ' checked="checked" class="checked"' : '');?>>
					</td>
					<td align="rights">
						Удалить? <input type="checkbox" name="delete[<?=$art['id'];?>]" value="<?=$art['id'];?>" class="not_checked">
					</td>						
				</tr>
				<? }
			} ?>
		</table>
		<input type="submit" value="Сохранить">
	</form>	
</div>
