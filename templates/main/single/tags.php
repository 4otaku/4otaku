<?
	/*Требует массива $item['meta']['tag'] и доступ к $url[1]. Используется в single/[video.php,post.php,art.php] */
	if (count($item['meta']['tag']) > 1) {
		?>
			Теги: 
		<?
	}
	else {
		?>
			Тег: 
		<?
	}

	$synonims = array();								
	foreach ($item['meta']['tag'] as $key => $meta) {
		if (!empty($meta['variants'])) $synonims = array_merge($synonims,$meta['variants']);	
		if ($nonfirst) {
			?>
			, 
			<?
		}	else $nonfirst = true;
		?>
		<?
			if ($url[1] == 'post' || $url[1] == 'video') {
				if (!is_numeric($url[2]) && $url[1] != 'search') {
					?>										
						<a href="<?=$output->mixed_add($key,'tag');?>">
							+
						</a> 
						<a href="<?=$output->mixed_add($key,'tag','-');?>">
							-
						</a> 	
					<?
				}
			}
		?>
			<a href="<?=$data['main']['navi']['base'];?>tag/<?=$key;?>/">
				<?=$meta['name'];?>
			</a>
		<?
	}	unset($nonfirst);
	
	if (!empty($synonims)) {
		?>
		,
			<span class="synonims">
				<a href="#" class="disabled" title="Показать синонимы">
					&gt;&gt;
				</a>
				<span class="hidden">
					<?
						foreach ($synonims as $one) {
							if ($nonfirst) 
							{
								?>
								, 
								<?
							}	
							else 
							{
								$nonfirst = true;
								echo " ";
							}
							?>
								<a href="/<?=$url[1];?>/tag/<?=urlencode($one);?>">
									<?=$one;?>
								</a>
							<?
						}	unset($nonfirst);
					?>
				</span>
			</span>												
		<?
	}								
?>
