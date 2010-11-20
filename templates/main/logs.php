<div class="logs-menu">
	<?
		foreach ($data['main']['logs']['menu']['lines'] as $key => $line) {
			?>
				<span>
					<?=$data['main']['logs']['menu']['row'][$key];?>:
				</span>
				<div>
					<ul>
						<?
							foreach ($line as $one) {
								?>
									<li<?=($one[$key] != $logs_url[$key] ? 
										'><a href="'.SITE_DIR.'/logs/'.($key == 'year' ? $one[$key] : $logs_url['year']).'/'.($key == 'month' ? $one[$key] : $logs_url['month']).'/'.($key == 'day' ? $one[$key] : $logs_url['day']).'/">'.($key == 'month' ? str_replace($eng_month,$ru_month,$one[$key]) : $one[$key]).'</a>' : ' class="logs-menu-inactive">'.($key == 'month' ? str_replace($eng_month,$ru_month,$one[$key]) 
										: $one[$key]));?>
									</li>
								<?
							}
						?>
					</ul>
				</div>
				<br />
			<?	
		}
	?>
</div>
<div id="logs">
	<?
		if (is_array($data['main']['logs']['logs'])) foreach ($data['main']['logs']['logs'] as $log) {
			?>
				<span class="logs-time">
					[<?=date('G:i:s',$log['time']/1000);?>]
				</span> 
				<?=$log['text'];?>
				<br />
			<?
		}
		else echo $data['main']['logs']['logs'];
	?>
</div>
