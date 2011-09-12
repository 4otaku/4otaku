<div class="shell">
	<h2>
		Введите заголовок и описание.
	</h2>
	<form enctype="multipart/form-data" method="post">
		<input type="hidden" name="do" value="admin.pack_edit">
		<input type="hidden" name="id" value="<?=$url[4];?>">
		Заголовок: <input type="text" name="name" size="60" value="<?=$data['main']['gal']['title'];?>">
		<br /><br />
		<table>
				<tr>
			<td>
				<? $data['main']['gal']['pretty_text'] = str_replace("\n", "<!--br-->", $data['main']['gal']['pretty_text']);?>
				<textarea id="textfield" name="text" rows="10" cols="100"><?=$data['main']['gal']['pretty_text'];?></textarea>		
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

		<input type="submit" value="Сохранить">
	</form>	
</div>
