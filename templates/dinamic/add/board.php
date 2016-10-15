<script type="text/javascript" src="<?=$def['site']['dir']?>/jss/m/?b=jss&f=fileupload.js,add/common.js,add/text.js,add/board.js,box.js"></script>
<br />Борда работает в режиме для чтения.
<? /* ?>
<form id="addform" method="post" action="/board/add/thread/<?=query::$get['info'];?>" enctype="multipart/form-data">
	<table width="100%">
		<tbody class="link_video">
			<tr>
				<td class="input">
					<span class="field_name right">
						Текст
					</span>
					<br />
					<span class="right">
						Форматирование с помощью
					</span>
					<br />
					<span class="right">
						<a href="<?=$def['site']['dir']?>/ajax.php?m=box&f=wakaba&width=700&height=380" title="Справка по Wakaba Mark" class="thickbox">Wakaba Mark</a>
					</span>
				</td>
				<td class="inputdata">
					<textarea name="text" cols="70" rows="8" id="textfield" class="left"></textarea>
				</td>
			</tr>
			<? if (!is_numeric(query::$get['info'])) { ?>
				<tr>
					<td class="input field_name">
						Доска
					</td>
					<td class="inputdata">
						<select name="category[]" class="left">
							<?
								foreach($data['category'] as $alias => $name) {
									?>
										<option value="<?=$alias;?>"<?=($alias == query::$get['info'] ? ' class="selected"' : '');?>>
											<?=$name;?>
										</option>
									<?
								}
							?>
						</select>
						<input type="submit" class="disabled sign add_meta" value="+" />
						<input type="submit" class="disabled hidden sign remove_meta" value="-" />
					</td>
				</tr>
			<? } else { ?>
				<input type="hidden" name="id" class="thread_id" value="<?=query::$get['info'];?>" />
			<? } ?>
			<tr class="link" rel="0">
				<td class="input field_name">
					Ссылка на видео
				</td>
				<td class="inputdata">
					<input size="55%" name="video[]" value="" type="text" class="input_link" />
					<input type="submit" class="disabled sign remove_link" rel="video" value="-" />
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td class="input field_name">
					Добавить еще видео
				</td>
				<td class="inputdata">
					<input type="submit" class="disabled add_link" rel="video" value="+" />
				</td>
			</tr>
			<tr>
				<td class="input field_name">
					Загрузить изображение или flash
				</td>
				<td class="inputdata">
					<div class="right">
						<? if (is_numeric(query::$get['info'])) { ?>
							<input class="disabled add_random" value="Добавить пикрандом" type="submit" />
						<? } ?>
					</div>
					<table>
						<tr>
							<td>
								<div id="board-image"></div>
							</td>
							<td>
								<img class="processing" src="<?=$def['site']['dir']?>/images/ajax-processing.gif" />
							</td>
							<td>
								<span class="processing">Изображение/flash загружается.</span>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" id="error">

				</td>
			</tr>
			<tr id="transparent" class="board_images">
				<td colspan="2">

				</td>
			</tr>
			<tr>
				<td class="input field_name">
					Ваш ник
				</td>
				<td class="inputdata">
					<input size="35%" name="user" value="<?=$sets['user']['name'].($sets['user']['trip'] ? '#'.$sets['user']['trip'] : '');?>" type="text" class="username">
				</td>
			</tr>
			<tr>
	            <td class="input field_name">
					<input class="submit" value="Добавить" type="submit">
					<input type="hidden" name="do" value="board.add" />
					<input type="hidden" name="remember" value="true" />
				</td>
				<td class="inputdata">
					&nbsp;
				</td>
			</tr>
        </tfoot>
    </table>
</form>
<? */ ?>