<div class="edit_wrap">
	<form id="edit_post" disabled="disabled" onsubmit="return false;">
		<input type="hidden" name="id" value="<?=$get['id'];?>" />
		<input type="hidden" name="type" value="<?=($get['type'] == 'orders' ? 'order' : $get['type']);?>" />
		<input type="hidden" name="part" value="<?=$get['f'];?>" />
