<table class="boardthread">
	<tr>
		<td align="left">
			<span class="boardname">
				<?=$thread['name'].$thread['trip'];?>
			</span>, 
			Тред № <?=$id;?> 
			[<a href="/board/<?=$url[2];?>/thread/<?=$id;?>#reply">
				Ответить
			</a>]
		</td>
		<td align="right" valign="top">
			<?=$thread['pretty_date'];?>
			<? if ($thread['cookie'] && $_COOKIE['settings'] === $thread['cookie']) { ?>
				 <img src="/images/comment_delete.png" alt="удалить" rel="<?=$id;?>" class="delete_thread">
			<? } ?>
		</td>
	</tr>
</table>
<table width="100%">
	<tr>
		<td class="imageholder">
			<? if ($thread['image']) { ?>
				<img align="left" src="/images/board/thumbs/<?=$thread['image'][2];?>" rel="/images/board/full/<?=$thread['image'][1];?>">
			<? } elseif ($thread['video']) { ?>
				<?=$thread['video'];?>
			<? } ?>
		</td>
		<td valign="top">
			<div class="posttext">
				<?=$thread['text'];?>
			</div>
		</td>
	</tr>		
</table>
