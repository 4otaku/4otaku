<div class="car-container car-collapse">
	<a href="#" class="car-toggler" rel="closed">
		Развернуть все
	</a>
	<ul class="car-list">
		<br />
		Всего <?=$data['main']['count'];?> на 4отаку.
		<br /><br />
		<?	$i = 0;
			foreach ($data['main']['archives'] as $key => $type) { $i++
				?>
					<li>
						<img src="<?=SITE_DIR.'/images'?>/tb2.gif">
						&nbsp;
						<span>
							<?=$data['main']['name'][$key];?>:  
							<a href="<?=SITE_DIR.'/art'?>/<?=$url[3];?>/<?=$key;?>/">
								<?=$type;?> артов
							</a>.
						</span>
					</li>
				<?			
			}
