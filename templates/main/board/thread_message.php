<div class="thread" id="board-<?=$id;?>">	
	<? if ($url[3] != 'thread') { ?>
		<span class="link_last">
			<a href="/board/<?=($url[2] && $url[2] != 'page' ? $url[2] : $thread['boards'][array_rand($thread['boards'])]);?>/thread/<?=$id;?>#reply">
				Ответить
			</a>
		</span>
		<span class="link_right">
			<a href="/board/<?=$thread['current_board'];?>/thread/<?=$id;?>/">
				Читать
			</a>
		</span>
	<? } else { ?>
<!--
		<span class="link_last">
			<a href="#">
				Скачать тред
			</a>
		</span>	
-->		<span class="link_right">
			<a href="#" rel="Свернуть все" class="board_unfold_all">
				Развернуть все
			</a>
		</span>	
	<? } ?>
	<? if ($thread['cookie'] && $_COOKIE['settings'] === $thread['cookie']) { ?>
		<span class="link_delete">
			 <img src="/images/comment_delete.png" alt="удалить" rel="<?=$id;?>" class="delete_from_board">
		</span>
	<? } ?>
	<span class="author">
		<?=$thread['name'];?>
	</span>
	<? if (!empty($thread['trip'])) { ?>
		<span class="trip">
			<?=$thread['trip'];?>
		</span>
	<? } ?>
	<span class="number">
		<a href="/board/<?=$thread['current_board'];?>/thread/<?=$id;?>/" class="number_link">
			#<?=$id;?>
		</a>
		 в разделе 
		<? foreach($thread['boards'] as $key => $board) { ?>
			<? if (!empty($key)) { ?>
				/
			<? } ?>
			<a href="/board/<?=$board;?>/">
				<?=$board;?>
			</a>
		<? } ?>
	</span>
	<span class="date">
		<?=$thread['pretty_date'];?>
	</span>
	<div class="tbody">
		<? if ($thread['image']) { ?>
			<a href="/images/board/full/<?=$thread['image'][1];?>" target="_blank" class="board_image_thumb">
				<img src="/images/board/thumbs/<?=$thread['image'][2];?>" rel="/images/board/full/<?=$thread['image'][1];?>">
			</a>	
		<? } elseif ($thread['video']) { ?>
			<div class="video">
				<? if (is_array($thread['video'])) { ?>
					<br />
					<input type="button" class="open_video margin10" rel="<?=implode('#',$thread['video']);?>" value="Показать видео">
					<br />
					<input type="button" class="always_embed_video" value="Всегда показывать">				
				<? } else { ?>
					<?=$thread['video'];?>
				<? } ?>
			</div>
		<? } ?>
		<div class="posttext">
			<?=$thread['text'];?>
		</div>
	</div>
</div>