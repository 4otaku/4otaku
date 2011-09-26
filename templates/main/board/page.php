<?
if (!empty($data['main']['threads'])) {
	foreach ($data['main']['threads'] as $id => $thread) { ?>
		<? $thread_url = '/board/'.$thread['current_board'].'/thread/'.$id; ?>
		<? $read_all = ' ... <br /><br />Чтобы прочесть сообщение целиком, <a href="'.$def['site']['dir'].$thread_url.'/">перейдите в тред</a>.'; ?>
		<? include TEMPLATE_DIR .SL.'main'.SL.'board'.SL.'thread_message.php'; ?>
		<? if (is_array($thread['posts'])) { ?>
			<? if (!empty($thread['skipped']['posts'])) { ?>
				<div class="skipped">
					<a href="<?=$def['site']['dir'].$thread_url;?>/" class="readmore">
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
					<? if (!empty($thread['skipped']['flash'])) { ?>
						,
						<?=$thread['skipped']['flash'];?>
						<?=obj::transform('text')->wcase($thread['skipped']['flash'],'флешка','флешки','флешек');?>
					<? } ?>
					<? if (!empty($thread['skipped']['video'])) { ?>
						,
						<?=$thread['skipped']['video'];?>
						видео
					<? } ?>
				</div>
			<? } ?>
			<? foreach ($thread['posts'] as $post) { ?>
				<? include TEMPLATE_DIR .SL.'main'.SL.'board'.SL.'message.php'; ?>
			<? }
		}
	}
}
