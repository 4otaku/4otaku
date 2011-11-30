<script type="text/javascript" src="<?=$def['site']['dir']?>/jss/m/?b=jss&f=fileupload.js,add/common.js,add/art.js"></script>
<form id="addform" method="post" enctype="multipart/form-data">
	<div class="fields">
		<span>
			Обязательные поля
		</span>
	</div>
	<table width="100%">
		<tbody>
			<tr>
				<td class="input field_name">
					Загрузить изображения
				</td>
				<td class="inputdata">
					<table width="100%">
						<tr>
							<td>
								<div id="art-image"></div>
							</td>
							<td>
								<img class="processing" src="<?=$def['site']['dir']?>/images/ajax-processing.gif" />
							</td>
							<td>
								<span class="processing">Изображение загружается. (Вы можете загружать несколько изображений одновременно.)</span>
							</td>
							<td>
								<div class="hidden as_variations right">
									<input type="submit" class="disabled" value="Объединить" />
								</div>
							</td>
						</tr>
					</table>					
				</td>
			</tr>
			<tr>
				<td colspan="2" id="error">

				</td>
			</tr>
			<tr>
				<td colspan="2" id="is_dublicates">

				</td>
			</tr>
			<tr id="transparent" class="art_images">
				<td colspan="2">

				</td>
			</tr>
			<tr>
				<td class="input field_name">
					Теги
				</td>
				<td class="inputdata input_tags">
					<? if (!sets::edit('newtags')) { ?>
						<input size="65%" name="tags" value="" type="text">
					<? } else { ?>
						<div id="add_tags">
							<div class="tags-loader">
								<img src="/images/ajax-loader.gif" />
							</div>
							<select data-placeholder=" "
								 multiple
								 id="chozen"
								 name="tags[]">
							</select>
						</div>
					<? } ?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<a href="http://wiki.4otaku.ru/%D0%94%D0%BE%D0%B1%D0%B0%D0%B2%D0%BB%D0%B5%D0%BD%D0%B8%D0%B5_%D0%B0%D1%80%D1%82%D0%B0" target="_blank">
						Справка по добавлению арта
					</a>.
					 Для утверждения арта модератором необходимо минимум 5 тегов.
					 <a href="http://wiki.4otaku.ru/%D0%98%D0%BD%D1%81%D1%82%D1%80%D1%83%D0%BA%D1%86%D0%B8%D1%8F_%D0%BF%D0%BE_%D1%82%D0%B5%D0%B3%D0%B0%D0%BC#.D0.94.D0.BB.D1.8F_.D0.BF.D0.BE.D0.BB.D1.8C.D0.B7.D0.BE.D0.B2.D0.B0.D1.82.D0.B5.D0.BB.D0.B5.D0.B9" target="_blank">
						Инструкция по тегам
					</a>.
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
					Источник
				</td>
				<td class="inputdata">
					<input size="65%" name="source" value="" type="text">
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
					<input size="35%" name="author" value="<?=str_replace(' ', '_', $sets['user']['name']);?>" type="text" class="username">
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
					<? if (!empty($sets['user']['rights'])) { ?>
						<input type="radio" name="transfer_to" value="main" /> Сразу на главную
						<input type="radio" name="transfer_to" value="sprites" /> Сразу в спрайты
						<input type="radio" name="transfer_to" value="" checked="checked" /> Как обычно
					<? } ?>
				</td>
			</tr>
        </tbody>
    </table>
</form>
