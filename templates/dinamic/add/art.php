<script type="text/javascript" src="<?=$def['site']['dir']?>/jss/m/?b=jss&f=ajaxupload.js,add/common.js,add/art.js"></script>
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
					<table>
						<tr>
							<td>
								<img src="<?=$def['site']['dir']?>/images/upload_button.png" id="art-image">
							</td>
							<td>
								<img class="processing" src="<?=$def['site']['dir']?>/images/ajax-processing.gif" />
							</td>
							<td>
								<span class="processing">Изображение загружается. (Вы можете загружать несколько изображений одновременно.)</span>
							</td>
							<td class="cancel-holder">
								<span class="processing">Отменить <img class="art_upload_stop" src="<?=$def['site']['dir']?>/images/cancel.png"></span>
							</td>
						</tr>
					</table>
				</td>
			</tr>			
			<tr>
				<td colspan="2" id="error">
				
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
				<td class="inputdata">
					<input size="65%" name="tags" value="" type="text">
				</td>
			</tr>
			<tr>
				<td colspan="2">
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
					<input size="35%" name="author" value="<?=$sets['user']['name'];?>" type="text" class="username">
				</td>
			</tr>			
			<tr>
	            <td class="input field_name">
					<input class="submit" value="Добавить" type="submit">
					<input type="hidden" name="do" value="art.add" />
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
