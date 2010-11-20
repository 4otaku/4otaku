<script type="text/javascript" src="<?=SITE_DIR?>/jss/m/?b=jss&f=add/common.js,add/text.js"></script>
<form id="addform" method="post" enctype="multipart/form-data">
	<table width="100%">		
		<tbody>
			<tr>
				<td class="input field_name">
					Ваш ник (необязательно)
				</td>
				<td class="inputdata">
					<input size="35%" name="user" value="<?=$sets['user']['name'];?>" type="text">
				</td>
			</tr>
			<tr>
				<td class="input field_name">
					Ваш е-мейл
				</td>
				<td class="inputdata">
					<input size="35%" name="mail" value="<?=($sets['user']['mail'] != "default@avatar.mail" ? $sets['user']['mail'] : "");?>" type="text">
					<div class="right">
						Подписаться на уведомления? 
						<input type="checkbox" name="subscribe" checked>
					</div>
				</td>
			</tr>			
			<tr>
				<td class="input field_name">
					Что ищете?
				</td>
				<td class="inputdata">
					<input size="65%" name="subject" value="" type="text">
				</td>
			</tr>			
			<tr>
				<td class="input field_name">
					Дополнительная информация
				</td>
				<td class="inputdata">
					<textarea name="description" cols="70" rows="6" id="textfield" class="left"></textarea>
					<table cellspacing="3px" class="bbholder">
						<tr>
							<td><img src="<?=SITE_DIR.'/images'?>/bb/bold.png" rel="b" class="bb" title="Выделить жирным" /></td>
						</tr>
						<tr>
							<td><img src="<?=SITE_DIR.'/images'?>/bb/italic.png" rel="i" class="bb" title="Выделить курсивом" /></td>
						</tr>
						<tr>
							<td><img src="<?=SITE_DIR.'/images'?>/bb/strike.png" rel="s" class="bb" title="Зачеркнутый текст" /></td>
						</tr>
						<tr>
							<td><img src="<?=SITE_DIR.'/images'?>/bb/spoiler.png" rel="spoiler" class="bb" title="Спойлер" /></td>
						</tr>								
						<tr>
							<td><img src="<?=SITE_DIR.'/images'?>/bb/picture.png" rel="img" class="bb" title="Добавить картинку" /></td>
						</tr>
						<tr>
							<td><img src="<?=SITE_DIR.'/images'?>/bb/link.png" rel="url" class="bb" title="Добавить ссылку" /></td>
						</tr>
					</table>					
				</td>
			</tr>			
			<tr>
				<td class="input field_name">
					Категория заказа
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
					<input class="submit" value="Добавить" type="submit">
					<input type="hidden" name="do" value="order.add" />
					<input type="hidden" name="remember" value="true" />					
				</td>
				<td class="inputdata">
					&nbsp;
				</td>
			</tr>		  
        </tbody>		
    </table>
</form>
