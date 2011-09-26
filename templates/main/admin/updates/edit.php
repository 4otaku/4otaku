<script type="text/javascript" src="<?=$def['site']['dir']?>/jss/m/?b=jss&f=add/text.js,add/post.js"></script>
<form id="addform" method="post" enctype="multipart/form-data">
	<table width="100%">		
		<tbody class="link_main">
			<tr>
				<td class="input field_name">
					Ваше имя
				</td>
				<td class="inputdata">
					<input size="65%" name="author" value="<?=$data['main']['update']['username'];?>" type="text">
				</td>
			</tr>
			<tr>
				<td class="input field_name">
					Текст
				</td>
				<td class="inputdata">
					<textarea name="text" cols="70" rows="8" id="textfield" class="left">
						<?=$data['main']['update']['pretty_text'];?>
					</textarea>
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
			<? foreach ($data['main']['update']['link'] as $key => $link) {
				if (is_array($link['url'])) foreach ($link['url'] as $key2 => $linkurl) { ?>
					<tr class="link" rel="<?=++$i;?>">
						<td class="input field_name">
							Ссылка
						</td>
						<td class="inputdata">
							<input size="12%" type="text" name="link[<?=$i;?>][name]" value="<?=$link['name'];?>" />: 
							<input size="36%" type="text" name="link[<?=$i;?>][link]" value="<<?=$link['alias'][$key2];?>><?=$linkurl;?>" />
							~(<input size="2%" type="text" name="link[<?=$i;?>][size]" value="<?=$link['size'];?>" /> 
							<select name="link[<?=$i;?>][sizetype]">
								<option value="кб"<?=($link['sizetype'] == 'кб' ? ' selected="selected"' : '');?>>кб</option>
								<option value="мб"<?=($link['sizetype'] == 'мб' ? ' selected="selected"' : '');?>>мб</option>
								<option value="гб"<?=($link['sizetype'] == 'гб' ? ' selected="selected"' : '');?>>гб</option>
							</select>
							)
							<input type="submit" class="disabled sign remove_link" rel="main" value="-" />
						</td>
					</tr>	
				<? } ?>						
			<? } ?>	
        </tbody>
        <tfoot>
			<tr>
				<td class="input field_name">
					Добавить еще ссылку
				</td>
				<td class="inputdata">
					<input type="submit" class="disabled add_link" rel="main" value="+" />
				</td>
			</tr>
		</tfoot>       		
    </table>
    <table width="100%">
		<tr>
			<td class="input field_name" colspan="2">
				<input class="submit left" value="Редактировать обновление" type="submit">
				<input type="hidden" name="do" value="admin.edit_update" />
				<input type="hidden" name="id" value="<?=$data['main']['update']['id'];?>" />
			</td>
		</tr>		
    </table>
</form>