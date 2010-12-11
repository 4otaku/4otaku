<? foreach ($data['main']['threads'] as $id => $thread) { ?>
	<div class="shell">
		<? include TEMPLATE_DIR . '/main/board/thread_message.php'; ?>
		<? if (is_array($thread['posts'])) { ?>
			<table width="100%">
				<td>
					<td width="120px">
						&nbsp;
					</td>
					<td>
						<? if (!empty($thread['skipped']['posts'])) { ?>
							<div class="mini-shell margin20">
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
								; 
								[<a href="/board/<?=$url[2];?>/thread/<?=$id;?>/">
									Читать
								</a>]
							</div>
						<? } ?>		
						<? foreach ($thread['posts'] as $post) { ?>
							<? include TEMPLATE_DIR . '/main/board/message.php'; ?>
						<? } ?>
					</td>
				</tr>
			</table>	
		<? } ?>
	</div>
<? } ?>
