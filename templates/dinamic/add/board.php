<script type="text/javascript" src="/jss/m/?b=jss&f=ajaxupload.js,add/common.js,add/text.js,add/board.js,box.js"></script>
<form id="addform" method="post" action="/board/add/thread/<?=$get['info'];?>" enctype="multipart/form-data">
	<table width="100%">		
		<tbody>
			<tr>
				<td class="input">
					<span class="field_name right">
						Текст
					</span>
					<br />
					<span class="right">
						Форматирование с помощью 
					</span>
					<br />
					<span class="right">
						<a href="/ajax.php?m=box&f=wakaba&width=700&height=380" title="Справка по Wakaba Mark" class="thickbox">Wakaba Mark</a>
					</span>						
				</td>
				<td class="inputdata">
					<textarea name="text" cols="70" rows="8" id="textfield" class="left"></textarea>
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
