<ul class="car-list">
	<? 
		if (is_array($data['main']['pools'])) foreach($data['main']['pools'] as $key => $pool) {
			?>
				<li>
					<a href="<?=SITE_DIR.'/art'?>/pool/<?=$key;?>">
						<?=$pool['name'];?>
					</a>
					 (Изображений: <?=$pool['count'];?>)
				</li>
			<?
		}
	?>
</ul>
