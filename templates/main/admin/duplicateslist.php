<? 
	$areas = array('main' => 'на главной', 'workshop' => 'в очереди', 'flea_market' => 'на барахолке');

	if(is_array($data['main']['doubles']))
	{
		foreach ($data['main']['doubles'] as $double) {
			$double = explode('-', $double);
			?>
				<table width="100%" cellspacing="20">
					<tr>					
					<?
					foreach ($double as $one) {
						?>
							<td valign="top" align="center" width="50%">
								<a href="/art/<?=$one;?>"
									class="with_help3"
									title="
										<?
											if (count($data['main']['arts'][$one]['meta']['tag']) > 1) {
												?>
													Теги: 
												<?
											}
											else {
												?>
													Тег: 
												<?
											}
											echo implode(', ',$data['main']['arts'][$one]['meta']['tag']);
										?>								
									"
								>
									<img src="http://4otaku.ru/images/booru/thumbs/large_<?=$data['main']['arts'][$one]['thumb'];?>.jpg">
								</a>
								<br /><br />
								ТТХ: <?=$data['main']['arts'][$one]['width'];?>x<?=$data['main']['arts'][$one]['height'];?>px; <?=strtoupper($data['main']['arts'][$one]['extension']);?>; <?=$data['main']['arts'][$one]['size'];?>;
								<br />
								Комментариев: <?=$data['main']['arts'][$one]['comment_count'];?>
								<br />
								Проживает <?=$areas[$data['main']['arts'][$one]['area']];?>
								<? if ($data['main']['arts'][$one]['translation']) { ?>
									<br /><b>Есть переводы!</b>
								<? } ?>
							</td>
						<?
					}
					?>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<form method="post">
								<select name="action">
									<option value="delete|AB">
										Удалить первую картинку
									</option>
									<option value="delete|BA">
										Удалить вторую картинку
									</option>
									<option value="move_meta|AB">
										Копировать теги/категории из первой во вторую
									</option>
									<option value="move_meta|BA">
										Копировать теги/категории из второй в первую
									</option>
									<option value="make_similar|AB">
										Прикрепить первую ко второй
									</option>
									<option value="make_similar|BA">
										Прикрепить вторую к первой
									</option>
									<option value="nondublicates|AB">
										Это не дубликаты
									</option>
								</select>
								<br /><br />
								<input type="hidden" name="A" value="<?=reset($double);?>">
								<input type="hidden" name="B" value="<?=end($double);?>">
								<input type="hidden" name="do" value="admin.similar">
								<input type="submit" value="Выполнить">
							</form>
						</td>
					</tr>
				</table>
			<?		
		}
	}
?>
