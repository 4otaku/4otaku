<? foreach ($data['main']['threads'] as $id => $thread) { ?>
	<div class="shell">
		<? include TEMPLATE_DIR . '/main/board/thread_message.php'; ?>
		<? foreach ($thread['posts'] as $post) { ?>
			<? include TEMPLATE_DIR . '/main/board/message.php'; ?>
		<? } ?>
	</div>
<? } ?>
