<br /><br />
<? if (!empty($data['main']['new'])) { ?>
<b>Свежезалитые</b>
<div class="shell">
	<table border="1" width="100%">
		<? foreach ($data['main']['new'] as $pack) { ?>
			<tr>
				<td>
					<a href="/art/cg_packs/<?=$pack['id'];?>/">
						<?=$pack['title'];?>
					</a>
				</td>
				<td>
					<? if ($pack['count'] > 0) { ?>
						Осталось обработать <?=$pack['count'];?> картинок.
					<? } elseif ($pack['status'] == 'done' || empty($pack['status'])) { ?>
						Обработан целиком.
					<? } else { ?>
						Ожидает очереди
					<? } ?>
				</td>
				<td>
					<a href="/admin/cg_packs/sort/<?=$pack['id'];?>/">
						Сортировать/выбирать обложку
					</a>
				</td>
				<td>
					<a href="/admin/cg_packs/edit/<?=$pack['id'];?>/">
						Редактировать название/описание
					</a>
				</td>
				<td>
					<form method="post" enctype="multipart/form-data">
						<input type="hidden" name="do" value="admin.pack_delete" />
						<input type="checkbox" name="sure" />
						<input type="hidden" name="id" value="<?= $pack['id'];?>" />
						<input type="submit" value="Удалить" class="submit" />
					</form>				
				</td>
			</tr>		
		<? } ?>
	</table>
</div>	
<? } ?>

<b>Уже выложенные</b>
<div class="shell">
	<table border="1" width="100%">
<?
	if (!empty($data['main']['packs'])) {
		foreach ($data['main']['packs'] as $id => $name) {
		?>
			<tr>
				<td>
					<a href="/art/cg_packs/<?=$id;?>/">
						<?=$name;?>
					</a>
				</td>
				<td>
					<a href="/admin/cg_packs/sort/<?=$id;?>/">
						Сортировать/выбирать обложку
					</a>
				</td>
				<td>
					<a href="/admin/cg_packs/edit/<?=$id;?>/">
						Редактировать название/описание
					</a>
				</td>
				<td>
					<form method="post" enctype="multipart/form-data">
						<input type="hidden" name="do" value="admin.pack_delete" />
						<input type="checkbox" name="sure" />
						<input type="hidden" name="id" value="<?=$id;?>" />
						<input type="submit" value="Удалить" class="submit" />
					</form>				
				</td>
			</tr>
		<?
		}	
	}
?>
	</table>
</div>
