<div class="shell" id="mainpart">
	<h2>
		Заливка нового архива (zip, 80 мегабайт макс.)
	</h2>
	<table>
		<? if (query::$post['name']) { ?>
			<tr>
				<td align="center">
					<input class="upload_name" type="hidden" value="<?=urlencode(query::$post['name']);?>" />
					<input class="upload_text" type="hidden" value="<?=urlencode(query::$post['text']);?>" />
					<script type="text/javascript" src="<?=$def['site']['dir']?>/jss/m/?b=jss&f=fileupload.js,add/pack.js"></script>
					<div id="upload"></div>
				</td>
				<td>
					<img class="processing" src="/images/ajax-processing.gif" />
				</td>
				<td>
					<span class="processing">Архив загружается.</span>
				</td>
			</tr>
			<tr>
				<td colspan="3" id="error">

				</td>
			</tr>
		<? } else { ?>
		<tr>
			<td colspan="3">
				<form enctype="multipart/form-data" method="post">
					<table class="text">
						<tr>
							<td colspan="2">
								Заголовок: <input type="text" name="name" size="60" value="" class="name">
							</td>
						</tr>
						<tr>
							<td>
								<textarea id="textfield" name="text" rows="10" cols="100" class="text"></textarea>
							</td>
							<td valign="top">
								<table cellspacing="3px" class="bbholder">
									<tr>
										<td>
											<img src="/images/bb/bold.png" rel="b" class="bb" title="Выделить жирным" />
										</td>
									</tr>
									<tr>
										<td>
											<img src="/images/bb/italic.png" rel="i" class="bb" title="Выделить курсивом" />
										</td>
									</tr>
									<tr>
										<td>
											<img src="/images/bb/strike.png" rel="s" class="bb" title="Зачеркнутый текст" />
										</td>
									</tr>
									<tr>
										<td>
											<img src="/images/bb/picture.png" rel="img" class="bb" title="Добавить картинку" />
										</td>
									</tr>
									<tr>
										<td>
											<img src="/images/bb/link.png" rel="url" class="bb" title="Добавить ссылку" />
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					<input type="submit" value="Перейти к заливке">
				</form>
			</td>
		</tr>
		<? } ?>
	</table>
</div>
