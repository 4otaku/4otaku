<div class="message" id="board-<?=$post['id'];?>">	
	<span class="link_last">
		<a href="<?=($url[3] != 'thread' ? '/board/'.$thread['current_board'].'/thread/'.$id.'#reply-'.$post['id'] : "javascript:add_text('>>".$post['id']."')");?>">
			Ответить
		</a>
	</span>
	<span class="link_delete">
		<? if ($post['cookie'] && $_COOKIE['settings'] === $post['cookie']) { ?>
			 <img src="/images/comment_delete.png" alt="Удалить" rel="<?=$post['id'];?>" class="delete_from_board">
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
		<a href="<?='/board/'.$thread['current_board'].'/thread/'.$id.'/#board-'.$post['id'];?>" class="number_link">
			#<?=$post['id'];?>
		</a>
	</span>
	<span class="date">
		<?=$post['pretty_date'];?>
	</span>
	<div class="tbody">
		<? if (isset($post['content']['image'][0])) { ?>
			<a href="/images/board/full/<?=$post['content']['image'][0]['full'];?>" target="_blank" class="board_image_thumb">
				<img align="left" src="/images/board/thumbs/<?=$post['content']['image'][0]['thumb'];?>" 
					rel="/images/board/full/<?=$post['content']['image'][0]['full'];?>"
					title="<?=$post['content']['image'][0]['full_size_info'];?>"
					class="with_help"
				>
			</a>
		<? } elseif ($post['content']['video']) { ?>
			<? if (!sets::board('embedvideo') && $post['content']['video']['is_api']) { ?>
				<div class="video" style="height:<?=$post['content']['video']['api']['height'];?>px;">
					<br />
					<input type="button" class="open_video margin10" rel="<?=implode('#',$post['content']['video']['api']);?>" value="Показать видео">
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
			<?=$post['text'];?>
		</div>
	</div>
</div>
