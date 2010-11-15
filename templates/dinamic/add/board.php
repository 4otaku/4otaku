<script type="text/javascript" src="/jss/m/?b=jss&f=ajaxupload.js,add/common.js,add/text.js,add/board.js"></script>
<form id="addform" method="post" enctype="multipart/form-data">
	<table width="100%">		
		<tbody>
			<tr>
				<td class="input field_name">
					Текст
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
			<? if (!is_numeric($get['info'])) { ?>
				<tr>
					<td class="input field_name">
						Доска
					</td>
					<td class="inputdata">
						<select name="category[]" class="left">
							<? 
								foreach($data['category'] as $alias => $name) {
									?>
										<option value="<?=$alias;?>"<?=($alias == $get['info'] ? ' class="selected"' : '');?>>
											<?=$name;?>
										</option>
									<?
								}
							?>
						</select>
						<input type="submit" class="disabled sign add_meta" value="+" />
						<input type="submit" class="disabled hidden sign remove_meta" value="-" />
					</td>
				</tr>
			<? } else { ?>
				<input type="hidden" name="id" value="<?=$get['info'];?>" />
			<? } ?>				
			<tr>
				<td class="input field_name">
					Ссылка на видео
				</td>
				<td class="inputdata">
					<input size="65%" name="video" value="" type="text">
				</td>
			</tr>				
			<tr>
				<td class="input field_name">
					Загрузить изображение
				</td>
				<td class="inputdata">
					<table>
						<tr>
							<td>
								<img src="/images/upload_button.png" id="board-image">
							</td>
							<td>
								<img class="processing" src="/images/ajax-processing.gif" />
							</td>
							<td>
								<span class="processing">Изображение загружается.</span>
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
			<tr id="transparent" class="board_images">
				<td colspan="2">
					
				</td>
			</tr>	
			<tr>
				<td class="input field_name">
					Ваш ник
				</td>
				<td class="inputdata">
					<input size="35%" name="user" value="<?=$sets['user']['name'];?>" type="text" class="username">
				</td>
			</tr>			
			<tr>
	            <td class="input field_name">
					<input class="submit" value="Добавить" type="submit">
					<input type="hidden" name="do" value="board.add" />
					<input type="hidden" name="remember" value="true" />					
				</td>
				<td class="inputdata">
					&nbsp;
				</td>
			</tr>		  
        </tbody>		
    </table>
</form>
