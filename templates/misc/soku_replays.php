<?
	$replays = obj::db()->sql('select * from misc where type = "soku_replay" order by data5','id');
	
	if ($replays) {
?>

	<table width="100%">
		<thead align="left" valign="top">
			<th height="30px">
				Этап
			</th>
			<th height="30px">
				Первый игрок
			</th>
			<th height="30px">
				Второй игрок
			</th>
			<th height="30px">
				&nbsp;
			</th>
		</thead>
		<tbody>
		<? foreach ($replays as $replay) { ?>
			<tr>
				<td>
					<? 
						switch ($replay['data5']) {
							case 1: echo "Финал"; break;
							case 2: echo "Полуфинал"; break;
							case 3: echo "1/4 финала"; break;
							default: echo "Прочее"; break;
						}
					?>
				</td>
				<td>
					<?=$replay['data3'];?>
				</td>
				<td>
					<?=$replay['data4'];?>
				</td>
				<td>
					<a href="<?=$def['site']['dir']?>/files/<?=$replay['data1'];?>/<?=$replay['data2'];?>">
						Скачать
					</a>
				</td>
			</tr>
		<? } ?>
		</tbody>
	</table>
<? } else { ?>
	Пока нет реплеев. Возможно турнир еще не начался, или первые игры еще не сыграны.
<? } ?>
