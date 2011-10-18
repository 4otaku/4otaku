<script type="text/javascript" src="<?=$def['site']['dir']?>/jss/m/?b=jss&f=fileupload.js,add/common.js,add/text.js,add/post.js"></script>
<form id="addform" method="post" enctype="multipart/form-data">
	<div class="fields">
		<span>
			Обязательные поля
		</span>
	</div>
	<table width="100%">
		<tbody class="link_main">
			<tr>
				<td class="input field_name">
					Название
				</td>
				<td class="inputdata">
					<input size="65%" name="title" value="" type="text">
				</td>
			</tr>
			<tr class="link" rel="0">
				<td class="input field_name">
					Ссылка
				</td>
				<td class="inputdata">
					<input size="12%" type="text" name="link[0][name]" value="Скачать" />:
					<input size="36%" type="text" name="link[0][link]" value="http://" />
					~(<input size="2%" type="text" name="link[0][size]" value="" />
					<select name="link[0][sizetype]">
						<option value="кб">кб</option>
						<option value="мб" selected>мб</option>
						<option value="гб">гб</option>
					</select>
					)
					<input type="submit" class="disabled sign remove_link" rel="main" value="-" />
				</td>
			</tr>
        </tbody>
        <tfoot>
			<tr>
				<td class="input field_name">
					Добавить еще ссылку
				</td>
				<td class="inputdata">
					<input type="submit" class="disabled add_link" rel="main" value="+" />
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
							<td class="cancel-holder">
								<span class="processing-image">Отменить <img class="image_upload_stop right" src="<?=$def['site']['dir']?>/images/cancel.png"></span>
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
					<textarea name="text" cols="70" rows="8" id="textfield" class="left"></textarea>
					<table cellspacing="3px" class="bbholder">
						<tr>
							<td><img src="<?=$def['site']['dir']?>/images/bb/bold.png" rel="b" class="bb" title="Выделить жирным" /></td>
						</tr>
						<tr>
							<td><img src="<?=$def['site']['dir']?>/images/bb/italic.png" rel="i" class="bb" title="Выделить курсивом" /></td>
						</tr>
						<tr>
							<td><img src="<?=$def['site']['dir']?>/images/bb/strike.png" rel="s" class="bb" title="Зачеркнутый текст" /></td>
						</tr>
						<tr>
							<td><img src="<?=$def['site']['dir']?>/images/bb/spoiler.png" rel="spoiler" class="bb" title="Спойлер" /></td>
						</tr>
						<tr>
							<td><img src="<?=$def['site']['dir']?>/images/bb/picture.png" rel="img" class="bb" title="Добавить картинку" /></td>
						</tr>
						<tr>
							<td><img src="<?=$def['site']['dir']?>/images/bb/link.png" rel="url" class="bb" title="Добавить ссылку" /></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
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
							<td class="cancel-holder">
								<span class="processing-file">Отменить <img class="file_upload_stop right" src="<?=$def['site']['dir']?>/images/cancel.png"></span>
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
					<? if (false) { ?>
						<input size="65%" name="tags" value="" type="text">
					<? } else { ?>
						<div id="add_tags">
							<div class="tags-loader">
								<img src="/images/ajax-loader.gif" />
							</div>
							<select data-placeholder=" "
								 id="chozen"
								 name="tags[]">
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
					<input size="35%" name="author" value="<?=$sets['user']['name'];?>" type="text" class="author">
				</td>
			</tr>
			<tr>
	            <td class="input field_name">
					<input class="submit" value="Добавить" type="submit">
					<input type="hidden" name="do" value="post.add" />
					<input type="hidden" name="remember" value="true" />
				</td>
				<td class="inputdata">
					&nbsp;
					<?=($sets['user']['rights'] ? '<input type="checkbox" name="transfer_to_main" /> Сразу на главную' : '');?>
				</td>
			</tr>
        </tfoot>
    </table>
</form>
