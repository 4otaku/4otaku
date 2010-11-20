<div class="cats">	
	<h2>
		<a href="<?=SITE_DIR.'/post'?>/updates/">
			Обновления
		</a>
		 <a href="#" class="bar_arrow" rel="update">
			<? if ($sets['dir']['update']) { ?>
				<img src="<?=SITE_DIR.'/images'?>/text2391.png">
			<? } else { ?>
				<img src="<?=SITE_DIR.'/images'?>/text2387.png">
			<? } ?>
		</a>
	</h2>
	<div id="update_bar"<?=($sets['dir']['update'] ? '' : ' class="hidden"');?>>
		<? if ($data['sidebar']['update']['text']) { ?>
			<?=$data['sidebar']['update']['author'];?>: 
			<?=$data['sidebar']['update']['text'];?>
			<br />
			<a href="<?=SITE_DIR.'/post'?>/<?=$data['sidebar']['update']['post_id'];?>/show_updates/">
				<?=$data['sidebar']['update']['post_title'];?>
			</a>
			<br />
		<? } ?>
	</div>
</div>
