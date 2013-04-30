<?
if (!empty($data['main']['days'])) {
	foreach($data['main']['days'] as $day => $logs) { ?>
	<div class="shell">
		<h2><a href="/<?=$data['main']['log_base'];?>/<?=str_replace('-','/',$day);?>/">Логи за <?=$day;?></a></h2>
		<? foreach($logs as $log) { ?>
			[<?=$log['time'];?>] <?=$log['text'];?><br />
		<? } ?>
	</div>
	<?
	}
} else {
	?>
		<div class="clear center">
			<img src="<?=$def['site']['dir']?>/images/search.gif" />
		</div>
	<?
}
