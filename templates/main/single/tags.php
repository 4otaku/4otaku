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

	if (engine::have_tag_variants($item['meta']['tag'])) {
		?>
			 <span class="synonims">
				<a href="#" class="disabled" title="Показать синонимы" rel="<?=$item['id'];?>">
					&gt;&gt;
				</a>
			</span>
		<?
	}

	if ($total = count($item['meta']['tag'])) {
		$i = 0;
		foreach ($item['meta']['tag'] as $key => $meta) {
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
			} ?>
			 <a href="<?=$data['main']['navi']['base'];?>tag/<?=$key;?>/">
				<?=$meta['name'];?>
			</a>
			<? if (++$i != $total) { ?>
				,
			 <? } ?>
			<span class="hidden tag_synonims tag_synonims_<?=$item['id'];?>">
				<? if ($meta_total = count($meta['variants'])) {
					if ($i == $total) { ?>, <? }
					$j = 0;
					foreach ($meta['variants'] as $synonim) {
						?>
							 <a href="<?=$data['main']['navi']['base'];?>tag/<?=urlencode($synonim);?>">
								<?=$synonim;?>
							</a>
							<? if (++$j != $meta_total) { ?>
								,
							 <? } ?>
						<?
					}
				} ?>
				&nbsp;
			</span>
		<?	}
	}
?>
