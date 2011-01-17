<div class="thread" id="board-<?=$id;?>">
	<span class="link_last">
		<a href="http://<?=def::site('domain');?>/board/<?=$thread['current_board'];?>/thread/<?=$id;?>#reply">
			Ответить
		</a>
	</span>
	<span class="author">
		<?=$thread['name'];?>
	</span>
	<? if (!empty($thread['trip'])) { ?>
		<span class="trip">
			<?=$thread['trip'];?>
		</span>
	<? } ?>
	<span class="number">
		<a href="http://<?=def::site('domain');?><?=$def['site']['dir'];?>/board/<?=$thread['current_board'];?>/thread/<?=$id;?>/" class="number_link">
			#<?=$id;?>
		</a>
		 в разделе 
		<? foreach($thread['boards'] as $key => $board) { ?>
			<? if (!empty($key)) { ?>
				/
			<? } ?>
			<a href="http://<?=def::site('domain');?><?=$def['site']['dir'];?>/board/<?=$board;?>/">
				<?=$board;?>
			</a>
		<? } ?>
	</span>
	<span class="date">
		<?=$thread['pretty_date'];?>
	</span>
	<div class="tbody">
		<? if (isset($thread['content']['image'][0])) { ?>
			<a href="http://<?=def::site('domain');?><?=$def['site']['dir'];?>/images/board/full/<?=$thread['content']['image'][0]['full'];?>">
				<img align="left" src="<?=$def['site']['dir'];?>/images/board/thumbs/<?=$thread['content']['image'][0]['thumb'];?>">
			</a>
		<? } elseif (isset($thread['content']['flash'])) { ?>
			<a href="http://<?=def::site('domain');?><?=$def['site']['dir'];?>/images/board/full/<?=$thread['content']['flash']['full'];?>">
				<img align="left" src="<?=$def['site']['dir'];?>/images/flash.png">
			</a>
		<? } elseif (isset($thread['content']['video'])) { ?>
			<a href="<?=$thread['content']['video']['link'];?>">
				<img align="left" src="<?=$def['site']['dir'];?>/images/videolink.png">
			</a>
		<? } ?>
		<div class="posttext">
			<?=$thread['text'];?>
		</div>
	</div>
</div>