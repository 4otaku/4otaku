<?
	$participants = obj::db()->sql('select * from _atai_players order by id','id');
	$stages = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20);
	$characters = array('Hakurei Reimu','Kirisame Marisa','Alice Margatroid','Shameimaru Aya','Izayoi Sakuya','Konpaku Youmu','Patchouli Knowledge','Saigyouji Yuyuko','Remilia Scarlet','Yakumo Yukari','Ibuki Suika','Reisen Udongein Inaba','Onozuka Komachi','Nagae Iku','Hinanai Tenshi','Kochiya Sanae','Cirno','Hong Meiling','Moriya Suwako','Reiuji Utsuho');
?>
<script type="text/javascript" src="<?=$def['site']['dir']?>/jss/m/?b=jss&f=fileupload.js,add/common.js,add/replay.js"></script>
<form id="addform" method="post" enctype="multipart/form-data">
	<table width="100%">		
		<tbody>
			<tr>
				<td class="input field_name" width="30%">
					Загрузить реплей
				</td>
				<td class="inputdata">
					<table>
						<tr>
							<td>
								<div id="replay_upload"></div>
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
					<select name="nickname1">
						<? foreach ($participants as $participant) { ?>
							<option value="<?=$participant;?>"><?=$participant;?></option>
						<? } ?>
					</select>
				</td>
			</tr>					
			<tr>
				<td class="input field_name">
					<nobr>
						Персонаж первого игрока
					</nobr>
				</td>
				<td class="inputdata">
					<select name="character1">
						<? foreach ($characters as $character) { ?>
							<option value="<?=$character;?>"><?=$character;?></option>
						<? } ?>
					</select>
				</td>
			</tr>			
			<tr>
				<td class="input field_name">
					<nobr>
						Ник второго игрока
					</nobr>
				</td>
				<td class="inputdata">
					<select name="nickname2">
						<? foreach ($participants as $participant) { ?>
							<option value="<?=$participant;?>"><?=$participant;?></option>
						<? } ?>
					</select>
				</td>
			</tr>		
			<tr>
				<td class="input field_name">
					<nobr>
						Персонаж второго игрока
					</nobr>
				</td>
				<td class="inputdata">
					<select name="character2">
						<? foreach ($characters as $character) { ?>
							<option value="<?=$character;?>"><?=$character;?></option>
						<? } ?>
					</select>
				</td>
			</tr>				
			<tr class="hidden">
				<td class="input field_name">
					Номер игры
				</td>
				<td class="inputdata">
					<select name="stage">
						<? foreach ($stages as $stage) { ?>
							<option value="<?=$stage;?>"><?=$stage;?></option>
						<? } ?>
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
