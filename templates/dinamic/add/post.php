<script type="text/javascript" src="<?=$def['site']['dir']?>/jss/m/?b=jss&f=fileupload.js,add/common.js,add/text.js,add/post.js"></script>

<h3>Нельзя сотворить здесь.</h3>
<? /* ?>
<form id="addform" method="post" enctype="multipart/form-data">
	<div class="fields">
		<span>
			Обязательные поля
		</span>
	</div>
	<table width="100%">
		<thead class="link_main">
			<tr>
				<td class="input field_name">
					Название
				</td>
				<td class="inputdata">
					<input size="65%" name="title" value="" type="text">
				</td>
			</tr>
			<tr class="link hidden" rel="0">
				<td class="input field_name">
					Ссылка
				</td>
				<td class="inputdata">
					<input size="12%" type="text" name="link[0][name]" value="Скачать" />:
					<input size="36%" type="text" name="link[0][link]" value="http://" />
					~(<input size="2%" type="text" name="link[0][size]" value="" />
					<select name="link[0][sizetype]">
						<option value="0">кб</option>
						<option value="1" selected>мб</option>
						<option value="2">гб</option>
					</select>
					)
					<input type="submit" class="disabled sign remove_link" rel="main" value="-" />
				</td>
			</tr>
		</thead>
		<tbody class="link_torrent">
			<tr>
				<td class="input field_name">
					Добавить ссылку
				</td>
				<td class="inputdata">
					<input type="submit" class="disabled add_link" rel="main" value="+" />
				</td>
			</tr>
			<tr>
				<td class="input field_name">
					Прикрепить торрент
				</td>
				<td class="inputdata">
					<table>
						<tr>
							<td>
								<div id="post-torrent" rel="add"></div>
							</td>
							<td>
								<img
									 class="processing-torrent hidden"
									 src="/images/ajax-processing.gif" />
							</td>
							<td>
								<span
									 class="processing-torrent hidden">
									Торрент загружается. (Вы можете загружать несколько торрентов одновременно.)
								</span>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2">
					Для добавления записи нужна хотя бы одна ссылка или хотя бы один торрент.
					 <a href="http://wiki.4otaku.org/%D0%94%D0%BE%D0%B1%D0%B0%D0%B2%D0%BB%D0%B5%D0%BD%D0%B8%D0%B5_%D0%B7%D0%B0%D0%BF%D0%B8%D1%81%D0%B8" target="_blank">
						Справка по добавлению записи</a>.
				</td>
			</tr>
		</tfoot>
    </table>
	<div class="fields">
		<span>
			Дополнительные поля
		</span>
	</div>
	<table width="100%">
		<thead class="link_file">
			<tr>
				<td class="input field_name">
					Загрузить картинку
				</td>
				<td class="inputdata">
					<table>
						<tr>
							<td>
								<div id="post-image"></div>
							</td>
							<td>
								<img class="processing-image" src="<?=$def['site']['dir']?>/images/ajax-processing.gif" />
							</td>
							<td>
								<span class="processing-image">Изображение загружается. (Вы можете загружать несколько изображений одновременно.)</span>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="2" id="error">

				</td>
			</tr>
			<tr id="transparent" class="post_images">
				<td colspan="2">

				</td>
			</tr>
			<tr>
				<td class="input field_name">
					Описание
				</td>
				<td class="inputdata">
					<table cellspacing="3px" class="bbholder" width="100%">
						<tr>
							<td rowspan="6" width="100%">
								<div class="textarea">
									<textarea name="text" rows="8" id="textfield" class="left"></textarea>
								</div>
							</td>
							<td style="width:22px">
								<img src="/images/bb/bold.png" rel="b" class="bb" title="Выделить жирным" />
							</td>
						</tr>
						<tr>
							<td><img src="/images/bb/italic.png" rel="i" class="bb" title="Выделить курсивом" /></td>
						</tr>
						<tr>
							<td><img src="/images/bb/strike.png" rel="s" class="bb" title="Зачеркнутый текст" /></td>
						</tr>
						<tr>
							<td><img src="/images/bb/spoiler.png" rel="spoiler" class="bb" title="Спойлер" /></td>
						</tr>
						<tr>
							<td><img src="/images/bb/picture.png" rel="img" class="bb" title="Добавить картинку" /></td>
						</tr>
						<tr>
							<td><img src="/images/bb/link.png" rel="url" class="bb" title="Добавить ссылку" /></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr class="after-torrent">
				<td class="input field_name">
					Загрузить файл
				</td>
				<td class="inputdata">
					<table>
						<tr>
							<td>
								<div id="post-file" rel="add"></div>
							</td>
							<td>
								<img class="processing-file" src="<?=$def['site']['dir']?>/images/ajax-processing.gif" />
							</td>
							<td>
								<span class="processing-file">Файл загружается. (Вы можете загружать несколько файлов одновременно.)</span>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</thead>
		<tbody class="link_bonus">
			<tr class="link" rel="0">
				<td class="input field_name">
					Дополнительная ссылка
				</td>
				<td class="inputdata">
					<input size="12%" type="text" name="bonus_link[0][name]" value="" />:
					<input size="36%" type="text" name="bonus_link[0][link]" value="http://" />
					<input type="submit" class="disabled sign remove_link" rel="bonus" value="-" />
				</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td class="input field_name">
					Добавить еще ссылку
				</td>
				<td class="inputdata">
					<input type="submit" class="disabled add_link" rel="bonus" value="+" />
				</td>
			</tr>
			<tr>
				<td class="input field_name">
					Теги
				</td>
				<td class="inputdata">
					<? if (!sets::edit('newtags')) { ?>
						<input size="65%" name="tag" value="" type="text" />
					<? } else { ?>
						<div id="add_tags">
							<div class="tags-loader">
								<img src="/images/ajax-loader.gif" />
							</div>
							<select data-placeholder=" "
								 multiple
								 id="chozen"
								 name="tag[]">
							</select>
						</div>
					<? } ?>
				</td>
			</tr>
			<tr>
				<td class="input field_name">
					Категория
				</td>
				<td class="inputdata">
					<select name="category[]" class="left">
						<?
							foreach($data['category'] as $alias => $name) {
								?>
									<option value="<?=$alias;?>"><?=$name;?></option>
								<?
							}
						?>
					</select>
					<input type="submit" class="disabled sign add_meta" value="+" />
					<input type="submit" class="disabled hidden sign remove_meta" value="-" />
				</td>
			</tr>
			<tr>
				<td class="input field_name">
					Язык
				</td>
				<td class="inputdata">
					<select name="language[]" class="left">
						<?
							foreach($data['language'] as $alias => $name) {
								?>
									<option value="<?=$alias;?>"><?=$name;?></option>
								<?
							}
						?>
					</select>
					<input type="submit" class="disabled sign add_meta" value="+" />
					<input type="submit" class="disabled hidden sign remove_meta" value="-" />
				</td>
			</tr>
			<tr>
				<td class="input field_name">
					Ваш ник
				</td>
				<td class="inputdata">
					<input size="35%" name="author" value="<?=str_replace(' ', '_', $sets['user']['name']);?>" type="text" class="author">
				</td>
			</tr>
			<tr>
	            <td class="input field_name">
					<input class="submit" value="Добавить" type="submit">
					<input type="hidden" name="action" value="Create" />
					<input type="hidden" name="remember" value="true" />
				</td>
				<td class="inputdata">
					&nbsp;
					<?=($sets['user']['rights'] ? '<input type="checkbox" name="transfer_to" value="main" /> Сразу на главную' : '');?>
				</td>
			</tr>
        </tfoot>
    </table>
</form>
<? */ ?>