<script type="text/javascript" src="<?=$def['site']['dir']?>/jss/m/?b=jss&f=fileupload.js,add/common.js,add/text.js,add/news.js"></script>
<form id="addform" method="post" enctype="multipart/form-data">
	<div class="fields">
		<span>
			Обязательные поля
		</span>
	</div>
	<table width="100%">
		<tr>
			<td class="input field_name">
				Название
			</td>
			<td class="inputdata">
				<input size="65%" name="title" value="" type="text">
			</td>
		</tr>
    </table>
	<div class="fields">
		<span>
			Дополнительные поля
		</span>
	</div>
	<table width="100%">
		<tr>
			<td class="input field_name">
				Загрузить картинку
			</td>
			<td class="inputdata">
				<table>
					<tr>
						<td>
							<div id="news-image"></div>
						</td>
						<td>
							<img class="processing-image" src="/images/ajax-processing.gif" />
						</td>
						<td>
							<span class="processing-image">Изображение загружается.</span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2" id="error">

			</td>
		</tr>
		<tr id="transparent" class="news_images">
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
		</tr>
    </table>
</form>
