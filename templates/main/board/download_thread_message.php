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
		<? foreach($thread['categories'] as $key => $board) { ?>
			<? if (!empty($key)) { ?>
				/
			<? } ?>
			<a 
				href="http://<?=def::site('domain');?><?=$def['site']['dir']?>/board/<?=$board['alias'];?>/" 
				class="<?=($board['actual'] ? 'actual' : 'not_actual');?>"
			>
				<?=$board['alias'];?>
			</a>
		<? } ?>
	</span>
	<span class="date">
		<?=$thread['pretty_date'];?>
	</span>
	<div class="tbody<?=(empty($thread['multi_content']) ? '' : ' multi_download');?>">
		<? if (!empty($thread['content']['video'])) { 
			foreach ($thread['content']['video'] as $video) { ?>
				<a href="<?=$video['link'];?>">
					<img align="left" src="<?=$def['site']['dir'];?>/images/videolink.png">
				</a>
		<? } }
		if (!empty($thread['content']['image'])) {
			foreach ($thread['content']['image'] as $image) { ?>
				<a href="http://<?=def::site('domain');?><?=$def['site']['dir'];?>/images/board/full/<?=$image['full'];?>">
					<img align="left" src="<?=$def['site']['dir']?>/images/board/thumbs/<?=$image['thumb'];?>">
				</a>
		<? } }
		if (!empty($post['content']['random'])) {
			foreach ($post['content']['random'] as $image) { ?>
				<a href="http://<?=def::site('domain');?><?=$def['site']['dir'];?>/art/<?=$image['id'];?>/">
					<img align="left" src="<?=$def['site']['dir']?>/images/board/thumbs/<?=$image['thumb'];?>.jpg">
				</a>
		<? } }		
		if (!empty($thread['content']['flash'])) { 
			foreach ($thread['content']['flash'] as $flash) { ?>
				<a href="http://<?=def::site('domain');?><?=$def['site']['dir'];?>/images/board/full/<?=$flash['full'];?>">
					<img align="left" src="<?=$def['site']['dir']?>/images/flash.png">
				</a>		
		<? } } ?>
		<div class="posttext">
			<?=$thread['text'];?>
		</div>
	</div>
</div>
