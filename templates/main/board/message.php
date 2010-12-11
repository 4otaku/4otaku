<table class="boardpost" id="board-<?=$post['id'];?>">
	<tr>
		<td align="left">
			<span class="boardname">
				<?=$post['name'].$post['trip'];?>
			</span>, 
			Пост № <?=$post['id'];?> 
			[<a href="<?=($url[3] != 'thread' ? '/board/'.$url[2].'/thread/'.$id.'#reply-'.$post['id'] : "javascript:add_text('>>".$post['id']."')");?>">
				Ответить
			</a>]
		</td>
		<td align="right" valign="top">
			<?=$post['pretty_date'];?>
			<? if ($post['cookie'] && $_COOKIE['settings'] === $post['cookie']) { ?>
				 <img src="/images/comment_delete.png" alt="удалить" rel="<?=$post['id'];?>" class="delete_from_board">
			<? } ?>
		</td>
	</tr>
</table>
<table width="100%">
	<tr>
		<td class="imageholder">
			<? if ($post['image']) { ?>
				<a href="/images/board/full/<?=$post['image'][1];?>">
					<img align="left" src="/images/board/thumbs/<?=$post['image'][2];?>" rel="/images/board/full/<?=$post['image'][1];?>">
				</a>
			<? } elseif ($post['video']) { ?>
				<?=$post['video'];?>
			<? } ?>
		</td>
		<td valign="top">
			<div class="posttext">
				<?=$post['text'];?>
			</div>
		</td>
	</tr>		
</table>
