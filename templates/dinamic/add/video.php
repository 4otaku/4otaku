<script type="text/javascript" src="<?=$def['site']['dir']?>/jss/m/?b=jss&f=add/common.js,add/text.js"></script>
<form id="addform" method="post" enctype="multipart/form-data">
	<div class="fields">
		<span>
			Обязательные поля
		</span>
	</div>
	<table width="100%" id="main_fields">
		<tbody>
			<tr>
				<td class="input field_name">
					Заголовок
				</td>
				<td class="inputdata">
					<input size="65%" name="title" value="" type="text">
				</td>
			</tr>
			<tr>
				<td class="input field_name">
					Ссылка на видео
				</td>
				<td class="inputdata">
					<span class="link">
						<input size="65%" name="link" value="" type="text">
					</span>
				</td>
			</tr>
        </tbody>
    </table>
	<div class="fields">
		<span>
			Дополнительные поля
		</span>
	</div>
	<table width="100%" id="main_fields">
		<tbody>
			<tr>
				<td class="input field_name">
					Описание
				</td>
				<td class="inputdata">
					<textarea name="description" cols="70" rows="6" id="textfield" class="left"></textarea>
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
					Теги
				</td>
				<td class="inputdata">
					<? if (query::$cookie != '4e1d95d25d3bbc34f64b021c9db906cf') { ?>
						<input size="65%" name="tags" value="" type="text">
					<? } else { ?>
						<div id="add_tags">
							<div class="tags-loader">
								<img src="/images/ajax-loader.gif" />
							</div>
<<<<<<< HEAD
							<select data-placeholder="Проставьте теги"
								 multiple tabindex="3"
=======
							<select data-placeholder=" "
								 multiple
>>>>>>> f41a72cee016a8d55d042e2fe3df09d2f31ef9dd
								 id="chozen"
								 name="tags[]">
							</select>
						</div>
					<? } ?>
				</td>
			</tr>
			<tr>
				<td class="input field_name">
					Ваш ник
				</td>
				<td class="inputdata">
					<input size="35%" name="user" value="<?=$sets['user']['name'];?>" type="text">
				</td>
			</tr>
			<tr>
	            <td class="input field_name">
					<input class="submit" value="Добавить" type="submit">
					<input type="hidden" name="do" value="video.add" />
					<input type="hidden" name="remember" value="true" />
				</td>
				<td class="inputdata">
					&nbsp;
					<?=($sets['user']['rights'] ? '<input type="checkbox" name="transfer_to_main" /> Сразу на главную' : '');?>
				</td>
			</tr>
        </tbody>
    </table>
</form>
