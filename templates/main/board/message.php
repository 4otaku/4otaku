<div class="message" id="board-<?=$post['id'];?>">	
	<span class="link_last">
		<a href="<?=$def['site']['dir'];?><?=($url[3] != 'thread' ? '/board/'.$thread['current_board'].'/thread/'.$id.'#reply-'.$post['id'] : "javascript:add_text('>>".$post['id']."')");?>">
			Ответить
		</a>
	</span>
	<span class="link_delete">
		<? if ($post['cookie'] && query::$cookie === $post['cookie']) { ?>
			 <img src="<?=$def['site']['dir']?>/images/comment_delete.png" alt="Удалить" rel="<?=$post['id'];?>" class="delete_from_board">
		<? } ?>
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
		<a href="<?=$def['site']['dir'];?><?='/board/'.$thread['current_board'].'/thread/'.$id.'/#board-'.$post['id'];?>" class="number_link">
			#<?=$post['id'];?>
		</a>
	</span>
	<span class="date">
		<?=$post['pretty_date'];?>
	</span>
	<div class="tbody<?=(empty($post['multi_content']) ? '' : ' multi');?>">
		<? if (!empty($post['content']['video'])) { 
			foreach ($post['content']['video'] as $video_key => $video) { ?>
				<? if (!sets::board('embedvideo')) { ?>
					<div class="video video_frame" style="height:<?=$video['height'];?>px;">
						<br />
						<input type="button" class="open_video margin10" rel="<?=$post['id'].'-'.$video_key;?>" value="Показать видео">
						<br />
						<input type="button" class="always_embed_video" value="Всегда показывать">
					</div>
				<? } else { ?>
					<div class="video">
						<?=$video['object'];?>
					</div>
				<? } ?>			
		<? } } 		
		if (!empty($post['content']['image'])) { 
			foreach ($post['content']['image'] as $image) { ?>
				<a href="<?=$def['site']['dir']?>/images/board/full/<?=$image['full'];?>" 
					target="_blank" class="board_image_thumb board_image_thumb_clickable with_help" 
					title="<?=$image['full_size_info'];?>" 
					rel="<?=$image['sizes'];?>">
					<img 
						src="<?=$def['site']['dir']?>/images/board/thumbs/<?=$image['thumb'];?>" 
						rel="/images/board/full/<?=$image['full'];?>"
					>
				</a>
		<? } }
		if (!empty($post['content']['random'])) { 
			foreach ($post['content']['random'] as $image) { ?>
				<a href="<?=$def['site']['dir']?>/art/<?=$image['id'];?>/" 
					title="Арт №<?=$image['id'];?>; <?=$image['full_size_info'];?>" 
					target="_blank" class="board_image_thumb with_help art_random">
					<img src="<?=$def['site']['dir']?>/images/board/thumbs/<?=$image['thumb'];?>.jpg" />
					<img src="<?=$def['site']['dir']?>/images/dice-small.png" class="dice" />
				</a>
		<? } }		
		if (!empty($post['content']['flash'])) { 
			foreach ($post['content']['flash'] as $flash) { ?>
				<a href="<?=$def['site']['dir']?>/images/board/full/<?=$flash['full'];?>" 
					target="_blank" class="board_flash with_help" 
					title="<?=$flash['full_size_info'];?>">
					<img align="left" src="<?=$def['site']['dir']?>/images/flash.png">
				</a>				
		<? } } ?>
		<? if (!empty($post['multi_content'])) { ?>
			<div class="clear"></div>
		<? } ?>			
		<div class="posttext">
			<? if ($url[3] != 'thread') { ?>
				<?=obj::transform('text')->cut_long_text($post['text'],800,$read_all,100);?>
			<? } else { ?>
				<?=$post['text'];?>
			<? } ?>
		</div>
	</div>
</div>
