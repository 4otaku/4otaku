<h2>Реплеи:</h2>
<div class="shell">
<?
	$replays = obj::db()->sql('select * from _atai_replays order by id','id');
	
	if ($replays) {
?>

	<table width="100%">
		<thead align="left" valign="top">
<? /*				<th height="30px">
				Номер игры
			</th> */ ?>
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
<? /*				<td>
					<?=$replay['game_number'];?>
				</td> */ ?>
				<td>
					<?=$replay['player1'];?> (<?=$replay['character1'];?>)
				</td>
				<td>
					<?=$replay['player2'];?> (<?=$replay['character2'];?>)
				</td>
				<td>
					<a href="<?=$def['site']['dir']?>download.php?file=/files/post/<?=$replay['file'];?>">
						Скачать
					</a>
				</td>
			</tr>
		<? } ?>
		</tbody>
	</table>
<? } else { ?>
	<b>Пока нет реплеев. Возможно турнир еще не начался, или первые игры еще не сыграны.</b>
<? } ?>
</div>
