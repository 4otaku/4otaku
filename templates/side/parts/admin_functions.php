<?
	if ($sets['user']['rights']) {
		?>
			<form enctype="multipart/form-data" method="post">
				<input type="hidden" name="do" value="admin.logout">
				<input type="submit" value="Разлогиниться">
			</form>
			<br /><br /><a href="<?=$def['site']['dir']?>/admin/overview/">Обзор последнего</a>
			<br /><a href="<?=$def['site']['dir']?>/admin/tags/">Управление тегами</a>
			<br /><a href="<?=$def['site']['dir']?>/admin/subscribe/">Подписаться на комментарии</a>
			<br /><a href="<?=$def['site']['dir']?>/admin/duplicates/">Дубликаты картинок</a>
			<br /><a href="<?=$def['site']['dir']?>/admin/cg_packs/">CG паки</a>
			<br /><a href="<?=$def['site']['dir']?>/admin/menu/">Редактирование меню</a>
		<?
	}
?>
