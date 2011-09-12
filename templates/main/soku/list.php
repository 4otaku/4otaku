<div class="shell">
<?
	$participants = obj::db()->sql('select * from _atai_players order by id','id');
	
	if ($participants) {
?>

	<table width="100%">
		<thead align="left" valign="top">
			<th width="100%" colspan="3" height="30px">
				Список зарегистрировавшихся:
			</th>
		</thead>
		<tbody>
		<? 	$i = 0;
			foreach ($participants as $participant) { 
			$i++;
		?>
			<? if ($i==1) { ?><tr><? } ?>
				<td width="33%">
					<?=$participant;?>
				</td>
			<? if ($i==3) { ?></tr><? $i=0;} ?>
		<? } ?>
		</tbody>
	</table>
<? } else { ?>
	Пока никто не зарегистрировался. Будете первым?
<? } ?>
</div>
