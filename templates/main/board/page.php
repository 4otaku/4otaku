<? foreach ($data['main']['threads'] as $thread) { ?>
	<div class="shell">
		<div class="board_post">
			<? if ($thread['image']) { ?>
				<img align="left" src="/images/board/thumbs/<?=$thread['image'][2];?>" rel="/images/board/full/<?=$thread['image'][1];?>">
			<? } ?>
		</div>
	</div>
<? } ?>
