<div class="center margin20">
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
