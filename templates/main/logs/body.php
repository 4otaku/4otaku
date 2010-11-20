<div id="logs" class="margin20">
	<?	$key = 'even';
		if (is_array($data['main']['logs'])) foreach ($data['main']['logs'] as $log) {
			if ($key == 'even') $key = 'odd'; else $key = 'even';
			?>
				<div class="logs-<?=$key;?>" id="time-<?=date('G:i:s',$log['logTime']/1000).'.'.($log['logTime']%1000);?>">
					<a href="/logs/<?=$url[2].'/'.$url[3].'/'.$url[4].'#time-'.date('G:i:s',$log['logTime']/1000).'.'.($log['logTime']%1000);?>">
						<span class="logs-time">
							[<?=date('G:i:s',$log['logTime']/1000);?>]
						</span>
					</a> 
					<?=$log['text'];?>
				</div>
			<?
		}
		elseif ($data['main']['logs']) echo $data['main']['logs'];
		else {
			?>
				<h2 class="center"><?=$data['main']['nologs'];?></h2>
			<?
		}
	?>
</div>
