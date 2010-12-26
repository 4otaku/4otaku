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
		<? if ($thread['downloads']) { ?>
			<span class="link_last download_thread">
				<a href="#" class="disabled">
					Скачать
				</a>
				<div class="relative">
					<span class="variants">
						<? if ($thread['downloads']['pdf']) { ?>
							<a href="#" class="board_download">
								<nobr>
									Тред в html
								</nobr>
							</a>
							<br />
						<? } ?>
						<? if ($thread['downloads']['zip']) { ?>
							<a href="#download-<?=$url[4];?>-zip" class="board_download">
								<nobr>
									Все картинки, архивом
								</nobr>
							</a>
							<br />
						<? } ?>
					</span>
				</div>	
			</span>	
		<? } ?>
		<? if ($thread['images_count'] > 0) { ?>
			<span class="link_right">
				<a href="#" rel="Свернуть все" class="board_unfold_all">
					Развернуть все
				</a>
			</span> 
		<? } ?>
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
		<? if (isset($thread['content']['image'][0])) { ?>
			<a href="/images/board/full/<?=$thread['content']['image'][0]['full'];?>" 
				target="_blank" class="board_image_thumb with_help" 
				title="<?=$thread['content']['image'][0]['full_size_info'];?>">
				<img align="left" src="/images/board/thumbs/<?=$thread['content']['image'][0]['thumb'];?>" rel="/images/board/full/<?=$thread['content']['image'][0]['full'];?>">
			</a>
		<? } elseif (isset($thread['content']['flash'])) { ?>
			<a href="/images/board/full/<?=$thread['content']['flash']['full'];?>" 
				target="_blank" class="with_help" 
				title="<?=$thread['content']['flash']['full_size_info'];?>">
				<img align="left" src="/images/flash.png">
			</a>				
		<? } elseif (isset($thread['content']['video'])) { ?>
			<? if (!sets::board('embedvideo')) { ?>
				<div class="video" style="height:<?=$thread['content']['video']['height'];?>px;">
					<br />
					<input type="button" class="open_video margin10" rel="<?=$id;?>" value="Показать видео">
					<br />
					<input type="button" class="always_embed_video" value="Всегда показывать">
				</div>
			<? } else { ?>
				<div class="video">
					<?=$thread['content']['video']['object'];?>
				</div>
			<? } ?>			
		<? } ?>
		<div class="posttext">
			<?=$thread['text'];?>
		</div>
	</div>
</div>