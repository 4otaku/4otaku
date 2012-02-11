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
	<div class="tbody<?=(empty($post['multi_content']) ? '' : ' multi_download');?>">
		<? if (!empty($post['content']['video'])) { 
			foreach ($post['content']['video'] as $video) { ?>
				<a href="<?=$video['link'];?>">
					<img align="left" src="<?=$def['site']['dir'];?>/images/videolink.png">
				</a>
		<? } }
		if (!empty($post['content']['image'])) {
			foreach ($post['content']['image'] as $image) { ?>
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
		if (!empty($post['content']['flash'])) { 
			foreach ($post['content']['flash'] as $flash) { ?>
				<a href="http://<?=def::site('domain');?><?=$def['site']['dir'];?>/images/board/full/<?=$flash['full'];?>">
					<img align="left" src="<?=$def['site']['dir']?>/images/flash.png">
				</a>		
		<? } } ?>
		<div class="posttext">
			<?=$post['text'];?>
		</div>
	</div>
</div>
