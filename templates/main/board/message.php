<table class="boardpost">
	<tr>
		<td align="left">
			<span class="boardname">
				<?=$post['name'].$post['trip'];?>
			</span>, 
			Пост № <?=$post['id'];?> 
			[<a href="/board/<?=$url[2];?>/thread/<?=$id;?>#reply-<?=$post['id'];?>">
				Ответить
			</a>]
		</td>
		<td align="right" valign="top">
			<?=$post['pretty_date'];?>
			<? if ($post['cookie'] && $_COOKIE['settings'] === $post['cookie']) { ?>
				 <img src="/images/comment_delete.png" alt="удалить" rel="<?=$id;?>" class="delete_thread">
			<? } ?>
		</td>
	</tr>
</table>
<table width="100%">
	<tr>
		<td class="imageholder">
			<? if ($post['image']) { ?>
				<img align="left" src="/images/board/thumbs/<?=$post['image'][2];?>" rel="/images/board/full/<?=$thread['image'][1];?>">
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
