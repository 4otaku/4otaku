<? foreach ($data['main']['threads'] as $thread) { ?>
	<div class="shell">
		<table width="100%">
			<tr>
				<td align="left" class="boardname">
					<?=$thread['name'].$thread['trip'];?>
				</td>
				<td align="right" valign="top">
					<?=$thread['pretty_date'];?>
				</td>
			</tr>
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
	</div>
<? } ?>
