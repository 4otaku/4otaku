<div class="message" id="board-<?=query::$post['id'];?>">	
	<span class="link_last">
		<a href="<?=($url[3] != 'thread' ? '/board/'.$thread['current_board'].'/thread/'.$id.'#reply-'.query::$post['id'] : "javascript:add_text('>>".query::$post['id']."')");?>">
			Ответить
		</a>
	</span>
	<span class="link_delete">
		<? if (query::$post['cookie'] && $_COOKIE['settings'] === query::$post['cookie']) { ?>
			 <img src="/images/comment_delete.png" alt="Удалить" rel="<?=query::$post['id'];?>" class="delete_from_board">
		<? } ?>
	</span>
	<span class="author">
		<?=query::$post['name'];?>
	</span>
	<? if (!empty(query::$post['trip'])) { ?>
		<span class="trip">
			<?=query::$post['trip'];?>
		</span>
	<? } ?>
	<span class="number">
		<a href="<?='/board/'.$thread['current_board'].'/thread/'.$id.'/#board-'.query::$post['id'];?>" class="number_link">
			#<?=query::$post['id'];?>
		</a>
	</span>
	<span class="date">
		<?=query::$post['pretty_date'];?>
	</span>
	<div class="tbody">
		<? if (isset(query::$post['content']['image'][0])) { ?>
			<a href="/images/board/full/<?=query::$post['content']['image'][0]['full'];?>" 
				target="_blank" class="board_image_thumb with_help" 
				title="<?=query::$post['content']['image'][0]['full_size_info'];?>">
				<img align="left" src="/images/board/thumbs/<?=query::$post['content']['image'][0]['thumb'];?>" rel="/images/board/full/<?=query::$post['content']['image'][0]['full'];?>">
			</a>
		<? } elseif (isset(query::$post['content']['flash'])) { ?>
			<a href="/images/board/full/<?=query::$post['content']['flash']['full'];?>" 
				target="_blank" class="with_help" 
				title="<?=query::$post['content']['flash']['full_size_info'];?>">
				<img align="left" src="/images/flash.png">
			</a>		
		<? } elseif (isset(query::$post['content']['video'])) { ?>
			<? if (!sets::board('embedvideo')) { ?>
				<div class="video" style="height:<?=query::$post['content']['video']['height'];?>px;">
					<br />
					<input type="button" class="open_video margin10" rel="<?=query::$post['id'];?>" value="Показать видео">
					<br />
					<input type="button" class="always_embed_video" value="Всегда показывать">
				</div>
			<? } else { ?>
				<div class="video">
					<?=query::$post['content']['video']['object'];?>
				</div>
			<? } ?>			
		<? } ?>
		<div class="posttext">
			<?=query::$post['text'];?>
		</div>
	</div>
</div>
