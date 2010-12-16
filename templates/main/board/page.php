<? foreach ($data['main']['threads'] as $id => $thread) { ?>
		<? include TEMPLATE_DIR . '/main/board/thread_message.php'; ?>
		<? if (is_array($thread['posts'])) { ?>
			<? if (!empty($thread['skipped']['posts'])) { ?>
				<div class="skipped">
					<a href="/board/<?=($url[2] && $url[2] != 'page' ? $url[2] : $thread['boards'][array_rand($thread['boards'])]);?>/thread/<?=$id;?>/" class="readmore">
						Читать
					</a>
					Пропущено: 
					<?=$thread['skipped']['posts'];?> 
					<?=obj::transform('text')->wcase($thread['skipped']['posts'],'ответ','ответа','ответов');?>
					<? if (!empty($thread['skipped']['images'])) { ?>
						, 
						<?=$thread['skipped']['images'];?> 
						<?=obj::transform('text')->wcase($thread['skipped']['images'],'картинка','картинки','картинок');?>
					<? } ?>					
					<? if (!empty($thread['skipped']['video'])) { ?>
						, 
						<?=$thread['skipped']['video'];?> 
						видео
					<? } ?>	
				</div>
			<? } ?>		
			<? foreach ($thread['posts'] as $post) { ?>
				<? include TEMPLATE_DIR . '/main/board/message.php'; ?>
			<? } ?>
		<? } ?>
<? } ?>
