<div class="thread">	
	<? if ($url[3] != 'thread') { ?>
	
		<span class="link_last">
			<a href="<?=$def['site']['dir'].$thread_url;?>#reply">
				Ответить
			</a>
		</span>
		<span class="link_right">
			<a href="<?=$def['site']['dir'].$thread_url;?>/">
				Читать
			</a>
		</span>
		
	<? } else { ?>

		<span class="link_last" style="margin-left: 10px">
			<a href="<?=$def['site']['dir'];?><?=($url[3] != 'thread' ? '/board/'.$thread['current_board'].'/thread/'.$id.'#reply-'.$post['id'] : "javascript:add_text('>>".$post['id']."')");?>">
				Ответить
			</a>
		</span>

		<? if ($thread['downloads']) { ?>
			<span class="link_last download_thread">
				<a href="#" class="disabled">
					Скачать
				</a>
				<div class="relative">
					<span class="variants">
						<? if ($thread['downloads']['pdf']) { ?>
							<a href="#download-<?=$url[4];?>-html" class="board_download">
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
	<? if ($thread['cookie'] && query::$cookie === $thread['cookie']) { ?>
		<span class="link_delete">
			 <img src="<?=$def['site']['dir']?>/images/comment_delete.png" alt="удалить" rel="<?=$id;?>" class="delete_from_board">
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
		<a href="<?=$def['site']['dir']?>/board/<?=$thread['current_board'];?>/thread/<?=$id;?>/" class="number_link">
			#<?=$id;?>
		</a>
		 в разделе 
		<? foreach($thread['categories'] as $key => $board) { ?>
			<? if (!empty($key)) { ?>
				/
			<? } ?>
			<a 
				href="<?=$def['site']['dir']?>/board/<?=$board['alias'];?>/" 
				class="<?=($board['actual'] ? 'actual' : 'not_actual');?>"
			>
				<?=$board['alias'];?>
			</a>
		<? } ?>
	</span>
	<span class="date">
		<?=$thread['pretty_date'];?>
	</span>
	<div class="tbody<?=(empty($thread['multi_content']) ? '' : ' multi');?>">
		<? if (!empty($thread['content']['video'])) { 
			foreach ($thread['content']['video'] as $video_key => $video) { ?>
				<? if (!sets::board('embedvideo')) { ?>
					<div class="video video_frame" style="height:<?=$video['height'];?>px;">
						<br />
						<input type="button" class="open_video margin10" rel="<?=$id.'-'.$video_key;?>" value="Показать видео">
						<br />
						<input type="button" class="always_embed_video" value="Всегда показывать">
					</div>
				<? } else { ?>
					<div class="video">
						<?=$video['object'];?>
					</div>
				<? } ?>			
		<? } }
		if (!empty($thread['content']['image'])) { 
			foreach ($thread['content']['image'] as $image) { ?>
				<a href="<?=$def['site']['dir']?>/images/board/full/<?=$image['full'];?>" 
					target="_blank" class="board_image_thumb with_help" 
					title="<?=$image['full_size_info'];?>" 
					rel="<?=$image['sizes'];?>">
					<img 
						src="<?=$def['site']['dir']?>/images/board/thumbs/<?=$image['thumb'];?>" 
						rel="/images/board/full/<?=$image['full'];?>"
					>
				</a>
		<? } }
		if (!empty($thread['content']['flash'])) { 
			foreach ($thread['content']['flash'] as $flash) { ?>
				<a href="<?=$def['site']['dir']?>/images/board/full/<?=$flash['full'];?>" 
					target="_blank" class="board_flash with_help" 
					title="<?=$flash['full_size_info'];?>">
					<img align="left" src="<?=$def['site']['dir']?>/images/flash.png">
				</a>
		<? } } ?>	
		<? if (!empty($thread['multi_content'])) { ?>
			<div class="clear"></div>
		<? } ?>	
		<div class="posttext">
			<? if ($url[3] != 'thread') { ?>
				<?=obj::transform('text')->cut_long_text($thread['text'],1000,$read_all,100);?>
			<? } else { ?>
				<?=$thread['text'];?>
			<? } ?>
		</div>
	</div>
	<? if (!empty($thread['multi_content'])) { ?>
		<div class="clear"></div>
	<? } ?>
</div>
