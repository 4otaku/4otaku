<div class="edit_wrap">
	<form id="edit_post" disabled="disabled" onsubmit="return false;">
		<input type="hidden" name="id" value="<?=query::$get['id'];?>" />
		<input type="hidden" name="type" value="<?=(query::$get['type'] == 'orders' ? 'order' : query::$get['type']);?>" />
		<input type="hidden" name="part" value="<?=query::$get['f'];?>" />
