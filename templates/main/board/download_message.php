<div class="message" id="board-<?=$post['id'];?>">	
	<span class="link_last">
		<a href="http://<?=def::site('domain');?>/board/<?=$thread['current_board'];?>/thread/<?=$id;?>#reply-<?=$post['id'];?>">
			Ответить
		</a>
	</span>
	<span class="author">
		<?=$post['name'];?>
	</span>
	<? if (!empty($post['trip'])) { ?>
		<span class="trip">
			<?=$post['trip'];?>
		</span>
	<? } ?>
	<span class="number">
		<a href="http://<?=def::site('domain');?>/board/<?=$thread['current_board'];?>/thread/<?=$id;?>#reply-<?=$post['id'];?>" class="number_link">
			#<?=$post['id'];?>
		</a>
	</span>
	<span class="date">
		<?=$post['pretty_date'];?>
	</span>
	<div class="tbody">
		<? if (isset($post['content']['image'][0])) { ?>
			<a href="http://<?=def::site('domain');?><?=$def['site']['dir'];?>/images/board/full/<?=$post['content']['image'][0]['full'];?>">
				<img align="left" src="<?=$def['site']['dir']?>/images/board/thumbs/<?=$post['content']['image'][0]['thumb'];?>">
			</a>
		<? } elseif (isset($post['content']['flash'])) { ?>
			<a href="http://<?=def::site('domain');?><?=$def['site']['dir'];?>/images/board/full/<?=$post['content']['flash']['full'];?>">
				<img align="left" src="<?=$def['site']['dir']?>/images/flash.png">
			</a>		
		<? } elseif (isset($post['content']['video'])) { ?>
			<a href="<?=$post['content']['video']['link'];?>">
				<img align="left" src="<?=$def['site']['dir'];?>/images/videolink.png">
			</a>	
		<? } ?>
		<div class="posttext">
			<?=$post['text'];?>
		</div>
	</div>
</div>
