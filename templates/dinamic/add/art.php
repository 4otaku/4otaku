<script type="text/javascript" src="/jss/m/?b=jss&f=ajaxupload.js,add/common.js,add/art.js"></script>
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
								<img src="/images/upload_button.png" id="art-image">
							</td>
							<td>
								<img class="processing" src="/images/ajax-processing.gif" />
							</td>
							<td>
								<span class="processing">Изображение загружается. (Вы можете загружать несколько изображений одновременно.)</span>
							</td>
							<td class="cancel-holder">
								<span class="processing">Отменить <img class="art_upload_stop" src="/images/cancel.png"></span>
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
