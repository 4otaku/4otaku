<?

	global $db;
	$participants = $db->sql('select * from soku order by id');
	
	if ($participants) {
?>

	<table width="100%">
		<thead align="left" valign="top">
			<th width="33%" height="30px">
				Никнейм
			</th>
			<th width="33%" height="30px">
				Первый персонаж
			</th>
			<th width="33%" height="30px">
				Второй персонаж
			</th>
		</thead>
		<tbody>
		<? foreach ($participants as $participant) { ?>
			<tr>
				<td>
					<?=$participant['nickname'];?>
				</td>
				<td>
					<?=$participant['character'];?>
				</td>
				<td>
					<?=$participant['second_character'];?>
				</td>
			</tr>
		<? } ?>
		</tbody>
	</table>
<? } else { ?>
	Пока никто не зарегистрировался. Будете первым?
<? } ?>
