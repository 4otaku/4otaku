<?
	if ($url[4] != 'sure') {
		?>
			<div class="shell">
				<a href="/admin/cg_packs/cleanup/sure/">
					<input type="submit" value="Выполнить очистку">
				</a>
			</div>
		<?
	} else {
		?>
			<div class="shell">
				<a href="/admin/cg_packs/">
					<input type="submit" value="Готово, назад">
				</a>
			</div>		
		<?
	}
?>
