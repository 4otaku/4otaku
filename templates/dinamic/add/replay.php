<script type="text/javascript" src="<?=$def['site']['dir']?>/jss/m/?b=jss&f=ajaxupload.js,add/common.js,add/replay.js"></script>
<form id="addform" method="post" enctype="multipart/form-data">
	<table width="40%">		
		<tbody>
			<tr>
				<td class="input field_name">
					Загрузить реплей
				</td>
				<td class="inputdata">
					<table>
						<tr>
							<td>
								<img src="<?=$def['site']['dir']?>/images/upload_button.png" id="replay_upload">
							</td>
							<td>
								<img class="processing" src="<?=$def['site']['dir']?>/images/ajax-processing.gif" />
							</td>
							<td>
								<span class="processing">Реплей загружается.</span>
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
				<td colspan="2" id="success" style="color:green; font-weight:bold;" class="hidden">
					Реплей загружен. Для сохранения заполните ники игроков и нажмите "Добавить".
				</td>
			</tr>			
			<tr id="transparent" class="hidden">
				<td colspan="2">
					
				</td>
			</tr>				
			<tr>
				<td class="input field_name">
					<nobr>
						Ник первого игрока
					</nobr>
				</td>
				<td class="inputdata">
					<input size="25%" name="nick1" value="" type="text">
				</td>
			</tr>				
			<tr>
				<td class="input field_name">
					<nobr>
						Ник второго игрока
					</nobr>
				</td>
				<td class="inputdata">
					<input size="25%" name="nick2" value="" type="text">
				</td>
			</tr>		
			<tr>
				<td class="input field_name">
					Тип
				</td>
				<td class="inputdata">
					<select name="stage" class="left">
						<option value="4">Прочее</option>
						<option value="3">1/4 финала</option>
						<option value="2">Полуфинал</option>
						<option value="1">Финал</option>
					</select>
				</td>
			</tr>
			<tr>
	            <td class="input field_name">
					<input class="submit" value="Добавить" type="submit">
					<input type="hidden" name="do" value="soku.replay_add" />
					<input type="hidden" name="remember" value="true" />					
				</td>
				<td class="inputdata">
					&nbsp;
				</td>
			</tr>		  
        </tbody>		
    </table>
</form>
