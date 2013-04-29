<?php

if (!empty($data['main']['threads'])) {
	foreach ($data['main']['threads'] as $id => $thread) { ?>
		<div class="catalog_item">
			<? $thread_url = '/board/'.$thread['current_board'].'/thread/'.$id; ?>
			<? $thread_title = $thread['name'] . (empty($thread['trip']) ? '' : ' ' .$thread['trip']); ?>
			<? $thread_title .= ' в разделе '; ?>
			<? foreach($thread['categories'] as $key => $board) { ?>
				<? if (!empty($key)) { ?>
					<? $thread_title .= ' / '; ?>
				<? } ?>
				<? $thread_title .= $board['alias']; ?>
			<? } ?>
			<? $thread_title .= '; ' . $thread['pretty_date'] . "\n<br />"; ?>
			<? $thread_title .= obj::transform('text')->cut_long_text(strip_tags($thread['text'],'<br><em><strong><s>'),500); ?>
			<? if (!empty($thread['content']['video'])) {
				$video = reset($thread['content']['video']); ?>
				<a href="<?=$thread_url;?>"
				   target="_blank" class="board_image_thumb with_help"
				   title="<?=$thread_title;?>"
					<img src="<?=$def['site']['dir']?>/images/video.png">
				</a>
			<? } elseif (!empty($thread['content']['image'])) {
				$image = reset($thread['content']['image']); ?>
				<a href="<?=$thread_url;?>"
				   target="_blank" class="board_image_thumb with_help"
				   title="<?=$thread_title;?>">
					<img src="<?=$def['site']['dir']?>/images/board/thumbs/<?=$image['thumb'];?>">
				</a>
			<? } elseif (!empty($thread['content']['random'])) {
				$image = reset($thread['content']['random']); ?>
				<a href="<?=$thread_url;?>"
				   title="<?=$thread_title;?>"
				   target="_blank" class="board_image_thumb with_help art_random">
					<img src="<?=$def['site']['dir']?>/images/board/thumbs/<?=$image['thumb'];?>.jpg" />
					<img src="<?=$def['site']['dir']?>/images/dice-small.png" class="dice" />
				</a>
			<? } elseif (!empty($thread['content']['flash'])) {
				$flash = reset($thread['content']['flash']); ?>
				<a href="<?=$thread_url;?>"
				   target="_blank" class="board_flash with_help"
				   title="<?=$thread_title;?>">
					<img align="left" src="<?=$def['site']['dir']?>/images/flash.png">
				</a>
			<? }
		?> </div> <?
 	}
} ?>
<div class="clear"></div>