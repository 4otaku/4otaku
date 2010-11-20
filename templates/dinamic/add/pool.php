<script type="text/javascript" src="/jss/m/?b=jss&f=add/common.js,add/text.js"></script>
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
					Название
				</td>
				<td class="inputdata">
					<input size="65%" name="name" value="" type="text">
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
							<td><img src="/images/bb/bold.png" rel="b" class="bb" title="Выделить жирным" /></td>
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
					Пароль (задав его вы сделаете группу закрытой)
				</td>
				<td class="inputdata">
					<input size="45%" name="password" value="" type="text">
				</td>
			</tr>
			<tr>
				<td class="input field_name">
					Е-мейл (для восстановления пароля)
				</td>
				<td class="inputdata">
					<input size="45%" name="email" value="" type="text">
				</td>
			</tr>						
			<tr>
	            <td class="input field_name">
					<input class="submit" value="Добавить" type="submit">
					<input type="hidden" name="do" value="art.addpool" />
					<input type="hidden" name="remember" value="true" />					
				</td>
				<td class="inputdata">
					&nbsp;
				</td>
			</tr>		  
        </tbody>		
    </table>
</form>
