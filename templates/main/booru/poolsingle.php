<div class="shell">
	<table width="100%">
		<tr>
			<td align="left">
				<h2>
					<a href="<?=$def['site']['dir']?>/art/pool/<?=$url[3];?>/">
						<?=$data['main']['pool']['name'];?>
					</a>
				</h2>
			</td>
			<td align="right" valign="top">
				Изображений: <?=$data['main']['pool']['count'];?>
				<br />
				<a href="<?=$def['site']['dir'];?>/art/slideshow/pool/<?=$url[3];?>#1" target="_blank">
					Запустить слайдшоу
				</a>
			</td>
		</tr>
	</table>
	<div class="posttext">
		<?=$data['main']['pool']['text'];?>
	</div>
	<?
		if ($data['main']['pool']['password']) {
			?>
				<div class="closed_group hidden">
					Это закрытая группа. Для внесения любых изменений, вам нужно быть владельцем и написать пароль в поле в правом верхнем углу.
					<br /><br />
					Забыли пароль? <input type="submit" class="disabled redeem_password" value="Выслать новый"> на адрес:
					<input type="text" class="redeem_email">
				</div>
			<?
		}
	?>
	<div class="wrapper">
		<span class="right">
			Режим удаления: <input type="checkbox" class="delete_mode check_closed">
		</span>
		<?
			if ($url[4] != 'sort') {
				?>
					<a href="<?=$def['site']['dir']?>/art/pool/<?=$url[3];?>/sort/">
						Перейти к сортировке
					</a>
				<?
			}
			else {
				?>
					<script type="text/javascript" src="<?=$def['site']['dir']?>/jss/m/?b=jss&f=ui/core.js,ui/sortable.js,edit/pool.js"></script>
					Сортировка:
					 <a href="<?=$def['site']['dir']?>/art/pool/<?=$url[3];?>/sort/">
						Сбросить
					</a>,
					 <a href="<?=$def['site']['dir']?>/art/pool/<?=$url[3];?>/" class="save_pool_order check_closed disabled" rel="<?=$data['main']['pool']['art'];?>">
						Сохранить
					</a>.
				<?
			}
		?>
	</div>
</div>
