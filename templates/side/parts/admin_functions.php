<?
	if ($sets['user']['rights']) {
		?>
			<form enctype="multipart/form-data" method="post">
				<input type="hidden" name="do" value="admin.logout">
				<input type="submit" value="Разлогиниться">
			</form>
			<br /><br /><a href="<?=$def['site']['dir']?>/admin/overview/">Обзор последнего</a>
			<br /><a href="<?=$def['site']['dir']?>/admin/tags/">Управление тегами</a>
			<br /><a href="<?=$def['site']['dir']?>/admin/updates/">Редактирование обновлений</a>
			<br /><a href="<?=$def['site']['dir']?>/admin/subscribe/">Подписаться на комментарии</a>
			<br /><a href="<?=$def['site']['dir']?>/admin/dublicates/">Дубликаты картинок</a>
	<!--		
			
			<br /><br /><a href="<?=$def['site']['dir']?>/admin/re-check/">Обновить индексы мета-данных</a>
			<br /><br /><a href="<?=$def['site']['dir']?>/admin/deadposts/">Записи с неотображающимися ссылками</a>

	-->	<?
	}
?>
