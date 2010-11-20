<?
	if ($sets['user']['rights']) {
		?>
			<form enctype="multipart/form-data" method="post">
				<input type="hidden" name="do" value="admin.logout">
				<input type="submit" value="Разлогиниться">
			</form>
			<br /><br /><a href="<?=SITE_DIR.'/admin'?>/overview/">Обзор последнего</a>
			<br /><a href="<?=SITE_DIR.'/admin'?>/tags/">Управление тегами</a>
	<!--		<br /><br /><a href="/admin/synonims/">Список синонимов</a>
			<br /><br /><a href="/admin/gouf/">Проверяльщик ссылок GOUF</a>
			<br /><br /><a href="/admin/re-check/">Обновить индексы мета-данных</a>
			<br /><br /><a href="/admin/deadposts/">Записи с неотображающимися ссылками</a>

			<br /><br /><a href="/admin/yukari/">Редактирование справки Юкари</a>
			<br /><br /><a href="/admin/alias/">Редактирование алиасов и имен</a>
	-->	<?
	}
?>
