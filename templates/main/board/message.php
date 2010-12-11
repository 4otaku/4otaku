<div class="message" id="post-<?=$post['id'];?>">	
	<span class="link_reply"><a href="<?=($url[3] != 'thread' ? '/board/'.$url[2].'/thread/'.$id.'#reply-'.$post['id'] : "javascript:add_text('>>".$post['id']."')");?>">Ответить</a></span>
	<span class="link_delete"><? if ($post['cookie'] && $_COOKIE['settings'] === $post['cookie']) { ?>
				 <img src="/images/comment_delete.png" alt="Удалить" rel="<?=$post['id'];?>" class="delete_from_board">
			<? } ?></span>
	<span class="author"><?=$post['name'];?></span>
	<? if (!empty($post['trip'])) { ?><span class="trip"><?=$post['trip'];?></span><? } ?>
	<span class="number"><a href="<?='/board/'.$url[2].'/thread/'.$id.'/#post-'.$post['id'];?>">#<?=$post['id'];?></a></span>
	<span class="date"><?=$post['pretty_date'];?></span>
	<div class="tbody">
			<? if ($post['image']) { ?>
				<a href="/images/board/full/<?=$post['image'][1];?>" target="_blank">
					<img align="left" src="/images/board/thumbs/<?=$post['image'][2];?>" rel="/images/board/full/<?=$post['image'][1];?>">
				</a>
			<? } elseif ($post['video']) { ?>
				<div class="video"><?=$post['video'];?></div>
			<? } ?>
			<div class="posttext">
				<?=$post['text'];?>
			</div>
	</div>
</div>
