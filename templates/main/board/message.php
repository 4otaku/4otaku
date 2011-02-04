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
	<div class="tbody">
		<? if (isset($post['content']['image'][0])) { ?>
			<a href="<?=$def['site']['dir']?>/images/board/full/<?=$post['content']['image'][0]['full'];?>" 
				target="_blank" class="board_image_thumb with_help" 
				title="<?=$post['content']['image'][0]['full_size_info'];?>" 
				rel="<?=$post['content']['image'][0]['sizes'];?>">
				<img align="left" src="<?=$def['site']['dir']?>/images/board/thumbs/<?=$post['content']['image'][0]['thumb'];?>" rel="/images/board/full/<?=$post['content']['image'][0]['full'];?>">
			</a>
		<? } elseif (isset($post['content']['flash'])) { ?>
			<a href="<?=$def['site']['dir']?>/images/board/full/<?=$post['content']['flash']['full'];?>" 
				target="_blank" class="with_help" 
				title="<?=$post['content']['flash']['full_size_info'];?>">
				<img align="left" src="<?=$def['site']['dir']?>/images/flash.png">
			</a>		
		<? } elseif (isset($post['content']['video'])) { ?>
			<? if (!sets::board('embedvideo')) { ?>
				<div class="video" style="height:<?=$post['content']['video']['height'];?>px;">
					<br />
					<input type="button" class="open_video margin10" rel="<?=$post['id'];?>" value="Показать видео">
					<br />
					<input type="button" class="always_embed_video" value="Всегда показывать">
				</div>
			<? } else { ?>
				<div class="video">
					<?=$post['content']['video']['object'];?>
				</div>
			<? } ?>			
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
