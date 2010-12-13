<div class="thread" id="board-<?=$id;?>">	
	<span class="link_reply">
		<a href="<?=($url[3] != 'thread' ? '/board/'.$url[2].'/thread/'.$id.'#reply' : "javascript:add_text('>>".$id."')");?>">
			Ответить
		</a>
	</span>
	<span class="link_read">
		<a href="/board/<?=$url[2];?>/thread/<?=$id;?>/">
			Читать
		</a>
	</span>
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
		<a href="/board/<?=$url[2];?>/thread/<?=$id;?>/">
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
			<a href="/images/board/full/<?=$thread['image'][1];?>" target="_blank">
				<img src="/images/board/thumbs/<?=$thread['image'][2];?>" rel="/images/board/full/<?=$thread['image'][1];?>">
			</a>	
		<? } elseif ($thread['video']) { ?>
			<div class="video"><?=$thread['video'];?></div>
		<? } ?>
		<div class="posttext">
			<?=$thread['text'];?>
		</div>
	</div>
</div>