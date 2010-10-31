<div class="margin20 center">
	 : 
	<?
		foreach ($data['main']['month'] as $key => $month) {
			if ($key != 'current') {
				?>
					<a href="<?=$key;?>">
						<?=$month;?>
					</a> : 
				<?
			}
			else {
				?>
					<?=$month;?> : 
				<?
			}
		}
	?>
</div>	
<div class="margin20 center">
<?	if ($data['main']['navi']['yesterday']) echo '<= ';
	$i = 0;
	foreach ($data['main']['navi'] as $key => $day) { 
		?>
			<? 
				if ($i) {
					?>
						 : 
					<?
				}
				if ($day['url']) {
					?>
						<a href="<?=$day['url'];?>">
							<?=$day['name'];?>
						</a>
					<?
				}
				else {
					?>
						<?=$day['name'];?>
					<?
				}
			?>					
		<?
	$i++; }
	if ($data['main']['navi']['tomorrow']) echo ' =>';
?>
</div>
