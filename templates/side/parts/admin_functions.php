<?
	if ($sets['user']['rights']) {
		?>
			<form enctype="multipart/form-data" method="post">
				<input type="hidden" name="do" value="admin.logout">
				<input type="submit" value="Разлогиниться">
			</form>
			<br /><br /><a href="/admin/overview/">Обзор последнего</a>
			<br /><a href="/admin/tags/">Управление тегами</a>
			<br /><a href="/admin/updates/">Редактирование обновлений</a>
	<!--		
			
			<br /><br /><a href="/admin/re-check/">Обновить индексы мета-данных</a>
			<br /><br /><a href="/admin/deadposts/">Записи с неотображающимися ссылками</a>

	-->	<?
	}
?>
